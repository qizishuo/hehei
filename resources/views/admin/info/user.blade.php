@extends('admin.base')

@section('title', '信息管理-用户列表')

@section('content')
<div class="container-fluid p-y-md">
    <div class="card">
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
                        <th class="table_arr">公司名称或老板姓名</th>
                        <th class="table_arr">电话</th>
                        <th style="width:180px">操作</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <!--id序号-->
                        <td>{{ $item->company_name ? $item->company_name : $item->boss_name }}</td>
                        <!--公司名称-->
                        <td>{{ $item->phone_number }}</td>
                        <!--电话-->
                        <td>
                            <!--操作-->
                            <div class="btn-group">
                                <a href="{{ route("info.list", ["phone_id" => $item->phone_id]) }}" class="btn btn-sm btn-success">查看更多订单</a>
                                <!--跳转新页面-->
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $data->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
