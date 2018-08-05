@extends('layouts.main')

@section('head_content')
    
	<style>
		
    </style>
@endsection

<!-- 面包屑 -->
@section('BreadcrumbTrail')
<ul class="breadcrumb">
	<li>
		<i class="icon-home"></i>
		<a href="{{route('admin.index')}}">主页</a>  
		<i class="icon-angle-right"></i>
	</li>
	<li>
		<i class="icon-home"></i>
		<a href="{{route('infoSelf.index')}}">信息列表</a> 
		<i class="icon-angle-right"></i>
	</li>
	<li><a href="#1f">修改信息</a></li>
</ul>
@endsection
<!-- 主体 -->
@section('content')

@include('layouts.message')

<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
			<form class="form-horizontal" id="info_update" action="{{route('infoSelf.update', ['info'=>$info->id])}}" method="post" enctype="multipart/form-data">
				{!! csrf_field() !!}
				{{ method_field('PUT') }}
				<fieldset>
				<!-- 新号码 -->
                <div class="form-group">
                    <label class="col-md-1 control-label">新号码:</label>
                        <div class="col-md-4">
                            <input type="text" readonly="readonly"  name="new_telephone" placeholder="新号码" class="form-control" value="{{$info->new_telephone}}" />
                        </div>
                 </div>
				<div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>项目名称:</label>
                            <div class="col-md-4">
                                <input type="text" name="project_name" required placeholder="项目名称" class="form-control" value="{{$info->project_name}}"/>
                            </div>
                </div>
                        <!-- 客户经理 -->
                        <div class="form-group">
                            <label class="control-label col-md-1">客户经理:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="manager" name="manager" style="width:15%;display: inline-block;">
                                    @foreach($managers as $key=>$manager)
                                        <option @if(($info->manage_id) == ($manager->id)) selected='selected' @endif value="{{$manager->id}}" >{{$manager->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- 套餐 -->
                        <div class="form-group">
                            <label class="control-label col-md-1">套餐:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="package" name="package_id" style="width:15%;display: inline-block;">
                                    @foreach($packages as $key=>$package)
                                        <option @if(($info->package_id) == ($package->id)) selected='selected' @endif value="{{$package->id}}" >{{$package->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- 客户名 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>客户姓名:</label>
                            <div class="col-md-4">
                                <input type="text" name="name" placeholder="客户姓名" class="form-control" value="{{$info->name}}"/>
                            </div>
                        </div>
                        <!-- 联系电话 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">联系电话:</label>
                            <div class="col-md-4">
                                <input type="text"  name="telephone" placeholder="联系电话" class="form-control" value="{{$info->user_telephone}}" />
                            </div>
                        </div>
                        <!-- UIM码 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">UIM码:</label>
                            <div class="col-md-4">
                                <input type="text"  name="uim_number" placeholder="UIM码" class="form-control" value="{{$info->uim_number}}" />
                            </div>
                        </div>
                        <!-- 副卡 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">副卡:</label>
                            <div id="fuka_info" class="col-md-4">
                            @if(!empty($side_number_array))
                            	@foreach($side_number_array as $key=>$side_number)
                            		@if($key == 0)
                                		<input type="text" class="form-control fuka_list" style="display: inline-block;width:75%;margin-bottom:5px;"  name="side_numbers[]" placeholder="副卡" class="form-control" value="{{$side_number}}" />
                                		<button style="display: inline-block;width:20%;" id="fuka_add" type="button" class="btn btn-success">
                                			添加副卡
                                		</button>
                                	@else
										<input type="text" class="form-control fuka_list" style="display: inline-block;width:75%;margin-bottom:5px;"  name="side_numbers[]" placeholder="副卡" class="form-control" value="{{$side_number}}" />
                                		<button style="display: inline-block;width:20%;" type="button" class="btn btn-danger fuka_del">
                                			删除
                                		</button>
                                	@endif
                                @endforeach
                            @else
								<input type="text" class="form-control fuka_list" style="display: inline-block;width:75%;margin-bottom:5px;"  name="side_numbers[]" placeholder="副卡" class="form-control" value="" />
                                <button style="display: inline-block;width:20%;" id="fuka_add" type="button" class="btn btn-success">
                                	添加副卡
                                </button>
                            @endif
                            </div>
                            
                        </div>
                        <!-- 是否绑老卡 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">绑老卡:</label>
                            <div class="col-md-9">
                                <label class="switch switch-primary">
                                    <input @if($info->old_bind == '1') checked @endif name="old_bind" style="" type="checkbox"><span class="switch"></span>
                                </label>
                            </div>
                        </div>
                        <!-- 入网时间 -->
                        <div class="form-group">
                                <label class="control-label col-md-1">入网时间: </label>
                                <div class="col-md-1">
                                    <select class="form-control" name="netin_year" style="display: inline-block;">
                                        @foreach($package_year as $key=>$year)
                                        <option @if(($netin_year) == ($year)) selected='selected' @endif value="{{$year}}" >{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select class="form-control" name="netin_moth" style="display: inline-block;">
                                        @foreach($package_month as $key=>$mo)
                                        <option @if(($netin_month) == ($mo)) selected='selected' @endif value="{{$mo}}" >{{$mo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <!-- 收款 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"></font>收款(元):</label>
                            <div class="col-md-4">
                                <input type="collections" style="width:25%;"  name="collections" placeholder="收款" class="form-control" value="{{$info->collections}}" />
                            </div>
                        </div>
                        <!-- 收款方式 -->
                        <div class="form-group goods_list">
                                <label class="control-label col-md-1">收款方式:</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="collections_type" name="collections_type" style="width:15%;display: inline-block;">
                                        @foreach($collections_type as $key=>$collections)
                                        <option @if(($info->collections_type) == ($key)) selected='selected' @endif value="{{$key}}" >{{$collections}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                        </div>
                        <!-- 备注 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">备注</label>
                            <div class="col-md-4">
                            <textarea id="remark" name="remark" style="width:400px;">{{$info->remark}}</textarea>
                            </div>
                        </div>	  				
				  <div class="form-actions">
					<button type="submit" class="btn btn-primary">确定</button>
					<button class="btn" onclick="window.history.go(-1);return false;">返回</button>
				  </div>
				</fieldset>
			</form>				
                </div>
            </div>
        </div>
    </div>
</section>  
@endsection
@section('script_content')
<!-- 引入副卡处理模块js -->
<script src="{{URL::asset('yazan/js/fuka.js')}}"></script>
<!-- 引入表单验证js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
<script>
    $(document).ready(function(){
        //表单验证
        $('#info_update').bootstrapValidator({
            live: 'submitted',
            feedbackIcons: {
                valid: '',
                invalid: '',
                validating: ''
            },
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: '请输入客户经理名'
                        }
                    }
                }, 
                collections: {
                    validators: {
                        notEmpty: {
                            message: '请输入收款金额'
                        }
                    }
                },
                telephone: {
                    validators: {
                        notEmpty: {
                            message: '请输入手机号码'
                        },
                        regexp: {
                            regexp: /^1[3|5|7|8|9]{1}[0-9]{9}$/,
                            message: '请输入正确的手机号!'
                        },
                    }
                },  
                new_telephone: {
                    validators: {
                        notEmpty: {
                            message: '请输入手机号码'
                        },
                        regexp: {
                            regexp: /^1[3|5|7|8|9]{1}[0-9]{9}$/,
                            message: '请输入正确的手机号!'
                        },
                    }
                },          
            }
        }); 
    });
</script>
@endsection