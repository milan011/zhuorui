@extends('layouts.main')

@section('head_content')
<link id="bootstrap-style" href="{{ URL::asset('css/tcl/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<!-- <link id="bootstrap-style" href="{{ URL::asset('css/tcl/dropzone/dropzone.css') }}" rel="stylesheet"> -->
	<style type="text/css">
		.one_line{
			width:40%;
			margin-bottom:5px;
		}
		ul.dropdown-menu::after, ul.dropdown-menu::before{
			top: -1px;
			left: 10px;
			border-right: 9px solid transparent;
			border-bottom: 9px solid #222 !important;
			border-left: 9px solid transparent;
			content: none;
		}

		ul.dropdown-menu{
			min-width:100%;
		}

		/*.dropzone{
			padding: 30px 20px;
		}*/
	</style>
@endsection

@section('BreadcrumbTrail')	
	<section class="content-header">
        <div class="pull-left">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}">首页</a></li>
                <li class="active">电信信息</li>
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
                            <a href="#modal-select" data-toggle="modal" class="btn btn-primary btn-sm">搜索信息</a>
						</li>
						<li style="display: inline-block;line-height:20px;">
                            <a href="{{route('infoDianxin.create')}}" data-toggle="modal" class="btn btn-primary btn-sm">添加信息</a>
						</li>
            		  	<li style="display: inline-block;line-height:20px;float:right;">
							<button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modal-file-upload">
                				<i class="fa fa-upload"></i> 导入
            				</button>
						</li>
						<li style="display:inline-block;line-height:20px;">
							<a href="{{route('infoDianxin.exampleExcelDownload')}}" class="btn btn-primary btn-sm">标准表下载</a>
						</li>
						<li style="display:inline-block;line-height:20px;">
							<a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
						</li>
            		</ul>
        		</div>

				<table  class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>返还号码</th>
							<th>返还金额</th>
							<th>返还月</th>						
							<th>返还日期</th>						
							<th>导入时间</th>
							<th>操作</th>
						</tr>
					</thead> 
					<tbody>
						@foreach ($infos as $info)
    					<tr>
							<td>{{$info->return_telephone}}</td>							
							<td>{{$info->refunds}}</td>		
							<td>{{$info->balance_month}}</td>		
							<td>{{$info->netin}}</td>		
							<td>{{$info->belongsToCreater->nick_name}}|{{substr($info->created_at, 0 ,10)}}</td>		
							<td class="center">
                                <a class="btn btn-success" target="_blank" href="{{route('infoDianxin.show', ['info'=>$info->id])}}">
                                    <i class="icon-edit icon-white"></i> 查看
                                </a>
                                <a class="btn btn-warning"  href="{{route('infoDianxin.edit', ['info'=>$info->id])}}">
                                    <i class="icon-edit icon-white"></i> 编辑
                                </a>
                                <span>
                                <form action="{{route('infoDianxin.destroy', ['info'=>$info->id])}}" method="post" style="display: inherit;margin:0px;">
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
            	<div class="dataTables_paginate paging_simple_numbers" style="float:left;">
                        <div class="pagination pagination-centered">
                          <ul class="pagination">
                            <li class="disabled"><span>共{{ $infos->total() }}条</span></li>
                          </ul>
                        </div>
                    </div>
                <div class="dataTables_paginate paging_simple_numbers" id="datatables_paginate">
                	<div class="pagination pagination-centered">
                	    {!! $infos->links() !!}
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
                    <h4 id="myModalLabel" class="modal-title">信息搜索</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="condition" action="{{route('infoDianxin.index')}}/index" method="post">
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
 <!-- 上传文件 -->
<div class="modal fade" id="modal-file-upload">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/upload/file" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="folder" value="{{ $folder or '' }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        ×
                    </button>
                    <h4 class="modal-title">Excel导入</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file" class="col-sm-3 control-label" style="padding-top: 4px;">
                            Excel文件:
                        </label>
                        <div class="col-sm-8">
                            <input type="file" id="file" name="file">
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="padding-top: 0px;" for="file_name" class="col-sm-3 control-label">
                            注意:
                        </label>
                        <div class="col-sm-8">
                            <span style="color:red;">本系统只会导Excel文件的第一个sheet</span>
                        </div>
                    </div>
                    <div style="display: none;" class="form-group">
                        <label for="file_name" class="col-sm-3 control-label">
                            Optional Filename
                        </label>
                        <div class="col-sm-4">
                            <input type="text" id="file_name" name="file_name" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        取消
                    </button>
                    <button type="submit" class="btn btn-primary">
                        导入
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</section>
@endsection

@section('script_content')
<!-- 引入车型级联js -->
<!-- <script src="{{URL::asset('js/tcl/category.js')}}"></script>  -->
<!-- 引入日历插件 -->
<script src="{{URL::asset('js/tcl/bootstrap-datepicker.js')}}"></script> 
<script src="{{URL::asset('js/tcl/locales/bootstrap-datepicker.zh-CN.js')}}"></script> 
<script src="{{URL::asset('yazan/assets/plugins/jquery-file-input/file-input.js')}}"></script> 
<!-- 引入确认框js -->
<script src="{{URL::asset('yazan/js/confirm.js')}}"></script>
<script>
	$(document).ready(function(){
		
		$('.date-picker').datepicker({
            language: 'zh-CN',
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true
        });

		$('.pagination').children('li').children('a').click(function(){

			// alert($(this).attr('href'));
			$('#condition').attr('action', $(this).attr('href'));
			// alert($('#condition').attr('action'));
			$('#condition').submit();
			return false;
		});  

		// 初始化数据
    	$/*(function() {
    	    $("#uploads-table").DataTable();
    	});*/
	});
</script>
@endsection
