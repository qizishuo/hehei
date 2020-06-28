<?php

namespace App\Http\Controllers\Crm\Admin;


use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function delete(Request $request,int $id = 0)
    {

        $id = $request->input('id');
        $this->model::destroy($id);

        return $this->jsonSuccessData();
    }

}







