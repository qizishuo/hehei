<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int status
 */
class Apply extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_PASS = 2;
    public const STATUS_REFUSE = 3;

    protected $attributes = [
        "status" => self::STATUS_PENDING,
    ];

    protected $fillable = [
        "info_id",
        "child_account_id",
        "apply_reason",
    ];

    public function getStatus(): string
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return '<span class="text-blue">申诉中</span>';
            case self::STATUS_PASS:
                return '<span class="text-green">通过</span>';
            case self::STATUS_REFUSE:
                return '<span class="text-red">未通过</span>';
            default:
                return '<span class="text-red">未知</span>';
        }
    }

    public function info()
    {
        return $this->belongsTo(Info::class)->withTrashed();
    }

    public function childAccount()
    {
        return $this->belongsTo(ChildAccount::class)->withTrashed();
    }
}
