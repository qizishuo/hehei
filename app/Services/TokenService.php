<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class TokenService
{
    protected $type;
    protected $expire;

    public function __construct($type, $expire = 60 * 60 * 24)
    {
        $this->type = $type;
        $this->expire = $expire;
    }

    public function write($data)
    {
        $token = $this->generate();
        $key = "token:{$this->type}:{$token}";

        Redis::setex($key, $this->expire, json_encode($data));


        return $token;
    }

    public function get($token)
    {
        $key = "token:{$this->type}:{$token}";
        $data = Redis::get($key);
        return json_decode($data, true);
    }

    public function delete($token)
    {
        $key = "token:{$this->type}:{$token}";
        Redis::del($key);
    }

    public function ttl($token)
    {
        $key = "token:{$this->type}:{$token}";
        return Redis::ttl($key);
    }

    protected function generate()
    {
        return md5(uniqid(uniqid(), true));
    }
}
