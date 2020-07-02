<?php


namespace App\Entities;
class ClinetApply extends Model
{
    public const STATUS_PENDING = 1;
    public const STATUS_PASS = 2;
    public const STATUS_REFUSE = 3;

    protected $fillable = [
        'client_id',
        'status',
        'created_at'
    ];

    public function clinet(){
        return $this->hasOne(Client::class,'id','client_id');
    }
}
