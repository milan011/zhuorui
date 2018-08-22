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
                <li><a href="{{route('infoSelf.index')}}">信息列表</a></li>
                <li class="active">信息详情</li>
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

		<p class="title">[客户]:{{$info->name}}({{($info->user_telephone)}})</p>

		<div class="col-xs-12">
			<p class="detial">
				
				<span>
					<i>客户经理:</i>
					{{$info->manage_name}}({{$info->manage_telephone}})
				</span>
				<span>
					<i>项目:</i>
					{{$info->project_name}}
				</span>
				<span>
					<i>绑旧卡:</i>
					@if($info->old_bind == 0) 是 @else 否 @endif
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				<span>
					<i>新号码:</i>
					{{$info->new_telephone}}
				</span>
				<span>
					<i>UIM码:</i>
					{{$info->uim_number}}
				</span>
				<span>
					<i>入网日期:</i>		
					{{$info->netin}}			
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				<span>
					<i>收款:</i>
					{{$info->collections}}
				</span>
				<span>
					<i>收款方式:</i>		
					{{$collections_type[$info->collections_type]}}			
				</span>
				<span>
					<i>还款月数:</i>
					{{$info->balance_month}}
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				
				<span>
					<i>返还状态:</i>
					{{$info_status[$info->status]}}
				</span>
				<span>
					<i>创建者/日期:</i>
					{{$info->belongsToCreater->nick_name}}/{{substr($info->created_at, 0 ,10)}}
				</span>
				<span>
					<i>备注:</i>		
					{{$info->remark}}			
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				<span style="display:inline;">
					<i>副卡:</i>
					{{$info->side_number}}
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
						<th>套餐名称</th>
						<th>返还月</th>
						<th>返还金额</th>
					</tr>
				</thead> 
				<tbody>
    				<tr>
    					<td>{{$package_info->name}}</td>
    					<td>{{$package_info->month_nums}}</td>
    					<td>{{$package_info->package_price}}</td>
    				</tr>						
				</tbody>
			</table> 		
		</div>		
	</div>	
</div>  
<div class="row-fluid">
	<div class="box span12" style="padding:10px;">

		<p class="title">[返还信息]</p>
		<div class="box-content">
			<table  class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>返还金额</th>
						<th>返还序号</th>
						<th>返还日期</th>
					</tr>
				</thead> 
				<tbody>
					@foreach ($info->hasManyInfoDianxin as $return_ifno)
    				<tr>
    					<td>{{$return_ifno->refunds}}</td>
    					<td>{{$return_ifno->balance_month}}</td>
    					<td>{{$return_ifno->netin}}</td>
    				</tr>	
    				@endforeach					
				</tbody>
			</table> 		
		</div>		
	</div>	
</div>
</section>
@endsection