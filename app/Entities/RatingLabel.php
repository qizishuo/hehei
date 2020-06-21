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

    public function getType(): string
    {
        $types = RatingLabel::getTypes();
        return $types[$this->type] ?? "未知";
    }

    public static function getTypes(): array
    {
        return [
            RatingLabel::LABLE_A => "A类",
            RatingLabel::LABLE_B => "B类",
            RatingLabel::LABLE_C => "C类",
            RatingLabel::LABLE_D => "D类",
            RatingLabel::LABLE_E => "E类",
        ];
    }

}
