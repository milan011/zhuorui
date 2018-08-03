@extends('layouts.main')

@section('head_content')
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datetimepicker/jquery.datetimepicker.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/timepicker/jquery.timepicker.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/clockpicker/css/bootstrap-clockpicker.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/data-tables/DT_bootstrap.css')}}">
	<style>

    </style>
@endsection

@section('BreadcrumbTrail')
	<section class="content-header">
        <div class="pull-left">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}">首页</a></li>
                <li class="active">客户经理列表</li>
            </ol>
        </div>
    </section>
@endsection

@section('content')

@include('layouts.message')
<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-body">
            		<ul class="nav nav-tabs">
            		  	<li style="display: inline-block;line-height:20px;">
                            <a href="#modal-select" data-toggle="modal" class="btn btn-primary btn-sm">搜索客户经理</a>
						</li>
            		  	<li style="display: inline-block;line-height:20px;float:right;">
							<a class="btn btn-primary" href="{{route('manager.create')}}">添加客户经理</a>
						</li>
						<li style="display:inline-block;line-height:20px;float:right;">
							<a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
						</li>
            		</ul>
                    <table id="datatables" class="table table-striped table-no-border">
                        <thead class="bg-default">
                            <tr>
                                <th>客户经理</th>
                                <th>电话</th>
                                <th>创建日期</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($all_manager as $manager)
                        <tr>
                            <td>
                                <a target="_blank" href="{{route('manager.show', ['manager'=>$manager->id])}}">
                                    {{$manager->name}}
                                </a>
                            </td>
                            <td>{{$manager->telephone or ''}}</td>                           
                            <td>{{substr($manager->created_at, 0 ,10)}}</td>                                
                            <td class="center">
                                <!-- <a class="btn btn-success" target="_blank" href="{{route('manager.show', ['manager'=>$manager->id])}}">
                                    <i class="icon-edit icon-white"></i> 查看
                                </a> -->
                                <a class="btn btn-warning"  href="{{route('manager.edit', ['manager'=>$manager->id])}}">
                                    <i class="icon-edit icon-white"></i> 编辑
                                </a>
                                <span>
                                <form action="{{route('manager.destroy', ['manager'=>$manager->id])}}" method="post" style="display: inherit;margin:0px;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-danger delete-confrim" type="button">
                                        <i class="icon-trash icon-white"></i> 删除
                                    </button>
                                </form>
                                </span>
                            </td>
                        </tr>
                        @endforeach 
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 col-sm-12">
                	<div class="dataTables_paginate paging_simple_numbers" id="datatables_paginate">
                	    <div class="pagination pagination-centered">
                	       {!! $all_manager->links() !!}
                        </div>
                	</div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-select" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 id="myModalLabel" class="modal-title">客户经理搜索</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="condition" action="{{route('manager.index')}}/index" method="post">
                    {!! csrf_field() !!}
                        <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" value="{{$select_conditions['user_telephone'] or ''}}"  name="user_telephone" placeholder="客户电话" class="col-md-12 form-control mbm" />
                                <input type="text" name="date" value="{{$select_conditions['date'] or ''}}" placeholder="日期" id="daterangepicker_default" class="col-md-12 form-control mbm" />
                                <label class="control-label" for="category_type">信息状态:</label>
                                <select name="status" class="col-md-4 form-control mbm">
                                    <option value=''>不限</option>                                        
                                </select>
                            </div>
                        </div> 
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">搜索</button>
                            <a href="javascript:void(0);" class="btn" data-dismiss="modal">关闭</a>                            
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
<!-- 引入表格js -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/datatables/media/js/jquery.dataTables.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js')}}"></script> -->
<!-- <script src="{{URL::asset('yazan/assets/js/table-datatables.js')}}"></script> -->
<!-- 引入确认框js -->
<script src="{{URL::asset('yazan/js/confirm.js')}}"></script> 
<!-- 引入日期插件js -->
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-daterangepicker/moment.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/clockpicker/js/bootstrap-clockpicker.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/jquery-file-input/file-input.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/bootstrap-slider/js/bootstrap-slider.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/selectize/js/standalone/selectize.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/datetimepicker/jquery.datetimepicker.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/timepicker/jquery.timepicker.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/jquery-minicolors/jquery.minicolors.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/dropzone/js/dropzone.min.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/js/form-plugins.js')}}"></script>  
<!-- 引入编辑表格js -->
<script src="{{URL::asset('yazan/assets/plugins/data-tables/jquery.dataTables.js')}}"></script>  
<!-- <script src="{{URL::asset('yazan/assets/plugins/data-tables/DT_bootstrap.js')}}"></script>   -->
<script src="{{URL::asset('yazan/assets/js/editable-table.js')}}"></script>  

<script type="text/javascript">

	jQuery(document).ready(function($){

	});
</script>

@endsection
