<?php

namespace App\Http\Controllers\Qiming;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }
}
