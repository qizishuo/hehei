<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Yunpian\Sdk\YunpianClient;

function searchArr($keyword){
    $data = require '../data.php';
    $arr = array();
    foreach($data as $key=>$values ){
        if (strstr( $values , $keyword ) !== false ){
            array_push($arr, $values);
        }
    }
    return $arr;
}


function location_name(string $code): string
{
    try {
        return \cn\GB2260::parse($code);
    } catch (Exception $exception) {
        return \cn\GB2260::getData()[$code] ?? "未知区域";
    }
}

function active_menu(string $route = '', string $text = 'active'): string
{
    $active = '';

    if (Str::startsWith(Route::currentRouteName(), $route) || Request::routeIs($route)) {
        $active = $text;
    }

    return $active;
}

function is_phone(string $phone): bool
{
    if (preg_match("/^1[3456789]\d{9}$/", $phone)) {
        return true;
    }
    return false;
}

function send_captcha(string $phone, string $captcha): bool
{
    $text = '【大有企服】您的验证码是' . $captcha;
    if (config("app.debug")) {
        Log::info($phone . " " . $text);
        return true;
    }

    $clnt = YunpianClient::create("65ac9a915e7686ac5555303642ad2fc9");

    $param = [
        YunpianClient::MOBILE => $phone,
        YunpianClient::TEXT => $text,
    ];

    $r = $clnt->sms()->single_send($param);

    if ($r->isSucc()) {
        $data = $r->data();
        switch ($data["code"]) {
            case 0:
                return true;
            case 3:
                // TODO: 余额不足提醒
            default:
                return false;
        }
    }

    return false;
}
