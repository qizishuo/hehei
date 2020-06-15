<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct(Request $request)
    {
        $this->middleware("auth");
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $temp_array = explode("\\", get_class($this));
            $permission = Str::snake(str_replace("Controller", "", end($temp_array)));
            if (!$user->can($permission)) {
                abort(403);
            }
            return $next($request);
        });

        parent::__construct($request);
    }
}
