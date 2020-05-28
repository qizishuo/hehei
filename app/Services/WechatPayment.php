<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\GuzzleException;
use App\Entities\Qiming\Order;

class WechatPayment
{

    protected $payment = "";
    protected static $instance = "";

    private function __construct()
    {
        $this->payment = \EasyWeChat::payment();

    }

    public static function get_instance()
    {

        if (empty(self::$instance)) {
            self::$instance = new WechatPayment();
        }
        return self::$instance;
    }

    /**
     * @param $body
     * @param $out_trade_no
     * @param $total_fee
     * @return bool|string 统一下单失败返回false 成功返回 mweb_url
     */
    public function unify($body, $out_trade_no, $total_fee)
    {

        try {
            $result = $this->payment->order->unify([
                'body' => $body,
                'out_trade_no' => $out_trade_no,
                'total_fee' => intval($total_fee),
                'trade_type' => 'NATIVE',
            ]);

            if ($result['return_code'] !== "SUCCESS" || $result['result_code'] !== "SUCCESS") {
                Log::error("微信支付统一下单错误:" . $result['return_msg']);
                return false;
            }
            return $result['code_url'];

        } catch (GuzzleException $exception) {
            Log::error("微信支付请求错误:" . $exception->getMessage());
            return false;
        }

    }

    /**根据用户订单号查询交易
     * @param $order_code
     * @return bool|mixed
     */
    public function queryByOutTradeNumber($order_code)
    {
        try {
            $result = $this->payment->order->queryByOutTradeNumber($order_code);
            return $result;

        } catch (GuzzleException $exception) {
            Log::error("微信支付订单查询错误:" . $exception->getMessage());
            return false;
        }

    }

    /**微信支付信息回调
     * @return mixed
     */
    public function paymentMsg(\Closure $func)
    {
        $response = $this->payment->handlePaidNotify($func);
        return $response;
    }

}
