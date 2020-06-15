<?php

namespace App\Http\Controllers\NewEdit\Admin;

use App\Services\Redis;
use Illuminate\Http\Request;
use App\Http\Controllers\NewEdit\Controller;
class SystemController extends Controller
{
    public function money(Request $request)
    {
        $money_redis = new Redis("system_money");
        if ($request->isMethod("get")) {
            $money = $money_redis->get();
            return $this->jsonSuccessData([
                'money' => $money
            ]);
        }
        $money = $request->post('money');

        $money_redis->set($money);

        return $this->jsonSuccessData();
    }
}
