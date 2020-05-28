@extends('admin.base')

@section('title', '系统设置-扣款金额设置')

@section('content')
<div class="container-fluid p-y-md">
    <div class="card col-sm-12">
        <div class="card-header">
            <h4 class="base_tit">扣款金额设置</h4>
        </div>
        <div class="col-sm-5">
            <!--表单 主要使用了layui-->
            <form class="layui-form form_list" method="post" action="">
                @csrf
                <div class="layui-form-item">
                    <label class="layui-form-label">第三方账户扣款金额</label>
                    <div class="layui-input-block">
                        <!--名称不可修改，是调用的数据-->
                        <input type="text" name="money" lay-verify="title" autocomplete="off" value="{{ $money }}" class="layui-input">
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