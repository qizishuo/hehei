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


//namespace App\Http\Controllers\NewEdit;
//
//use Illuminate\Http\Request;
//use Illuminate\Foundation\Bus\DispatchesJobs;
//use Illuminate\Routing\Controller as BaseController;
//use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
//use App\Http\Response\ResponseJson;
//
//class Controller extends BaseController
//{
//    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseJson;
//
//    protected $model;
//    protected $page_size;
//
//    public function __construct(Request $request)
//    {
//        $this->page_size = $request->get('page_size', 10);
//    }
//
//    public function delete(Request $request)
//    {
//
//        $id = $request->input('id');
//        $data = $this->model::findOrFail($id);
//        $data->delete();
//
//        return $this->jsonSuccessData();
//    }
//
//
//}






