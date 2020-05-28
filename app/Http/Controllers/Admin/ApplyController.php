<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Info;
use App\Entities\Apply;
use App\Entities\Money;
use App\Entities\Phone;
use App\Exports\ApplyExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

        return view("admin.info.apply", [
            "data" => $data,
        ]);
    }

    public function pass(int $id)
    {
        $data = $this->model::with(["info", "childAccount"])->find($id);
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
        $data->push();

        return redirect()->back();
    }

    public function refuse(Request $request, int $id)
    {
        $reason = $request->get("reason");
        $this->model::where("id", $id)->update([
            "status" => Apply::STATUS_REFUSE,
            "refuse_reason" => $reason,
        ]);

        return redirect()->back();
    }

    public function export(Request $request)
    {
        $from = $request->post("from");
        $to = $request->post("to");

        return Excel::download(
            new ApplyExport($from, $to),
            "apply.{$from}--{$to}.xlsx"
        );
    }
}
