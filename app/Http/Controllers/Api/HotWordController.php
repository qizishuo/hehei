<?php

namespace App\Http\Controllers\Api;

use App\Services\Redis;
use Illuminate\Http\Request;

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

    public function index()
    {
        $words = $this->redis->get();
        $words = preg_split("/\s+/", $words);

        return $words;
    }
}
