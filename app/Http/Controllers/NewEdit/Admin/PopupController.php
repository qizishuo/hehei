<?php

namespace App\Http\Controllers\NewEdit\Admin;

use App\Entities\Popup;
use Illuminate\Http\Request;
use App\Http\Controllers\NewEdit\Controller;
class PopupController extends Controller
{
    protected $model = Popup::class;
    public function list(Request $request)
    {
        $page_size = $request->get('page_size', 10);
        $data = Popup::paginate($page_size);
        $data->appends(['page_size' => $page_size]);
        return $this->jsonSuccessData([
            'data' => $data,
            'page_size' => $page_size,
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
            return $this->jsonErrorData(0,'请上传文件');
        }
        $file = $excel->path();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows  = $sheet->getHighestRow();
        for($i = 2; $i <= $rows; $i++) {
            $phone = $sheet->getCell('A' . $i)->getValue();
            $company_name = $sheet->getCell('B' . $i)->getValue();
            $name = $sheet->getCell('C' . $i)->getValue();

            Popup::firstOrCreate(
                ['phone' => $phone],
                ['company_name' => trim($company_name), 'name' => trim($name)]
            );
        }

        return $this->jsonSuccessData();
    }
}
