@extends('child.base')

@section('title', '子账号后台')

@section('content')
    <div class="container-fluid p-y-md">
        <div class="card col-sm-12">
            <div class="card-header">
                <h4 class="base_tit">申请删除</h4>
            </div>
            <div class="col-sm-5">
                <!--表单 主要使用了layui-->
                <!--大有  以下为 【起名】修改分配表单-->
                <form class="layui-form form_list" method="post">
                    @csrf
                    <!--ID开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">ID</label>
                        <div class="layui-input-block">
                            <!--ID不可修改，是调用的数据-->
                            <input type="text" lay-verify="title" autocomplete="off" value="{{ $data->id }}" readonly class="layui-input input_noborder">
                        </div>
                    </div>

                    <!--咨询公司名称开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">咨询公司名称</label>
                        <div class="layui-input-block">
                            <!--名称不可修改，是调用的数据-->
                            <input type="text" lay-verify="title" autocomplete="off" value="{{ $data->getName() }}" readonly class="layui-input input_noborder">
                        </div>
                    </div>

                    <!--手机号开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">手机号</label>
                        <div class="layui-input-block">
                            <!--手机号不可修改，是调用的数据-->
                            <input type="text" autocomplete="off" value="{{ $data->phone_number }}" readonly class="layui-input input_noborder">
                        </div>
                    </div>

                    <!--问题描述开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">问题描述</label>
                        <div class="layui-input-block">
                            <textarea name="reason" placeholder="" class="layui-textarea" style="width:500px"></textarea>
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
                <!--【起名】修改分配表单-->
            </div>
        </div>
    </div>
@endsection
