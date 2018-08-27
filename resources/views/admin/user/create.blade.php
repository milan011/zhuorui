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
            <li><a href="{{route('user.index')}}">用户列表</a></li>
            <li class="active">添加用户</li>
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
                    <form action="{{route('user.store')}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                        <!-- 用户名 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>用户名</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="用户名" class="form-control" value="{{old('name')}}"/>
                            </div>
                        </div>
                        <!-- 昵称 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>昵称</label>
                            <div class="col-md-4">
                                <input type="text" required name="nick_name" placeholder="用户昵称" class="form-control" value="{{old('nick_name')}}"/>
                            </div>
                        </div>
                        <!-- 密码 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>登录密码</label>
                            <div class="col-md-4">
                                <input type="password" style="display:none">
                                <input type="password" required name="password" placeholder="请输入密码" class="form-control" value=""/>
                            </div>
                        </div>
                        <!-- 密码确认 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>确认密码</label>
                            <div class="col-md-4">
                                <input type="password" required name="password_confirmation" placeholder="再次输入密码" class="form-control" value=""/>
                            </div>
                        </div>
                        <!-- 联系电话 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>联系电话</label>
                            <div class="col-md-4">
                                <input type="text" required name="telephone" placeholder="手机号" class="form-control" value="{{old('telephone')}}" />
                            </div>
                        </div>
                        <!-- 微信号 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>微信号</label>
                            <div class="col-md-4">
                                <input type="text" required name="wx_number" placeholder="微信号" class="form-control" value="{{old('wx_number')}}" />
                            </div>
                        </div>
                        <!-- 邮箱 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>常用邮箱</label>
                            <div class="col-md-4">
                                <input type="email" required name="email" placeholder="常用邮箱" class="form-control" value="{{old('email')}}" />
                            </div>
                        </div>
                        <!-- 备注 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">备注</label>
                            <div class="col-md-4">
                            <textarea id="remark" name="remark" required style="width:400px;">{{old('remark')}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4" style="text-align:center;">
                             	<button type="submit" class="btn btn-sm btn-success">添加</button>
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
<!-- <script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script> -->
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.quicksearch.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-select.js')}}"></script>
<!-- 引入user模块js -->
<script src="{{URL::asset('yazan/js/user.js')}}"></script>
<script>
	$(document).ready(function(){
        
	});
</script>
@endsection