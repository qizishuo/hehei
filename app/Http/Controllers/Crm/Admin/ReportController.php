<?php


namespace App\Http\Controllers\Crm\Admin;


use App\Entities\Client;
use App\Entities\ClientClosing;
use App\Entities\RatingLabel;
use App\Entities\Region;
use App\Entities\Sale;
use App\Entities\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class ReportController extends Controller
{
    /**->where(function ($query) use ($region_id,$region,$servoce_id,$sale_id){
    if($region_id){
    $query->whereIN('service_id',$region);
    }
    if($servoce_id){
    $query->where('service_id',$servoce_id);
    }
    if($sale_id){
    $query->where('sale_id',$sale_id);
    }
    })
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


        for ($i=1;$i<=12;$i++){
            $result = ClientClosing::whereMonth('created_at',$i)->where($where)->where(function ($query) use ($region_id,$region){
                if($region_id){
                    $query->whereIn('service_id',$region);
                }
            })->get()->toArray();

            $data[$i]['money'] = @array_sum(array_column($result,'closing_price'));
            $data[$i]['sum']   = count($result);
            if($i=1){
                $data[$i]['h']=0;
                $data[$i]['t']=0;
            }else{
                $data[$i]['h']= ($data[$i]['money']-$data[$i-1]['money'])/$data[$i-1]['money']*100;
                $data[$i]['t']=0;
            }

        }
        return $this->jsonSuccessData([
            'data' => $data
        ]);
    }

    /**客户新增
     * @param Request $request
     */
    public function newClient(Request $request){
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

        $range_start = $request->get("range_start") ? $request->get("range_start") :Carbon::today()->subWeekday()->toDateString();
        $range_end = $request->get("range_end") ? $request->get("range_end") : Carbon::today()->toDateString();
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
