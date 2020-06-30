<?php

namespace App\Http\Controllers\Crm\Admin;
use App\Http\Controllers\Controller;
use App\Entities\News;
use Illuminate\Http\Request;
use App\Entities\NewsPeruser;

class NewsController extends Controller
{
    /** 系统消息
     * @param Request $request
     * @return false|string\
     */
    public function system(Request $request){
        $user = $request->get('user');
        $page_size = $request->get('page_size', 10);
        $title = $request->get('title');
        $start = $request->get('start');
        $end = $request->get('end');

        $data = News::with(['peruser' => function($query) use($user){
            $query->where('peruser_id',$user->id)->where('peruser_type',1)->select('news_id');
        }])->where(function($query) use($title,$start,$end){
            if($title){
                $query->where('title','Like',"%$title%");
            }
            if($start){
                $query->whereDate('create_at','>=',$start);
            }
            if($end){
                $query->whereDate('create_at','<=',$start);
            }
        })->where('type',1)->orderBy('id','desc')->paginate($page_size);
        return $this->jsonSuccessData(['data' => $data]);
    }

    /**业务消息
     * @param Request $request
     * @return false|string
     */
    public function business(Request $request){
        $user = $request->get('user');
        $page_size = $request->get('page_size', 10);

        $title = $request->get('title');
        $start = $request->get('start');
        $end = $request->get('end');

        $data = News::with(['peruser' => function($query) use($user){
            $query->where('peruser_id',$user->id)->where('peruser_type',1)->select('news_id');
        }])->where(function($query) use($title,$start,$end){
            if($title){
                $query->where('title','Like',"%$title%");
            }
            if($start){
                $query->whereDate('created_at','>=',$start);
            }
            if($end){
                $query->whereDate('created_at','<=',$end);
            }
        })->where('type',2)->orderBy('id','desc')->paginate($page_size);
        return $this->jsonSuccessData(['data' => $data]);
    }

    /**消息详情
     * @param Request $request
     * @return false|string
     */
    public function peruser(Request $request){
        $user = $request->get('user');
        $id = $request->get('id');
        $news = News::findOrFail($id);
        $data = NewsPeruser::where(['news_id' => $news->id,'peruser_type' => 1,'peruser_id'=>$user->id])->get()->toArray();
        if(empty($data)){
            NewsPeruser::create([
                'peruser_type' => 1,
                'peruser_id'   =>$user->id,
                'news_type'    =>$news->type,
                'news_id'      =>$id
            ]);
        }
        return $this->jsonSuccessData();
    }

    /**一键已读
     * @param Request $request
     */
    public function AllPeruser(Request $request){
        $user = $request->get('user');
        switch ($request->get('type')){
            case News::BUSINESS_MSG:
                $type = News::BUSINESS_MSG;
                break;
            case News::SYSTEM_MSG:
                $type = News::SYSTEM_MSG;
                break;
            default:
             return $this->jsonErrorData(0,'参数类型错误');
        }

        $data = News::with(['peruser' => function($query) use($user){
            $query->where('peruser_type',1)->where('peruser_id',$user->id)->select('news_id');
        }])->where('type',$type)->get()->toArray();
        foreach ($data as $k => $v){
            if(!$v['peruser']){
                NewsPeruser::create([
                    'peruser_type' => 1,
                    'peruser_id'   =>$user->id,
                    'news_type'    =>$v['type'],
                    'news_id'      =>$v['id']
                ]);
            }
        }
        return $this->jsonSuccessData();
    }

    public function NoReading(Request $request){
        $user = $request->get('user');
        //系统消息梳理
        $all_system_count = News::where('type',News::SYSTEM_MSG)->count();
        //业务消息总量
        $all_business_count = News::where('type',News::BUSINESS_MSG)->count();

        //系统消息已读数量
        $system_count = NewsPeruser::where(['peruser_type' => 1,'peruser_id' => $user->id,'news_type'=>News::SYSTEM_MSG])->count();
        //业务消息已读数量
        $business_count = NewsPeruser::where(['peruser_type' => 1,'peruser_id' => $user->id,'news_type'=>News::BUSINESS_MSG])->count();

        return $this->jsonSuccessData([
            'system_noreading'   => $all_system_count-$system_count,
            'business_noreading' => $all_business_count-$business_count,
            'all_noreading'      => ($all_system_count+$all_business_count)-($all_system_count-$system_count),
        ]);

    }
}
