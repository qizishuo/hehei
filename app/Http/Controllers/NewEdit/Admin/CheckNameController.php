<?php

namespace App\Http\Controllers\NewEdit\Admin;

use App\Entities\CheckName;
use Illuminate\Http\Request;
use App\Http\Controllers\NewEdit\Controller;
class CheckNameController extends Controller
{
    protected $model = CheckName::class;
    public function web(Request $request)
    {
        $page_size = $request->get('page_size', 10);
        $data = CheckName::paginate($page_size);
        $data->appends(['page_size' => $page_size]);
        return  $this->jsonSuccessData([
            'data' => $data,
            'page_size' => $page_size
        ]);
    }

    public function delete(Request $request,int $id=0)
    {
        $id = $request->input('id');
        Popup::destroy($id);

        return $this->jsonSuccessData();
    }

    public function import(Request $request)
    {
        $excel = $request->file('excel');
        if(!$excel){
            return  $this->jsonErrorData(0,'请上穿文件');
        }
        $file = $excel->path();

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->getHighestRow();
        for($i = 2; $i <= $rows; $i++) {
            $phone = $sheet->getCell('A' . $i)->getValue();
            $name = $sheet->getCell('B' . $i)->getValue();
            $status = $sheet->getCell('C' . $i)->getValue();
            $phone = trim(trim($phone), "用户");

            CheckName::firstOrCreate(
                ['phone' => $phone],
                ['name' => trim($name), 'status' => intval($status)]
            );
        }

        return  $this->jsonSuccessData();
    }
}
