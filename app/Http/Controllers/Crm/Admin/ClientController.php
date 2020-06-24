<?php


namespace App\Http\Controllers\Crm\Admin;

use App\Entities\ClientFollowUp;
use App\Entities\ClientFollowUpLog;
use App\Entities\ClientFollowUpComment;
use App\Entities\Stage;
use App\Http\Controllers\Controller;
use App\Entities\Client;
use http\Env\Request;
use Illuminate\Validation\Rule;
use App\Entities\RatingLabel;


class ClientController extends  Controller
{
    protected $model = Client::class;


    /**列表
     * @param Request $request
     * @return false|string
     */
    public function list(Request $request){

        $page_size = $request->get('page_size', 10);
        $data = $this->model::where(function($query) use($request){
            $data = $request->get();
            if($data['initials']){
                $query->where('initials',$data['initials']);
            }
            if($data['start_time']){
                $query->whereDate('create_at','>=',$data['start_time']);
            }
            if($data['end_time']){
                $query->whereDate('create_at','<',$data['end_time']);
            }
            if($data['sale_id']){
                $query->where('sale_id',$data['sale_id']);
            }
        })->paginate($page_size);
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
        $data = $request->validate([
            "company_name" => "required",
            "location"     => ["required", Rule::in(array_keys($location_data))],
            "address"      => "required",
            "contacts"     => "required",
            "phone"        => ["required","mobile"],
            "gender"       => [Rule::in([Client::MALE_CODE,Client::FEMALE_CODE,Client::GENDER_NO])],
        ],[
            "company_name.required" => "公司名称不能为空",
            "location.required"     => "请选择地址",
            "address.required"      => "请填写详细地址",
            "contacts.required"     => "请填写联系人",
            "phone.required"        => "请填写手机号",
            "phone.mobile"          => "手机号格式错误"
        ]);

        $this->model::create([
            "company_name" => $data["company_name"],
            "location"     => $data["location"],
            "address"      => $data["address"],
            "contacts"     => $data["contacts"],
            "phone"        => $data["phone"],
            "gender"       => $data["gender"],
            "industry"     => $data["industry"],
            "wechat_number"=> $data["wechat_number"],
            "initials"     => $this->getFirstCharter($data["company_name"])
        ]);

        return $this->jsonSuccessData();
    }

    /**改变为垃圾客户
     * @param Request $request
     * @return false|string
     */
    public function changeRadio(Request $request){
        $data = $request->validate([
             "ids"             => "required",
        ]);

        $e = RatingLabel::where('level',"E")->find();

        $info = $this->model::whereIn('id',$data['ids'])->update(['rating_lable_id' => $e->id]);
        $info->addRable($request->get('lable_ids'));
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
        $file = $request->file('excel')->path();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->getHighestRow();
        $true_num = 0;
        $false_num = 0;
        $log = [];
        $location_data = \cn\GB2260::getData();
        for($i = 2; $i <= $rows; $i++) {
            $company_name = $sheet->getCell('A' . $i)->getValue();
            $location     = $sheet->getCell('B' . $i)->getValue();
            $contacts     = $sheet->getCell('D' . $i)->getValue();
            $phone        = $sheet->getCell('E' . $i)->getValue();
            $industry     = $sheet->getCell('F' . $i)->getValue();
            $happen_false = 0;
            if(empty($company_name)){
                $happen_false+=1;
               $log[] = "第"+($i-1)+"行：【客户公司名称】字段不能为空";
            }
            if($this->model::where('company_name',$company_name)->find()){
                $happen_false+=1;
                $log[] = "第"+($i-1)+"行：已存在名称为【"+$company_name+"】的客户，如果继续导入将会更新这条客户的数据";
            }
            if(empty($location)){
                $happen_false+=1;
                $log[] = "第"+($i-1)+"行：【地址】字段不能为空";
            }
            if(!in_array($location,array_keys($location_data))){
                $happen_false+=1;
                $log[] = "第"+($i-1)+"行：【地址】不存在";
            }
            if(empty($contacts)){
                $happen_false+=1;
                $log[] = "第"+($i-1)+"行：【联系人】字段不能为空";
            }
            if(empty($phone)){
                $happen_false+=1;
                $log[] = "第"+($i-1)+"行：【电话号码】字段不能为空";
            }
            if(!preg_match("/^1[3456789]\d{9}$/",$phone)){
                $log[] = "第"+($i-1)+"行：【电话号码】字段格式不正确";
                $happen_false+=1;
            }
            if(empty($industry)){
                $happen_false+=1;
                $log[] = "第"+($i-1)+"行：【行业】字段不能为空";
            }
            $happen_false > 0?$true_num++:$false_num++;
        }
        return $this->jsonSuccessData([
            'true_num'  => $true_num,
            'false_num' => $false_num,
            'log'       => $log
        ]);
        return redirect()->back();
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
        $detail = $this->model::findOrFail($id);

        $log = ClientFollowUp::where('client_id' ,$id)->select();
        return $this->jsonSuccessData([
            'detail' => $detail,
            'log'    => $log
        ]);
    }

    /**客户跟进
     * @param Request $request
     * @return false|string
     */
    public function followUp(Request $request){
        $user = $request->get('user');
        $rabit =  RatingLabel::where('pid',0)->get()->toArray();
        $stage = Stage::orderBy('id', 'asc')->get()->toArray();

        $location_data = \cn\GB2260::getData();

        $data = $request->validate([
            'rating_lable_id' => ["required",Rule::in(array_column($rabit,'id'))],
            'stages_id'       => ["required",Rule::in(array_column($stage,'id'))],
            'exchang_at'      => "required",
            'contacts'        => "required",
            'location'        => ["required",Rule::in(array_keys($location_data))],
            'industry'        => "required",
            'exchange_type'   => ["required",Rule::in([ClientFollowUpLog::EXCHANGE_TYPE_PHONE,ClientFollowUpLog::EXCHANGE_TYPE_VISIT])],
            'is_before_visit' => "required",
            'exchange_situation'        => "required",
            'exchange_plan'   => "required",
        ]);

        $info = ClientFollowUp::create([
            'follow_type' => ClientFollowUp::FOLLOW_TYPE_UP,
            'client_id'   => $data['client_id'],
            'sale_id'     => $user['id'],
        ]);
        $info->addLog($data);
        $info->addRable($request->get('lable_ids'));
        return $this->jsonSuccessData();
    }

    /**客户成交
     * @param Request $request
     * @return false|string
     */
    public function deal(Request $request){
        $user = $request->get('user');
        $data = $request->validate([
            'closing_at' => "required",
            'closing_remarks' => "required",
            'closing_price' => "required",
        ]);

        $id = ClientFollowUp::insertGetId([
            'follow_type' => ClientFollowUp::FOLLOW_TYPE_SIGN,
            'client_id'   => $data['client_id'],
            'sale_id'     => $user['id'],
        ]);
        $data['follow_up_id'] = $id;
        ClientFollowUpLog::create([
            $data
        ]);
        return $this->jsonSuccessData();
    }

    public function comment(Request $request){
        $user = $request->get('user');
        $data = $request->validate([
            'id'                  => "required",
            'commentator_content' => "required",
        ]);

        $data = [
            'follow_up_log_id' => $data['id'],
            'commentator_type' => ClientFollowUpComment::TYPE_ADMIN,
            'commentator_id'   => $user->id
        ];

        return $this->jsonSuccessData();
    }

}
