<?php

namespace App\Entities;

class CheckName extends Model
{
    public const STATUS_ALLOW = 1;
    public const STATUS_REFUSE = 2;

    protected $fillable = ['phone', 'name', 'status'];
}
