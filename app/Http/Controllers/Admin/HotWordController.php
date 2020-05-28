<?php

namespace App\Http\Controllers\Admin;

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

    public function edit()
    {
        $words = $this->redis->get();

        return view('admin.hot_word.index', [
            'words' => $words,
        ]);
    }

    public function save(Request $request)
    {
        $words = $request->post('words');

        $this->redis->set($words);

        return redirect()->back();
    }
}
