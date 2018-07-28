// 添加车型级联菜单js
$(document).ready(function(){
	//用户角色js
	$('#role_id').change(function(event) {
        /* 用户角色选择 */
        var role_id     = $(this).val();    //角色id
        var role_level  = $(this).find("option:selected").attr('data');   //角色等级
        var user_option = $('#pid').children();
        var request_url  = $("input[name='agents_ajax_request_url']").val();
        var token        = $("input[name='_token']").val();
        /**
         * 选择角色为代理商,则显示代理商等级输入框
         */
        /*console.log(role_id);
        console.log(role_level);*/

        if(role_level >= '1'){
        	console.log(user_option);

        	$.ajax({
			type: 'POST',		
			url: request_url,		
			data: { role_level : role_level},		
			dataType: 'json',		
			headers: {		
				'X-CSRF-TOKEN': token		
			},		
			success: function(data){		
				if(data.status == 1){
					var content = '<option  value="0">==请选择上级代理==</option>';
					$.each(data.data, function(index, value){
						content += '<option value="';
						content += value.id;
						content += '">';
						content += value.nick_name;
						content += '</option>';
					});
					// $('#agents_total').append(content);
					// console.log($('#agents_frist'));
					$('#pid').empty();
					$('#pid').append(content);
					// console.log(content);
				}else{
					alert(data.message);
					$('#pid').empty();
					$('#pid').append('<option  value="0">==请选择上级代理==</option>');
					$('#pid').hide();
					return false;
				}
			},		
			error: function(xhr, type){

				alert('Ajax error!');
			}
		});

        	$('#agents_chain').show();
        }else{

        	//总代\零售不需要上级代理
        	$('#pid').empty();
			$('#pid').append('<option  value="0">==请选择上级代理==</option>');
        	$('#agents_chain').hide();
        }
        /*switch (role_id) {
            case '4':// 添加一级代理
                $('#agents_chain').show();
                // $('#agents_frist').hide();
                // $('#agents_secend').hide();
                console.log('添加一级代理');
            break;
            case '5':// 添加二级代理
                $('#agents_chain').show();
                // $('#agents_frist').css('display','inline-block');
                // $('#agents_secend').hide();
                console.log('添加二级代理');
            break;
            case '6':// 添加三级代理
                $('#agents_chain').show();
                // $('#agents_frist').css('display','inline-block');
                // $('#agents_secend').css('display','inline-block');
                console.log('添加三级代理');
            break;
            case '7':// 添加零售商
                $('#agents_chain').show();
                console.log('添加零售商');
            break;
            default :
                $('#agents_chain').hide();
                //$('#pid_select').hide();
                console.log('不是代理');
        }*/
    });

	$('#role_id').trigger('change'); //刷新页面时触发change事件
	$('#pid')[0].selectedIndex = 0;

	//获得一级代理列表
	/*$('#agents_total').change(function(){

		var agents_total = $(this).val();
		var token        = $("input[name='_token']").val();
		var request_url  = $("input[name='agents_ajax_request_url']").val();
		$('#agents_frist').hide();
		$('#agents_secend').hide();
		// alert(agents_total);return false;
		//获得该总代理的子代理
        $.ajax({
			type: 'POST',		
			url: request_url,		
			data: { pid : agents_total},		
			dataType: 'json',		
			headers: {		
				'X-CSRF-TOKEN': token		
			},		
			success: function(data){		
				if(data.status == 1){
					var content = '<option  value="0">一级代理</option>';
					$.each(data.data, function(index, value){
						content += '<option value="';
						content += value.id;
						content += '">';
						content += value.nick_name;
						content += '</option>';
					});
					// $('#agents_total').append(content);
					// console.log($('#agents_frist'));
					$('#agents_frist').empty();
					$('#agents_frist').append(content);
					// console.log(content);
					$('#agents_frist').css('display', 'inline-block');
				}else{
					alert(data.message);
					$('#agents_frist').empty();
					$('#agents_frist').append('<option  value="0">一级代理</option>');
					$('#agents_frist').hide();
					$('#agents_secend').hide();
					return false;
				}
			},		
			error: function(xhr, type){

				alert('Ajax error!');
			}
		});
	});  
	//获得二级代理列表
	$('#agents_frist').change(function(){

		var agents_frist = $(this).val();
		var token        = $("input[name='_token']").val();
		var request_url  = $("input[name='agents_ajax_request_url']").val();
		$('#agents_secend').hide();
		// alert(agents_frist);return false;
		//获得该总代理的子代理
        $.ajax({
			type: 'POST',		
			url: request_url,		
			data: { pid : agents_frist},		
			dataType: 'json',		
			headers: {		
				'X-CSRF-TOKEN': token		
			},		
			success: function(data){		
				if(data.status == 1){
					var content = '<option  value="0">二级代理</option>';
					$.each(data.data, function(index, value){
						content += '<option value="';
						content += value.id;
						content += '">';
						content += value.nick_name;
						content += '</option>';
					});
					// $('#agents_frist').append(content);
					// console.log($('#agents_frist'));
					$('#agents_secend').empty();
					$('#agents_secend').append(content);
					// console.log(content);
					$('#agents_frist').css('display', 'inline-block');
					$('#agents_secend').css('display', 'inline-block');
				}else{
					alert(data.message);
					$('#agents_secend').empty();
					$('#agents_secend').append('<option  value="0">二级代理</option>');
					$('#agents_secend').hide();
					return false;
				}
			},		
			error: function(xhr, type){

				alert('Ajax error!');
			}
		});
	}); */    
});