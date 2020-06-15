<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

/**
 * @property int id
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Model extends BaseModel
{
    /**
     * 性别
     */
    public const MALE_CODE   = 1;
    public const FEMALE_CODE = 2;
    public const GENDER_NO   = 0;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setPerPage(Request::get("page_size", 10));
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderByDesc("updated_at")->orderByDesc("id");
        });
    }
}
