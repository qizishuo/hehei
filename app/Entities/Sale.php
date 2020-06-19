<?php


namespace App\Entities;


use Illuminate\Support\Facades\Hash;

class Sale extends Model
{
    protected $appends = [
        'money'
    ];
    protected $fillable = ['name','account','password','scale_num'];

    public function service()
    {
        return $this->hasOne(Service::class,'id','service_id');
    }
    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        $this->attributes["password"] = Hash::make($value);
    }
    public function client(){
        return $this->hasMany(Client::class,'sale_id','id');
    }
    public function money(){
        return $this->hasMany(ClientClosing::class,'sale_id','id');
    }
    public function getMoneyAttribute(): string
    {
        return $this->money()->sum("closing_price");
    }

}
