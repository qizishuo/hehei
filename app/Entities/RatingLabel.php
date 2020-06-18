<?php

namespace App\Entities;
use Illuminate\Support\Facades\DB;
class RatingLabel extends Model
{
    protected const LABLE_A = 1;
    protected const LABLE_B = 2;
    protected const LABLE_C = 3;
    protected const LABLE_D = 4;
    protected const LABLE_E = 5;

    protected $fillable = ['name', 'info','pid'];

    public function menu(){
        return $this->hasMany(RatingLabel::class,'pid','id')->orderBy('id','asc');
    }



}
