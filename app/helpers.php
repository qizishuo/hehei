<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Yunpian\Sdk\YunpianClient;

function searchArr($keyword){
    $data = require '../public/city.php';
    $arr = array();
    foreach($data as $key=>$values ){
        if (strstr( $values , $keyword ) !== false ){
            return  $key;
        }
    }

}
/**
 *
 *获取中文字符拼音首字母
 *
 * @param $str 中文字符
 *
 * @return null|string
 *
 */
function getFirstCharter($str)
{
    if (empty($str)) {
        return '';
    }
    $fchar = ord($str{0});
    if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
    $s1 = iconv('UTF-8', 'gb2312', $str);
    $s2 = iconv('gb2312', 'UTF-8', $s1);
    $s = $s2 == $str ? $s1 : $str;
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 && $asc <= -20284) return 'A';
    if ($asc >= -20283 && $asc <= -19776) return 'B';
    if ($asc >= -19775 && $asc <= -19219) return 'C';
    if ($asc >= -19218 && $asc <= -18711) return 'D';
    if ($asc >= -18710 && $asc <= -18527) return 'E';
    if ($asc >= -18526 && $asc <= -18240) return 'F';
    if ($asc >= -18239 && $asc <= -17923) return 'G';
    if ($asc >= -17922 && $asc <= -17418) return 'H';
    if ($asc >= -17417 && $asc <= -16475) return 'J';
    if ($asc >= -16474 && $asc <= -16213) return 'K';
    if ($asc >= -16212 && $asc <= -15641) return 'L';
    if ($asc >= -15640 && $asc <= -15166) return 'M';
    if ($asc >= -15165 && $asc <= -14923) return 'N';
    if ($asc >= -14922 && $asc <= -14915) return 'O';
    if ($asc >= -14914 && $asc <= -14631) return 'P';
    if ($asc >= -14630 && $asc <= -14150) return 'Q';
    if ($asc >= -14149 && $asc <= -14091) return 'R';
    if ($asc >= -14090 && $asc <= -13319) return 'S';
    if ($asc >= -13318 && $asc <= -12839) return 'T';
    if ($asc >= -12838 && $asc <= -12557) return 'W';
    if ($asc >= -12556 && $asc <= -11848) return 'X';
    if ($asc >= -11847 && $asc <= -11056) return 'Y';
    if ($asc >= -11055 && $asc <= -10247) return 'Z';
    return null;
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

function is_phone($phone): bool
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
