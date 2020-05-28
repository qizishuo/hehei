<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Illuminate\Console\Command;
use App\Entities\Qiming\Order;
use Illuminate\Support\Facades\Log;

class CheckOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '调用微信接口,查询所有未支付(STATUS_UNPAY)订单,并且更新订单状态';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $datas = Order::where('status', Order::STATUS_UNPAY)->get();
        $order_service = new OrderService();
        foreach ($datas as $data) {
            $order_service->queryByOutTradeNumber($data);
        }
    }
}
