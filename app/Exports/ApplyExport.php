<?php

namespace App\Exports;

use App\Entities\Apply;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ApplyExport implements FromView
{
    protected $from;
    protected $to;

    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function view(): View
    {
        $data = Apply::with(["info", "childAccount"])
            ->whereDate("created_at", ">=", $this->from)
            ->whereDate("created_at", "<=", $this->to)
            ->orderBy("id")->paginate(1000);
        return view("admin.info.apply_table", [
            "data" => $data,
        ]);
    }
}
