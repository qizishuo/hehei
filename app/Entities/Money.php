<?php

namespace App\Entities;

class Money extends Model
{
    public const TYPE_RECHARGE = 1;
    public const TYPE_CONSUME = 2;

    protected $fillable = [
        "child_account_id",
        "amount",
        "type",
        "comment"
    ];

    public function childAccount()
    {
        return $this->belongsTo(ChildAccount::class);
    }

    public function getAmountAttribute($value): string
    {
        return abs($value);
    }
}
