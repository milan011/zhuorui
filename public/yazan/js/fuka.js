// 删除副卡
$("#fuka_info").on("click",".fuka_del", function() {

    // 删除对应副卡信息及按钮本身
    $(this).parent().remove();
    $(this).remove();
            // console.log($(this).prev());
});

//增加副卡
$('#fuka_add').click(function(){

    /*var form_fuka = $('.fuka_list').first().clone(true);
    var form_del_button = '';
           

    form_del_button += '<button style="display: inline-block;width:20%;margin-left:2px;" type="button" class="btn btn-danger fuka_del">删除</button>';*/

    var fuka_content = '<div class="fuka_list form-inline" style="margin-bottom:5px;"><input type="text" class="form-control"   name="side_numbers[]" style="margin-right:3px;" placeholder="副卡" class="form-control" value="" /><input type="text" class="form-control" style="margin-right:3px;"  name="side_uim_numbers[]" placeholder="副卡UIM" class="form-control" value="" /><button  type="button" class="btn btn-danger fuka_del" style="margin-left:">删除</button></div>';
            
            // console.log(form_fuka);return false;
    /*$(form_fuka).val('');
    $('#fuka_add').after(form_del_button);
    $('#fuka_add').after(form_fuka);*/
    $("#fuka_info").append(fuka_content);
            
});