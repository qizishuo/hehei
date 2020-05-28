<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $model;
    protected $page_size;

    public function __construct(Request $request)
    {
        $this->page_size = $request->get('page_size', 10);
    }

    public function delete(int $id)
    {
        $this->model::destroy($id);

        return redirect()->back();
    }
}
