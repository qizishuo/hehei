<?php


namespace App\Entities;


use App\Entities\ClientFollowUpLog;
use phpDocumentor\Reflection\Types\Self_;
class ClientFollowUp extends Model
{
    public const FOLLOW_TYPE_UP       = 1;
    public const FOLLOW_TYPE_TRANSFER = 2;
    public const FOLLOW_TYPE_TO       = 3;
    public const FOLLOW_TYPE_COME     = 4;
    public const FOLLOW_TYPE_SIGN     = 5;
    public const FOLLOW_TYPE_E        = 6;
    protected $with = ["log"];


    protected $fillable = [
        "id",
        'follow_type',
        'client_id',
        'sale_id',
        'old_sale_id',
        'admin_id',
        'type'
    ];

    public function getFOLLOWTYPEAttribute($value)
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
            case self::FOLLOW_TYPE_E:
                return '申诉';
            default:
                return '未知';
        }
    }
    public function money(){
        return $this->hasMany(ClientClosing::class,'follow_up_id','id');
    }

    /**
     * 关联详情
     */
    public function log(){
        return $this->hasMany(ClientFollowUpLog::class,'follow_up_id','id');
    }

    public function rable(){
        return $this->hasMany(ClientLogRable::class,'follow_up_id','id');
    }

    public function addRable(array $rable){
        $id = $this->id;
        foreach ($rable as $item){
            ClientLogRable::create([
                'log_id' => $id,
                'rating_lable_id' => $item
            ]);
        }

    }

    public function addLog(array $data){
        $data['follow_up_id'] = $this->id;

        ClientFollowUpLog::create([
            $data
        ]);
    }
}
