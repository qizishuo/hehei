@extends('admin.base')

@section('title', '信息管理-修改分配')

@section('content')
<div class="container-fluid p-y-md">
    <div class="card col-sm-12">
        <div class="card-header">
            <h4 class="base_tit">修改分配</h4>
        </div>
        <div class="col-sm-5">
            <!--大有  以下为 【起名】修改分配表单-->
            <form class="layui-form form_list" action="" method="post">
                @csrf
                @switch($data->type)
                @case(\App\Entities\Info::TYPE_CHECK)
                <!--咨询公司名称开始-->
                <div class="layui-form-item">
                    <label class="layui-form-label">公司名称</label>
                    <div class="layui-input-block">
                        <!--名称不可修改，是调用的数据-->
                        <input type="text" name="title" lay-verify="title" autocomplete="off" value="{{ $data->company_name }}" readonly class="layui-input input_noborder">
                    </div>
                </div>

                <!--手机号开始-->
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-block">
                        <!--手机号不可修改，是调用的数据-->
                        <input type="text" name="title" autocomplete="off" value="{{ $data->phone_number }}" readonly class="layui-input input_noborder">
                    </div>
                </div>
                @break
                @case(\App\Entities\Info::TYPE_NAMED)
                <div class="layui-form-item">
                    <label class="layui-form-label">老板姓名</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" lay-verify="title" autocomplete="off" value="{{ $data->boss_name }}" readonly class="layui-input input_noborder">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" autocomplete="off" value="{{ $data->phone_number }}" readonly class="layui-input input_noborder">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">城市</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" autocomplete="off" value="{{ $data->getLocation() }}" readonly class="layui-input input_noborder">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">行业</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" autocomplete="off" value="{{ $data->industry }}" readonly class="layui-input input_noborder">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">生日</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" autocomplete="off" value="{{ $data->boss_birth }}" readonly class="layui-input input_noborder">
                    </div>
                </div>
                @break
                @default
                @break
                @endswitch

                <!--大有 子账号所属于-->
                <div class="layui-form-item">
                    <label class="layui-form-label">子账号所属</label>
                    <div class="layui-input-inline">
                        <select name="child_account_id">
                            <option>请选择子账号</option>
                            @foreach($child_accounts as $item)
                                @if($item->id == $data->child_account_id)
                                    <option selected value="{{ $item->id }}">{{ $item->name }}</option>
                                @else
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <!--大有  提交开始-->
                <div class="layui-form-item">
                    <label class="layui-form-label"></label>
                    <div class="layui-input-block">
                        <button class="btn btn-success btn-block" type="submit">确定</button><!-- 大有  确定 按钮-->
                    </div>
                </div>
            </form>
            <!--【起名】修改分配表单-->
        </div>
    </div>
</div>
@endsection
