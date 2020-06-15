<?php

namespace App\Entities\Qiming;

use App\Entities\Model as BaseModel;

class Model extends BaseModel
{
    protected $connection = 'qiming';

    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
}
