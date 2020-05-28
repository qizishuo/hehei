@extends('admin.base')

@section('title', '子账号管理-添加子账号')

@section('content')
    <div class="container-fluid p-y-md">
        <div class="card col-sm-12">
            <div class="card-header">
                <h4 class="base_tit">添加子账号</h4>
            </div>
            <div class="col-sm-5">
                <!--表单 主要使用了layui-->
                <form class="layui-form" method="post" action="">
                    @csrf
                    <!--账号名称开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">账号名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" lay-verify="title" autocomplete="off" placeholder="账号名称" class="layui-input">
                        </div>
                    </div>

                    <!--账号密码开始-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">账号密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="password"  autocomplete="off" placeholder="账号密码" class="layui-input">
                        </div>
                    </div>

                    <!--子账号所属地址-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">子账号所属地址</label>
                        <!-- 地址选择插件 start -->
                        <div class="layui-row" style="float:left; width:440px">
                            <div class="layui-col-sm4" style="width:200px">
                                <div class="layui-inline layui-select-default" >
                                    <select name="province" data-area="" lay-filter="province">
                                        <option value="">选择省</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-col-sm4" style="width:200px">
                                <div class="layui-inline layui-select-default" style="width: 99%;">
                                    <select name="location" data-area="" lay-filter="city">
                                        <option value="">选择市</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- 地址选择插件 end -->
                    </div>

                    <!-- 大有 子账号类型-->
                    <div class="layui-form-item">
                        <label class="layui-form-label">子账号类型</label>

                        <div class="layui-input-inline">
                            <select name="type">
                                <option disabled value="0">请选择身份</option>
                                <option selected value="{{ \App\Entities\ChildAccount::TYPE_PLATFORM }}">平台子账号</option>
                                <option disabled value="{{ \App\Entities\ChildAccount::TYPE_THIRD }}">第三方账号</option>
                            </select>
                        </div>

                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">权重</label>
                        <div class="layui-input-inline">
                            <input type="text" name="weight" value="{{ \App\Entities\ChildAccount::DEFAULT_WEIGHT }}" class="layui-input" />
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
            </div>

        </div>



    </div>
@endsection
@section("script")
    <!--地区联动 开始-->
            <script src="{{ asset("assets/js/city-picker.js") }}"></script>
            <script type="text/javascript">
                //config的设置是全局的
                layui.config({
                    base: '/assets/js/' //假设这是存放拓展模块的根目录
                });

                layui.use(['form', 'common'], function(){
                    var common = layui.common,
                        form = layui.form;
                    //地址联动
                    common.showCity('province', 'location');
                    //监听提交
                    // form.on('submit(formDemo)', function(data){
                    //     var resData = data.field,
                    //         province = resData.province,
                    //         city = resData.city;
                    //     //district = resData.district
                    //
                    //     console.log(province, city)
                    //
                    //     // 通过地址code码获取地址名称
                    //     var address = common.getCity({
                    //         province,
                    //         city,
                    //         //district
                    //     });
                    //     console.log(address);
                    //     return false;
                    // });
                });
            </script>
            <!--地区联动 end-->
@endsection
