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
                    <a href="{{ route("child.password") }}">修改密码</a>
                    <!--按钮-->
                </li>
            </ul>
        </li>
        <li class="dropdown dropdown-profile">
            <a href="{{ route("child.logout") }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">退出</a>
            <form id="logout-form" action="{{ route("child.logout") }}" method="POST" style="display: none;">
                @csrf
            </form>
            <!--按钮-->
        </li>
    </ul>
@endsection

@section("menu")
    <ul class="nav nav-drawer">
        <li class="nav-item nav-item-has-subnav {{ active_menu('child.index', 'active open') }}">
            <a href="javascript:void(0)"><i class="ion-ios-calculator-outline"></i>绑定管理</a>
            <ul class="nav nav-subnav">
                <li class="{{ active_menu('child.index') }}"><a href="{{ route('child.index') }}">绑定管理</a></li>
            </ul>
        </li>

        <li class="nav-item nav-item-has-subnav {{ active_menu('child.info', 'active open') }}">
            <a href="javascript:void(0)"><i class="ion-ios-compose-outline"></i>信息管理</a>
            <ul class="nav nav-subnav">
                <li class="{{ active_menu('child.info.index') }}"><a href="{{ route('child.info.index') }}">信息列表</a></li>
                <li class="{{ active_menu('child.info.list') }}"><a href="{{ route('child.info.list') }}">申请记录</a></li>
            </ul>
        </li>
    </ul>
@endsection
