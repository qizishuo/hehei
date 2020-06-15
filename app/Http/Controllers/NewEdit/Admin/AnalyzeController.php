<?php

namespace App\Http\Controllers\NewEdit\Admin;

use App\Entities\Info;
use App\Entities\Phone;
use Illuminate\Http\Request;
use App\Entities\ChildAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NewEdit\Controller;
class AnalyzeController extends Controller
{
    public function index(Request $request)
    {
        $end = Carbon::today();
        $start = $end->copy()->subMonth();
        $date_range = array_map(function ($item) {
            return $item->toDateString();
        }, $start->range($end)->toArray());

        $date_raw = DB::raw('DATE(created_at) as date');
        $count_raw = DB::raw("COUNT(DISTINCT(phone_id)) as count");
        $phone_count_raw = DB::raw("COUNT(DISTINCT(id)) as count");
        $origin_data = Info::select($date_raw, $count_raw)
            ->whereDate("created_at", ">=", $start)
            ->whereDate("created_at", "<=", $end)
            ->groupBy("date")->get();
        $dict_data = [];
        $data = [];
        foreach ($origin_data as $item) {
            $dict_data[$item["date"]] = $item["count"];
        }
        foreach ($date_range as $date) {
            $data[$date] = $dict_data[$date] ?? 0;
        }

        $daily_date = $request->get("daily_date", Carbon::yesterday()->toDateString());
        $daily_origin_data = Info::select("location", $count_raw)
            ->whereDate("created_at", $daily_date)
            ->groupBy("location")->get();
        $daily_data = [];
        foreach ($daily_origin_data as $item) {
            $daily_data[$item->getLocation()] = $item->count;
        }

        $monthly_date = $request->get("monthly_date", Carbon::today()->format("Y-m"));
        $carbon_monthly_date = Carbon::create($monthly_date);
        $monthly_origin_data = Phone::select("child_account_id", $phone_count_raw)
            ->whereYear("created_at", $carbon_monthly_date->year)
            ->whereMonth("created_at", $carbon_monthly_date->month)
            ->groupBy("child_account_id")->get();
        $monthly_data = [];
        foreach ($monthly_origin_data as $item) {
            array_push($monthly_data, [
                "value" => $item->count,
                "name" => ChildAccount::getName($item->child_account_id),
            ]);
        }

        $range_start = $request->get("range_start")??Carbon::today()->subMonth()->toDateString();
        $range_end = $request->get("range_end")??Carbon::today()->toDateString();
        $type = $request->get("type");

        $query = Info::select("location", $count_raw)
            ->whereDate("created_at", ">=", $range_start)
            ->whereDate("created_at", "<=", $range_end);
        if (in_array($type, array_keys(Info::getTypes()))) {
            $query = $query->where("type", $type);
        }
        $range_origin_data = $query->groupBy("location")->get();
        $range_data = [];
        foreach ($range_origin_data as $item) {
            $range_data[$item->getLocation()] = $item->count;
        }

        // 按location分组来统计昨天的phone数量
        $yesterday = Carbon::yesterday()->toDateString();
        $yesterday_origin_data = Phone::select("child_account_id", $phone_count_raw)
            ->whereDate("created_at", $yesterday)
            ->groupBy("child_account_id")
            ->get();
        $yesterday_data = [];
        foreach ($yesterday_origin_data as $item) {
            $yesterday_data[ChildAccount::getName($item->child_account_id)] = $item->count;
        }
        return $this->jsonSuccessData([
            "data" => $data,
            "daily_data" => $daily_data,
            "yesterday_data" => $yesterday_data,
            "monthly_data" => $monthly_data,
            "range_data" => $range_data,
        ]);

    }
}
