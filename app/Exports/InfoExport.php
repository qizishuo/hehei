<?php

namespace App\Exports;

use App\Entities\Info;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InfoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $from;
    protected $to;

    public function __construct(string $from, string $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return Info::whereDate("created_at", ">=", $this->from)
            ->whereDate("created_at", "<=", $this->to)
            ->orderBy("id")->get();
    }

    public function headings(): array
    {
        return [
            "ID", "类型", "来源", "公司名称或老板姓名",
            "电话", "所属子账号", "客户IP", "提交时间",
        ];
    }

    public function map($item): array
    {
        return [
            $item->id,
            $item->getType(),
            $item->source,
            $item->type == \App\Entities\Info::TYPE_CHECK ? $item->company_name : $item->boss_name,
            $item->phone_number,
            $item->child_account ? $item->child_account->name : '未指定',
            $item->client_ip,
            $item->created_at,
        ];
    }
}
