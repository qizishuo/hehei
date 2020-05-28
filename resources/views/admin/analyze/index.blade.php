@extends('admin.base')

@section('title', '总后台')

@section('content')
    <div class="container-fluid p-y-md">
        <!--大有 图表1：  平台数据统计 开始 ,折线图，近30天数据-->
        <div class="card col-sm-12">
            <h4 class="data_wordtit">平台数据统计</h4>
            <!--图表开始，以下一行-->
            <div id="main1"  class="data_base_grap"></div>
        </div>

        <!--大有 图表2： 平台每日统计 开始 , 柱状图，可以按日期查询（近30日）（默认显示当日）-->
        <div class="card col-sm-12" style="height:580px">
            <h4 class="data_wordtit">平台每日统计</h4>
            <!--查询表单-->
            <div class="data_screening" style="position:  relative;z-index: 99;">
                <form class="layui-form form_list" action="">
                    <input type="hidden" name="monthly_date" value="{{ $params["monthly_date"] }}" />
                    <input type="hidden" name="range_start" value="{{ $params["range_start"] }}" />
                    <input type="hidden" name="range_end" value="{{ $params["range_end"] }}" />
                    <input type="hidden" name="type" value="{{ $params["type"] }}" />
                    <div class="layui-form-item" style="clear:none; float:left">
                        <label class="layui-form-label">查询日期</label>
                        <div class="layui-input-inline">
                            <!--大有   开始日期，以下一行input代码-->
                            <input type="text" class="layui-input" id="date1" name="daily_date" value="{{ $params["daily_date"] }}">
                        </div>
                    </div>

                    <!--大有  查询开始-->
                    <div class="layui-input-block">
                        <button class="btn btn-success btn-block" type="submit">查询</button><!-- 大有  查询 按钮-->
                    </div>
                </form>
            </div>
            <!--图表 ，以下一行-->
            <div id="main2" class="data_base_grap"></div>
        </div>

        <!--  大有 图表3： 平台每月统计开始 , 柱状图，可以按月份查询（本月，及上月数据。默认显示本月），可以按区域查询（可选择多个区域同时查询）-->
        <div class="card col-sm-12" style="height:530px">
            <h4 class="data_wordtit">平台每月统计</h4>
            <!--查询表单-->
            <div class="data_screening" style="position:  relative;z-index: 99;">
                <form class="layui-form form_list" action="">
                    <input type="hidden" name="daily_date" value="{{ $params["daily_date"] }}" />
                    <input type="hidden" name="range_start" value="{{ $params["range_start"] }}" />
                    <input type="hidden" name="range_end" value="{{ $params["range_end"] }}" />
                    <input type="hidden" name="type" value="{{ $params["type"] }}" />
                    <!--以下为查询月份-->
                    <div class="layui-form-item" style="clear:none; float:left">
                        <label class="layui-form-label">查询月份</label>
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" id="date_month" name="monthly_date" value="{{ $params["monthly_date"] }}">
                        </div>
                    </div>




                    <!--大有  查询开始-->
                    <div class="layui-input-block">
                        <button class="btn btn-success btn-block" type="submit">查询</button><!-- 大有  查询 按钮-->
                    </div>
                </form>
            </div>
            <!--图表 ，以下一行-->
            <div id="main3"  class="data_base_grap"></div>
        </div>


        <!--  大有 图表4： 平台起名核名统计开始 , 柱状图，可以按月份查询（本月，及上月数据。默认显示本月），可以按类型查询（起名、核名）-->
        <div class="card col-sm-12" style="height:580px">
            <h4 class="data_wordtit">平台起名核名统计</h4>
            <!--查询表单-->
            <div class="data_screening" style="position:  relative;z-index: 99;">
                <form class="layui-form form_list" action="">
                    <input type="hidden" name="daily_date" value="{{ $params["daily_date"] }}" />
                    <input type="hidden" name="monthly_date" value="{{ $params["monthly_date"] }}" />
                    <!--以下为查询时间段-->
                    <div class="layui-form-item" style="clear:none; float:left">
                        <label class="layui-form-label">查询时间段</label>
                        <div class="layui-input-inline">
                            <!--大有   开始日期，以下一行input代码-->
                            <input type="text" class="layui-input" id="date2" name="range_start" value="{{ $params["range_start"] }}">
                        </div>
                        <label class="layui-form-label">至</label>
                        <div class="layui-input-inline">
                            <!--大有   结束日期，以下一行input代码-->
                            <input type="text" class="layui-input" id="date3" name="range_end" value="{{ $params["range_end"] }}">
                        </div>
                    </div>

                    <!--以下为查询类型-->
                    <div class="layui-form-item" style="clear:none; float:left">
                        <label class="layui-form-label">查询类型</label>
                        <div class="layui-input-inline">
                            <select name="type">
                                <option value="">请选择类型</option>
                                @foreach(\App\Entities\Info::getTypes() as $type => $name)
                                    <option @if($params["type"] == $type) selected @endif value="{{ $type }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!--大有  查询开始-->
                    <div class="layui-input-block">
                        <button class="btn btn-success btn-block" type="submit">查询</button><!-- 大有  查询 按钮-->
                    </div>
                </form>
            </div>
            <!--图表 ，以下一行-->
            <div id="main4"  class="data_base_grap"></div>
        </div>


        <div class="card col-sm-12">
            <h4 class="data_wordtit">昨日数据</h4>
            <!--图表开始，以下一行-->
            <div id="yesterday_info"  class="data_base_grap"></div>
        </div>

    </div>
@endsection

@section("script")
    <script src="/assets/js/formSelects-v4.min.js"></script><!--下拉，多选，区域-->
    <script src="/assets/js/echarts.min.js"></script>
    <script>
        //大有  日期 使用layui
        layui.use('laydate', function(){
            var laydate = layui.laydate;
            //执行一个laydate实例
            laydate.render({
                elem: '#date1' //指定元素，平台每日统计(表2)
            });
            laydate.render({
                elem: '#date_month',
                type: 'month',
            });
            laydate.render({
                elem: '#date2' //指定元素，平台起名核名统计（表4）（开始日期）
            });
            laydate.render({
                elem: '#date3' //指定元素，平台起名核名统计（表4）（结束日期）
            });
        });

        //第一个图表：平台数据统计，（折线图，近30日数据）
        var myChart1 = echarts.init(document.getElementById('main1'));
        var option1 = {
            xAxis: {
                type: 'category',
                data: @json(array_keys($data))
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                data: @json(array_values($data)),
                type: 'line'
            }]
        };
        myChart1.setOption(option1);


        //第二个图表： 平台每日统计 ,（柱状图）根据后台的子账号名称，显示每个子账号的某日数据统计
        var myChart2 = echarts.init(document.getElementById('main2'));
        var option2 = {
            xAxis: {
                type: 'category',
                data: @json(array_keys($daily_data)),
                //以下文字竖向排列
                axisLabel:{
                    formatter:function(value){
                        return value.split(' ')[value.split(' ').length - 1].split("").join("\n");
                    }
                },
            },
            yAxis: {
                type: 'value'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            series: [{
                data: @json(array_values($daily_data)),
                type: 'bar',

                barWidth:45,
                itemStyle: {
                    normal: {
                        label: {
                            show: true, //开启显示
                            position: 'top', //在上方显示
                            textStyle: { //数值样式
                                color: 'black',
                                fontSize: 16
                            }
                        },
                        color: function(params) {//注意，如果颜色太少的话，后面颜色不会自动循环，最好多定义几个颜色
                            var colorList = [
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                            ];
                            return colorList[params.dataIndex];
                        }
                    }
                }
            }]
        };
        myChart2.setOption(option2);


        //第3个图表： 平台每日统计 ,（柱状图）根据后台的子账号名称，显示每个子账号的某月（本月、上月）数据统计
        var dom = document.getElementById("main3");
        var myChart = echarts.init(dom);
        var app = {};
        option = null;
        option = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'horizontal',
                x: 'center',
                data: @json(array_column($monthly_data, "name"))
            },
            series : [
                {
                    name: '访问来源',
                    type: 'pie',
                    radius : '55%',
                    center: ['50%', '50%'],
                    data: @json($monthly_data),
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    },
                    label: {
                        normal: {
                            show: true,
                            formatter: '{b}: {c}'
                        }
                    },
                }
            ]
        };
        if (option && typeof option === "object") {
            myChart.setOption(option, true);
        };


        //第4个图表： 平台每日统计 ,（柱状图）根据后台的子账号名称，显示每个子账号的某月（本月、上月）数据统计
        var myChart4 = echarts.init(document.getElementById('main4'));
        var option4 = {
            xAxis: {
                type: 'category',
                data: @json(array_keys($range_data)),
                //以下文字竖向排列
                axisLabel:{
                    formatter:function(value){
                        return value.split(' ')[value.split(' ').length - 1].split("").join("\n");
                    }
                },
            },
            yAxis: {
                type: 'value'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            series: [{
                data: @json(array_values($range_data)),
                type: 'bar',
                barWidth:45,
                itemStyle: {
                    normal: {
                        label: {
                            show: true, //开启显示
                            position: 'top', //在上方显示
                            textStyle: { //数值样式
                                color: 'black',
                                fontSize: 16
                            }
                        },
                        color: function(params) {//注意，如果颜色太少的话，后面颜色不会自动循环，最好多定义几个颜色
                            var colorList = [
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                            ];
                            return colorList[params.dataIndex];
                        }
                    }
                }
            }]
        };
        myChart4.setOption(option4);


        //图表5,显示昨天的数据
        var yesterday_info = echarts.init(document.getElementById('yesterday_info'));
        var today_info_option = {
            xAxis: {
                type: 'category',
                data: @json(array_keys($yesterday_data)),
                //以下文字竖向排列
                axisLabel:{
                    formatter:function(value){
                        return value.split(' ')[value.split(' ').length - 1].split("").join("\n");
                    }
                },
            },
            yAxis: {
                type: 'value'
            },
            tooltip : {
                trigger: 'axis',
                axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                    type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            series: [{
                data: @json(array_values($yesterday_data)),
                type: 'bar',

                barWidth:45,
                itemStyle: {
                    normal: {
                        label: {
                            show: true, //开启显示
                            position: 'top', //在上方显示
                            textStyle: { //数值样式
                                color: 'black',
                                fontSize: 16
                            }
                        },
                        color: function(params) {//注意，如果颜色太少的话，后面颜色不会自动循环，最好多定义几个颜色
                            var colorList = [
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                                '#c23531', '#61a0a8', '#d48265', '#91c7ae', '#749f83', '#ca8622',
                            ];
                            return colorList[params.dataIndex];
                        }
                    }
                }
            }]
        };
        yesterday_info.setOption(today_info_option);
    </script>
@endsection
