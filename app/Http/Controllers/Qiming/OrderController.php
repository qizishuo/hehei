<?php

namespace App\Http\Controllers\Qiming;

use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Entities\Qiming\Order;
use App\Entities\Qiming\Name;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $model = Order::class;
    protected $service;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->service = new OrderService();
    }

    /**
     * Store a newly created resource in storage
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'surname' => ['required'],
            'gender' => [
                'required',
                Rule::in([Order::GENDER_UNKNOW, Order::GENDER_BOY, Order::GENDER_GIRL]),
            ],
            'birthday' => ['required', "numeric"],
            'type' => [
                'required',
                Rule::in([Order::TYPE_SINGLE, Order::TYPE_MULTI]),
            ],
        ]);

        $data["status"] = ORDER::STATUS_UNPAY;

        $data = $this->model::create($data);


        if ($data) {
            $url = $this->service->unify($data->code);
        }

        return response()->json([
            'code' => $data->code,
            'status' => $data->status,
            'url' => $url,
        ]);

    }

    /**
     * Display the specified resource.
     * @param \App\Entities\Qiming\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order, Request $request)
    {
        if ($order->status == Order::STATUS_PAIED) {
            $page = $request->get("page", 1);
            $page_size = $request->get("page_size", 100);
            $query = Name::where("xing", $order->surname);
            if ($order->gender) {
                $query = $query->where("sex", $order->gender);
            }
            $names = $query->where("active", 1)
                ->orderBy("score", "desc")
                ->offset(($page - 1) * $page_size)
                ->limit($page_size)
                ->get();
            $order->names = $names;
        }

        return $order;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Entities\Qiming\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $order->status = $request->json("status");
        $order->save();

        return response("", 204);
    }


    /**主动查询订单状态
     * @param Order $order
     * @return number
     */
    public function status(Order $order)
    {
        $status = $this->service->queryByOutTradeNumber($order);
        return response()->json([
            'code' => $order->code,
            'status' => $status,
        ]);
    }

    /**用户重新支付订单
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function repay(Order $order)
    {
        $url = $this->service->repay($order);
        return response()->json([
            'code' => $order->code,
            'status' => $order->status,
            'url' => $url,
        ]);
    }
}
