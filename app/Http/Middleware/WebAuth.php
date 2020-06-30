<?php
namespace App\Http\Middleware;
use App\Entities\Service;
use Closure;
use App\Http\Response\ResponseJson;
use App\Services\TokenService;
use App\User;
use App\Entities\ChildAccount;

class WebAuth
{
    use ResponseJson;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        switch ($request->route()->getAction()['namespace']){
            case 'App\Http\Controllers\NewEdit\Admin':
                $model = new User();
                $redis_type = 'admin';
                break;
            case 'App\Http\Controllers\NewEdit\Child':
                $model = new ChildAccount();
                $redis_type = 'child';
                break;
            case 'App\Http\Controllers\Crm':
                $model = new User();
                $redis_type = 'admin';
                break;
            case 'App\Http\Controllers\Crm\Service':
                $model = new Service();
                $redis_type = 'service';
                break;
            default:

                abort(401,'拒绝访问');
                break;
        }
        $auth = $request->header('Authorization');
        if(empty($auth)){
            abort(401, "缺失必要参数：Authorization");
        }
        $token = $this->parseAuth($auth,'Bearer');

        $token_service = new TokenService($redis_type);
        $user_id = $token_service->get($token);
        if(!$user_id){
            $token_service->delete($token);
            abort(401,'请重新登陆');
        }
        // 如果redis即将过期给他重新生成

        $user = $model::findOrFail($user_id);
        $request->attributes->add(['user' => $user]);//添加参数
        return $next($request);
    }


}
