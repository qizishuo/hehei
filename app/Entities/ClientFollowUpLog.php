<?php


namespace App\Entities;
use App\Entities\ClientFollowUpComment;

class ClientFollowUpLog extends Model
{
    public const EXCHANGE_TYPE_PHONE = 1;
    public const EXCHANGE_TYPE_VISIT = 2;

    protected $with = ["log"];

    protected $fillable = [
        'follow_up_id',
        'rating_lable_id',
        'stages_id',
        'exchang_at',
        'contacts',
        'location',
        'Industry',
        'exchange_type',
        'is_before_visit',
        'exchange_situation',
        'exchange_plan',
    ];
    public function log(){
        return $this->hasMany(ClientFollowUpComment::class,'id','follow_up_log_id');
    }


}
