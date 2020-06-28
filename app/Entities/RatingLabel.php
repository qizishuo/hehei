<?php

namespace App\Entities;
use Illuminate\Support\Facades\DB;
class RatingLabel extends Model
{

    protected $fillable = ['id','name', 'info','pid'];

    public function menu(){
        return $this->hasMany(RatingLabel::class,'pid','id')->orderBy('id','asc');
    }



}
