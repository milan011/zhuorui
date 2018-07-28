@extends('layouts.main')

@section('head_content')
<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/select2/select2-custom.css')}}">
<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/multi-select/css/multi-select-custom.css')}}">
<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-validator/css/bootstrapValidator.min.css')}}">
    <style type="text/css">
        input.shaddress{
            margin-bottom:5px;
        }
    </style>
@endsection

<!-- 面包屑 -->
@section('BreadcrumbTrail')

<section class="content-header">
    <div class="pull-left">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.index')}}">首页</a></li>
            <li><a href="{{route('package.index')}}">套餐列表</a></li>
            <li class="active">添加套餐</li>
        </ol>
    </div>
</section>
@endsection
<!-- 主体 -->
@section('content')

@include('layouts.message')

<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
                    <form id="packageCreate" action="{{route('package.store')}}" class="form-horizontal" method="post">
                    	{!! csrf_field() !!}
                        <!-- 套餐名称 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>套餐名称</label>
                            <div class="col-md-4">
                                <input type="text" name="package_name" required placeholder="套餐名称" class="form-control" value="{{old('name')}}"/>
                            </div>
                        </div>
                        <!-- 所属集团 -->
                        <!-- <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>所属集团</label>
                            <div class="col-md-4">
                                <input type="text" name="bloc" required placeholder="所属集团" class="form-control" value="{{old('bloc')}}"/>
                            </div>
                        </div> -->
                        <!-- 套餐价格 -->
                        <div class="form-group">
                                <label class="control-label col-md-1">套餐价格: </label>
                                <div class="col-md-2">
                                    <input style="display: inline-block;"  placeholder="套餐价格" type="text" name="package_price" id="package_price" value="{{old('package_price')}}" class="form-control" />
                                </div>
                        </div>
                        <!-- 入网时间
                        <div class="form-group">
                                <label class="control-label col-md-1">入网时间: </label>
                                <div class="col-md-1">
                                    <select class="form-control" name="month_nums" style="display: inline-block;">
                                        @foreach($package_year as $key=>$year)
                                        <option value="{{$key}}" >{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select class="form-control" name="month_nums" style="display: inline-block;">
                                        @foreach($package_month as $key=>$mo)
                                        <option value="{{$key}}" >{{$mo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div> -->
                        <!-- 返还期 -->
                        <div class="form-group goods_list">
                                <label class="control-label col-md-1">返还期: <span class="required">*</span></label>
                                <div class="col-md-8">
                                    <select class="form-control" id="month_nums" name="month_nums" style="width:15%;display: inline-block;">
                                        <!-- <option  value="0">=返还期=</option> -->
                                        @foreach($return_month as $key=>$month)
                                        <option value="{{$month}}" >{{$month}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <!-- 每期返还金额 -->
                        <div class="form-group" id="monthn_price" style="display:none;">
                            <label class="col-md-1 control-label">月返金额:<font style="color:red;">*</font></label>
                            <div id="month_price_list" class="col-md-1">
                                <input style="margin-bottom: 5px;" type="text" name="month_price[]" required placeholder="首月" class="form-control" value=""/>
                                
                                <input style="margin-bottom: 5px;" type="text" name="month_price[]" required placeholder="首月" class="form-control" value=""/>
                            </div>
                        </div>
                        <!-- 备注 -->
                        <div class="form-group">
                                <label class="control-label col-md-1">备注:</span></label>
                                <div class="col-md-4">
                                    <textarea id="remark" name="remark" required style="width:400px;">{{old('remark')}}</textarea>
                                </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4" style="text-align:center;">
                             	<button type="submit" id="package_add" class="btn btn-sm btn-success">添加</button>
                                <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script_content')
<!-- 引入表单验证js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
<script>
	$(document).ready(function(){

		$("#month_nums").change(function() {

            var month_num = $(this).val(); //还款期数
            var contents = '';
            var month_price_list = $('#month_price_list');

            month_price_list.empty();

            if(month_num != 0){

                for (var i=0; i<month_num; i++){ 

                    contents += '<input style="margin-bottom:5px;" type="text" name="month_price[]" required placeholder="第';
                    contents += i+1;
                    contents += '个月" ';
                    contents += 'class="form-control" value="" />';

                }

                month_price_list.append(contents);

                $('#monthn_price').show();
            }else{

                $('#monthn_price').hide();
            }

            // console.log(contents);
        }); 

        $("#month_nums").trigger('change'); //刷新时触发change事件
        
        $('#packageCreate').bootstrapValidator({
            live: 'submitted',
            feedbackIcons: {
                valid: '',
                invalid: '',
                validating: ''
            },
            fields: {
                package_name: {
                    validators: {
                        notEmpty: {
                            message: '请输入套餐名称'
                        }
                    }
                }, 
                package_price: {
                    validators: {
                        notEmpty: {
                            message: '请输入套餐价格'
                        }
                    }
                },
                remark: {
                    validators: {
                        notEmpty: {
                            message: '请输入备注'
                        }
                    }
                },             
            }
        }); 
	});    
</script>
@endsection
