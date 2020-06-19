<?php


namespace App\Http\Controllers\Crm\Admin;
use App\Entities\Client;
use App\Entities\Sale;
use App\Entities\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SaleController extends Controller
{
    /**xiaoshou
     * @param Request $request
     * @return false|string
     */
    public function list(Request $request){

        $id = $request->get('id');
        $service = Service::findOrFail($id);
        $list = $this->model::withCount(['client as now_client' => function ($query) {
            $query->where("is_deal", 0);
        }])->WithCount(['client'])->where('service_id',$service['id'])->get();

        return $this->jsonSuccessData([
            'data' => $list
        ]);
    }

    /**服务商详细数据
     * @param Request $request
     * @return false|string
     * service       服务商详细信息
     * sum_money     签单金额
     * all_clinet    客户持有
     * now_clinet    正在跟进
     * clos          签单量
     */

    public function listData(Request $request){

        $id = $request->get('id');
        $service = Service::findOrFail($id);

        $list = $this->model::withCount(['client as now_client' => function ($query) {
            $query->where("is_deal", 0);
        },'client as clos' => function($query){
            $query->where("is_deal", 1);
        }])->WithCount(['client'])->where('service_id',$service['id'])->get()->toArray();

        return $this->jsonSuccessData([
            "service" => $service,
            "sum_money" => array_sum(array_column($list, 'money')),
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
            "name"          => "required",
            "account"       => "required",
            "password"      => "required",
            "scale_num"     => "required",
            "service_id"    => "required",
            "gender"        => [Rule::in([Service::MALE_CODE,Service::FEMALE_CODE,Service::GENDER_NO])],
            "phone"         => ["required","mobile"],
        ],[
            "name.required"     => "请填写姓名",
            "account.required"        => "请填写账号",
            "password.required"        => "请填写密码",
            "scale_num.required"     => "请填写分配额度",
            "service_id.required"    => "丢失服务商参数",
            "phone.required"          => "手机号不能为空",
            "phone.mobile"          => "手机号格式错误"
        ]);

        $this->model::create([
            "name"         => $data["name"],
            "account"      => $data["account"],
            "password"     => $data["password"],
            "scale_num"    => $data["scale_num"],
            "service_id"   => $data["service_id"],
            "gender"       => $data["gender"],
            "phone"        => $data["phone"]
        ]);

        return $this->jsonSuccessData();
    }

    /**编辑
     * @param Request $request
     * @return false|string
     */
    public function edit(Request $request){
        $id = $request->get('id');
        $sale = Sale::findOrFail($id);
        $data = $request->validate([
            "name"          => "required",
            "account"       => "required",
            "password"      => "required",
            "scale_num"     => "required",
            "service_id"    => "required",
            "gender"        => [Rule::in([Service::MALE_CODE,Service::FEMALE_CODE,Service::GENDER_NO])],
            "phone"         => ["required","mobile"],
            "id"            => "required"
        ],[
            "name.required"           => "请填写姓名",
            "account.required"        => "请填写账号",
            "password.required"       => "请填写密码",
            "scale_num.required"      => "请填写分配额度",
            "service_id.required"     => "丢失服务商参数",
            "phone.required"          => "手机号不能为空",
            "phone.mobile"            => "手机号格式错误",
            "id"                      => "编辑用户信息丢失"
        ]);
        $sale->name    = $data['name'];
        $sale->account = $data['account'];
        if($data['password']){
            $sale->password = $data['password'];
        }
        $sale->scale_num = $data['scale_num'];
        $sale->gender    = $data['gender'];
        $sale->phone     = $data['phone'];

        $sale->save();
        return $this->jsonSuccessData();
    }

    /**单销售数据
     * @param Request $request
     */
    public function sale(Request $request){
        $id = $request->get('id');

        $sale = $this->model::finOrFail($id);
        $data = $this->model::withCount(['client as now_client' => function ($query) {
            $query->where("is_deal", 0);
        },'client as clos' => function($query){
            $query->where("is_deal", 1);
        }])->WithCount(['client'])->where('id',$id)->get()->toArray();

        return $this->jsonSuccessData([
            "sale"       => $sale,
            "sum_money"  => array_sum(array_column($data, 'money')),
            "all_client" => array_sum(array_column($data, 'client_count')),
            "now_client" => array_sum(array_column($data, 'now_client')),
            "clos"       => array_sum(array_column($data, 'clos')),
        ]);
    }
}
