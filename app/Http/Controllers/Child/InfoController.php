<?php

namespace App\Http\Controllers\Child;

use App\Entities\Apply;
use App\Entities\Info;
use App\Entities\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    protected $model = Info::class;

    public function index()
    {
        $today = Carbon::now()->toDateString();

        $data = $this->model::whereHas("phone", function ($query) {
            $query->where("child_account_id", Auth::id());
        })->groupBy("phone_id")->paginate();
        $daily = Phone::where("child_account_id", Auth::id())->whereDate("created_at", $today)->count();

        return view("child.info.index", [
            "data" => $data,
            "daily" => $daily,
            "amount" => Auth::user()->amount,
        ]);
    }

    public function apply(Request $request, int $id)
    {
        $data = $this->model::find($id);

        if ($request->isMethod("post")) {
            $reason = $request->post("reason");

            Apply::updateOrCreate([
                "info_id" => $id,
                "child_account_id" => Auth::id(),
            ], [
                "apply_reason" => $reason,
            ]);

            return redirect()->route("child.info.index");
        }

        return view("child.info.apply", [
            "data" => $data,
        ]);
    }

    public function list()
    {
        $data = Auth::user()->applies()->with("info")->paginate();

        return view("child.info.list", [
            "data" => $data,
        ]);
    }
}
