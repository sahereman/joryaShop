<script type="text/javascript" src="{{asset('vendor/laravel-admin/jquery-table-sort-master/jquery.table_sort.min.js')}}"></script>
@if ($messages)
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        @foreach ($messages as $message)
            <h4>
                <i class="icon fa fa-ban"></i>
                {{ $message[0] }}
            </h4>
            <p></p>
            @break
        @endforeach
    </div>
@endif

{{--<button onclick="getAttrCombo()">test</button>--}}

<div class="box">
    <form id="sku_generator_form" role="form" method="POST" enctype="multipart/form-data"
          action="{{ route('admin.products.sku_generator_store', ['product' => $product->id]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="attrs" value="">
        <div class="box-header">
            <div class="pull-left col-lg-5">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">差价</span>
                        <input type="number" step="0.01" class="form-control" name="delta_price" value="0">
                    </div>
                </div>
                {{--<div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">库存</span>
                        <input type="number" step="1" min="0" class="form-control" name="stock" value="0">
                    </div>
                </div>--}}
            </div>
            <button type="button" class="btn-group pull-right btn btn-primary" id="submit_btn">生成</button>
            <div class="btn-group pull-right" style="margin-right: 15px">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-default">&nbsp;返回</a>
            </div>
        </div>
        <div class="box-body">
            <div class="container-fluid">
                <div class="row" style="display: flex;justify-content: flex-start;flex-wrap: wrap;">
                    <!--col-md-4这个class值不是固定的。要根据不同的数目的表格来进行区分，总数为12，现在有3类每一类占4分，-->
                    @foreach($product->attrs as $attr)
                        <div class="col-md-4">
                            <table class="table photo_tab attr_table" name="{{ $attr->name }}" attr_name="{{ $attr->id }}">
                                {{--Head--}}
                                <tr>
                                    <th>{{ $attr->name }}</th>
                                </tr>
                                {{--Data--}}
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            @if($attr->has_photo)
                                                <span class="input-group-btn pic_btn" style="overflow: hidden;">
                                                    <img src="{{asset('img/pic_upload.png') }}" style="height: 34px; border: 1px solid #ccc; padding: 2px;">
                                                    <input type="file" name="image" data-url="{{ route('image.upload') }}"
                                                           style="opacity: 0; position: absolute; top: 0; width: 100%; height: 100%;" onchange="imgChange(this)">
                                                </span>
                                                <input type="text" name="{{ $attr->id }}" data_path='' class="form-control table_value" value="" autocomplete="off">
                                                <span class="tip-l"></span>
                                                <ul class="skus-select-dropdown" style="display: none;">
                                                    @if($attr->basic_attr)
                                                        @foreach($attr->basic_attr->values as $value)
                                                            <li>{{ $value->value }}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            @else
                                                <input type="text" name="{{ $attr->id }}" data_path='' class="form-control table_value" value="" autocomplete="off">
                                                <span class="tip-l"></span>
                                                <ul class="skus-select-dropdown" style="display: none;">
                                                    @if($attr->basic_attr)
                                                        @foreach($attr->basic_attr->values as $value)
                                                            <li>{{$value->value}}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            @endif
                                            <span class="input-group-btn">
                                                <button class="btn btn-danger" type="button" onclick="delCol()">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                {{--Button--}}
                                <tr>
                                    <td>
                                        <button type="button" attr_name="{{ $attr->id }}" class="btn-group btn btn-primary" onclick="addCol()">
                                            增加
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>
<div class="box">
    <div class="box-body table-responsive no-padding">
        <form id="sku_editor_form" role="form" method="POST" enctype="multipart/form-data" action="{{ route('admin.products.sku_editor_store', ['product' => $product->id]) }}">
            <div class="box-header">
                <h4>
                    <span class="pull-left">SKU 列表</span>
                    <span class="pull-left" style="margin-left: 20px;color: #666">{{ $product->sub_name_en }}</span>
                    <button type="submit" class="btn btn-primary  pull-right">提交并修改</button>
                </h4>
            </div>
            {{ csrf_field() }}
            <table class="table table-hover table-sort">
                <thead>
                <tr>
                    @if($skus->isNotEmpty())
                        <th>Photo</th>
                        <th>Created at <i class="fa fa-sort"></i></th>
                        @foreach($product->attrs as $attr)
                            <th>{{ $attr->name }} <i class="fa fa-sort"></i></th>
                        @endforeach
                        <th>Delta Price</th>
                        {{--<th>Stock</th>
                        <th>Stock +</th>
                        <th>Stock -</th>--}}
                        <th>Action</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                @foreach($skus as $sku)
                    <tr>
                        <td style="position: relative">
                            <img src="{{ $sku->photo_url }}" style="min-width:40px;min-height:40px;max-width:40px;max-height:40px" class="img img-thumbnail">
                            <div class="changeImgArea" style="position: absolute;width: 60px;height:30px;overflow: hidden;top: 16px;left: 50px;">
                                <input type="file" id="skus[{{ $sku->id }}][photo]" name="skus[{{ $sku->id }}][photo]" value=""
                                       style="width: 75px; position: absolute; top: 0; z-index: 5; opacity: 0"
                                       data-url="{{ route('image.upload') }}" onchange="addImg(this)">
                                {{--<button type="button" class="btn-group btn btn-primary btn-xs" aria-label="Left Align" style="width:30px; position: absolute; top: 0;">--}}
                                <button type="button" class="btn-group btn btn-primary btn-xs" aria-label="Left Align" style="width: 25px; position: absolute; top: 0;">
                                    <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn-group btn btn-primary btn-xs" aria-label="Left Align" onclick="delImg(this)" data-id="{{ $sku->id }}"
                                   data-url="{{ route('admin.product_skus.del_img', ['sku' => $sku->id]) }}" style="position: absolute; left: 32px; z-index: 9;">
                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                </button>
                            </div>
                        </td>
                        <td style="@if($sku->last_generated) font-weight: bold; @endif">{{$sku['created_at']}}</td>
                        @foreach($sku->attr_values as $value)
                            <td style="@if($sku->last_generated) font-weight: bold; @endif">{{$value['value']}}</td>
                        @endforeach
                        <td>
                            <input style="width: 80px" class="form-control" type="text" id="skus[{{ $sku->id }}][delta_price]"
                                   name="skus[{{ $sku->id }}][delta_price]" value="{{ $sku->delta_price }}">
                        </td>
                        {{--<td>
                            <input style="width: 60px" class="form-control" type="text" id="skus[{{ $sku->id }}][stock]"
                                   name="skus[{{ $sku->id }}][stock]" value="{{ $sku->stock }}">
                        </td>
                        <td>
                            <input style="width: 60px" class="form-control" type="text" id="skus[{{ $sku->id }}][stock_increment]"
                                   name="skus[{{ $sku->id }}][stock_increment]" value="0">
                        </td>
                        <td>
                            <input style="width: 60px" class="form-control" type="text" id="skus[{{ $sku->id }}][stock_decrement]"
                                   name="skus[{{ $sku->id }}][stock_decrement]" value="0">
                        </td>--}}
                        <td>
                            <a class="btn btn-default" onclick="RemoveSku(this)" remove_url="{{route('admin.product_skus.destroy', $sku->id)}}">
                                Remove
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </form>
    </div>
</div>

<script type="text/javascript">
    // function getAttrArray () {
    //     var obj = {};
    //     $('.attr_table').each(function (i) {
    //         var attr_name = $(this).attr('name');
    //         obj[attr_name] = [];
    //         console.log();
    //         $(this).find('.table_value').each(function (i) {
    //             obj[attr_name].push($(this).val());
    //         });
    //     });
    //     return obj;
    // }

    // $('.editable-select').editableSelect({
    //     effects: 'slide',
    // });
    // 表格中的input输入框select的选择事件
    var isBox = false; // 定义一个触发焦点事件的开关，默认为不开启状态 || 也可以给input设置一个属性，来判断
    var tableName = $(".attr_table"),
        selectDom = tableName.find(".skus-select-dropdown"),
        inputDom = tableName.find(".table_value"),
        inputBox = tableName.find(".input-group");
    selectDom.hide();
    tableName.on("focus", ".table_value", function () { // input绑定焦点事件，触发时打开焦点开关
        $(".container-fluid").find(".skus-select-dropdown").hide();
        $(this).siblings(".skus-select-dropdown").show();
        isBox = true;
    });
    tableName.on("mousemove", ".input-group", function () { // 鼠标进入input-box区域内打开焦点开关
        isBox = true;
    });
    tableName.on("mouseout", ".input-group", function () { // 鼠标离开input-box区域内关闭焦点开关
        isBox = false;
    });
    tableName.on("blur", ".table_value", function () { // input失去焦点时通过焦点开关状态判断鼠标所在区域
        if (isBox == true) return false;
        $(this).siblings(".skus-select-dropdown").hide();
    });
    tableName.on("click", "li", function () {
        isBox = false;
        var text = $(this).text();
        $(this).parent().siblings(".table_value").val(text);
        $(this).parents(".skus-select-dropdown").hide();
    });

    function addCol() {
        var _$this = $(event.target);
        var addRowCom = "<tr>" + _$this.parents("table").find("tr")[1].innerHTML + "</tr>";
        var newSelect = _$this.parents("tr").find(".editable-select");
        newSelect.on("focus", function () {
            _$this.parents("tr").find("ul").css("display", "block")
        });
        _$this.parents("tr").before(addRowCom);
        _$this.parents("tr").prev().find("img").attr('src', "{{ asset('img/pic_upload.png') }}");
        _$this.parents("tr").prev().find("input").val("");
        _$this.parents("tr").prev().find("input[type='text']").attr("data_path", "");
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

    function addImg(obj) {
        var imgs = $(obj).parent().parent().find('img.img-thumbnail');
        var img = $(imgs[0]);
        // var filePath = $(obj).val();
        var url = $(obj).attr("data-url");
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
                img.attr("src", data.preview);
                // $(obj).val(data.path);
            },
            error: function (e) {
                console.log(e)
            },
        });
    }

    function delImg(obj) {
        var imgs = $(obj).parent().parent().find('img.img-thumbnail');
        var img = $(imgs[0]);
        var files = $(obj).parent().find('input[type="file"]');
        var file = $(files[0]);
        url = $(obj).attr('data-url');
        id = $(obj).attr('data-id');
        if (img.attr('src') && url && id) {
            sku_data = {};
            sku_data.sku_id = id;
            sku_data._token = '{{ csrf_token() }}';
            $.ajax({
                url: url,
                data: sku_data,
                dataType: 'json',
                // cache: false,
                // contentType: false, // 必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理
                // processData: false, // 必须false才会自动加上正确的Content-Type
                type: 'put',
                success: function (data) {
                    img.attr('src', '');
                    file.val('');
                },
                error: function (e) {
                    console.log(e)
                },
            });
        }
    }

    function RemoveSku(obj) {
        $.ajax({
            method: 'post',
            url: $(obj).attr('remove_url'),
            dataType: "json",   //返回格式为json
            data: {
                _token: LA.token,
                _method: 'DELETE'
            },
            success: function (data) {
                // $.pjax.reload('#pjax-container');
                // swal(data.message, '', 'success');
            }
        });
        $(obj).parent().parent().remove();
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

    $(function () {
        //表格排序
        $(".table-sort").tableSort({
            indexes: [1, 2, 3, 4, 5], // Target columns. Default is all.
            after: function (th) { // The process to hook into sort after execution.
                console.log($(th).text() + " sorted!");
                // If use when combined with tableMove.
                // $(".sort").tableMove();
            },
        });
        $(".table-sort").find('th').eq(1).trigger('click');
        $(".table-sort").find('th').eq(1).trigger('click');

        // $(".table-sort").find('th').eq(4).trigger('click');
        // $(".table-sort").find('th').eq(3).trigger('click');
        // $(".table-sort").find('th').eq(2).trigger('click');

        // 表单提交
        $('#submit_btn').on("click", function () {
            var json_str = new Object();
            var totalAttrs = [];
            var totalTabs = $(".box-body").find(".attr_table");
            var photo_tab = $(".photo_tab").find("tr");
            var allSkuInp = $(".attr_table").find("input[type=text]");
            for (var i = 0; i < allSkuInp.length; i++) {
                if ($(allSkuInp[i]).val() == "") {
                    alert("请完善信息!");
                    return;
                }
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
                // totalAttrs = unique(totalAttrs, "data");
                json_str[keyName] = totalAttrs;
            }
            $("input[name='attrs']").val(JSON.stringify(json_str));
            setTimeout(function () {
                $("form#sku_generator_form").submit();
            }, 500);
        });
    });
</script>
