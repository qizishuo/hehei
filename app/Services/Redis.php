<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis as RedisCli;
use Illuminate\Support\Str;

/**
 * @method string get()
 * @method del()
 * @method set(string $money)
 */
class Redis
{
    protected $key;

    public function __construct(string $type, ...$args)
    {
        $function_name = "get" . Str::studly($type) . "Key";
        $this->key = $this->$function_name(...$args);
    }

    protected function getCaptchaKey(string $phone): string
    {
        return "captcha:" . $phone;
    }

    protected function getIpKey(string $ip): string
    {
        return "ip:" . $ip;
    }

    protected function getSystemMoneyKey(): string
    {
        return "system:money";
    }

    protected function getHotwordKey(): string
    {
        return "hotword";
    }

    protected function getOrderStatusKey($out_trade_no): string
    {
        return "order_status:" . $out_trade_no;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([RedisCli::class, $name], Arr::prepend($arguments, $this->key));
    }
}
