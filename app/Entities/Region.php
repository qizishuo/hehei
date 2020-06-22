<?php


namespace App\Entities;


class Region extends Model
{
    protected $fillable = ['province', 'location','scale_num'];

    public function getLocationAttribute()
    {
        return location_name($this->location);
    }

    public function getProvinceAttribute()
    {
        return location_name($this->province);
    }
}
