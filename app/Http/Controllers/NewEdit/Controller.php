<?php

namespace App\Http\Controllers\NewEdit;


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
        $data = $this->model::findOrFail($id);
        $data->delete();

        return $this->jsonSuccessData();
    }

}





