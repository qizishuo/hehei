<?php

namespace App\Http\Controllers\Child;

class IndexController extends Controller
{
    public function index()
    {
        return view("child.index.index");
    }
}
