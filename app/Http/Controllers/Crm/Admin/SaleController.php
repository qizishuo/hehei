<?php


namespace App\Http\Controllers\Crm\Admin;
use App\Entities\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**xiaoshou
     * @param Request $request
     * @return false|string
     */
    public function sale(Request $request){

        $id = $request->get('id');
        $service = Service::findOrFail($id);
        $list = $this->model::withCount(['client as now_client' => function ($query) {
            $query->where("is_deal", 0);
        }])->WithCount(['client'])->where('service_id',$service['id'])->get();

        return $this->jsonSuccessData([
            'data' => $list
        ]);
    }
    public function saleData(Request $request){

        $id = $request->get('id');
        $service = Service::findOrFail($id);
        $list = $this->model::withCount(['client as now_client' => function ($query) {
            $query->where("is_deal", 0);
        },'client as clos' => function($query){
            $query->where("is_deal", 1);
        }])->WithCount(['client'])->where('service_id',$service['id'])->get()->toArray();

        return $this->jsonSuccessData([
            "sum" => array_sum(array_column($list, 'money')),
            "all_client" => array_sum(array_column($list, 'client_count')),
            "now_client" => array_sum(array_column($list, 'now_client')),
            "clos"       => array_sum(array_column($list, 'clos')),
        ]);

    }

    /**创建
     * @param Request $request
     * @return false|string
     */
    public function create(Request $request){
        $data = $request->validate([
            "name"     => "required",
            "account"     => "required",
            "password"     => "required",
            "scale_num"     => "required",
            "service_id"    => "required"
        ],[
            "name.required"     => "请填写姓名",
            "account.required"        => "请填写账号",
            "password.required"        => "请填写密码",
            "scale_num.required"     => "请填写分配额度",
            "service_id.required"    => "丢失服务商参数"
        ]);

        $this->model::create([
            "name"         => $data["name"],
            "account"      => $data["account"],
            "password"     => $data["password"],
            "scale_num"    => $data["scale_num"],
        ]);

        return $this->jsonSuccessData();
    }
}
