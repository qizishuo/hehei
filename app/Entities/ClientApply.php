<?php


namespace App\Entities;
class ClientApply extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_PASS = 2;
    public const STATUS_REFUSE = 3;
    protected $appends = ['checked'];
    protected $fillable = [
        'client_id',
        'status',
        'created_at'
    ];
    public function getCheckedAttribute()
    {
        return false;
    }
    public function client(){
        return $this->hasOne(Client::class,'id','client_id');
    }
}
