<?php

namespace App\Http\Controllers\NewEdit\Child;

use App\Entities\ChildAccount;

use Illuminate\Http\Request;
use App\Http\Controllers\NewEdit\Controller;
class WechatController extends Controller
{
    public function bind(Request $request)
    {	
    	$id = $request->input('id');
        $wechat_user = session('wechat.oauth_user.default');
        $child_account = ChildAccount::find($id);
        $child_account->openid = $wechat_user->getId();
        $child_account->save();

        return view("child.wechat.bind");
    }
}
