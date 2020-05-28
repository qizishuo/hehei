<?php

namespace App\Entities\Qiming;

use Illuminate\Support\Carbon;

class Order extends Model
{
    public const GENDER_UNKNOW = 0;
    public const GENDER_BOY = 1;
    public const GENDER_GIRL = 2;

    public const TYPE_SINGLE = 1;
    public const TYPE_MULTI = 2;

    public const STATUS_UNPAY = 1;
    public const STATUS_PAIED = 2;
    public const STATUS_USER_CANCEL_PAY = 3;

    protected $fillable = ['surname', 'gender', 'birthday', "type", "status"];

    protected $hidden = ["id"];

    protected $casts = [
        'birthday' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public function fill(array $attributes)
    {
        $result = parent::fill($attributes);
        $result->setAttribute("code", substr(md5(uniqid("", true)), 0, 13));

        return $result;
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes["birthday"] = Carbon::createFromTimestamp($value);
    }
}
