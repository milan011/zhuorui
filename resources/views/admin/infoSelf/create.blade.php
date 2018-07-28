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
            <li><a href="{{route('infoSelf.index')}}">信息列表</a></li>
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
                    <form action="{{route('infoSelf.store')}}" id="manager_store" class="form-horizontal" method="post">
                    {!! csrf_field() !!}
                        <!-- 项目名称 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>项目名称</label>
                            <div class="col-md-4">
                                <input type="text" name="project_name" required placeholder="项目名称" class="form-control" value="{{old('project_name')}}"/>
                            </div>
                        </div>
                        <!-- 客户名 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"><font style="color:red;">*</font>客户姓名</label>
                            <div class="col-md-4">
                                <input type="text" name="name" required placeholder="客户姓名" class="form-control" value="{{old('name')}}"/>
                            </div>
                        </div>
                        <!-- 联系电话 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">联系电话</label>
                            <div class="col-md-4">
                                <input type="text"  name="telephone" placeholder="联系电话" class="form-control" value="{{old('telephone')}}" />
                            </div>
                        </div>
                        <!-- 新号码 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">新号码</label>
                            <div class="col-md-4">
                                <input type="text"  name="new_telephone" placeholder="新号码" class="form-control" value="{{old('new_telephone')}}" />
                            </div>
                        </div>
                        <!-- UIM码 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">UIM码</label>
                            <div class="col-md-4">
                                <input type="text"  name="uim_number" placeholder="UIM码" class="form-control" value="{{old('uim_number')}}" />
                            </div>
                        </div>
                        <!-- 副卡 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">副卡</label>
                            <div class="col-md-4">
                                <input type="text"  name="side_number" placeholder="副卡" class="form-control" value="{{old('side_number')}}" />
                            </div>
                        </div>
                        <!-- 入网时间 -->
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
                        </div>
                        <!-- 收款 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label"></font>收款(元)</label>
                            <div class="col-md-4">
                                <input type="collections" style="width:25%;"  name="collections" placeholder="收款" class="form-control" value="{{old('collections')}}" />
                            </div>
                        </div>
                        <!-- 收款方式 -->
                        <div class="form-group goods_list">
                                <label class="control-label col-md-1">收款方式:</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="collections_type" name="collections_type" style="width:15%;display: inline-block;">
                                        @foreach($collections_type as $key=>$collections)
                                        <option value="{{$key}}" >{{$collections}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                        </div>
                        <!-- 备注 -->
                        <div class="form-group">
                            <label class="col-md-1 control-label">备注</label>
                            <div class="col-md-4">
                            <textarea id="remark" name="remark" style="width:400px;">{{old('remark')}}</textarea>
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
<!-- <script src="{{URL::asset('yazan/js/user.js')}}"></script> -->
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
                            message: '请输入客户经理名'
                        }
                    }
                },              
            }
        }); 
    });
</script>
@endsection