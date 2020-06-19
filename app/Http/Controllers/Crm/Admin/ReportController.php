<?php


namespace App\Http\Controllers\Crm\Admin;


use App\Entities\Client;
use App\Entities\ClientClosing;
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
    public function payRollList($time)
    {

    }


}
