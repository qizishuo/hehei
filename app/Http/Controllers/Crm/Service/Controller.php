<?php

namespace App\Http\Controllers\Crm\Admin;


use App\Entities\RatingLabel;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    public $rating_label;
    public $service;
    public function __construct(Request $request)
    {
        $this->service = $request->get('user');
        $this->rating_label = RatingLabel::where('pid',0)->orderBy('id','asc')->get()->toArray();
        parent::__construct($request);
    }

    public function delete(Request $request,int $id = 0)
    {

        $id = $request->input('id');
        $this->model::destroy($id);

        return $this->jsonSuccessData();
    }

}







