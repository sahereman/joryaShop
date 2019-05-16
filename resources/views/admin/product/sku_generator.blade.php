<div class="box">
	<form role="form" 
	      method="POST" 
	      action=""
          enctype="multipart/form-data"
    >
    {{ csrf_field() }}
    <input type="hidden" name="data_json" value="">
	    <div class="box-header">
	        <button type="button" class="btn-group pull-right btn btn-primary" id="submit_btn">保存</button>
	    </div>
	    <div class="box-body">
	        <div class="container-fluid">
              <div class="row">
                  <!--col-md-4这个class值不是固定的。要根据不同的数目的表格来进行区分，总数为12，现在有3类每一类占4分，-->
                  <div class="col-md-4">
                  	<table class="table photo_tab" arr_nmae = 'color'>
                        <tr>
                            <th>Hair Colour</th>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-btn pic_btn" style="overflow: hidden;">
                                        <img src="{{ asset('img/pic_upload.png') }}" 
                                             style="height: 34px;border: 1px solid #ccc;padding: 2px;">
                                        <input type="file" name="image" 
                                               style="opacity: 0;position: absolute;top: 0;width: 100%;height: 100%;" 
                                               data-url="{{ route('image.upload') }}" 
                                               onchange="imgChange(this)"
                                        >
                                    </span>
                                    <input type="text" name="hair_colour" data_path = '' class="form-control">
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
                                <button type="button" atName="hair_colour" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
                            </td>
                        </tr>                
                    </table>
                  </div>
                  <div class="col-md-4">
                    <table class="table" arr_nmae = 'size'>
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
                                <button type="button" atName="base_size" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
                            </td>
                        </tr>                
                    </table>
                  </div>
                  <div class="col-md-4">
                    <table class="table" arr_nmae = 'density'>
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
                                <button type="button" atName="hair_density" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
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
    function addCol(){
        var _$this = $(event.target);
        var addRowCom = "<tr>"+ _$this.parents("table").find("tr")[1].innerHTML +"</tr>";
        _$this.parents("tr").before(addRowCom);
        _$this.parents("tr").prev().find("img").attr('src',"{{ asset('img/pic_upload.png') }}");
        _$this.parents("tr").prev().find("input").val("");
    }
    function delCol(){
        var _$this = $(event.target);
        if(_$this.parents("table").find("tr").length != 3) {
            _$this.parents("tr").remove();   
        }
    }
    //图片上传
    function imgChange(obj){
        var filePath = $(obj).val();
        var url = $(obj).attr("data-url");
//      if($(obj).parents("tr").find("input[type='text']").val() == "" ){
//          alert("请完善对应信息才可以上传图片!")
//      }else {
            UpLoadImg(obj, url);   
//      }
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
            contentType: false,//必须false才会避开jQuery对 formdata 的默认处理 XMLHttpRequest会对 formdata 进行正确的处理  
            processData: false,//必须false才会自动加上正确的Content-Type
            type: 'post',
            success: function (data) {
                $(obj).parents("span").find("img").attr("src",data.preview);
                $(obj).parents("tr").find("input[type=text]").attr("data_path",data.path);
            }, 
            error: function (e) {
                console.log(e)
            }
        });
    } 
    //去重
    function unique(arr, type) {
        const res = new Map();
        return arr.filter((a) => !res.has(a[type]) && res.set(a[type], 1));
    }
    //表单提交
    $('#submit_btn').on("click",function(){
        var json_str = new Object(),
            totalArr = [];
        var totalTab = $(".box-body").find("table");
        var photo_tab = $(".photo_tab").find("tr");
        if($(".table").find("input[type=text]").val()==""){
            alert("请完善信息!");
            return;
        }
        for(var i = 0;i <= totalTab.length-1;i++) {
            var kyeNmae = $(totalTab[i]).attr("arr_nmae");
            var totalTr = $(totalTab[i]).find("tr");
            totalArr = [];
            for(var iteam = 1; iteam<=totalTr.length-2; iteam++){
                if($(totalTab[i]).hasClass("photo_tab")){
                    totalArr.push({"data":$(totalTr[iteam]).find("input[type=text]").val(),"photo":$(totalTr[iteam]).find("input[type=text]").attr("data_path")});   
                }else {
                    totalArr.push({"data":$(totalTr[iteam]).find("input[type=text]").val()});
                }
            }
            totalArr = unique(totalArr,"data");
            json_str[kyeNmae] = totalArr;
        }
        $("input[name='data_json']").val(JSON.stringify(json_str));
        setTimeout(function(){
            $("form").submit();
        },500)
    })
</script>