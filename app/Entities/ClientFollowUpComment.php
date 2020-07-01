<?php


namespace App\Entities;


class ClientFollowUpComment extends Model
{
    public const TYPE_ADMIN   = 1;
    public const TYPE_SERVICE = 2;
    public const TYPE_SALE    = 3;


    protected $fillable = [
        'follow_up_id',
        'commentator_type',
        'commentator_id',
        'commentator_content',

    ];

    /**
     * @param $value
     */
//    public function setCommentatorTypeAttribute($value)
//    {
//        switch ($value){
//            case self::TYPE_ADMIN:
//                return $this->hasOne(ChildAccount::class,'id','commentator_id');
//            case self::TYPE_SERVICE:
//                return $this->hasOne(ChildAccount::class,'id','commentator_id');
//            case self::TYPE_SALE:
//                return $this->hasOne(ChildAccount::class,'id','commentator_id');
//        }
//    }

}
