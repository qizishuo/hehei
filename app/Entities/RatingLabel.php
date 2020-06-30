<?php

namespace App\Entities;
use Illuminate\Support\Facades\DB;
class RatingLabel extends Model
{

    protected $fillable = ['id','name', 'info','pid'];
    protected $appends = ['checked'];
    public function menu(){
        return $this->hasMany(RatingLabel::class,'pid','id')->orderBy('id','asc');
    }


    public function getCheckedAttribute()//此处的Pic与追加字段'pic' 相对应
    {

        return false;
    }
}
