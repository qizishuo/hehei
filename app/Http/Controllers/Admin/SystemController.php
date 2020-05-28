<?php

namespace App\Http\Controllers\Admin;

use App\Services\Redis;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function money(Request $request)
    {
        $money = $request->post('money');
        $money_redis = new Redis("system_money");
        if ($money) {
            $money_redis->set($money);
        } else {
            $money = $money_redis->get();
        }

        return view('admin.system.money', [
            'money' => $money,
        ]);
    }
}
