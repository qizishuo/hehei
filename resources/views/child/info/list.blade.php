@extends('child.base')

@section('title', '信息管理-申请记录')

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
                            <th class="table_arr">公司名称</th>
                            <th class="table_arr">电话</th>
                            <th class="table_arr">创建时间</th>
                            <th class="table_arr">申请提交时间</th>
                            <th class="table_arr">申请原因</th>
                            <th style="width:90px">状态</th>
                            <th style="width:280px">拒绝理由</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item->id }}</td><!--id序号-->
                                <td>{{ $item->info->getName() }}</td><!--公司名称-->
                                <td>{{ $item->info->phone_number }}</td><!--电话-->
                                <td>{{ $item->info->created_at }}</td>
                                <td>{{ $item->created_at }}</td><!--申请提交时间-->
                                <td>{{ $item->apply_reason }}</td><!--申请原因：子账号的申请原因-->
                                <td>{!! $item->getStatus() !!}</td><!--状态，有三种：申请中（text-blue）, 通过（text-green）,未通过（text-red）。-->
                                <td>{{ $item->status == \App\Entities\Apply::STATUS_REFUSE ? $item->refuse_reason : "" }}</td><!--拒绝理由，只有拒绝后，才会有拒绝内容，  在状态为申请中、通过时，此处没有内容-->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
