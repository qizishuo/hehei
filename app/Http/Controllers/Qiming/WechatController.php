<?php

namespace App\Http\Controllers\Qiming;

use App\Http\Controllers\Controller;
use App\Services\WechatPayment;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{
    public function payment()
    {
        $payment = WechatPayment::get_instance();
        return $payment->paymentMsg(OrderService::pay_callback_msg());
    }
}
