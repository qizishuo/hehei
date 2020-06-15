<?php

namespace App\Http\Controllers\Child;

use App\Entities\ChildAccount;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    public function bind(int $id)
    {
        $wechat_user = session('wechat.oauth_user.default');
        $child_account = ChildAccount::find($id);
        $child_account->openid = $wechat_user->getId();
        $child_account->save();

        return view("child.wechat.bind");
    }
}
