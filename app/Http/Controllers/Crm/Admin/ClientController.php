<?php
namespace App\Http\Controllers\Crm\Admin;

use App\Entities\ChildAccount;
use App\Entities\ClientClosing;
use App\Entities\ClientFollowUp;
use App\Entities\ClientFollowUpLog;
use App\Entities\ClientFollowUpComment;
use App\Entities\Stage;
use App\Entities\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Entities\RatingLabel;


class ClientController extends  Controller
{
    protected $model = Client::class;


    /**公海列表
     * @param Request $request
     * @return false|string
     */
    public function seaList(Request $request){
        $page_size = $request->get('page_size', 10);

        $initials = $request->get('initials','');
        $start_time = $request->get('start_time','');
        $end_time = $request->get('end_time','');
        $sale_id = $request->get('sale_id','');

        $query =  $this->model::with(['sale','service'])->where('rating_label_id','!=',$this->rating_label['4']['id'])->where('sale_id',0);

        if($initials){
            $query->where('initials',$initials);
        }
        if($start_time){
            $query->whereDate('create_at','>=',$start_time);
        }
        if($end_time){
            $query->whereDate('create_at','<',$end_time);
        }
        if($sale_id){
            $query->where('sale_id',$sale_id);
        }

        $data = $query->paginate($page_size);
        $data->appends(['page_size' => $page_size]);


        return $this->jsonSuccessData([
            'data' => $data
        ]);
    }

    public function privateList(Request $request){
        $page_size = $request->get('page_size', 10);

        $initials = $request->get('initials','');
        $start_time = $request->get('start_time','');
        $end_time = $request->get('end_time','');
        $location = $request->get('location','');
        $service_id = $request->get('service_id','');
        $sale_id = $request->get('sale_id','');

        $query =  $this->model::with(['sale','service'])->where('rating_label_id','!=',$this->rating_label['4']['id'])->where('sale_id','>',0);

        if($initials){
            $query->where('initials',$initials);
        }
        if($location){
            $query->where('location',$location);
        }
        if($service_id){
            $query->where('service',$service_id);
        }
        if($sale_id){
            $query->where('sale_id',$sale_id);
        }
        if($start_time){
            $query->whereDate('create_at','>=',$start_time);
        }
        if($end_time){
            $query->whereDate('create_at','<',$end_time);
        }


        $data = $query->OrderBy('created_at','desc')->paginate($page_size);
        $data->appends(['page_size' => $page_size]);


        return $this->jsonSuccessData([
            'data' => $data
        ]);
    }

    /**导入
     * @param Request $request
     * @return false|string
     */
    public function create(Request $request){
        $location_data = \cn\GB2260::getData();
        $admin = $request->get('user');
        $request->validate([
            "company_name" => "required",
            "province"     => ["required", Rule::in(array_keys($location_data))],
            "location"     => ["required", Rule::in(array_keys($location_data))],
            "address"      => "required",
            "contacts"     => "required",
            "phone"        => ["required",
                                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/',
                              ],
            "gender"       => [Rule::in([Client::MALE_CODE,Client::FEMALE_CODE,Client::GENDER_NO])],
        ],[
            "company_name.required" => "公司名称不能为空",
            "province.required"     => "请选择省份",
            "location.required"     => "请选择地址",
            "address.required"      => "请填写详细地址",
            "contacts.required"     => "请填写联系人",
            "phone.required"        => "请填写手机号",

        ]);
        $data = $request->all();

        $data =
            [
                "company_name" => $data["company_name"],
                "province"     => $data["province"],
                "location"     => $data["location"],
                "address"      => $data["address"],
                "contacts"     => $data["contacts"],
                "phone"        => $data["phone"],
                "gender"       => $data["gender"],
                "industry"     => $data["industry"],
                "wechat_number"=> $data["wechat_number"],
                "initials"     => $this->getFirstCharter($data["company_name"]),
                "created_by_type" => Client::TYPE_ADMIN,
                "created_by"   => $admin->id,
                "identifier"   => "C".date('YmdHis')
            ];

        $this->model::updateOrCreate(['company_name' => $data['company_name']],$data);

        return $this->jsonSuccessData();
    }

    /**改变为垃圾客户
     * @param Request $request
     * @return false|string
     */
    public function changeRadio(Request $request){
        $user = $request->get('user');
        $data = $request->validate([
             "ids"             => "required",
        ]);

        $e = RatingLabel::where('level',"E")->first();

       $this->model::whereIn('id',$data['ids'])->update(['rating_label_id' => $e->id]);
       $res = [
           'follow_type' => ClientFollowUp::FOLLOW_TYPE_E,
           'admin_id'     => $user->id,
       ];

        foreach ($data['ids'] as $item){
            $res['client_id'] = $item;
            $info = ClientFollowUp::create($res);
            $info->addRabel($request->get('label_ids'));
        }
        return $this->jsonSuccessData();
    }

    /** 导入数据预览
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function import(Request $request)
    {
        $file = $request->file('excel');
        if(!$file){
            return  $this->jsonErrorData('0','请上传文件');
        }
        $file = $file->path();
        $type = $request->post('type');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->getHighestRow();
        $true_num = 0;  //验证通过条数
        $false_num = 0; //验证错误条数
        $num = 0;
        $log = [];      //记录
        $location_data = \cn\GB2260::getData();
        for($i = 3; $i <= $rows; $i++) {
            $company_name = $sheet->getCell('A' . $i)->getValue();
            $province     = $sheet->getCell('B' . $i)->getValue();
            $location     = $sheet->getCell('C' . $i)->getValue();
            $address      = $sheet->getCell('D' . $i)->getValue();
            $contacts     = $sheet->getCell('E' . $i)->getValue();
            $phone        = $sheet->getCell('F' . $i)->getValue();
            $gender       = $sheet->getCell('G' . $i)->getValue();
            $industry     = $sheet->getCell('H' . $i)->getValue();
            $wechat_number = $sheet->getCell('I' . $i)->getValue();
            if($type == 1) {
                $happen_false = 0;
                if (empty($company_name)) {
                    $happen_false += 1;
                    $log[] = "第{$i}行：【客户公司名称】字段不能为空";
                }
                if ($this->model::where('company_name', $company_name)->first()) {
                    $happen_false += 1;
                    $num+=1;
                    $log[] = "第{$i}行：已存在名称为【{$company_name}】的客户，如果继续导入将会更新这条客户的数据";
                }
                if (empty($province)) {
                    $happen_false += 1;
                    $log[] = "第{$i}行：【省份】字段不能为空";
                }
                if (empty($location)) {
                    $happen_false += 1;
                    $log[] = "第{$i}行：【地址】字段不能为空";
                }
                if(empty($address)){
                    $happen_false += 1;
                    $log[] = "第{$i}行：【地址】字段不能为空";
                }
                if (empty($contacts)) {
                    $happen_false += 1;
                    $log[] = "第{$i}行：【联系人】字段不能为空";
                }
                if (!is_phone($phone)) {
                    $log[] = "第{$i}行：【电话号码】字段格式不正确";
                    $happen_false += 1;
                }else{
                    if ($this->model::where('phone', $phone)->first()) {
                        $happen_false += 1;
                        $log[] = "第{$i}行：已存在电话号码为【{$phone}】的客户";
                    }
                }
                if (empty($industry)) {
                    $happen_false += 1;
                    $log[] = "第{$i}行：【行业】字段不能为空";
                }
                $happen_false >= 1 ? $false_num++ : $true_num++;
            }else{
                $data[] = [
                    'company_name' => $company_name,
                    'province' => $province,
                    'location' => $location,
                    'address'  => $address,
                    'contacts' => $contacts,
                    'phone' => $phone,
                    'gender' => $gender,
                    'industry' => $industry,
                    'wechat_number' => $wechat_number
                ];
            }

        }
        if($type == 1){
            $data = [
                'true_num'  => $true_num,
                'false_num' => $false_num,
                'num'       => $num,
                'log'       => $log
            ];
        }

        return $this->jsonSuccessData([
            'data' => $data
        ]);

    }
    public function importData(Request $request){
        $admin = $request->get('user');
        $file = $request->file('excel')->path();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->getHighestRow();
        $true_num = 0;
        $false_num = 0;
        $log = [];
        $location_data = \cn\GB2260::getData();
        for($i = 3; $i <= $rows; $i++) {
            $company_name = $sheet->getCell('A' . $i)->getValue();
            $province     = $sheet->getCell('B' . $i)->getValue();
            $location     = $sheet->getCell('C' . $i)->getValue();
            $address      = $sheet->getCell('D' . $i)->getValue();
            $contacts     = $sheet->getCell('E' . $i)->getValue();
            $phone        = $sheet->getCell('F' . $i)->getValue();
            $gender       = $sheet->getCell('G' . $i)->getValue();
            $industry     = $sheet->getCell('H' . $i)->getValue();
            $wechat_number = $sheet->getCell('I' . $i)->getValue();

            $data =  [
                'company_name' => $company_name,
                'province' => searchArr($province),
                'location' => searchArr($location),
                'address'  => $address,
                'contacts' => $contacts,
                'phone' => $phone,
                'gender' => $gender == '女' ? 2 : 1,
                'industry' => $industry,
                'wechat_number' => $wechat_number,
                "created_by_type" => Client::TYPE_ADMIN,
                "created_by"   => $admin->id,
                "identifier"   => "I".date('YmdHis')
            ];
            Client::updateOrCreate(['company_name' => $company_name],$data);
        }


        return $this->jsonSuccessData([

        ]);
    }

    /**
     *
     *获取中文字符拼音首字母
     *
     * @param $str 中文字符
     *
     * @return null|string
     *
     */
    function getFirstCharter($str)
    {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) return 'A';
        if ($asc >= -20283 && $asc <= -19776) return 'B';
        if ($asc >= -19775 && $asc <= -19219) return 'C';
        if ($asc >= -19218 && $asc <= -18711) return 'D';
        if ($asc >= -18710 && $asc <= -18527) return 'E';
        if ($asc >= -18526 && $asc <= -18240) return 'F';
        if ($asc >= -18239 && $asc <= -17923) return 'G';
        if ($asc >= -17922 && $asc <= -17418) return 'H';
        if ($asc >= -17417 && $asc <= -16475) return 'J';
        if ($asc >= -16474 && $asc <= -16213) return 'K';
        if ($asc >= -16212 && $asc <= -15641) return 'L';
        if ($asc >= -15640 && $asc <= -15166) return 'M';
        if ($asc >= -15165 && $asc <= -14923) return 'N';
        if ($asc >= -14922 && $asc <= -14915) return 'O';
        if ($asc >= -14914 && $asc <= -14631) return 'P';
        if ($asc >= -14630 && $asc <= -14150) return 'Q';
        if ($asc >= -14149 && $asc <= -14091) return 'R';
        if ($asc >= -14090 && $asc <= -13319) return 'S';
        if ($asc >= -13318 && $asc <= -12839) return 'T';
        if ($asc >= -12838 && $asc <= -12557) return 'W';
        if ($asc >= -12556 && $asc <= -11848) return 'X';
        if ($asc >= -11847 && $asc <= -11056) return 'Y';
        if ($asc >= -11055 && $asc <= -10247) return 'Z';
        return null;
    }

    /** 客户详情
     * @param Request $request
     * @return false|string
     */
    public function detail(Request $request){
        $id = $request->get('id');
        $detail = $this->model::with('service','sale','rating','stage','lastService')->findOrFail($id);
        $date_raw = DB::raw('*,DATE(created_at) as date');
        $log = ClientFollowUp::select($date_raw)->with(['log','money','comment'])->where('client_id' ,$id)->orderBy('created_at','desc')->get();
        $data = [];
        foreach ($log as $k =>&$item) {
            if($item['follow_type'] == 1){
                $item['pinci'] = count($log)-$k;
            }
            $data[$item["date"]][] = $item;
        }

        return $this->jsonSuccessData([
            'detail' => $detail,
            'log'    => $data
        ]);
    }

    /**客户跟进
     * @param Request $request
     * @return false|string
     */
    public function followUp(Request $request){
        $admin = $request->get('user');
        $rabit =  RatingLabel::where('pid',0)->get()->toArray();
        $stage = Stage::orderBy('id', 'asc')->get()->toArray();

        $location_data = \cn\GB2260::getData();

        $data = $request->validate([
            'rating_label_id' => ["required",Rule::in(array_column($rabit,'id'))],
            'stages_id'       => ["required",Rule::in(array_column($stage,'id'))],
            'exchang_at'      => "required",
            'exchange_type'   => ["required",Rule::in([ClientFollowUpLog::EXCHANGE_TYPE_PHONE,ClientFollowUpLog::EXCHANGE_TYPE_VISIT])],
            'is_before_visit' => "required",
            'exchange_situation'        => "required",
            'exchange_plan'   => "required",
            'client_id'       => "required"
        ]);
        $user = Client::findOrFail($data['client_id']);
        if($user->is_deal == 1){
            return $this->jsonErrorData(0,'该用户已成交');
        }
        //添加跟进信息
        $info = ClientFollowUp::create([
            'follow_type' => ClientFollowUp::FOLLOW_TYPE_UP,
            'client_id'   => $data['client_id'],
            'sale_id'     => $user->id,
            'service_id'  => $user->service_id,
            'admin_id'    => $admin->id
        ]);
        unset($data['client_id']);
        //添加用户操作记录
        $info->addLog($data);
        //添加子标签

        $info->addRabel($request->get('label_ids'));

        //修改用户标签
        $user->last_rating_label_id = $user->rating_label_id;
        $user->rating_label_id = $data['rating_label_id'];
        $user->save();
        return $this->jsonSuccessData();
    }

    /**客户成交
     * @param Request $request
     * @return false|string
     */
    public function deal(Request $request){
        $admin = $request->get('user');
        $data = $request->validate([
            'closing_at' => "required",
            'closing_remarks' => "required",
            'closing_price' => "required",
            'client_id'     => "required"
        ]);
        $user = Client::findOrFail($data['client_id']);
        if($user->is_deal == 1){
            return $this->jsonErrorData(0,'该用户已成交');
        }
        $info  = ClientFollowUp::create([
            'follow_type' => ClientFollowUp::FOLLOW_TYPE_SIGN,
            'client_id'   => $data['client_id'],
            'sale_id'     => $user->sale_id,
            'service_id'  => $user->service_id,
            'admin_id'    => $admin->id
        ]);

        $info->addClosing($data);
        $user->is_deal = 1;
        $user->save();
        return $this->jsonSuccessData();
    }

    public function comment(Request $request){
        $user = $request->get('user');
        $data = $request->validate([
            'id'                  => "required",
            'commentator_content' => "required",
        ]);

        $data = [
            'follow_up_id' => $data['id'],
            'commentator_type' => ClientFollowUpComment::TYPE_ADMIN,
            'commentator_id'   => $user->id,
            'commentator_content' => $data['commentator_content']
        ];
        ClientFollowUpComment::create($data);
        return $this->jsonSuccessData();
    }

}
