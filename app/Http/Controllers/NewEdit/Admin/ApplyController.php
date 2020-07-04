<?php

namespace App\Http\Controllers\NewEdit\Admin;

use App\Entities\Info;
use App\Entities\Apply;
use App\Entities\Money;
use App\Entities\Phone;
use App\Exports\ApplyExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\NewEdit\Controller;
class ApplyController extends Controller
{
    protected $model = Apply::class;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->model::with(["info", "childAccount"])->paginate();

        return $this->jsonSuccessData(
            [
                "data" => $data,
            ]
        );

    }

    public function adopt(Request $request)
    {
        $id = $request->get('id');
        $data = $this->model::with(["info", "childAccount"])->findOrFail($id);
        $data->status = Apply::STATUS_PASS;
        $amount = $data->info->money->amount;

        if ($data->info->money_id) {
            Money::create([
                "child_account_id" => $data->child_account_id,
                "amount" => $amount,
                "type" => Money::TYPE_RECHARGE,
                "comment" => "信息" . $data->info_id . "删除返还",
            ]);
        }

        Info::where("phone_id", $data->info->phone_id)->update([
            "child_account_id" => 0,
        ]);
        Phone::where("id", $data->info->phone_id)->update([
            "child_account_id" => 0,
        ]);
        $data->childAccount->amount += $amount;
        $data->save();

        return $this->jsonSuccessData();
    }

    public function refuse(Request $request)
    {
        $id = $request->get('id');
        $reason = $request->get("reason");
        $this->model::where("id", $id)->update([
            "status" => Apply::STATUS_REFUSE,
            "refuse_reason" => $reason,
        ]);

        return $this->jsonSuccessData();
    }

    public function export(Request $request)
    {
        $data = $this->validate($request, [
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);
        header('Access-Control-Allow-Origin: http://clue.gongsihezhun.com');
        return Excel::download(
            new ApplyExport($data['from'], $data['to']),
            "apply.{$data['from']}--{$data['to']}.xlsx"
        );
    }
}
