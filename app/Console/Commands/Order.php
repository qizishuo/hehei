<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Order extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '查看订单';

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
        $order = \App\Entities\Qiming\Order::where("code", $this->argument('code'))->first();
        print_r($order->toArray());
    }
}
