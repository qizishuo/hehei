<?php


namespace App\Entities;


use Illuminate\Support\Facades\Hash;

class Service extends Model
{

    protected $fillable = ['company_name', 'location','address','name','account','password','phone','gender','scale_num','cost_price'];

    public function sale()
    {
        return $this->hasMany(Sale::class);
    }
    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        $this->attributes["password"] = Hash::make($value);
    }
    public function client(){
        return $this->hasMany(Client::class,'service_id','id');
    }

    public function money(){
        return $this->hasMany(ClientClosing::class,'service_id','id');
    }
    public function getMoneyAttribute(): string
    {
        return $this->money()->sum("closing_price");
    }

}
