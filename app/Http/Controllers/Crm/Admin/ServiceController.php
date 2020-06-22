<?php


namespace App\Http\Controllers\Crm\Admin;

use App\Entities\Region;
use App\Entities\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected $model = Service::class;

    /**市
     * @param Request $request
     * @return false|string
     */
    public function region(Request $request){
        $list = Region::select();
        $list_origin = Region::select("province","location")
            ->groupBy("province")->get();
        $list = [];
        foreach ($list_origin as $item){
            $list[$item['province']][]['id'] = $item['id'];
            $list[$item['province']][]['location'] = $item['location'];
        }
        return $this->jsonSuccessData([
            'data' => $list
        ]);
    }

    /**服务商
     * @param Request $request
     * @return false|string
     */
    public function service(Request $request){

        $region_id = $request->get('id');
        $region = Region::findOrFail($region_id);
        $list = $this->model::withCount(['client as now_client' => function ($query) {
            $query->where("is_deal", 0);
        }])->where('region_id',$region['id'])->paginate();

        return $this->jsonSuccessData([
            'data' => $list,
        ]);
    }

    /**
     *
     */
    public function regionData(Request $request){

        $region_id = $request->get('id');
        $region = Region::findOrFail($region_id);
        $list = $this->model::withCount(['client as now_client' => function ($query) {
            $query->where("is_deal", 0);
        },'client as clos' => function($query){
            $query->where("is_deal", 1);
        }])->WithCount(['client'])->where('region_id',$region['id'])->get()->toArray();

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
        $location_data = \cn\GB2260::getData();
        $data = $request->validate([
            "company_name" => "required",
            "region_id"     => "required",
            "address"      => "required",
            "name"     => "required",
            "account"     => "required",
            "password"     => "required",
            "scale_num"     => "required",
            "cost_price"     => "required",
            "phone"        => ["required","mobile"],
            "gender"       => [Rule::in([Client::MALE_CODE,Client::FEMALE_CODE,Client::GENDER_NO])],
        ],[
            "company_name.required" => "公司名称不能为空",
            "region_id.required"     => "请选择地址",
            "address.required"      => "请填写详细地址",
            "name.required"     => "请填写姓名",
            "account.required"        => "请填写账号",
            "password.required"        => "请填写密码",
            "scale_num.required"     => "请填写分配额度",
            "cost_price.required"     => "请填写成交金额",
            "phone.mobile"          => "手机号格式错误"
        ]);

        $this->model::create([
            "company_name" => $data["company_name"],
            "region_id"    => $data["region_id"],
            "address"      => $data["address"],
            "name"         => $data["name"],
            "account"      => $data["account"],
            "password"     => $data["password"],
            "scale_num"    => $data["scale_num"],
            "cost_price"   => $data["cost_price"],
            "phone"        => $data["phone"]
        ]);

        return $this->jsonSuccessData();
    }
}
