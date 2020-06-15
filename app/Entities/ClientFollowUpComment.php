<?php


namespace App\Entities;


class ClientFollowUpComment extends Model
{
    protected $fillable = [
        'follow_up_log_id',
        'commentator_type',
        'commentator_id',
        'commentator_content',
        'contacts',
        'location',
        'Industry',
        'exchange_type',
        'is_before_visit',
        'exchange_situation',
        'exchange_plan',
    ];
}
