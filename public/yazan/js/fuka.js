// 删除副卡
$("#fuka_info").on("click",".fuka_del", function() {

    // 删除对应副卡信息及按钮本身
    $(this).prev().remove();
    $(this).remove();
            // console.log($(this).prev());
});

//增加副卡
$('#fuka_add').click(function(){

    var form_fuka = $('.fuka_list').first().clone(true);
    var form_del_button = '';
           

    form_del_button += '<button style="display: inline-block;width:20%;margin-left:2px;" type="button" class="btn btn-danger fuka_del">删除</button>';
            
            // console.log(form_fuka);return false;
    $(form_fuka).val('');
    $('#fuka_add').after(form_del_button);
    $('#fuka_add').after(form_fuka);
            
});