@extends('admin.base')

@section('title', '子账号管理-充值')

@section('content')
    <div class="container-fluid p-y-md">
        <div class="card">
            <div class="card-header">
                <!--大有 使用layui-->
                <form action="" method="post">
                    @csrf
                    <div class="layui-form-item form_word_left">
                        <div class="layui-inline">
                            <label class="layui-form-label">名称</label>
                            <div class="layui-input-inline">
                                <!--名称不可修改，是调用的数据-->
                                <input type="tel" name="phone" lay-verify="required|phone" autocomplete="off" class="layui-input input_noborder" readonly value="{{ $data->name }}">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">余额</label>
                            <div class="layui-input-inline">
                                <!--余额不可修改，是调用的数据-->
                                <input type="text" name="email" lay-verify="email" autocomplete="off" class="layui-input input_noborder" readonly value="{{ $data->amount }}">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">充值金额</label>
                            <div class="layui-input-inline">
                                <!--充值金额，可以修改-->
                                <input type="text" name="recharge" lay-verify="email" autocomplete="off" class="layui-input" placeholder="请输入">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">扣款金额</label>
                            <div class="layui-input-inline">
                                <!--扣款金额，可以修改-->
                                <input type="text" name="price" lay-verify="email" autocomplete="off" class="layui-input" value="{{ $data->price }}">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <button class="btn btn-success btn-block" type="submit">确定</button><!-- 大有  确定 按钮-->
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
                            <th class="table_arr">金额</th>
                            <th class="table_arr">变动</th>
                            <th class="table_arr">说明</th>
                            <th class="table_arr">提交时间</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($money_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <!--id序号-->
                                <td>{{ $item->amount }}</td>
                                <!--金额-->
                                @switch($item->type)
                                    @case(\App\Entities\Money::TYPE_RECHARGE)
                                        <td class="text-green font_spacing">+</td>
                                        @break
                                    @case(\App\Entities\Money::TYPE_CONSUME)
                                        <td class="text-red font_spacing">--</td>
                                        @break
                                    @default
                                        <td class="text-red font_spacing">见鬼了</td>
                                        @break
                                @endswitch
                                <td>{{ $item->comment }}</td>
                                <!--说明，有三种文字：自动分配订单扣除【备注：此时减钱】， 申诉订单通过 【备注：此时加钱】，平台主动充值 【备注：此时加钱】-->
                                <td>{{ $item->created_at }}</td>
                                <!--提交时间-->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $money_data->links() }}
            </div>
        </div>
    </div>
@endsection
