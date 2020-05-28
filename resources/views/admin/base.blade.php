@extends("base")

@section("navbar")
    <ul class="nav navbar-nav navbar-right navbar-toolbar hidden-sm hidden-xs">
        <li class="dropdown dropdown-profile">
            <a href="javascript:void(0)" data-toggle="dropdown">
                <span class="m-r-sm">{{ Auth::user()->name }}<span class="caret"></span></span>
                <!--管理员名字-->
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a href="{{ route("password") }}">修改密码</a>
                    <!--按钮-->
                </li>
            </ul>
        </li>
        <li class="dropdown dropdown-profile">
            <a href="{{ route("logout") }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">退出</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <!--按钮-->
        </li>
    </ul>
@endsection

@section("menu")
    <ul class="nav nav-drawer">
        <li class="nav-item {{ active_menu('index') }}">
            <a href="{{ route('index') }}"><i class="ion-ios-speedometer-outline"></i> 首页</a>
        </li>

        @can('analyze')
            <li class="nav-item {{ active_menu('analyze') }}">
                <a href="{{ route('analyze') }}"><i class="ion-ios-speedometer-outline"></i> 数据统计</a>
            </li>
        @endcan

        @can('child-account')
            <li class="nav-item nav-item-has-subnav {{ active_menu('child-account', 'active open') }}">
                <a href="javascript:void(0)"><i class="ion-ios-calculator-outline"></i>子账号管理</a>
                <ul class="nav nav-subnav">
                    <li class="{{ active_menu('child-account.index') }}"><a href="{{ route('child-account.index') }}">子账号列表</a></li>
                </ul>
            </li>
        @endcan

        @canany(["info", 'apply'])
            <li class="nav-item nav-item-has-subnav {{ active_menu('info', 'active open') }}">
                <a href="javascript:void(0)"><i class="ion-ios-compose-outline"></i>信息管理</a>
                <ul class="nav nav-subnav">
                    @can('info')
                        <li class="{{ active_menu('info.list') }}"><a href="{{ route('info.list') }}">信息列表</a></li>
                        <li class="{{ active_menu('info.user') }}"><a href="{{ route('info.user') }}">用户列表</a></li>
                    @endcan
                    @can('apply')
                        <li class="{{ active_menu("info.apply.index") }}"><a href="{{ route("info.apply.index") }}">申诉列表</a></li>
                    @endcan
                    @can('info')
                        <li class="{{ active_menu("info.export.view") }}"><a href="{{ route('info.export.view') }}">信息导出</a></li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('hot-word')
            <li class="nav-item {{ active_menu('hot-word') }}">
                <a href="{{ route('hot-word') }}"><i class="ion-ios-speedometer-outline"></i> 热度词</a>
            </li>
        @endcan

        @can('check-name')
            <li class="nav-item {{ active_menu('check-name') }}">
                <a href="{{ route('check-name') }}"><i class="ion-ios-speedometer-outline"></i> 核名动态</a>
            </li>
        @endcan

        @can('system')
            <li class="nav-item nav-item-has-subnav {{ active_menu('system', 'active open') }}">
                <a href="javascript:void(0)"><i class="ion-ios-compose-outline"></i>系统设置</a>
                <ul class="nav nav-subnav">
                    <li class="{{ active_menu('system.money') }}"><a href="{{ route('system.money') }}">扣款金额设置</a></li>
                </ul>
            </li>
        @endcan

        @can('popup')
            <li class="nav-item {{ active_menu('popup') }}">
                <a href="{{ route('popup') }}"><i class="ion-ios-speedometer-outline"></i> 弹窗动态</a>
            </li>
        @endcan
    </ul>
@endsection
