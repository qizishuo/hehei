<?php

namespace App\Http\Controllers\Admin;

use App\Entities\ChildAccount;
use App\Entities\Info;
use App\Exports\InfoExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class InfoController extends Controller
{
    protected $model = Info::class;

    public function list(Request $request)
    {
        $phone = $request->get("phone");
        $where = $phone ? ["phone" => $phone] : [];
        $child_account_id = $request->get("child_account_id");
        if ($child_account_id) {
            $where["child_account_id"] = $child_account_id;
        }
        $today = Carbon::now()->toDateString();

        $query = $this->model::where($where);
        $data = $query->paginate();
        $total = $query->count();
        $daily = $query->whereDate('created_at', $today)->count();

        return view('admin.info.list', [
            'data' => $data,
            'total' => $total,
            'daily' => $daily,
        ]);
    }

    public function change(Request $request, int $id)
    {
        if ($request->isMethod('post')) {
            $child_account_id = $request->post('child_account_id');
            $info = $this->model::findOrFail($id);
            $info->allocateTo($child_account_id);

            return redirect()->route('info.list');
        }

        $data = $this->model::findOrFail($id);
        $child_accounts = ChildAccount::all(['id', 'name']);

        return view('admin.info.change', [
            'data' => $data,
            'child_accounts' => $child_accounts,
        ]);
    }

    public function user()
    {
        $data = $this->model::with("phone")->groupBy("phone_id")->paginate();

        return view('admin.info.user', [
            'data' => $data,
        ]);
    }

    public function exportView()
    {
        return view('admin.info.export');
    }

    public function export(Request $request)
    {
       
        $from = $request->post("from");
        $to = $request->post("to");

        return Excel::download(
            new InfoExport($from, $to),
            "info.{$from}--{$to}.xlsx"
        );
    }
}
