<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string phone
 * @property int child_account_id
 * @property ChildAccount childAccount
 */
class Phone extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'phone',
    ];

    protected $with = ["childAccount"];

    public function childAccount()
    {
        return $this->belongsTo(ChildAccount::class);
    }
}
