<?php


namespace App\Entities;


class ClientClosing extends Model
{
    protected $fillable = [
        'follow_up_id',
        'closing_at',
        'closing_remarks',
        'closing_price',
    ];
}
