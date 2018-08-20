<!DOCTYPE html>
<html lang="en">

<head>
    <title>卓睿电信信息管理</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="卓睿电信信息管理" name="description">
    <meta content="wcg13731080174" name="author">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/font-awesome/css/font-awesome.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/ionicons/css/ionicons.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/simple-line-icons/simple-line-icons.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/animate.css/animate.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/iCheck/skins/all.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/plugins/rickshaw/rickshaw.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/css/style.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/page-demo.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/style-admin.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/style-plugins.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/style-responsive.css')}}">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/themes/default.css')}}" id="theme-color">
    <link href="{{URL::asset('yazan/confirm/css/jquery-confirm.css')}}" rel="stylesheet">
    @yield('head_content')
</head>

<body class="page-header-fixed page-sidebar-fixed">
    <div>
        <div class="page-wrapper">
            <!--BEGIN HEADER-->
            <header class="header">
                <div class="logo"><a href="/" class="logo-text">信息管理</a><a href="#" data-toggle="offcanvas" class="sidebar-toggle pull-right"><i class="fa fa-bars"></i></a></div>
                <nav role="navigation" class="navbar navbar-static-top">
                    <div class="navbar-right">
                        <ul class="nav navbar-nav">
                            <li class="dropdown dropdown-user menu-user">
                                <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                                    <span class="hidden-xs">用户 {{ Auth::user()->nick_name }}</span>&nbsp;
                                    <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('admin.user.resetPass')}}"><i class="icon-user"></i>修改密码</a></li>
                                    <li><a href="{{ url('logout') }}"><i class="icon-key"></i>退出</a></li>
                                </ul>
                            </li>
                            <!-- <li class="hidden-xs hidden-sm"><a href="javascript:;" class="fullscreen-toggle"><i class="icon-size-fullscreen"></i></a></li>
                            <li class="hidden-xs"><a href="javascript:;" class="toggle-quick-sidebar"><i class="icon-settings"></i></a></li> -->
                        </ul>
                    </div>
                </nav>
            </header>
            <!--END HEADER-->
            <!--BEGIN WRAPPER-->
            <div class="wrapper row-offcanvas row-offcanvas-left">
                <!--BEGIN SIDEBAR-->
                <aside class="page-sidebar sidebar-offcanvas">
                    <section class="sidebar">
                        <ul class="sidebar-menu">
                            <li class="active">
                                <a href="{{route('admin.index')}}"><i class="icon-home"></i><span class="sidebar-text">首页</span></a>
                            </li>
                            @ifUserCan('info.check')
                            <li>
                                <a href="{{route('infoSelf.index')}}/index"><i class="icon-rocket"></i><span class="sidebar-text">信息管理</span></a>
                            </li>
                            @endif
                            @ifUserCan('dianxin.check')
                            <li>
                                <a href="{{route('infoSelf.statistics')}}"><i class="icon-rocket"></i><span class="sidebar-text">信息统计</span></a>
                            </li>
                            @endif
                            @ifUserCan('dianxin.check')
                            <li>
                                <a href="{{route('infoSelf.payed')}}"><i class="icon-rocket"></i><span class="sidebar-text">已付款信息</span></a>
                            </li>
                            <li>
                                <a href="{{route('infoSelf.notPayed')}}"><i class="icon-rocket"></i><span class="sidebar-text">未返还完成信息</span></a>
                            </li>
                            <li>
                                <a href="{{route('infoDianxin.index')}}/index"><i class="icon-rocket"></i><span class="sidebar-text">电信信息管理</span></a>
                            </li>
                            @endif
                            <!-- <li>
                                <a href="#"><i class="icon-grid"></i><span class="sidebar-text">商品及分类</span></a>
                                <ul class="nav nav-second-level">
                                    <li><a href="/index">商品管理</a></li>
                                    <li><a href="/index">分类管理</a></li>
                                </ul>
                            </li> -->
                            @ifUserCan('user.check')
                            <li>
                                <a href="{{route('user.index')}}"><i class="icon-layers"></i><span class="sidebar-text">用户管理</span></a>
                            </li>
                            @endif
                            @ifUserCan('manage.check')
                            <li>
                                <a href="{{route('manager.index')}}"><i class="icon-layers"></i><span class="sidebar-text">客户经理管理</span></a>
                            </li>
                            @endif
                            @ifUserCan('packge.check')
                            <li>
                                <a href="{{route('package.index')}}"><i class="icon-layers"></i><span class="sidebar-text">套餐管理</span></a>
                            </li>
                            @endif
                            @ifUserCan('role.manage')
                            <li>
                                <a href="{{route('permission.index')}}"><i class="icon-layers"></i><span class="sidebar-text">权限管理</span></a>
                            </li>
                            <li>
                                <a href="{{route('role.index')}}"><i class="icon-layers"></i><span class="sidebar-text">角色管理</span></a>
                            </li>
                            @endif
                        </ul>
                    </section>
                </aside>
                <!--END SIDERBAR-->
                <!--BEGIN CONTENT-->
                <div class="content">
                    <!-- <section class="content-header">
                        <div class="pull-left">
                            <ol class="breadcrumb">
                                <li><a href="#">Home</a></li>
                                <li><a href="#">Dashboard</a></li>
                                <li class="active">Dashboard</li>
                            </ol>
                        </div>
                    </section> -->
                    <!-- 面包屑导航 -->
                    @yield('BreadcrumbTrail')
                    <!-- 主体内容 -->
                    @yield('content')
                </div>
            </div>
            <!--END WRAPPER-->
        </div>
    </div>
    <script src="{{URL::asset('yazan/global/js/jquery.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/jquery-ui.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/html5shiv.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/respond.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/slimScroll/jquery.slimscroll.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/iCheck/custom.min.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/plugins/jquery-metisMenu/jquery.menu.min.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/plugins/jquery.blockUI.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/app.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/js/quick-sidebar.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/js/admin-setting.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/js/layout.js')}}"></script>
    <script src="{{URL::asset('yazan/confirm/js/jquery-confirm.js')}}"></script>
    @yield('script_content')
</body>

</html>