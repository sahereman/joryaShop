<div class="box">
    <form role="form" method="POST" enctype="multipart/form-data"
          action="{{ route('admin.products.sku_generator_store', ['product' => $product->id]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="attrs" value="">
        <div class="box-header">
            <div class="pull-left col-lg-5">
                <div class="col-lg-6">
                    <div class="input-group">
                      <span class="input-group-addon">价格</span>
                      <input type="text" class="form-control" name="price">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="input-group">
                      <span class="input-group-addon">库存</span>
                      <input type="text" class="form-control" name="stock">
                    </div>
                </div>
            </div>
            <button type="button" class="btn-group pull-right btn btn-primary" id="submit_btn">保存</button>
        </div>
        <div class="box-body">
            <div class="container-fluid">
                <div class="row">
                    <!--col-md-4这个class值不是固定的。要根据不同的数目的表格来进行区分，总数为12，现在有3类每一类占4分，-->
                    <div class="col-md-4">
                        <table class="table photo_tab" attr_name='hair_colour'>
                            <tr>
                                <th>Hair Colour</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                    <span class="input-group-btn pic_btn" style="overflow: hidden;">
                                        <img src="{{ asset('img/pic_upload.png') }}"
                                             style="height: 34px;border: 1px solid #ccc;padding: 2px;">
                                        <input type="file" name="image" data-url="{{ route('image.upload') }}"
                                               style="opacity: 0;position: absolute;top: 0;width: 100%;height: 100%;"
                                               onchange="imgChange(this)">
                                    </span>
                                        <input type="text" name="hair_colour" data_path='' class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger" type="button" onclick="delCol()">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" attr_name="hair_colour" class="btn-group btn btn-primary"
                                            onclick="addCol()">增加
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table" attr_name='base_size'>
                            <tr>
                                <th>Hair Size</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <input type="text" name="base_size" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger" type="button" onclick="delCol()">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" attr_name="base_size" class="btn-group btn btn-primary"
                                            onclick="addCol()">增加
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table" attr_name='hair_density'>
                            <tr>
                                <th>Hair Density</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <input type="text" name="hair_density" class="form-control">
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger" type="button" onclick="delCol()">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </button>
                                    </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button type="button" attr_name="hair_density" class="btn-group btn btn-primary"
                                            onclick="addCol()">增加
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function addCol() {
        var _$this = $(event.target);
        var addRowCom = "<tr>" + _$this.parents("table").find("tr")[1].innerHTML + "</tr>";
        _$this.parents("tr").before(addRowCom);
        _$this.parents("tr").prev().find("img").attr('src', "{{ asset('img/pic_upload.png') }}");
        _$this.parents("tr").prev().find("input").val("");
    }
    function delCol() {
        var _$this = $(event.target);
        if (_$this.parents("table").find("tr").length != 3) {
            _$this.parents("tr").remove();
        }
    }
    // 图片上传
    function imgChange(obj) {
        var filePath = $(obj).val();
        var url = $(obj).attr("data-url");
        // if ($(obj).parents("tr").find("input[type='text']").val() == "") {
        // alert("请完善对应信息才可以上传图片!")
        // } else {
        UpLoadImg(obj, url);
        // }
    }
    function UpLoadImg(obj, url) {
        var formData = new FormData();
        formData.append('image', $(obj)[0].files[0]);
        formData.append('_token', "{{ csrf_token() }}");
        $.ajax({
            url: url,
            data: formData,
            dataType: 'json',
            cache: false,
            contentType: false, // 必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理
            processData: false, // 必须false才会自动加上正确的Content-Type
            type: 'post',
            success: function (data) {
                $(obj).parents("span").find("img").attr("src", data.preview);
                $(obj).parents("tr").find("input[type=text]").attr("data_path", data.path);
            },
            error: function (e) {
                console.log(e)
            },
        });
    }
    // 去重
    function unique(arr, type) {
        // const res = new Map();
        // return arr.filter((a) => !res.has(a[type]) && res.set(a[type], 1));
        var res = new Map();
        return arr.filter(function (a) {
            return !res.has(a[type]) && res.set(a[type], 1);
        });
    }
    // 表单提交
    $('#submit_btn').on("click", function () {
        var json_str = new Object();
        var totalAttrs = [];
        var totalTabs = $(".box-body").find("table");
        var photo_tab = $(".photo_tab").find("tr");
        if ($(".table").find("input[type=text]").val() == "") {
            alert("请完善信息!");
            return;
        }
        for (var i = 0; i <= totalTabs.length - 1; i++) {
            var keyName = $(totalTabs[i]).attr("attr_name");
            var totalTrs = $(totalTabs[i]).find("tr");
            totalAttrs = [];
            for (var item = 1; item <= totalTrs.length - 2; item++) {
                if ($(totalTabs[i]).hasClass("photo_tab")) {
                    totalAttrs.push({
                        "data": $(totalTrs[item]).find("input[type=text]").val(),
                        "photo": $(totalTrs[item]).find("input[type=text]").attr("data_path")
                    });
                } else {
                    totalAttrs.push({"data": $(totalTrs[item]).find("input[type=text]").val()});
                }
            }
            totalAttrs = unique(totalAttrs, "data");
            json_str[keyName] = totalAttrs;
        }
        $("input[name='attrs']").val(JSON.stringify(json_str));
        setTimeout(function () {
            $("form").submit();
        }, 500);
    });
</script>
