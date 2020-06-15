@extends($base)

@section('title', '修改密码')

@section('content')
<div class="container-fluid p-y-md">
    <div class="card col-sm-12">
        <div class="card-header">
            <h4 class="base_tit">修改密码</h4>
        </div>
        <div class="col-sm-5">
            <!--表单 主要使用了layui-->
            <form class="layui-form" action="" method="post">
                @csrf
                <!--新密码开始-->
                <div class="layui-form-item">
                    <label class="layui-form-label">新密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" autocomplete="off" placeholder="请输入新密码" class="layui-input">
                    </div>
                </div>

                <!--重复密码开始-->
                <div class="layui-form-item">
                    <label class="layui-form-label">重复新密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="password_confirmation" autocomplete="off" placeholder="请再次输入新密码" class="layui-input">
                    </div>
                </div>

                <!--大有  修改开始-->
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="btn btn-success btn-block" type="submit">修改</button><!-- 大有  修改 按钮-->
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
