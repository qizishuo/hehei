<?php


namespace App\Entities;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Entities\ClientFollowUp;
class Client extends Model
{
    public const TYPE_ADMIN   = 1;
    public const TYPE_SERVICE = 2;
    public const TYPE_SALE    = 3;

    protected $fillable = [
        'company_name',
        'province',
        'location',
        'address',
        'industry',
        'rating_label_id',
        'last_rating_label_id',
        'stages_id',
        'contacts',
        'wechat_number',
        'gender',
        'phone',
        'service_id',
        'old_service_id',
        'sale_id',
        'is_deal',
        'created_by',
        'created_by_type',
        'is_lock',
        'is_visit',
        'initials',
        'identifier'
    ];


    protected $appends = ['checked'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];
    protected $hidden = [
        'password',
    ];
    /**
     * 关联销售
     */

    /**
     * 关联服务商
     */

    /**
     * 关联前服务商
     */

    /**
     * 关联跟进记录
     */
    public function followlog(){
        return $this->hasMany(ClientFollowUp::class,'client_id','id');
    }
    public function money(){
        return $this->hasOne(ClientClosing::class,'client_id','id');
    }

    public function sale(){
        return $this->hasOne(Sale::class,'id','sale_id');
    }
    public function service(){
        return $this->hasOne(Service::class,'id','service_id');
    }
    public function rating(){
        return $this->hasOne(RatingLabel::class,'id','rating_label_id');
    }
    public function stage(){
        return $this->hasOne(Stage::class,'id','stage_id');
    }



    public function getCreatedByAttribute($value){
        if($this->created_by_type == self::TYPE_ADMIN){
            return $this->hasOne(User::class,'id','created_by');
        }
//        $location = location_name($value);
//        return strstr($location," ");
    }

    public function getLocationAttribute($value){
        $location = location_name($value);
        return strstr($location," ");
    }

    public function getIsVisitAttribute($value){

        return $value ? '是' : '否';
    }


    public function getCheckedAttribute()//此处的Pic与追加字段'pic' 相对应
    {
        return false;
    }
}
