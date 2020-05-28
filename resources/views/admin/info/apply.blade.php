@extends('admin.base')

@section('title', '信息管理-申请记录')

@section('content')
<div class="container-fluid p-y-md">
    <div class="card">
        <div class="card-header">
            <form class="layui-form-item form_word_left" action="{{ route("info.apply.export") }}" method="get">
                <div class="layui-inline">
                    <label class="layui-form-label">开始日期</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="date1" name="from" />
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">结束日期</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" id="date2" name="to" />
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="btn btn-success btn-block" type="submit">导出</button>
                </div>
            </form>
        </div>
        <div class="card-block">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
            @include('admin.info.apply_table')
            {{ $data->appends(request()->query())->links() }}
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

    // 拒绝理由弹框
    $('.table_btn_reason').on('click', function() {
        let href = $(this).data('href');
        layer.prompt({
            title: '请填写拒绝理由',
        }, function(value) {
            location.href = href + "?reason=" + value;
        });
    });
</script>
@endsection
