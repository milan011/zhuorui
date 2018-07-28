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
                <li><a href="{{route('order.index')}}">客户经理列表</a></li>
                <li class="active">客户经理详情</li>
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

		<p class="title">[客户经理客]:{{$manager->name}}({{$manager->telephone}})</p>

		<div class="col-xs-12">
			<p class="detial">
				
				<span>
					<i>订单编号:</i>
					{{$orders->order_code}}
				</span>
				<span>
					<i>快递单号:</i>
					{{$orders->exp_code}}
				</span>
				<span>
					<i>订单总价:</i>
					{{$orders->total_price}}元
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				<span>
					<i>用户电话:</i>
					{{$orders->user_telephone}}
				</span>
				<span>
					<i>创建者:</i>
					{{$orders->belongsToCreater->nick_name}}
				</span>
				<span>
					<i>备注:</i>		
					{{$orders->remark}}			
				</span>
			</p>
		</div>
	</div>	
</div> 
<div class="row-fluid">
	<div class="box span12" style="padding:10px;">

		<p class="title">[商品信息]</p>
		<div class="box-content">
			<table  class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>系列</th>
						<th>商品</th>
						<th>商品单价</th>
						<th>商品数</th>
						<th>商品总价</th>
					</tr>
				</thead> 
				<tbody>
					@foreach ($order_goods as $goods)
    				<tr>
						<td>{{$goods->belongsToCategory->name}}</td>
						<td>{{$goods->goods_name}}</td>
						<td>{{$goods->goods_price}}元</td>
						<td>{{$goods->goods_num}}</td>							
						<td>{{$goods->total_price}}元</td>
					</tr>
					@endforeach							
				</tbody>
			</table> 		
		</div>		
	</div>	
</div>  
</section>
@endsection