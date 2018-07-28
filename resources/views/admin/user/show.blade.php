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
                <li><a href="{{route('user.index')}}">用户列表</a></li>
                <li class="active">用户详情</li>
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

		<p class="title">[用户]:{{$user->nick_name}}</p>

		<div class="col-xs-12">
			<p class="detial">
				
				<span>
					<i>电话:</i>
					{{$user->telephone}}
				</span>
				<span>
					<i>地址:</i>
					{{$user->address}}
				</span>
				<span>
					<i>角色:</i>
					{{$user->hasManyRoles[0]->name or ''}}
				</span>
			</p>
		</div>
		<div class="col-xs-12">
			<p class="detial">
				<span>
					<i>邮箱:</i>
					{{$user->email}}
				</span>
				<span>
					<i>微信:</i>
					{{$user->wx_number}}
				</span>
				<span>
					<i>备注:</i>		
					{{$user->remark}}			
				</span>
			</p>
		</div>
	</div>	
</div>   
</section>
@endsection