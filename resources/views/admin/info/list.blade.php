@extends('admin.base')

@section('title', '信息管理-信息列表')

@section('content')
<div class="container-fluid p-y-md">
    <div class="card">
        <div class="card-header">
            <!--大有 使用layui-->
            <form action="" method="">
                <div class="layui-form-item form_word_left">
                    <div class="layui-inline">
                        <label class="layui-form-label">总计</label>
                        <div class="layui-input-inline">
                            <!--总计不可修改，是调用的数据-->
                            <input type="tel" name="phone" lay-verify="required|phone" autocomplete="off" class="layui-input input_noborder" readonly value="{{ $total }}">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">当日数量</label>
                        <div class="layui-input-inline">
                            <!--当日数量不可修改，是调用的数据-->
                            <input type="text" name="email" lay-verify="email" autocomplete="off" class="layui-input input_noborder" readonly value="{{ $daily }}">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-block">
            <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
            @include('admin.info.info_table')
            {{ $data->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
