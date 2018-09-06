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
                <li class="active">导入错误提示</li>
            </ol>
        </div>
    </section>
@endsection

@section('content')

@include('layouts.message')
<section class="main-content">
    <div class="row">
        <div class="col-lg-8  col-lg-offset-2">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <strong style="font-size:22px;" class="text-uppercase text-warning">
                            导入Excle错误,请注意以下几点:
                        </strong>
                    </h3>
                </div>
                <div class="panel-body" style="font-size:22px;">
                    <ol>
                        <li>
                            确认您选择的是有效的Excel文件.
                        </li>
                        <li>
                            确认文件大小不超过2MB.
                        </li>
                        <li>
                            <span style="color:red;">请下载标准表格</span>
                        </li>
                        <li>
                            <span style="color:red;">清除Excel所有数据格式(所有的sheet都需要清除)后重试.</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
                                
</section>
@endsection

@section('script_content')

<script>
	
</script>
@endsection
