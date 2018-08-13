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
                <li><a href="{{route('infoDianxin.index')}}">信息列表</a></li>
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

		<p class="title">[套餐]:{{$info->name or ''}}</p>

		<div class="col-xs-12">
			<p class="detial">
				
				<span>
					<i>返还号码:</i>
					{{$info->return_telephone}}
				</span>
				<span>
					<i>价款:</i>
					{{$info->jiakuan}}
				</span>
				<span>
					<i>返还金额:</i>
					{{$info->refunds}}
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				<span>
					<i>客户经理:</i>
					{{$info->manager or ''}}
				</span>
				<span>
					<i>返还月:</i>
					{{$info->balance_month}}
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
					<i>集团:</i>
					{{$info->jituan}}
				</span>
				<span>
					<i>创建者/日期:</i>
					{{$info->belongsToCreater->nick_name}}/{{substr($info->created_at, 0 ,10)}}
				</span>
				<span>
					<i>佣金方案:</i>
					{{$info->yongjin}}
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				<span>
					<i>备注:</i>		
					{{$info->remark}}			
				</span>
			</p>
		</div>
	</div>	
</div> 

</section>
@endsection