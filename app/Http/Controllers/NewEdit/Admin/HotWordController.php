<?php

namespace App\Http\Controllers\NewEdit\Admin;

use App\Services\Redis;
use Illuminate\Http\Request;
use App\Http\Controllers\NewEdit\Controller;
class HotWordController extends Controller
{
    /**
     * @var Redis
     */
    protected $redis;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->redis = new Redis("hotword");
    }

    public function edit()
    {
        $words = $this->redis->get();
        return  $this->jsonSuccessData(['words' => $words]);

    }

    public function save(Request $request)
    {
        $words = $request->post('words');

        $this->redis->set($words);

        return  $this->jsonSuccessData();
    }
}
