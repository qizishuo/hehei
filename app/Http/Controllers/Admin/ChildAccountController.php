<?php

namespace App\Http\Controllers\Admin;

use App\Entities\ChildAccount;
use App\Entities\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ChildAccountController extends Controller
{
    protected $model = ChildAccount::class;

    public function index(Request $request)
    {
        $page_size = $request->get('page_size', 10);
        $data = $this->model::withTrashed()->withCount(['phones as phones_daily_count' => function ($query) {
            $query->whereDate("created_at", Carbon::today()->toDateString());
        }])->withCount('phones')->paginate($page_size);
        $data->appends(['page_size' => $page_size]);

        return view("admin.child_account.index", [
            'data' => $data,
            'page_size' => $page_size,
        ]);
    }

    public function create(Request $request)
    {
        if ($request->isMethod("get")) {
            return view("admin.child_account.create");
        }

        $location_data = \cn\GB2260::getData();

        $data = $request->validate([
            "name" => ["required", "unique:child_accounts"],
            "password" => ["required"],
            "type" => ["required", Rule::in([ChildAccount::TYPE_THIRD, ChildAccount::TYPE_PLATFORM])],
            "location" => ["required", Rule::in(array_keys($location_data))],
            "weight" => ["nullable", "numeric"],
        ]);

        $this->model::create([
            "name" => $data["name"],
            "password" => $data["password"],
            "type" => $data["type"],
            "location" => $data["location"],
            "weight" => $data["weight"] ?? ChildAccount::DEFAULT_WEIGHT,
        ]);

        return redirect()->route("child-account.index");
    }

    public function edit(int $id, Request $request)
    {
        $account = $this->model::withTrashed()->find($id);
        if ($request->isMethod("get")) {
            return view("admin.child_account.edit", [
                "data" => $account,
            ]);
        }

        $location_data = \cn\GB2260::getData();

        $data = $request->validate([
            "password" => ["nullable"],
            "type" => ["required", Rule::in([ChildAccount::TYPE_THIRD, ChildAccount::TYPE_PLATFORM])],
            "weight" => ["required", "numeric"],
        ]);

        $account->password = $data["password"];
        $account->type = $data["type"];
        $account->weight = $data["weight"];
        $account->save();

        return redirect()->route("child-account.index");
    }

    public function open(int $id)
    {
        $account = $this->model::withTrashed()->find($id);
        $account->restore();

        return redirect()->back();
    }

    public function money(Request $request, int $id)
    {
        if ($request->isMethod("get")) {
            $data = $this->model::findOrFail($id);
            $money_data = $data->money()->paginate();

            return view("admin.child_account.money", [
                "data" => $data,
                "money_data" => $money_data,
            ]);
        }

        $user = Auth::user();
        $recharge = $request->post("recharge");
        $price = $request->post("price");
        $comment = $request->post("comment", "{$user->name}后台充值");
        $data = $this->model::sharedLock()->findOrFail($id);

        if ($recharge) {
            $data->amount += $recharge;
            Money::create([
                "child_account_id" => $id,
                "amount" => $recharge,
                "type" => Money::TYPE_RECHARGE,
                "comment" => $comment,
            ]);
        }

        $data->price = $price;
        $data->save();

        return redirect()->back();
    }
}
