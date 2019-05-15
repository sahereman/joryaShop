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
                          <input type="text" class="form-control" name="base_size[]">
                          <span class="input-group-btn">
                            <button class="btn btn-danger" type="button" onclick="delCol()">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                          </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="hair_colour[]" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="button" onclick="delCol()">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group">
                            <input type="text" name="hair_density[]" class="form-control">
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
                        <button type="button" atName="base_size[]" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
                    </td>
                    <td>
                        <button type="button" atName="hair_colour[]" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
                    </td>
                    <td>
                        <button type="button" atName="hair_density[]" class="btn-group  btn btn-primary" onclick="addCol()">增加</button>
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
        insert_num,  //列下标值
        trinsert_num, //行下标值
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
            _that =$(active_row_tds).find("td")[insert_num];
        insert_num = _$this.parent("td").index();
        //当倒数第二行的表格填满后增加新的一行
        if(table_row_th == adtive_row_col) {
            _$this.parents("tr").before(addRowCom);
            updateNum();
            inp_name = _$this.attr("atName");
            _that =$(active_row_tds).find("td")[insert_num];
            if($(_that).find(".input-group").length == 0) {
              _that.innerHTML=addColCom;   
            }
        }else {
            updateNum();
            inp_name = _$this.attr("atName");
            _that =$(active_row_tds).find("td")[insert_num];
            //获取第一个存在td为空的tr的下标值
            forrows(insert_num);
            //根据下标值查找对应的tr
            var missRow = $(".table").find("tr")[trinsert_num],
            //根据点击按钮的下标值查找第一个存在td为空的tr中的对应下标值的td
                judgeCol = $(missRow).find("td")[insert_num];   
            //判断根据点击事件传入的下标值的为空tr中的对应的td是否为空,
            //如果innerhtml为空时向其中插入模板内容
            //如果tr中存在内容为空的td，但是点击事件传入的下表值对应的td存在，则tr的下标值加一
            var lastNumTr = $(".table").find("tr").length - 1;
            if(trinsert_num > lastNumTr-1) {
                _$this.parents("tr").before(addRowCom);
                updateNum();
                inp_name = _$this.attr("atName");
                 _that =$(active_row_tds).find("td")[insert_num];
                if($(_that).find(".input-group").length == 0) {
                  _that.innerHTML=addColCom;   
                }
            }else {
                judgeCol.innerHTML=addColCom;
            }
            if(insert_num == table_row_th-1) {
                adtive_row_col +=1;
            }
        }
    }
    function updateNum(){
        active_row = $(".table tr").length-2;  //更新数值
        active_row_tds = $(".table tr")[active_row]; //更新数值
        adtive_row_col = $(active_row_tds).find(".input-group").length; //更新数值
    }
    function delCol(){
        var _$this = $(event.target);
        _$this.parents(".input-group").html("");
    }
    //遍历查找td内容为空的行
    //查找第一个td存在为空的情况
    function forrows(tdIndex){
        var trArr = $(".table").find("tr");
        var _that;
        for(var i = 1;i<=trArr.length-1;i++){
            if($(trArr[i]).find(".input-group").length< table_row_th) {
                _that = $(trArr[i]).find("td")[tdIndex];
                if($(_that).find(".input-group").length == 0){
                    trinsert_num = i;   
                    return
                }
            }
        }
    }
</script>