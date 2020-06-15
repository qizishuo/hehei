<?php


namespace App\Entities;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Entities\ClientFollowUp;
class Client extends Model
{
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
        return $this->hasMany(ClientFollowUp::class,'id','client_id');
    }
    /**
     * @param $val
     * @return string
     */
    public function getLocationAttribute($val): string
    {
        return location_name($val);
    }
}
