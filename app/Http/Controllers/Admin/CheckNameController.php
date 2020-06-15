<?php

namespace App\Http\Controllers\Admin;

use App\Entities\CheckName;
use Illuminate\Http\Request;

class CheckNameController extends Controller
{
    public function web(Request $request)
    {
        $page_size = $request->get('page_size', 10);
        $data = CheckName::paginate($page_size);
        $data->appends(['page_size' => $page_size]);

        return view('admin.check_name.index', [
            'data' => $data,
            'page_size' => $page_size,
        ]);
    }

    public function delete(int $id)
    {
        CheckName::destroy($id);

        return redirect()->back();
    }

    public function import(Request $request)
    {
        $file = $request->file('excel')->path();
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

        return redirect()->back();
    }
}
