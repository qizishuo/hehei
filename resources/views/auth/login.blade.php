<!DOCTYPE html>

<html class="app-ui">

<head>
    <!-- Meta -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <!-- Document title -->
    <title>登录</title>

    <meta name="description" content="AppUI - Admin Dashboard Template & UI Framework" />
    <meta name="author" content="rustheme" />
    <meta name="robots" content="noindex, nofollow" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="/assets/img/favicons/apple-touch-icon.png" />
    <link rel="icon" href="/assets/img/favicons/favicon.ico" />

    <!-- Google fonts -->
    <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,900%7CRoboto+Slab:300,400%7CRoboto+Mono:400" />-->

    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="/assets/js/plugins/datatables/jquery.dataTables.min.css" />

    <!-- AppUI CSS stylesheets -->
    <!--<link rel="stylesheet" id="css-font-awesome" href="/assets/css/font-awesome.css" />-->
    <link rel="stylesheet" id="css-ionicons" href="/assets/css/ionicons.css" />
    <link rel="stylesheet" id="css-bootstrap" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" id="css-app" href="/assets/css/app.css" />
    <link rel="stylesheet" href="/assets/css/layui.css">
    <!--<link rel="stylesheet" id="css-app-custom" href="/assets/css/app-custom.css" />-->
    <!-- End Stylesheets -->
</head>

<body>
    <div style="background:url('/assets/img/loginbg.jpg') no-repeat center; position:fixed; left:0;  top:0; bottom:0; right:0;">
        <div class="login_page" style="background:#fff; position:fixed; top:50%; left:50%; margin:-150px 0 0 -180px; width:360px;  padding:30px 30px 20px; border-radius:5px; box-shadow:0 0 10px #ccc">
            <!-- 大有 登录页 使用Layui-->
            <h4>欢迎使用 大有企服 平台系统</h4>
            <form class="layui-form" method="POST" action="">
                @csrf
                <div class="layui-form-item">
                    <div class="layui-input-inline input-custom-width">
                        <input type="text" name="name" placeholder="用户名" autocomplete="name" class="layui-input @error('name') is-invalid @enderror" required />
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-inline input-custom-width">
                        <input type="password" name="password" placeholder="密码" autocomplete="current-password" class="layui-input @error('password') is-invalid @enderror" required />
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-inline input-custom-width">
                        <input type="text" name="captcha" placeholder="验证码" autocomplete="off" class="layui-input @error('captcha') is-invalid @enderror" required />
                        @error('captcha')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div class="captcha">
                            <img src="{{ captcha_src() }}" alt="captche" title="点击切换" style="cursor:pointer" onclick="this.src='{!! captcha_src() !!}'" />
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-inline input-custom-width">
                        <button class="btn btn-success btn-allwidth" type="submit">立即登录</button><!-- 大有  立即登录 按钮-->
                    </div>
                </div>
            </form>
        </div>
    </div>



    <!-- AppUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock and App.js -->
    <script src="/assets/js/core/jquery.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/core/jquery.slimscroll.min.js"></script>
    <script src="/assets/js/core/jquery.scrollLock.min.js"></script>
    <script src="/assets/js/core/jquery.placeholder.min.js"></script>
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/app-custom.js"></script>


    <!-- Page JS Plugins -->
    <script src="/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>

    <!-- Page JS Code -->
    <script src="/assets/js/pages/base_tables_datatables.js"></script>

    <!--layui-->
    <script src="/assets/js/layui.all.js"></script>

</body>

</html>