<?php

namespace App\Http\Controllers\NewEdit\Admin;

use App\Entities\ChildAccount;
use App\Entities\Money;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\NewEdit\Controller;
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

        return  $this->jsonSuccessData([
            'data' => $data,
            'page_size' => $page_size,
        ]);

    }

    public function create(Request $request)
    {


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

        return  $this->jsonSuccessData();
    }

    public function edit(Request $request)
    {
        $id = $request->input('id');
        $account = $this->model::withTrashed()->findOrFail($id);
        if ($request->isMethod("get")) {
            return  $this->jsonSuccessData([
                "data" => $account,
            ]);
        }

        $location_data = \cn\GB2260::getData();

        $data = $request->validate([
            "password" => ["nullable"],
            "type" => ["required", Rule::in([ChildAccount::TYPE_THIRD, ChildAccount::TYPE_PLATFORM])],
            "weight" => ["required", "numeric"],
        ]);
        if($data['password']){
            $account->password = $data["password"];
        }
        $account->type = $data["type"];
        $account->weight = $data["weight"];
        $account->save();

        return  $this->jsonSuccessData();
    }

    public function open(Request $request)
    {
        $id = $request->get('id');
        $account = $this->model::withTrashed()->findOrFail($id);
        $account->restore();

        return $this->jsonSuccessData();
    }
    public function MoneyList(Request $request){
        $page_size = $request->get('page_size', 10);
        $id = $request->get('id');
        $data = Money::where('child_account_id',$id)->orderBy('id','desc')->paginate($page_size);
        return $this->jsonSuccessData([
            'list' => $data
        ]);
    }
    public function money(Request $request)
    {
        $id = $request->input('id');
        if ($request->isMethod("get")) {
            $data = $this->model::withTrashed()->findOrFail($id);

            return  $this->jsonSuccessData([
                "data" => $data,
            ]);
        }

        $user = $request->get('user');
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
        if ($price) {
            $data->price = $price;
            $data->save();
        }
        return  $this->jsonSuccessData();
    }
}
