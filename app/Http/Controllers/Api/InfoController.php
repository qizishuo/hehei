<?php

namespace App\Http\Controllers\Api;

use App\Entities\Info;
use App\Entities\Phone;
use App\Services\Redis;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    protected $model = Info::class;

    public function store(Request $request)
    {
        $phone = $request->json('phone');
        $captcha = $request->json("captcha");
        if (!is_null($captcha)) {
            $captcha_redis = new Redis("captcha", $phone);
            if ($captcha && $captcha === $captcha_redis->get()) {
                $captcha_redis->del();
            } else {
                return response("验证码错误，请重新输入！", 403);
            }
        }

        $location = $request->json('location');
        if (empty($location)) {
            return response("地区信息不能为空", 400);
        }

        $type = $request->json('type');
        $phone_model = Phone::withTrashed()->firstOrCreate(["phone" => $phone]);
        $data = [
            'type' => $type,
            "phone_id" => $phone_model->id,
            'source' => $request->json('source') ?? "",
            'location' => $location,
            "client_ip" => $request->server->get("HTTP_X_REAL_IP"),
        ];

        switch ($type) {
            case Info::TYPE_CHECK:
                $data['company_name'] = $request->json('company_name');
                break;
            case Info::TYPE_NAMED:
                $data['industry'] = $request->json('industry');
                $data['boss_name'] = $request->json('boss_name');
                $data['boss_birth'] = $request->json('boss_birth');
                break;
            default:
                return response('参数错误', 400);
        }

        $info = $this->model::Create($data);
        $info->autoAllocate();

        return $info->id;
    }
}
