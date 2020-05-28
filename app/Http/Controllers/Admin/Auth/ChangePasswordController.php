<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->isMethod("post")) {
            $data = $request->validate([
                "password" => "confirmed",
            ]);

            $user = Auth::user();
            $user->password = Hash::make($data["password"]);
            $user->save();

            return redirect()->back();
        }

        return view("auth.password", [
            "base" => "admin.base",
        ]);
    }
}
