@extends('child.base')

@section('title', '后台')

@section('content')
    <div class="container-fluid p-y-md">
        <div class="card">
                <div class="card-header"><h4 class="base_tit">绑定步骤 <span class="h6 text-red">（请按步骤进行操作，否则会收不到商机推送）</span></h4></div>
                <style>
                    .col-sm-6.col-lg-3 > a{ height:550px;}
                    .imgdiv{border:1px solid #f5f5f5; padding:10px; margin:10px auto;width:100%; max-width:280px;}
                    .titlit{height:40px; line-height:20px;}
                    .gobtn{ background:#5cb85c; color:#fff; border-radius:3px;display:block; padding:5px 0; max-width:300px; margin:auto}.gobtn:hover{ color:#fff}
                    @media screen and (max-width:1440px){
                        .col-sm-6.col-lg-3 > a{ height:430px;}
                        .card-block{ padding:10px}
                    }
                </style>
                <div class="row" style="margin:10px">
                    <div class="col-sm-6 col-lg-3"  >
                        <a class="card hover-shadow-3 text-center" href="javascript:void(0)">
                            <div class="card-block bg-green bg-inverse" style="padding:10px;">
                                <div class="h5 m-y-sm">第一步 </div>
                                <div class="text-muted m-t-0" style="font-size:12px;text-transform:uppercase">The first step</div>
                            </div>
                            <div class="card-block">
                                <table class="table table-borderless table-condensed">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <p class="titlit">搜索公众号“<span class="text-red">大有企服</span>”进行关注。</p>
                                            <p style="color:#aaa; margin-top:30px; border-top:1px dashed #ddd;padding-top:30px;">示例图片：</p>
                                            <div class="imgdiv"><img src="/assets/img/type1.jpg" alt="" style="width:100%"></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3"  >
                        <a class="card hover-shadow-3 text-center" href="javascript:void(0)">
                            <div class="card-block bg-green bg-inverse" style="padding:10px;">
                                <div class="h5 m-y-sm">第二步 </div>
                                <div class="h6 font-300 text-muted m-t-0" style="font-size:12px;text-transform:uppercase">The second step</div>
                            </div>
                            <div class="card-block">
                                <table class="table table-borderless table-condensed">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <p class="titlit"><a href="https://cli.im/api/qrcode/code?text={{ route("child.wechat.bind", ["id" => Auth::id()]) }}" class="gobtn"> 点击此处获取二维码进行绑定</a></p>
                                            <p style="color:#aaa;margin-top:30px; border-top:1px dashed #ddd;padding-top:30px;">示例图片：</p>
                                            <div class="imgdiv"><img src="/assets/img/type2.jpg" alt="" style="width:100%"></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3"  >
                        <a class="card hover-shadow-3 text-center" href="javascript:void(0)">
                            <div class="card-block bg-green bg-inverse" style="padding:10px;">
                                <div class="h5 m-y-sm">第三步 </div>
                                <div class="h6 font-300 text-muted m-t-0" style="font-size:12px;text-transform:uppercase">The third step</div>
                            </div>
                            <div class="card-block">
                                <table class="table table-borderless table-condensed">
                                    <tbody>
                                    <tr>
                                        <td>
                                            <p class="titlit">绑定成功啦！</p>
                                            <p style="color:#aaa;margin-top:30px; border-top:1px dashed #ddd;padding-top:30px;">示例图片：</p>
                                            <div class="imgdiv"><img src="/assets/img/type3.jpg" alt="" style="width:100%"></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-lg-3"   >
                        <a class="card hover-shadow-3 text-center" href="javascript:void(0)">
                            <div class="card-block bg-green bg-inverse" style="padding:10px;">
                                <div class="h5 m-y-sm">请稍等 </div>
                                <div class="h6 font-300 text-muted m-t-0" style="font-size:12px;text-transform:uppercase">Please wait</div>
                            </div>
                            <div class="card-block" style="font-size:16px; line-height:50px;">
                                <p><img src="/assets/img/wait.png" alt="" style="margin:150px 0 0"></p>
                                <p >请等待线索推送测试</p>
                            </div>
                        </a>
                    </div>
                </div>
            <!--<div class="card-block">-->
                            <!--<div class="alert alert-success">-->
                                <!--<p>第一步，搜索公众号“大易易分享”进行关注。 </p>-->
                            <!--</div>-->
                            <!--<div class="alert alert-success">-->
                                <!--<p>第二步：<a href="https://cli.im/api/qrcode/code?text={{ route("child.wechat.bind", ["id" => Auth::id()]) }}" class="gobtn"> 点击此处获取二维码进行绑定</a></p>-->
                            <!--</div>-->
                            <!--<div class="alert alert-success">-->
                                <!--<p>注：否则会收不到商机推送</p>-->
                            <!--</div>-->
          <!--</div>-->
        </div>
    </div>
@endsection
