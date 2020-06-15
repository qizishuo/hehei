<?php

namespace App\Http\Controllers\NewEdit\Child;
use Illuminate\Http\Request;
use App\Http\Controllers\NewEdit\Controller;
class IndexController extends Controller
{
    public function index(Request $request)
    {
        return $this->jsonSuccessData([
            'url' => route("child.wechat.bind", ["id" => $request->get('user')->id])
        ]);
    }
}
