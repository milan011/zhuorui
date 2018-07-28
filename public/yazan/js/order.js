// 添加用户级联菜单js
$(document).ready(function(){
	// alert('xixi');
	//用户代理等级ajax
	var is_update = $('#is_update').val();

	if(is_update != '1'){
		$('.goods_category')[0].selectedIndex = 0; 
		$('.goods_list').first().children('div').children('input.goods_num').val('1');
		$('.goods_list').first().children('div').children('input.goods_price').val('0');
		$('.goods_list').first().children('div').children('input.total_price').val('0');
	}
	
	//获得一级代理列表
	$('#user_id').change(function(){

		var user_id      = $(this).val();
		var token        = $("input[name='_token']").val();
		var request_url  = $("input[name='user_ajax_request_url']").val();
		
		// alert(user_id);return false;
		//获得该总代理的子代理
        $.ajax({
			type: 'POST',		
			url: request_url,		
			data: { user_id : user_id},		
			dataType: 'json',		
			headers: {		
				'X-CSRF-TOKEN': token		
			},		
			success: function(data){		
				if(data.status == 1){
					/*console.log(data.data);
					console.log(data.self);*/
					
					var content = '';

					// content += '<span style="color:red;">';
					content += data.self.role_name;
					content += ':';
					content += data.self.nick_name;
					// content += '</span>';

					$.each(data.data.parent, function(index, value){

						content += '==>';
						content += value.role_name;
						content += ':';
						content += value.nick_name;
						
					});
					// $('#user_id').append(content);
					// console.log($('#agents_frist'));
					$('#merchant_info').empty();
					$('#merchant_info').append(content);
					$('#nick_name').val(data.self.nick_name);
					$('#level').val(data.self.level);
					$('#user_telephone').val(data.self.telephone);
					$('#user_top_id').val(data.self.user_top_id);
					$('#send_name').val(data.self.nick_name);
					$('#send_telephone').val(data.self.telephone);
					//改变下单商户是刷新商品价格
					$('.goods').each(function(index, el) {
						$(this).trigger('change');
						// alert('hehe');
					});
					//$('#agents_frist').css('display', 'inline-block');
				}else{
					alert(data.message);
					$('#agents_frist').empty();
					$('#agents_frist').append('<option  value="0">--一级代理--</option>');
					$('#agents_frist').hide();
					$('#agents_secend').hide();
					return false;
				}
			},		
			error: function(xhr, type){

				/*alert('Ajax error!');*/
			}
		});
	});   

	$('#user_id').trigger('change'); //刷新页面时触发change事件 

	//增加商品
    $('#goods_add').click(function(){

        var form_goods = $('.goods_list').first().clone(true);
        var content    = $('.goods_list').last();
        var is_update  = $('#is_update').val();

		if(is_update == '1'){
			$(form_goods).children('div').children('select.goods_category').attr('name', 'goods_category_i[]');
			$(form_goods).children('div').children('select.goods').attr('name', 'goods_id_i[]');
			$(form_goods).children('div').children('input.goods_num').attr('name', 'goods_num_i[]');
			$(form_goods).children('div').children('input.goods_price').attr('name', 'goods_price_i[]');
			$(form_goods).children('div').children('input.total_price').attr('name', 'total_price_i[]');
			$(form_goods).children('div').children('input.goods_name').attr('name', 'goods_name_i[]');
			$(form_goods).children('div').children('input.order_goods_id').remove();
		}

        // console.log(form_goods);return false;
        content.after(form_goods);

        $('.goods_list').last().children('div').children('select.goods').empty();
        $('.goods_list').last().children('div').children('input.goods_num').val('1');
        $('.goods_list').last().children('div').children('input.goods_price').val('0');
        $('.goods_list').last().children('div').children('input.total_price').val('0');
        $('.goods_list').last().children('div').children('select.goods').append('<option  value="0">==选择商品==</option>');
        // console.log(content.children('div').children('select.goods'));
    });

    // 删除商品
    $('.goods_delete').click(function(event) {
        /* Act on the event */
        var goods_list_num = $('.goods_list').length;
        // console.log(goods_list_num);
        if(goods_list_num == 1){
            alert('大哥,留一个呗');
            return false;
        }
        var obj = $(this);
        $.confirm({
            title: '注意!',
            content: '确实要删除吗?',
            cancelButton: '取消',
            confirmButtonClass: 'btn-danger',
            confirm: function () {
                obj.parents('.goods_list').remove();
                // console.log(obj.parent('form'));
                // return false;
            },
            cancel: function () {
                return false;
            }
        });
        // $(this).parents('.goods_list').remove();
        // console.log($(this).parents('.goods_list'));
    });

    //商品ajax
    $('.goods_category').change(function(){

		var category_id  = $(this).val();
		var token        = $("input[name='_token']").val();
		var request_url  = $("input[name='goods_ajax_request_url']").val();
		var goods_list   = $(this).next();
		// alert(agents_total);return false;
		// console.log(goods_list);
		//获得该总代理的子代理
		$.ajax({
			type: 'POST',       
			url: request_url,       
			data: { category_id : category_id},        
			dataType: 'json',       
			headers: {      
			'X-CSRF-TOKEN': token       
			},      
			success: function(data){   
               	// console.log(data);  
                if(data.status == 1){

                    var content = '<option  value="">==选择商品==</option>';
                    $.each(data.data, function(index, value){
                        content += '<option value="';
                        content += value.id;
                        content += '">';
                        content += value.name;
                        content += '</option>';
                    });
                    // $('#agents_total').append(content);
                    // console.log($('#agents_frist'));
                    goods_list.empty();
                    goods_list.append(content);
                    // console.log(content);
                }else{
                    alert(data.message);
                    goods_list.empty();
                    goods_list.append('<option  value="">==选择商品==</option>');
                    return false;
                }
            },      
           	error: function(xhr, type){
    
                /*alert('Ajax error!');*/
            }
        });
    });

	//商品价格
    $('.goods').change(function(){

		var goods_id     = $(this).val();
		var token        = $("input[name='_token']").val();
		var request_url  = $("input[name='goods_price_ajax_request_url']").val();
		var goods_list   = $(this).next();
		var user_id      = $('#user_id').val();
		var goods_num    = $(this).next().val();
		var goods_price  = $(this).nextAll('input.goods_price');
		var goods_total  = $(this).nextAll('input.total_price');
		// var goods_name   = $(this).next().next().next().next();
		var goods_name   = $(this).nextAll('input.goods_name');
		// alert(agents_total);return false;
		// console.log(goods_total);
		// console.log(goods_name1);
		//获得该总代理的子代理
		$.ajax({
			type: 'POST',       
			url: request_url,       
			data: { goods_id : goods_id, user_id : user_id},        
			dataType: 'json',       
			headers: {      
			'X-CSRF-TOKEN': token       
			},      
			success: function(data){   
               	  
                if(data.status == 1){
                    goods_price.val(data.price);
                    goods_total.val(data.price*goods_num + '元');
                    goods_name.val(data.goods_name);
                    // console.log(goods_price.val());
                }else{
                    alert(data.message);
                    return false;
                }
            },      
           	error: function(xhr, type){
    
                /*alert('Ajax error!');*/
            }
        });
    });

    //商品数目变化
    $('.goods_num').change(function(event) {
    	/* Act on the event */
    	var num         = $(this).val();
    	var goods_price = $(this).next().val();
    	var total_price = $(this).next().next();

    	total_price.val(num*goods_price + '元');
    });

});