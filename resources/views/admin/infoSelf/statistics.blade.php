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
                <li class="active">信息统计</li>
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
                            <a href="#modal-select" data-toggle="modal" class="btn btn-primary btn-sm">统计</a>
                        </li>
                    </ul>
                    <table id="datatables" class="table table-striped table-border">
                        <thead class="bg-default">
                            <tr>
                                <th>业务员</th>
                                <th>主卡数</th>
                                <th>副卡数</th>
                                <th>入网日期</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($salesman_statistics as $statistic)
                        <tr>
                        	<td>{{$statistic['nick_name'] or ''}}</td> 
                        	<td>{{$statistic['info_nums'] or ''}}</td> 
                        	<td>{{$statistic['side_nums'] or ''}}</td> 
                            <td>{{$netin}}</td>
                            
                        </tr>
                        @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-select" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" class="close"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 id="myModalLabel" class="modal-title">信息搜索</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="condition" action="{{route('infoSelf.statistics')}}" method="post">
                    {!! csrf_field() !!}
                        <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                <label class="control-label col-md-2">入网: </label>
                                <div class="col-md-4">
                                    <select class="form-control" name="netin_year" style="display: inline-block;">
                                        @foreach($package_year as $key=>$year)
                                        <option @if(($netin_year) == ($year)) selected='selected' @endif value="{{$year}}" >{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control" name="netin_month" style="display: inline-block;">
                                        @foreach($package_month as $key=>$mo)
                                        <option @if(($netin_month) == ($key)) selected='selected' @endif value="{{$mo}}" >{{$mo}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
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
