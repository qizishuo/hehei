<?php


namespace App\Services;

use App\Entities\Qiming\Order;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected $wechat_payment;

    public function __construct()
    {
        $this->wechat_payment = WechatPayment::get_instance();

    }

    /**微信支付回调
     * @param $message
     * @param $fail
     * @return bool
     */
    static public function pay_callback_msg()
    {

        $func = function ($message, $fail) {
            Log::debug($message);

            if ($message['return_code'] === 'SUCCESS') {

                //如果订单存在在redis里面,并且状态为success 就判定为已经处理过的数据.
                $redis = new Redis("OrderStatus", $message['out_trade_no']);
                $data = $redis->get();
                if ($data == "SUCCESS") {
                    return true;
                }

                $order = Order::where('code', $message['out_trade_no'])->first();
                if (empty(($order))) {
                    return false;
                }

                if ($message['result_code'] === 'SUCCESS') {
                    $order->status = Order::STATUS_PAIED;

                } elseif ($message['result_code'] === 'FAIL') {
                    $order->status = Order::STATUS_USER_CANCEL_PAY;
                }
                $redis->setex(intval(env("REDIS_ORDER_LIVE_TIME", 250 * 3600)), $message['result_code']);
                $order->save();
                return true;
            } else {
                Log::error("微信支付回调信息错误:" . $message['return_msg']);
                return $fail('通信失败，请稍后再通知我');
            }


        };

        return $func;
    }


    /**下单
     * @param $code
     * @return bool|string 统一下单失败返回false 成功返回 mweb_url
     */
    public function unify($code)
    {
        $url = $this->wechat_payment->unify("大易乾行-起名支付", $code, 1);
        return $url;
    }

    /**查询用户订单状态
     * @param Order $order
     * @return bool 查询成功返回订单状态
     */
    public function queryByOutTradeNumber(Order $order)
    {

        $redis = new Redis("OrderStatus", $order->code);
        $data = $redis->get();

        if ($order->status == Order::STATUS_PAIED && $data == "SUCCESS") {
            return $order->status;
        }

        $result = $this->wechat_payment->queryByOutTradeNumber($order->code);
        if ($result && $result['return_code'] == "SUCCESS" && $result['result_code'] == "SUCCESS") {
            if ($result['trade_state'] == "SUCCESS") {
                $order->status = Order::STATUS_PAIED;
            } else {
                $order->status = Order::STATUS_USER_CANCEL_PAY;
            }

            $order->save();
            $redis->setex(intval(env("REDIS_ORDER_LIVE_TIME", 25 * 3600)), $result['trade_state']);
        }

        return $order->status;
    }


    /**用户重新支付订单,如果已经支付完成的不会重复支付
     * @param Order $order
     * @return bool|string
     */
    public function repay(Order $order)
    {
        $status = $this->queryByOutTradeNumber($order);
        $url = "";
        if ($status !== Order::STATUS_PAIED) {
            $url = $this->unify($order->code);
        }
        return $url;

    }


}
