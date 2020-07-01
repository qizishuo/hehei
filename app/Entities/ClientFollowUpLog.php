<?php


namespace App\Entities;
use App\Entities\ClientFollowUpComment;

class ClientFollowUpLog extends Model
{
    public const EXCHANGE_TYPE_PHONE = 1;
    public const EXCHANGE_TYPE_VISIT = 2;

    protected $with = ['rating','stage'];

    protected $fillable = [
        'follow_up_id',
        'rating_label_id',
        'stages_id',
        'exchang_at',
        'contacts',
        'location',
        'industry',
        'exchange_type',
        'is_before_visit',
        'exchange_situation',
        'exchange_plan',
    ];

    public function rating(){
        return $this->hasOne(RatingLabel::class,'id','rating_label_id');
    }
    public function stage(){
        return $this->hasOne(Stage::class,'id','stages_id');
    }


}
