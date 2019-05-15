<div class="box">
	<form role="form">
	    <div class="box-header">
	        <button type="button" class="btn-group pull-right btn btn-primary">保存</button>
	    </div>
	    <div class="box-body">
	        <table class="table">
                <tr>
                    <th>Base Size</th>
                    <th>Hair Colour</th>
                    <th>Hair Density</th>
                </tr>
                <tr>
                    <td>
                        <div class="input-group">
                          <input type="text" class="form-control" name="BaseSize[]">
                          <span class="input-group-btn">
                            <button class="btn btn-danger" type="button" onclick="delCol()">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                          </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="HairColour[]" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="button" onclick="delCol()">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="HairDensity[]" class="form-control">
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
                        <button type="button" atName="BaseSize[]" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
                    </td>
                    <td>
                        <button type="button" atName="HairColour[]" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
                    </td>
                    <td>
                        <button type="button" atName="HairDensity[]" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
                    </td>
                </tr>
            </table>
	    </div>
	</form>
</div>
<script type="text/javascript">
    var active_row = $(".table tr").length-2,
        active_row_tds = $(".table tr")[active_row], //用于获取活跃行及修改行的数目
        table_row_tds = $(".table tr")[0],  //表头用于获取th的数目
        table_row_th = $(table_row_tds).find("th").length;    //th的数目
        adtive_row_col = $(active_row_tds).find(".input-group").length; //新增行既修改行的列数
    var addRowCom = "<tr>"+
                    "<td></td>"+
                    "<td></td>"+
                    "<td></td>"+
                    "</tr>"
    var inp_name,
        addColCom = "<div class='input-group'>"+
                    "<input type='text' name='"+ inp_name +"' class='form-control'>"+
                    "<span class='input-group-btn'>"+
                    "<button class='btn btn-danger' type='button' onclick='delCol()'>"+
                    "<span class='glyphicon glyphicon-remove'></span>"+
                    "</button>"+
                    "</span>"+
                    "</div>"
    function addCol(){
        var _$this = $(event.target),
            insert_num = _$this.parent("td").index();
            console.log(adtive_row_col);
        if(table_row_th == adtive_row_col) {
            _$this.parents("tr").before(addRowCom);
            active_row = $(".table tr").length-2;  //更新数值
            active_row_tds = $(".table tr")[active_row]; //更新数值
            adtive_row_col = $(active_row_tds).find(".input-group").length; //更新数值
            inp_name = _$this.attr("atName");
            var _that =$(active_row_tds).find("td")[insert_num];
            _that.innerHTML=addColCom;
        }else {
            active_row = $(".table tr").length-2;  //更新数值
            active_row_tds = $(".table tr")[active_row]; //更新数值
            adtive_row_col = $(active_row_tds).find(".input-group").length; //更新数值
            inp_name = _$this.attr("atName");
            var _that =$(active_row_tds).find("td")[insert_num];
            _that.innerHTML=addColCom;
            if(insert_num == table_row_th-1) {
                adtive_row_col +=1;
            }
        }
        
    }
    function delCol(){
        var _$this = $(event.target);
        _$this.parents(".input-group").html("");
    }
</script>