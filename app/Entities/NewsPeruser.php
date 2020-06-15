<?php


namespace App\Entities;


class NewsPeruser extends Model
{
    protected $fillable = ['id','peruser_type','peruser_id','news_type','news_id'];
}
