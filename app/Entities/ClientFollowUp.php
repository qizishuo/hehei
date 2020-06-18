<?php


namespace App\Entities;


use phpDocumentor\Reflection\Types\Self_;
use App\Entities\ClientFollowUpLog;
class ClientFollowUp extends Model
{
    public const FOLLOW_TYPE_UP       = 1;
    public const FOLLOW_TYPE_TRANSFER = 2;
    public const FOLLOW_TYPE_TO       = 3;
    public const FOLLOW_TYPE_COME     = 4;
    public const FOLLOW_TYPE_SIGN     = 5;

    protected $with = ["log"];


    protected $fillable = [
        'follow_type',
        'client_id',
        'sale_id',
        'old_sale_id',
    ];
    protected $appends = [
        'money'
    ];
    public function setFOLLOWTYPEAttribute($value)
    {
        switch ($value){
            case self::FOLLOW_TYPE_UP:
                return '跟进';
            case self::FOLLOW_TYPE_TRANSFER:
                return '转移';
            case self::FOLLOW_TYPE_TO:
                return '转入公海';
            case self::FOLLOW_TYPE_COME:
                return '归属';
            case self::FOLLOW_TYPE_SIGN:
                return '签单';
            default:
                return '未知';
        }
    }
    public function money(){
        return $this->hasMany(ClientClosing::class,'follow_up_id','id');
    }
    public function getMoneyAttribute(): string
    {
        return $this->money()->sum("closing_price");
    }
    /**
     * 关联详情
     */
    public function log(){
        return $this->hasMany(ClientFollowUpLog::class,'id','follow_up_id');
    }
}
