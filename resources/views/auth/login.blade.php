<!DOCTYPE html>
<html lang="en">

<head>
    <title>卓睿电信系统</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="为宝贝做的系统" name="description">
    <meta content="wcg13731080174" name="author">
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/font-awesome/css/font-awesome.min.css')}}">
    <!-- <link type="text/css" rel="stylesheet" href="global/plugins/font-awesome/css/font-awesome.min.css"> -->
    <!-- <link type="text/css" rel="stylesheet" href="global/plugins/ionicons/css/ionicons.min.css"> -->
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/ionicons/css/ionicons.min.css')}}">
    <!-- <link type="text/css" rel="stylesheet" href="global/plugins/simple-line-icons/simple-line-icons.css"> -->
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/simple-line-icons/simple-line-icons.css')}}">
    <!-- <link type="text/css" rel="stylesheet" href="global/plugins/iCheck/skins/all.css"> -->
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/iCheck/skins/all.css')}}">
    <!-- <link type="text/css" rel="stylesheet" href="assets/plugins/bootstrap-validator/css/bootstrapValidator.min.css"> -->
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/plugins/bootstrap-validator/css/bootstrapValidator.min.css')}}">
    <!-- <link type="text/css" rel="stylesheet" href="global/css/style.css"> -->
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/global/css/style.css')}}">
    <!-- <link type="text/css" rel="stylesheet" href="assets/css/pages/login.css"> -->
    <link type="text/css" rel="stylesheet" href="{{URL::asset('yazan/assets/css/pages/login.css')}}">
</head>

<body class="page-login">
    <div class="outer">
        <div class="middle bg-overlay">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="inner">
                <!--BEGIN LOGO-->
                <div class="header"><a href="javascript:void(0);" class="logo">卓睿电信系统</a></div>
                <!--END LOGO-->
                <!--BEGIN CONTENT-->
                <form action="{{url('/login')}}" method="post" class="login-form">
                    {!! csrf_field() !!}
                    <h4 class="mbl">后台登录</h4>
                    <div class="form-group">
                        <div class="input-icon"><i class="icon-user"></i>
                            <input type="text" placeholder="用户名" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-icon"><i class="icon-lock"></i>
                            <input type="password" placeholder="密码" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox pull-left">
                            <label>
                                <input type="checkbox" name="remember" value="">记住我</label>
                        </div><a href="#" class="text-info pull-right mtm">忘记密码?</a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block">登陆</button>
                    </div>
                    </form>
                <!--END CONTENT-->
            </div>
        </div>
    </div>
    <!-- <script src="global/js/jquery.js"></script>
    <script src="global/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="global/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="global/js/html5shiv.js"></script>
    <script src="global/js/respond.min.js"></script>
    <script src="global/plugins/iCheck/icheck.min.js"></script>
    <script src="global/plugins/iCheck/custom.min.js"></script>
    <script src="assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js"></script>
    <script src="global/js/app.js"></script>
    <script src="assets/js/pages/login.js"></script> -->

    <script src="{{URL::asset('yazan/global/js/jquery.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/jquery-migrate-1.2.1.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/html5shiv.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/respond.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/plugins/iCheck/custom.min.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/plugins/bootstrap-validator/js/bootstrapValidator.min.js')}}"></script>
    <script src="{{URL::asset('yazan/global/js/app.js')}}"></script>
    <script src="{{URL::asset('yazan/assets/js/pages/login.js')}}"></script>
</body>

</html>