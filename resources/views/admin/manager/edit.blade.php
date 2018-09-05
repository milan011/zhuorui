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
            <li><a href="{{route('manager.index')}}/index">客户经理列表</a></li>
            <li class="active">客户经理修改</li>
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
                    <form id="managerCreate" action="{{route('manager.update', ['manager'=>$manager->id])}}" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    {{ method_field('PUT') }}
                        <div class="form-body">
                            <!-- 客户经理名 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>客户经理名</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="客户经理名" class="form-control" value="{{$manager->name}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>首字母</label>
                            <div class="col-md-4">
                                <input type="text" name="first_letter" placeholder="首字母大写" class="form-control" value="{{$manager->first_letter}}"/>
                            </div>
                        </div>
                        <!-- 联系电话 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">联系电话</label>
                            <div class="col-md-4">
                                <input type="text"  name="telephone" placeholder="手机号" class="form-control" value="{{$manager->telephone}}" />
                            </div>
                        </div>
                        <!-- 微信号 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">微信号</label>
                            <div class="col-md-4">
                                <input type="text"  name="wx_number" placeholder="微信号" class="form-control" value="{{$manager->wx_number}}" />
                            </div>
                        </div>
                        <!-- 邮箱 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">常用邮箱</label>
                            <div class="col-md-4">
                                <input type="email"  name="email" placeholder="常用邮箱" class="form-control" value="{{$manager->email}}" />
                            </div>
                        </div>
                        <!-- 邮箱 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"></font>地址</label>
                            <div class="col-md-4">
                                <input type="address"  name="address" placeholder="地址" class="form-control" value="{{$manager->address}}" />
                            </div>
                        </div>
                        <!-- 备注 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">备注</label>
                            <div class="col-md-4">
                            <textarea id="remark" name="remark" style="width:400px;">{{$manager->remark}}</textarea>
                            </div>
                        </div>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align:center;">
                                    <button type="submit" style="float:left;" id="goodsUpdate" class="btn btn-sm btn-success">修改</button>
                                    <button class="btn" onclick="window.history.go(-1);return false;">返回</button>
                                    
                                </div>
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
<!-- <script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/form-validation.js')}}"></script> -->
<!-- 引入表单select功能js -->
<script src="{{URL::asset('yazan/global/plugins/select2/select2.min.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{URL::asset('yazan/assets/plugins/multi-select/js/jquery.quicksearch.js')}}"></script>
<script src="{{URL::asset('yazan/assets/js/form-select.js')}}"></script>
<!-- 引入goods模块js -->
<!-- <script src="{{URL::asset('yazan/js/goods.js')}}"></script> -->
<script>
    $(document).ready(function(){
        

        $('#managerCreate').bootstrapValidator({
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
                            message: '请输入姓名'
                        }
                    }
                },   
                first_letter: {
                    validators: {
                        notEmpty: {
                            message: '请输入客户经理名首字母'
                        },
                        regexp: {
                            regexp: /^[A-Z]+$/,
                            message: '请输入大写字母!'
                        },
                        stringLength: {
                            min: 1,
                            max: 1,
                            message: '首字母一位即可'
                        },
                    }
                },           
            }
        });
    });
</script>
@endsection