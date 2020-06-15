<?php

namespace App\Http\Controllers\NewEdit\Admin;
use App\Http\Controllers\NewEdit\Controller;
class IndexController extends Controller
{
    public function index()
    {
        return view("admin.index.index");
    }
}
