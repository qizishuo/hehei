<?php


namespace App\Http\Controllers\Crm\Admin;


use App\Entities\Client;
use App\Entities\ClientClosing;
use App\Entities\ClientFollowUpLog;
use App\Entities\RatingLabel;
use App\Entities\Region;
use App\Entities\Sale;
use App\Entities\ClientFollowUp;
use App\Entities\Service;
use App\Entities\Stage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use function foo\func;

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

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(7)->toDateString();
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
        }])->withCount('client')->withCount(['money as money_sum' => function ($query) use($range_start,$range_end){
            $query->whereDate("client_closings.created_at", ">=", $range_start)->whereDate("client_closings.created_at", "<=", $range_end)->select(DB::raw("sum(closing_price) as moneysum"));
        }])->get()->toArray();
        dd($list);

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

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(7)->toDateString();
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



    /**投资回报率
     * @param Request $request
     */
    public function RateReturn(Request $request){
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

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(200)->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $origin_data = Service::withCount(['money as money_sum' => function ($query) use($range_start,$range_end){
                $query->whereDate("client_closings.created_at", ">=", $range_start)->whereDate("client_closings.created_at", "<=", $range_end)->select(DB::raw("sum(closing_price) as moneysum"));
            }])
            ->where($where)
            ->withCount(['sale','client as client_count'])
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->get()->toArray();

        foreach ($origin_data as $k => &$v){
            $v['cost'] = $v['client_count'] * $v['cost_price'];
            $v['cost'] ? $v['rate_return'] = ($v['money_sum']-$v['cost'])/$v['cost']*100 : 0;

        }
    }
    /**类型成交转换
     * @param Request $request
     */
    public function tranCon(Request $request){
        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');
        $rating_label_id   = $request->get('rating_label_id');
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
        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(7)->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $count = Client::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->count();
        $deal_count = Client::where($where)
            ->where('is_deal',1)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->count();

        //销售
        $list = Sale::with('service')
                ->withCount(['client as count' => function($query) use($range_start,$range_end){
                    $query->whereDate("clients.created_at", ">=", $range_start)->whereDate("clients.created_at", "<=", $range_end);
                },'client as deal_count'=> function($query) use($range_start,$range_end){
                    $query->where('is_deal',1)->whereDate("clients.created_at", ">=", $range_start)->whereDate("clients.created_at", "<=", $range_end);
                }])->get();

    }

    /**沟通频次
     * @param Request $request
     */
    public function connect(Request $request){
        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');
        $rating_lable_id   = $request->get('rating_lable_id');
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


        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(7)->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();

        $list = Sale::withCount(['followlog as follow_num' => function ($query) use($range_start,$range_end,$rating_lable_id) {
            $query->where("follow_type", ClientFollowUp::FOLLOW_TYPE_UP)->where('clients.rating_lable_id',$rating_lable_id)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);
        }])->withCount(['followlog as sign' => function ($query) use($range_start,$range_end,$rating_lable_id) {
            $query->where("follow_type", ClientFollowUp::FOLLOW_TYPE_SIGN)->where('clients.rating_lable_id',$rating_lable_id)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->withCount('client')->withCount(['money as money_sum' => function ($query) use($range_start,$range_end){
            $query->whereDate("client_closings.created_at", ">=", $range_start)->whereDate("client_closings.created_at", "<=", $range_end)->select(DB::raw("sum(closing_price) as moneysum"));
        }])->get()->toArray();

    }

    /**等级变化
     * @param Request $request
     */
    public function changeLevel(Request $request){
        $region_id  = $request->get('region_id');
        $service_id = $request->get('service_id');
        $sale_id    = $request->get('sale_id');
        $rating_lable_id = $request->get('rating_lable_id');
        $rating_lable_id = 1;
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

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(7)->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();

        $rating = RatingLabel::select('id')->where('pid', 0)->orderBy('id', 'asc')->get()->toArray();
        $where['rating_lable_id'] = $rating_lable_id;
        //待优化
        $aa = Client::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->where(function ($query) use($rating){
                $query->where('last_rating_lable_id',$rating[0]['id'])->count();
            })
            ->get()->toArray();
        $ab = Client::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->where('last_rating_lable_id',$rating[1]['id'])
            ->count();
        $ac = Client::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->where('last_rating_lable_id',$rating[2]['id'])
            ->count();
        $ad = Client::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->where('last_rating_lable_id',$rating[3]['id'])
            ->count();
        $ae = Client::where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->where('last_rating_lable_id',$rating[4]['id'])
            ->count();
dd($aa);
        $list = Sale::withCount(['client as one' => function ($query) use($range_start,$range_end,$rating,$rating_lable_id) {
            $query->where("rating_lable_id",$rating_lable_id)->where("last_rating_lable_id", $rating[0]['id'])->whereDate("clients.created_at", ">=", $range_start)->whereDate("clients.created_at", "<=", $range_end);
        }])->withCount(['client as two' => function ($query) use($range_start,$range_end,$rating,$rating_lable_id) {
            $query->where("rating_lable_id",$rating_lable_id)->where("last_rating_lable_id", $rating[1]['id'])->whereDate("clients.created_at", ">=", $range_start)->whereDate("clients.created_at", "<=", $range_end);;
        }])->withCount(['client as three' => function ($query) use($range_start,$range_end,$rating,$rating_lable_id) {
            $query->where("rating_lable_id",$rating_lable_id)->where("last_rating_lable_id", $rating[2]['id'])->whereDate("clients.created_at", ">=", $range_start)->whereDate("clients.created_at", "<=", $range_end);;
        }])->withCount(['client as four' => function ($query) use($range_start,$range_end,$rating,$rating_lable_id) {
            $query->where("rating_lable_id",$rating_lable_id)->where("last_rating_lable_id", $rating[3]['id'])->whereDate("clients.created_at", ">=", $range_start)->whereDate("clients.created_at", "<=", $range_end);;
        }])->withCount(['client as five' => function ($query) use($range_start,$range_end,$rating,$rating_lable_id) {
            $query->where("rating_lable_id",$rating_lable_id)->where("last_rating_lable_id", $rating[4]['id'])->whereDate("clients.created_at", ">=", $range_start)->whereDate("clients.created_at", "<=", $range_end);;
        }])->get()->toArray();
    }


    /**客户阶段分布
     * @param Request $request
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

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(7)->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $origin_data = ClientFollowUp::
           where($where)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end)
            ->get();

        $new_data = [];
        foreach ($origin_data as $item) {
            $new_data[$item['stage_id']] += $item;
        }
        $stage = Stage::select('id')->orderBy('id', 'asc')->get()->toArray();
        $list = Sale::withCount(['client as one' => function ($query) use($range_start,$range_end,$stage) {
            $query->where("stage_id", $stage[0]['id'])->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);
        }])->withCount(['client as two' => function ($query) use($range_start,$range_end,$stage) {
            $query->where("stage_id", $stage[1]['id'])->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->withCount(['client as three' => function ($query) use($range_start,$range_end,$stage) {
            $query->where("stage_id", $stage[2]['id'])->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->withCount(['client as four' => function ($query) use($range_start,$range_end,$stage) {
            $query->where("stage_id", $stage[3]['id'])->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->get()->toArray();
    }

    public function firstup(Request $request){
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

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(7)->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $rating = RatingLabel::select('id')->where('pid', 0)->orderBy('id', 'asc')->get()->toArray();
        $count = DB::raw("COUNT(DISTINCT(client_id)) as count");
        $origin_data = Client::with(['lable as one ' => function($query) use ($range_start,$range_end,$rating){
            $query->select('rating_lable_id', DB::raw('count(*) as total')) ->where('client_follow_ups.follow_type',ClientFollowUp::FOLLOW_TYPE_UP)->where("client_follow_ups.rating_lable_id", $rating[0]['id'])->where("client_follow_ups.is_first", 1)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end)->GroupBy('rating_lable_id');
        },'lable as two ' => function($query) use ($range_start,$range_end,$rating){
            $query->select('rating_lable_id', DB::raw('count(*) as total')) ->where('client_follow_ups.follow_type',ClientFollowUp::FOLLOW_TYPE_UP)->where("client_follow_ups.rating_lable_id", $rating[1]['id'])->where("client_follow_ups.is_first", 1)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end)->GroupBy('rating_lable_id');
        },'lable as three ' => function($query) use ($range_start,$range_end,$rating){
            $query->select('rating_lable_id', DB::raw('count(*) as total')) ->where('client_follow_ups.follow_type',ClientFollowUp::FOLLOW_TYPE_UP)->where("client_follow_ups.rating_lable_id", $rating[2]['id'])->where("client_follow_ups.is_first", 1)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end)->GroupBy('rating_lable_id');
        },'lable as four ' => function($query) use ($range_start,$range_end,$rating){
            $query->select('rating_lable_id', DB::raw('count(*) as total')) ->where('client_follow_ups.follow_type',ClientFollowUp::FOLLOW_TYPE_UP)->where("client_follow_ups.rating_lable_id", $rating[3]['id'])->where("client_follow_ups.is_first", 1)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end)->GroupBy('rating_lable_id');
        }])
        ->where($where)
        ->whereDate("created_at", ">=", $range_start)
        ->whereDate("created_at", "<=", $range_end)
        ->groupBy('rating_lable_id')
        ->get();

        $new_data = [];
        foreach ($origin_data as $item) {
            $new_data[$item['stage_id']] += $item;
        }
        $stage = Stage::select('id')->orderBy('id', 'asc')->get()->toArray();
        $list = Sale::withCount(['client as one' => function ($query) use($range_start,$range_end,$stage) {
            $query->where("stage_id", $stage[0]['id'])->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);
        }])->withCount(['client as two' => function ($query) use($range_start,$range_end,$stage) {
            $query->where("stage_id", $stage[1]['id'])->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->withCount(['client as three' => function ($query) use($range_start,$range_end,$stage) {
            $query->where("stage_id", $stage[2]['id'])->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->withCount(['client as four' => function ($query) use($range_start,$range_end,$stage) {
            $query->where("stage_id", $stage[3]['id'])->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->get()->toArray();
    }



    public function data(Request $request){
        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subDays(7)->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
        $rating_lable_id   = $request->get('rating_lable_id');
        Service::withCount('client')->withCount(['money as money_sum' => function ($query) use($range_start,$range_end){
            $query->whereDate("client_closings.created_at", ">=", $range_start)->whereDate("client_closings.created_at", "<=", $range_end)->select(DB::raw("sum(closing_price) as moneysum"));
        }])->withCount(['followlog as sign' => function ($query) use($range_start,$range_end){
            $query->where("follow_type", ClientFollowUp::FOLLOW_TYPE_SIGN)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->withCount(['followlog as follow_up' => function ($query) use($range_start,$range_end){
            $query->where("follow_type", ClientFollowUp::FOLLOW_TYPE_UP)->whereDate("client_follow_ups.created_at", ">=", $range_start)->whereDate("client_follow_ups.created_at", "<=", $range_end);;
        }])->withCount(['clinet' => function ($query) use($range_start,$range_end,$rating_lable_id) {
            $query->where("rating_lable_id", $rating_lable_id)->whereDate("clinets.created_at", ">=", $range_start)->whereDate("clinets.created_at", "<=", $range_end);;
        }]);
    }


}
