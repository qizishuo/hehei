<?php

namespace App\Http\Controllers\Api;

class WechatController extends Controller
{
    public function send()
    {
        $app = app('wechat.official_account');
        $app->template_message->send([
            'touser' => 'ovXnb5rAcDQr4n-JTpnBcHoGir04',
            'template_id' => 'LSI8BxlntFlv-6BEdR0Nox4dy09pg4P_CHUpMptuYt0',
            'data' => [
                "first" => "【大有企服平台分配】【核名线索】hausir您好，有新的客户分配给您",
                "keyword1" => "河北省 石家庄市",
                "keyword2" => "123456",
                "keyword3" => 18888888888,
                "keyword4" => "2020-01-01 00:00:00",
                "keyword5" => "核名",
            ],
        ]);
    }
}
