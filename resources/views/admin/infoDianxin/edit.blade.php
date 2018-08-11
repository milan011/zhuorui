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
			<form class="form-horizontal" id="info_update" action="{{route('infoDianxin.update', ['info'=>$info->id])}}" method="post" enctype="multipart/form-data">
				{!! csrf_field() !!}
				{{ method_field('PUT') }}
				<fieldset>
				<!-- 返款号码 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">返款号码:</label>
                            <div class="col-md-4">
                                <input type="text"  name="return_telephone" readonly placeholder="返款号码" class="form-control" value="{{$info->return_telephone}}" />
                            </div>
                        </div>
                        <!-- 套餐名称 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>套餐名称:</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="套餐名称" class="form-control" value="{{$info->name}}"/>
                            </div>
                        </div>
                        <!-- 客户经理 -->
                        <!-- <div class="form-group">
                            <label class="control-label col-md-1">客户经理:</label>
                            <div class="col-md-8">
                                <select class="form-control" id="manager" name="manager" style="width:15%;display: inline-block;">
                                    
                                </select>
                            </div>
                        </div> -->
                        <!-- 佣金方案 -->
                        <div class="form-group">
                            <label class="control-label col-md-1">佣金方案:</label>
                            <div class="col-md-4">
                                <input type="text" name="yongjin" required placeholder="佣金方案" class="form-control" value="{{$info->yongjin}}"/>
                            </div>
                        </div>
                        <!-- 返款金额 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">返款金额:</label>
                            <div class="col-md-4">
                                <input type="text"  name="refunds" placeholder="返款金额" class="form-control" value="{{$info->refunds}}" />
                            </div>
                        </div>
                        <!-- 价款 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>价款:</label>
                            <div class="col-md-4">
                                <input type="text" name="jiakuan" required placeholder="价款" class="form-control" value="{{$info->jiakuan}}"/>
                            </div>
                        </div>
                        <!-- 结算月 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">结算月:</label>
                            <div class="col-md-4">
                                <input type="text"  name="balance_month" placeholder="结算月" class="form-control" value="{{$info->balance_month}}" />
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
                                        <option @if(($netin_month) == ($key)) selected='selected' @endif value="{{$mo}}" >{{$mo}}</option>
                                        @endforeach
                                    </select>
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
                            message: '请输入套餐名称'
                        }
                    }
                }, 
                yongjin: {
                    validators: {
                        notEmpty: {
                            message: '请输入佣金'
                        }
                    }
                },
                refunds: {
                    validators: {
                        notEmpty: {
                            message: '请输入返款金额'
                        }
                    }
                },
                balance_month: {
                    validators: {
                        notEmpty: {
                            message: '请输入结算月'
                        }
                    }
                },
                jiakuan: {
                    validators: {
                        notEmpty: {
                            message: '请输入价款'
                        }
                    }
                },
                return_telephone: {
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