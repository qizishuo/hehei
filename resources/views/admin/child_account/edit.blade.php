@extends('admin.base')

@section('title', '子账号管理-编辑子账号')

@section('content')
    <div class="container-fluid p-y-md">
        <div class="card col-sm-12">
            <div class="card-header">
                <h4 class="base_tit">编辑子账号</h4>
            </div>
            <div class="col-sm-5">
                <!--表单 主要使用了layui-->
                <form class="layui-form" method="post" action="">
                    @csrf
                    <!--账号名称开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">账号名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" disabled lay-verify="title" autocomplete="off" placeholder="账号名称" class="layui-input" value="{{ $data->name }}">
                        </div>
                    </div>

                    <!--账号密码开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">账号密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="password"  autocomplete="off" placeholder="账号密码" class="layui-input">
                        </div>
                    </div>

                    <!-- 大有 子账号类型-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">子账号类型</label>
                        <div class="layui-input-inline">
                            <select name="type">
                                <option disabled value="0">请选择身份</option>
                                <option selected value="{{ \App\Entities\ChildAccount::TYPE_PLATFORM }}">平台子账号</option>
                                <option disabled value="{{ \App\Entities\ChildAccount::TYPE_THIRD }}">第三方账号</option>
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">权重</label>
                        <div class="layui-input-inline">
                            <input type="text" name="weight" value="{{ $data->weight }}" class="layui-input" />
                        </div>
                    </div>

                    <!--大有  提交开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label"></label>
                        <div class="layui-input-block">
                            <button class="btn btn-success btn-block" type="submit">确定</button><!-- 大有  确定 按钮-->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
