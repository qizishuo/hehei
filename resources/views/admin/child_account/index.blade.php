@extends('admin.base')

@section('title', '子账号管理')

@section('content')
    <div class="container-fluid p-y-md">
        <div class="card">
            <div class="card-header">
                <a href="{{ route("child-account.create") }}" class="btn btn-success">添加</a><!--按钮-->
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
                            <th>ID</th>
                            <th>子账号名称</th>
                            <th>子账号类型</th>
                            <th>地区</th>
                            <th>剩余金额</th>
                            <th>扣款金额</th>
                            <th class="table_arr">订单数量</th>
                            <th class="table_arr">当日数量</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>

                    <tbody>
                    <!-- 大有  表单列表开始 , 每个 tr-->
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item->id }}</td><!--id序号-->
                                <td>{{ $item->name }}</td><!--子账号名称-->
                                <!--子账号类型，有两种：第三方账户(样式为：text-red)，平台子账户(样式为：text-green)-->
                                @switch($item->type)
                                    @case(1)
                                        <td class="text-red">第三方账户</td>
                                        @break
                                    @case(2)
                                        <td class="text-green">平台子账户</td>
                                        @break
                                    @default
                                        <td class="text-red">未知类型</td>
                                        @break
                                @endswitch
                                <td>{{ location_name($item->location) }}</td><!--地区-->
                                <td>{{ $item->amount }}</td><!--剩余金额-->
                                <td>{{ $item->consume }}</td><!--扣款金额-->
                                <td><a href="{{ route("info.list", ["child_account_id" => $item->id]) }}" class="btn btn-sm btn-success">{{ $item->phones_count }}</a></td><!--订单数量，跳转新页面-->
                                <td>{{ $item->phones_daily_count }}</td><!--当日数量-->
                                <!--状态-->
                                @if ($item->trashed())
                                    <td class="text-red">已关闭</td>
                                @else
                                    <td class="text-green">正常</td>
                                @endif
                                <td class="text-center"><!--状态-->
                                    <div class="btn-group">
                                        @if ($item->trashed())
                                            <button data-href="{{ route('child-account.open', ['id' => $item->id]) }}" class="btn btn-sm btn-app-red table_btn_open">开启</button>
                                        @else
                                            <button data-href="{{ route('child-account.close', ['id' => $item->id]) }}" class="btn btn-sm btn-app-red table_btn_close">关闭</button>
                                        @endif
                                        <a href="{{ route("child-account.edit", ["id" => $item->id]) }}" class="btn btn-sm btn-success">编辑</a><!--跳转新页面-->
                                        <a href="{{ route("child-account.money", ["id" => $item->id]) }}" class="btn btn-sm btn-success">充值</a><!--跳转新页面-->
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->links() }}
            </div>
            <!-- .card-block -->
        </div>
        <!-- .card -->

        <!-- End Dynamic Table Simple -->
    </div>
@endsection

@section('script')
    <script>
        // 开启按钮
        $('.table_btn_open').on('click', function () {
            let href = $(this).data('href');
            layer.confirm('确定开启？', function () {
                location.href = href;
            });
        });

        // 关闭按钮
        $('.table_btn_close').on('click', function () {
            let href = $(this).data('href');
            layer.confirm('确定关闭？', function () {
                location.href = href;
            });
        });

        // 删除按钮
        $('.table_btn_del').on('click', function () {
            let href = $(this).data('href');
            layer.confirm('确定删除？', function () {
                location.href = href;
            });
        });

        // 恢复按钮
        $('.table_btn_undel').on('click', function () {
            let href = $(this).data('href');
            layer.confirm('确定恢复账号？', function () {
                location.href = href;
            });
        });
    </script>
@endsection
