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
            <li><a href="{{route('infoDianxin.index')}}">信息列表</a></li>
            <li class="active">添加信息</li>
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
                    <form action="{{route('infoDianxin.store')}}" id="manager_store" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                    <!-- 返款号码 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">返款号码:</label>
                            <div class="col-md-4">
                                <input type="text"  name="return_telephone" placeholder="返款号码" class="form-control" value="{{old('return_telephone')}}" />
                            </div>
                        </div>
                        <!-- 套餐名称 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>套餐名称:</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="套餐名称" class="form-control" value="{{old('name')}}"/>
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
                                <input type="text" name="yongjin" required placeholder="佣金方案" class="form-control" value="{{old('yongjin')}}"/>
                            </div>
                        </div>
                        <!-- 返款金额 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">返款金额:</label>
                            <div class="col-md-4">
                                <input type="text"  name="refunds" placeholder="返款金额" class="form-control" value="{{old('refunds')}}" />
                            </div>
                        </div>
                        <!-- 价款 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>价款:</label>
                            <div class="col-md-4">
                                <input type="text" name="jiakuan" required placeholder="价款" class="form-control" value="{{old('jiakuan')}}"/>
                            </div>
                        </div>
                        <!-- 结算月 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">结算月:</label>
                            <div class="col-md-4">
                                <input type="text"  name="balance_month" placeholder="结算月" class="form-control" value="{{old('balance_month')}}" />
                            </div>
                        </div>
                        <!-- 入网时间 -->
                        <div class="form-group">
                                <label class="control-label col-md-1">入网时间: </label>
                                <div class="col-md-1">
                                    <select class="form-control" name="netin_year" style="display: inline-block;">
                                        @foreach($package_year as $key=>$year)
                                        <option @if(($dt_year) == ($year)) selected='selected' @endif value="{{$year}}" >{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select class="form-control" name="netin_moth" style="display: inline-block;">
                                        @foreach($package_month as $key=>$mo)
                                        <option @if(($dt_month) == ($key)) selected='selected' @endif value="{{$mo}}" >{{$mo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <!-- 备注 -->
                        <!-- <div class="form-group">
                            <label class="col-md-1 control-label">备注</label>
                            <div class="col-md-4">
                            <textarea id="remark" name="remark" style="width:400px;">{{old('remark')}}</textarea>
                            </div>
                        </div> -->

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
<!-- 引入副卡处理模块js -->
<script src="{{URL::asset('yazan/js/fuka.js')}}"></script>
<script>
    $(document).ready(function(){
        //表单验证
        $('#manager_store').bootstrapValidator({
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