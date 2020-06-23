<?php


namespace App\Http\Controllers\Crm\Admin;

use App\Services\TokenService;
use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entities\RatingLabel;
use App\Entities\Stage;
use Illuminate\Support\Facades\DB;
class SettingController extends Controller
{
    public function role(Request $request){
        if ($request->isMethod("get")) {
            //评级标签
            $rating = RatingLabel::with(['menu'])->where('pid', 0)->orderBy('id', 'asc')->get()->toArray();
            //阶段说明
            $stage = Stage::orderBy('id', 'asc')->get()->toArray();
            return $this->jsonSuccessData(['rating' => $rating, 'stage' => $stage]);
        }
        $rating = $request->post('rating');
        if(count($rating) > 0){
            $rating_data = [];
            foreach ($rating as $k => $v){
                $rating_data[] = [
                    'id' => $k,
                    'info' => $v
                ];
            }
            RatingLabel::saved($rating_data);
        }


        $stage = $request->post('stage');
        if(count($stage) > 0) {
            $stage_data = [];
            foreach ($stage as $k => $v) {
                $stage_data[] = [
                    'id' => $k,
                    'info' => $v
                ];
            }
            Stage::saved($stage_data);
        }

        return $this->jsonSuccessData();
    }

    public function LabelManagement(Request $request){
        $id = $request->input('id');
        if ($request->isMethod("get")) {
            $rating_detail = RatingLabel::where(['pid' => $id])->get()->toArray();
            return $this->jsonSuccessData(['data' => $rating_detail]);
        }
        //要修改的数据
        $rating_edit = $request->post('rating_edit');



        if($rating_edit){
            $rating = [];
            foreach ($rating_edit as $k => $v){
                $rating[] = [
                    'id' => $k,
                    'info' => $v
                ];
            }
            RatingLabel::saved($rating);
        }

        //要添加的数据
        $rating_add = $request->post('rating_add');
        if($rating_add){
            RatingLabel::insert($rating_add);
        }

        //要删除的数据
        $rating_delete = $request->post('rating_delete');
        RatingLabel::destroy(explode(',',$rating_delete));
        return $this->jsonSuccessData();
    }


}
