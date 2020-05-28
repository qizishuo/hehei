@extends('admin.base')

@section('title', '信息管理-信息导出')

@section('content')
<div class="container-fluid p-y-md">
    <div class="card col-sm-12">
        <div class="card-header">
            <h4 class="base_tit">信息导出</h4>
        </div>
        <div class="col-sm-5">
            <!--表单 日期选择，主要使用了layui-->
        <form class="layui-form form_list" action="{{ route('info.export') }}" method="get">
                <div class="layui-form-item">
                    <label class="layui-form-label">开始日期</label>
                    <div class="layui-input-block">
                        <!--大有   开始日期，以下一行input代码-->
                        <input type="text" class="layui-input" id="date1" name="from">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">结束日期</label>
                    <div class="layui-input-block">
                        <!--大有   结束日期，以下一行input代码-->
                        <input type="text" class="layui-input" id="date2" name="to">
                    </div>
                </div>

                <!--大有  导出开始-->
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="btn btn-success btn-block" type="submit">导出</button><!-- 大有  导出 按钮，点击后，会弹出 浏览器的 下载文件框-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section("script")
<script>
    //大有  日期 使用layui
    layui.use('laydate', function() {
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#date1' //指定元素，开始日期
        });
        laydate.render({
            elem: '#date2' //指定元素，结束日期
        });
    });
</script>
@endsection