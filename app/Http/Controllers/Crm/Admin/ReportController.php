<?php


namespace App\Http\Controllers\Crm\Admin;


use App\Entities\Client;
use App\Entities\ClientClosing;
use App\Entities\RatingLabel;
use App\Entities\Region;
use App\Entities\Sale;
use App\Entities\ClientFollowUp;
use App\Entities\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    /**
     * @param Request $request
     * @return false|string
     */
    public function saleDate(Request $request){

        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');

        $where = [];$region = [];
        if($region_id){
            $region = Service::where('region_id',$region_id)->get(['id'])->toArray();
        }
        if($service_id){
            $where['service_id'] = $service_id;
        }
        if($sale_id){
            $where['sale_id'] = $sale_id;
        }

        $dt = Carbon::now();

        $data_raw = DB::raw('DATE_FORMAT(created_at,"%m") as date');
        $count_raw = DB::raw('COUNT(id) as count');
        $money_raw = DB::raw("SUM(closing_price) as money");
        // 今年数据统计
        $data = ClientClosing::select($money_raw,$data_raw,$count_raw)->whereYear('created_at',$dt->year)->where($where)->where(function ($query) use ($region_id,$region){
            if($region_id){
                $query->whereIn('service_id',$region);
            }
        })->groupBy("date")->get()->toArray();

        //去年数据统计
        $last_data = ClientClosing::select($money_raw,$data_raw,$count_raw)->whereYear('created_at',($dt->year)-1)->where($where)->where(function ($query) use ($region_id,$region){
            if($region_id){
                $query->whereIn('service_id',$region);
            }
        })->groupBy("date")->get()->toArray();

        $data_list = [];
        foreach($data as $item){
            $data_list[$item['date']]['money'] = $item['money'];
            $data_list[$item['date']]['count'] = $item['count'];
        }

        $last_data_list = [];
        foreach($last_data as $item){
            $last_data_list[$item['date']]['money'] = $item['money'];
        }

        $list = [];
        //计算同比增长与环比增长
        for($i=1;$i<13;$i++){

            $month = $i<10?'0'.$i:$i;

            $last_month = ($i-1)<10?'0'.($i-1):($i-1);

            if(isset($data_list[$month])){
                $list[$month]['link_ratio'] = 0;
                $list[$month]['year_on_year'] = 0;
                $list[$month]['avg'] = $data_list[$month]['money']/$data_list[$month]['count'];
                $list[$month]['money'] = $data_list[$month]['money'];
                $list[$month]['count'] = $data_list[$month]['count'];
                /**环比增长 */
                if($i != 1){
                    if(isset($data_list[$last_month]) && $data_list[$last_month]['money'] > 0){
                        @$list[$month]['link_ratio'] = ($data_list[$month]['money']-$data_list[$last_month]['money'])/$data_list[$last_month]['money']*100;
                    }
                }else{
                    if(isset($last_data_list["12"]) && $last_data_list["12"]['money'] > 0){
                        @$list[$month]['link_ratio'] = ($data_list[$month]['money']-$last_data_list["12"]['money'])/$last_data_list["12"]['money']*100;
                    }
                }
                /**同比增长 */
                if(isset($last_data_list[$month]) && $last_data_list[$month]['money'] > 0){
                    @$list[$month]['year_on_year'] = ($data_list[$month]['money']-$last_data_list[$month]['money'])/$last_data_list[$month]['money']*100;
                }
            }else{
                $list[$month]['link_ratio'] = 0;
                $list[$month]['year_on_year'] = 0;
                $list[$month]['avg'] = 0;
                $list[$month]['money'] = 0;
                $list[$month]['count'] = 0;

            }
        }


        return $this->jsonSuccessData([
            'data' => $list
        ]);
    }

    /**客户新增
     * @param Request $request
     */
    public function newClient(Request $request){
        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subWeekday()->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();

        $where = [];$region = [];
        if($region_id){
            $region = Service::where('region_id',$region_id)->get(['id'])->toArray();
        }
        if($service_id){
            $where['service_id'] = $service_id;
        }
        if($sale_id){
            $where['sale_id'] = $sale_id;
        }

        //新增客户数量
        $date_raw = DB::raw('DATE(created_at) as date');
        $origin_data = Client::select($date_raw)
            ->where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->groupBy("date")->get();
        $dict_data = [];
        foreach ($origin_data as $item) {
            $dict_data[$item["date"]] = $item["count"];
        }

        //客户新增报表
        $list = Service::withCount('client as client_num')->withCount(['client as new_client' => function ($query,$range_start,$range_end) {
            $query->whereDate("created_at", ">=", $range_start)->whereDate("created_at", "<=", $range_end);
        }])->get();
    }

    /**客户跟进
     * @param $time
     *
     */
    public function followUp(Request $request)
    {
        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');

        $where = [];
        $region = [];
        if($region_id){
            $region = Service::where('region_id',$region_id)->get(['id'])->toArray();
        }
        if($service_id){
            $where['service_id'] = $service_id;
        }
        if($sale_id){
            $where['sale_id'] = $sale_id;
        }

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subWeekday()->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $date_raw = DB::raw('DATE(created_at) as date');
        $origin_data = ClientFollowUp::select($date_raw)
            ->where($where)
            ->whereIN('follow_type',[ClientFollowUp::FOLLOW_TYPE_UP,ClientFollowUp::FOLLOW_TYPE_SIGN])
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->groupBy("date")->get();
        $dict_data = [];
        foreach ($origin_data as $item) {
            if($item['follow_type'] == ClientFollowUp::FOLLOW_TYPE_UP){
                $dict_data['follow_up'][$item["date"]] = $item["count"];
            }
            if($item['follow_type'] == ClientFollowUp::FOLLOW_TYPE_SIGN){
                $dict_data['sign'][$item["date"]] = $item["count"];
            }
        }
        //客户新增报表+
        $this->relationSearch = true;
        $list = Service::withCount(['followlog as follow_num' => function ($query) use($range_start,$range_end) {
            $query->where("follow_type", ClientFollowUp::FOLLOW_TYPE_UP)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);
        }])->withCount(['followlog as sign' => function ($query) use($range_start,$range_end) {
            $query->where("follow_type", ClientFollowUp::FOLLOW_TYPE_SIGN)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->withCount('client')->get()->toArray();


    }

    /**质量占比
     * @param Request $request
     */
    public function proportion(Request $request){
        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');

        $where = [];
        $region = [];
        if($region_id){
            $region = Service::where('region_id',$region_id)->get(['id'])->toArray();
        }
        if($service_id){
            $where['service_id'] = $service_id;
        }
        if($sale_id){
            $where['sale_id'] = $sale_id;
        }

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subWeekday()->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $origin_data = ClientFollowUp::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->get();

        $new_data = [];
        foreach ($origin_data as $item) {
            $new_data[$item['rating_label_id']] += $item;
        }


    }

    /**类型成交转换
     * @param Request $request
     */
    public function tranCon(Request $request){
        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');
        $rating_label_id   = $request->get('rating_label_id') ??RatingLabel::LABLE_A;
        $where = [];
        $region = [];
        if($region_id){
            $region = Service::where('region_id',$region_id)->get(['id'])->toArray();
        }
        if($service_id){
            $where['service_id'] = $service_id;
        }
        if($sale_id){
            $where['sale_id'] = $sale_id;
        }
        if($rating_label_id){
            $where['rating_label_id'] = $rating_label_id;
        }
        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subWeekday()->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $count = Client::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->get()->toArray();
        $qiandan = 0;
        foreach ($count as $item){
            if($item['is_deal'] == 1){
                $qiandan +=1;
            }
        }

    }

    /**
     *
     */
    public function  stage(Request $request){
        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');

        $where = [];
        $region = [];
        if($region_id){
            $region = Service::where('region_id',$region_id)->get(['id'])->toArray();
        }
        if($service_id){
            $where['service_id'] = $service_id;
        }
        if($sale_id){
            $where['sale_id'] = $sale_id;
        }

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subWeekday()->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $origin_data = ClientFollowUp::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->get();

        $new_data = [];
        foreach ($origin_data as $item) {
            $new_data[$item['stage_id']] += $item;
        }
    }
}
