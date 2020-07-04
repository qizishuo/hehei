<?php

namespace App\Http\Controllers\NewEdit\Admin;

use App\Entities\ChildAccount;
use App\Entities\Info;
use App\Exports\InfoExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\NewEdit\Controller;
class InfoController extends Controller
{
    protected $model = Info::class;

    public function list(Request $request)
    {
        $phone = $request->get("phone_id");
        $where = $phone ? ["phone_id" => $phone] : [];
        $child_account_id = $request->get("child_account_id");
        if ($child_account_id) {
            $where["child_account_id"] = $child_account_id;
        }
        $today = Carbon::now()->toDateString();

        $query = $this->model::where($where);
        $data = $query->paginate();
        $total = $query->count();
        $daily = $query->whereDate('created_at', $today)->count();

        return $this->jsonSuccessData(
            [
                'data' => $data,
                'total' => $total,
                'daily' => $daily,
            ]
        );
    }

    public function change(Request $request)
    {
        $id = $request->input('id');
        $info = $this->model::findOrFail($id);


        if($info->child_account){
            return $this->jsonErrorData(0,'该信息无法被分配');
        }
        if ($request->isMethod('post')) {
            $child_account_id = $request->post('child_account_id');
            $info->allocateTo($child_account_id);
            return $this->jsonSuccessData();
        }


        $child_accounts = ChildAccount::all(['id', 'name']);
        return  $this->jsonSuccessData(
            [
                'data' => $info,
                'child_accounts' => $child_accounts,
            ]
        );
    }

    public function user()
    {
        $data = $this->model::with("phone")->groupBy("phone_id")->paginate();

        return  $this->jsonSuccessData(
            [
                'data' => $data,
            ]
        );

    }


    public function export(Request $request)
    {
        $data = $this->validate($request, [
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);
       header('Access-Control-Allow-Origin: http://clue.gongsihezhun.com');
        return Excel::download(
            new InfoExport($data['from'], $data['to']),
            "apply.{$data['from']}--{$data['to']}.xlsx"
        );


    }
}
