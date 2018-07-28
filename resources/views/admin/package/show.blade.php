@extends('layouts.main')

@section('head_content')
	<style type="text/css">
		.detial {
	font-size: 14px;
	line-height: 24px;
	color: #3A3A3A;
	font-style: normal;
	font-family: 'Microsoft YaHei', arial, tahoma, sans-serif;
}

.detial i {
	font-style: normal;
	color: #999;
	font-family: 'Microsoft YaHei', arial, tahoma, sans-serif;
}

.detial span {
	width: 30%;
	display: inline-block;
	font-family: 'Microsoft YaHei', arial, tahoma, sans-serif;
}

.detial em {
	color: #f84949;
	font-size: 28px;
	font-style: normal;
	font-weight: bold;
	line-height: 28px;
	font-family: 'Microsoft YaHei', arial, tahoma, sans-serif;
}

.title {
	color: #333;
	font-size: 20px;
	line-height: 30px;
	font-weight: normal;
	font: 'Microsoft YaHei', arial, tahoma, sans-serif;
}

.title em {
	color: #f84949;
	font-size: 28px;
	font-style: normal;
	font-weight: bold;
	line-height: 30px;
	font: 'Microsoft YaHei', arial, tahoma, sans-serif;
}
	</style>
@endsection

<!-- 面包屑 -->
@section('BreadcrumbTrail')
<section class="content-header">
        <div class="pull-left">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.index')}}">首页</a></li>
                <li><a href="{{route('package.index')}}">套餐列表</a></li>
                <li class="active">套餐详情</li>
            </ol>
        </div>
    </section>
@endsection
<!-- 主体 -->
@section('content')

@include('layouts.message')
<section class="main-content">
<div class="row-fluid">
	<div class="box span12" style="padding:10px;">

		<p class="title">[套餐信息]</p>

		<div class="col-xs-12">
			<p class="detial">
				
				<span>
					<i>套餐名称:</i>
					{{$package->name}}
				</span>
				<span>
					<i>套餐价格:</i>
					{{$package->package_price}}
				</span>
				<span>
					<i>返还月:</i>
					{{$package->month_nums}}个月
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				<span>
					<i>套餐状态:</i>
					{{$package_status[$package->status]}}
				</span>
				<span>
					<i>创建者/日期:</i>
					{{$package->belongsToUser->nick_name}}/{{substr($package->created_at, 0 ,10)}}
				</span>
				<span>
					<i>备注:</i>		
					{{$package->remark}}			
				</span>
			</p>
		</div>
	</div>	
</div> 
<div class="row-fluid">
	<div class="box span12" style="padding:10px;">

		<p class="title">[套餐信息]</p>
		<div class="box-content">
			<table  class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>返还月</th>
						<th>返还金额</th>
					</tr>
				</thead> 
				<tbody>
					@foreach ($package_info as $info)
    					<tr>
    						<td>第{{$info->return_month}}月返还</td>
    						<td>{{$info->return_price}}</td>
    					</tr>
					@endforeach							
				</tbody>
			</table> 		
		</div>		
	</div>	
</div>  
</section>
@endsection