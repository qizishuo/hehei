<!DOCTYPE html>

<html class="app-ui">

<head>
    <!-- Meta -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <!-- Document title -->
    <title>@yield('title')</title>

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

    <link rel="stylesheet" type="text/css" href="/assets/css/formSelects-v4.css" /><!--下拉，多选，区域-->
    <!-- End Stylesheets -->
</head>

<body class="app-ui layout-has-drawer layout-has-fixed-header">


    <div class="app-layout-canvas">
        <div class="app-layout-container">

            <!--大有  左侧菜单整体-->
            <aside class="app-layout-drawer">
                <div class="app-layout-drawer-scroll">
                    <!-- 大有  顶部logo -->
                    <div id="logo" class="drawer-header">
                        <a href="index.html"><img class="img-responsive" src="/assets/img/logo/logo-backend.png" title="AppUI" alt="AppUI" /></a>
                    </div>
                    <!-- 大有  左侧菜单列表 -->
                    <!--
                        大有 菜单列表里li，
                        1、无二级菜单：添加active时，是选中状态
                        2、有二级菜单：选中状态时（展开二级菜单）,li 添加open
                                       进入二级菜单页面后，li添加active与open ，当前进入的二级菜单名称的子 li 添加active
                        -->
                    <nav class="drawer-main">
                        @yield("menu")
                    </nav>
                    <!-- End 左侧菜单列表-->
                </div>
            </aside>
            <!-- End  左侧菜单整体-->

            <!-- 大有  右侧顶部  -->
            <header class="app-layout-header">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="collapse navbar-collapse" id="header-navbar-collapse">
                            @yield("navbar")
                        </div>
                    </div>
                </nav>
            </header>
            <!-- End 右侧顶部 -->

            <main class="app-layout-content">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- 大有 右侧主体 -->
                @yield('content')
                <!-- .container-fluid -->
                <!-- End Page Content -->
            </main>
        </div>
        <!-- .app-layout-container -->
    </div>
    <!-- .app-layout-canvas -->

    <div class="app-ui-mask-modal"></div>

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

    <!--弹框,layui-->
    <script src="/assets/js/layui.all.js"></script>

    <script>
        // 删除按钮
        $('.table_btn_del').on('click', function() {
            let href = $(this).data('href');
            layer.confirm('确定删除？', function() {
                location.href = href;
            });
        });
    </script>

    @yield('script')
</body>

</html>
