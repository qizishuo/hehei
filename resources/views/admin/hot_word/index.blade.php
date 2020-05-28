@extends('admin.base')

@section('title', '热度词')

@section('content')
<div class="container-fluid p-y-md">
    <div class="card col-sm-12">
        <div class="card-header">
            <h4 class="base_tit">信息导出</h4>
        </div>
        <div class="col-sm-8">
            <!--表单 主要使用了layui-->
            <form class="layui-form form_list" method="post" action="">
                @csrf
                <div class="layui-form-item">
                    <label class="layui-form-label">热搜词</label>
                    <div class="layui-input-block" style="width:700px">
                        <!--大有   文本域，以下一行代码，内容为后台原来内容，以后可以修改-->
                        <textarea name="words" placeholder="" class="layui-textarea">{{ $words }}</textarea>
                    </div>
                </div>

                <!--大有  确定开始-->
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

@section('script')
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