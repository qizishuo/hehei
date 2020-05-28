@extends('admin.base')

@section('title', '核名动态')

@section('content')
    <div class="container-fluid p-y-md">
        <div class="card">
            <div class="card-block">
                <!--上传，上传的内容，才会显示在下面的table里。-->
                <form method="post" action="{{ route('check-name.import') }}" class="form-signin" enctype="multipart/form-data">
                    @csrf
                    <input name="excel" type="file" class="form-control form_upfile"><!--上传input框-->
                    <button class="btn btn-success">导入</button><!--按钮-->
                </form>

                <!-- DataTables init on table by adding .js-dataTable-full class, functionality initialized in js/pages/base_tables_datatables.js -->
                <table class="table table-bordered table-striped table-vcenter">
                    <!--
                    大有  表单头部
                    表单头部的th，添加class="table_arr"，则增加右侧排序箭头
                    -->
                    <thead>
                        <tr>
                            <th class="table_arr">ID</th>
                            <th class="table_arr">手机号</th>
                            <th class="table_arr">公司名称</th>
                            <th>状态</th>
                            <th style="width:130px">操作</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item->id }}</td><!--id序号-->
                                <td>{{ $item->phone }}</td><!--电话-->
                                <td>{{ $item->name }}</td><!--公司名称-->
                                @switch($item->status)
                                    @case(\App\Entities\CheckName::STATUS_ALLOW)
                                        <td class="text-green">可以注册</td>
                                        @break
                                    @case(\App\Entities\CheckName::STATUS_REFUSE)
                                        <td class="text-red">不可以注册</td>
                                        @break
                                    @default
                                        <td class="text-red">未知</td>
                                        @break
                                @endswitch
                                <td><!--操作-->
                                    <div class="btn-group">
                                        <button data-href="{{ route('check-name.delete', ['id' => $item->id]) }}" class="btn btn-sm btn-app-red table_btn_del">删除</button><!--删除信息-->
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
