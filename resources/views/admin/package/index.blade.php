@extends('layouts.main')

@section('head_content')
	<link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}">
    <style>
        .nav-list > li > a, .nav-list .nav-header{
            width: 80%;
            display: inline-block;
        }
        .nav-list > li > .btn-group{
            float: right;
            margin-left: 5px;
        }
    </style>
@endsection

@section('BreadcrumbTrail')
	<section class="content-header">
        <div class="pull-left">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}">首页</a></li>
                <li class="active">套餐</li>
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
                        <!-- <li style="display: inline-block;line-height:20px;">
                            <a class="btn btn-search" href="javascript:void(0);"><i class="halflings-icon search"></i>搜索系列</a>
                        </li> -->
                        <li style="display:inline-block;line-height:20px;float:right;">
                            <a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
                        </li>
                        <li style="display: inline-block;line-height:20px;float:right;">
                            <a class="btn btn-primary" href="{{route('package.create')}}">添加套餐</a>
                        </li>
                    </ul>
                    <table id="datatables" class="table table-striped table-no-border">
                        <thead class="bg-default">
                            <tr>
                                <th>套餐</th>
                                <th>价格</th>
                                <th>返还月</th>
                                <th>状态</th>
                                <th>创建日期</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($packages as $package)
                        <tr>
                            <td>
                                <a target="_blank" href="{{route('package.show', ['package'=>$package->id])}}">
                                    {{$package->name}}
                                </a>
                            </td> 
                            <td>{{$package->package_price}}</td>                       
                            <td>{{$package->month_nums}}</td>                       
                            <td>{{$package_status[$package->status]}}</td>                       
                            <td>{{substr($package->created_at, 0 ,10)}}</td>      
                            <td class="center">
                                <a class="btn btn-success" target="_blank"  href="{{route('package.show', ['package'=>$package->id])}}">
                                    <i class="icon-edit icon-white"></i> 查看
                                </a>
                                <a class="btn btn-warning" href="{{route('package.edit', ['package'=>$package->id])}}">
                                    <i class="icon-edit icon-white"></i> 编辑
                                </a>
                                <input type="hidden" name="category_id" value="{{$package->id}}">
                                
                                <span>
                                <form action="{{route('package.destroy', ['package'=>$package->id])}}" method="post" style="display: inherit;margin:0px;">
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
                <div class="col-md-7 col-sm-12">
                    <div class="dataTables_paginate paging_simple_numbers" style="float:left;">
                        <div class="pagination pagination-centered">
                          <ul class="pagination">
                            <li class="disabled"><span>共{{ $packages->total() }}条</span></li>
                          </ul>
                        </div>
                    </div>
                    <div class="dataTables_paginate paging_simple_numbers" id="datatables_paginate">
                    <div class="pagination pagination-centered">
                       {!! $packages->links() !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script_content')
<!-- 引入确认框js -->
<script src="{{URL::asset('yazan/js/confirm.js')}}"></script> 
<script type="text/javascript">

	jQuery(document).ready(function($){

        /*$('.pagination').children('li').children('a').click(function(){

            // alert($(this).attr('href'));
            $('#condition').attr('action', $(this).attr('href'));
            // alert($('#condition').attr('action'));
            $('#condition').submit();
            return false;
        });*/
	});
</script>

@endsection
