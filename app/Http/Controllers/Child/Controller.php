<?php

namespace App\Http\Controllers\Child;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct(Request $request)
    {
        $this->middleware("auth:child");
        parent::__construct($request);
    }
}
