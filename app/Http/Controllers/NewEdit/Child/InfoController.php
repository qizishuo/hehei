<?php

namespace App\Http\Controllers\NewEdit\Child;

use App\Entities\Apply;
use App\Entities\Info;
use App\Entities\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NewEdit\Controller;
class InfoController extends Controller
{
    protected $model = Info::class;

    public function index(Request $request)
    {
        $today = Carbon::now()->toDateString();

        $data = $this->model::whereHas("phone", function ($query) use($request) {
            $query->where("child_account_id", $request->get('user')->id);
        })->groupBy("phone_id")->paginate();
        $daily = Phone::where("child_account_id", $request->get('user')->id)->whereDate("created_at", $today)->count();

        return $this->jsonSuccessData([
            "data" => $data,
            "daily" => $daily,
            "amount" => $request->get('user')->amount,
        ]);

    }

    public function apply(Request $request)
    {
        $id = $request->input('id');
        $data = $this->model::findOrFail($id);
        if ($request->isMethod("post")) {
            $reason = $request->post("reason");
            Apply::updateOrCreate([
                "info_id" => $id,
                "child_account_id" => $request->get('user')->id,
            ], [
                "apply_reason" => $reason,
            ]);
            return $this->jsonSuccessData();
        }
        return $this->jsonSuccessData([
            "data" => $data,
        ]);

    }

    public function list(Request $request)
    {
        $data = $request->get('user')->applies()->with("info")->paginate();

        return $this->jsonSuccessData([
            "data" => $data,
        ]);
    }
}
