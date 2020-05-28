<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Popup;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function list(Request $request)
    {
        $page_size = $request->get('page_size', 10);
        $data = Popup::paginate($page_size);
        $data->appends(['page_size' => $page_size]);

        return view('admin.popup.index', [
            'data' => $data,
            'page_size' => $page_size,
        ]);
    }

    public function delete(int $id)
    {
        Popup::destroy($id);

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
            $company_name = $sheet->getCell('B' . $i)->getValue();
            $name = $sheet->getCell('C' . $i)->getValue();

            Popup::firstOrCreate(
                ['phone' => $phone],
                ['company_name' => trim($company_name), 'name' => trim($name)]
            );
        }

        return redirect()->back();
    }
}
