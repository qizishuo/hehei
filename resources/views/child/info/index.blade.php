@extends('child.base')

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
                                <input type="tel" name="phone" lay-verify="required|phone" autocomplete="off" class="layui-input input_noborder" readonly value="{{ $data->total() }}">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">当日数量</label>
                            <div class="layui-input-inline">
                                <!--当日数量不可修改，是调用的数据-->
                                <input type="text" name="email" lay-verify="email" autocomplete="off" class="layui-input input_noborder" readonly value="{{ $daily }}">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">余额</label>
                            <div class="layui-input-inline">
                                <!--余额不可修改，是调用的数据-->
                                <input type="text" name="email" lay-verify="email" autocomplete="off" class="layui-input input_noborder" readonly value="{{ $amount }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-block">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter">
                    <!--
                        大有  表单头部
                        表单头部的th，添加class="table_arr"，则增加右侧排序箭头
                        -->
                    <thead>
                        <tr>
                            <th class="table_arr">ID</th>
                            <th class="table_arr">类型</th>
                            <th class="table_arr">公司名称或老板姓名</th>
                            <th class="table_arr">电话</th>
                            <th class="table_arr">行业</th>
                            <th class="table_arr">提交时间</th>
                            <th style="width:180px">操作</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <!--id序号-->
                                <td>{{ $item->getType() }}</td>
                                <td>{{ $item->getName() }}</td>
                                <!--公司名称-->
                                <td>{{ $item->phone_number }}</td>
                                <!--电话-->
                                <td>{{ $item->industry?$item->industry:'无' }}</td>
                                <td>{{ $item->created_at }}</td>

                                <!--提交时间-->
                                <td>
                                    <!--操作-->
                                    <div class="btn-group">
                                        <a href="{{ route("child.info.apply", ["id" => $item->id]) }}" class="btn btn-sm btn-app-red">申诉</a>
                                        <!--跳转新页面-->
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
