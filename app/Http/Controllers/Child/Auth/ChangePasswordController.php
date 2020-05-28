<?php

namespace App\Http\Controllers\Child\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Child\Controller;
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
            $user->password = $data["password"];
            $user->save();

            return redirect()->back();
        }

        return view("auth.password", [
            "base" => "child.base",
        ]);
    }
}
