<?php


namespace App\Entities;


class News extends Model
{
    protected $fillable = ['id','pid','name','info'];
    protected $appends = ['checked'];
    /**
     * 创建者类型
     */
    public const CREATOR_ADMIN = 1; /**  总后台  **/
    public const CREATOR_CHILD = 2; /**  服务商  **/
    /**
     * 发布类型
     */
    public const RELEASE_NOW = 1;   /** 立即发布 **/
    public const RELEASE_TIME = 2;  /** 定时发布 **/

    /**
     * 消息类型
     */
    public const SYSTEM_MSG = 1;    /** 系统消息 **/
    public const BUSINESS_MSG = 2;  /** 业务消息 **/

    public function peruser(){

        return $this->hasOne(NewsPeruser::class,'news_id','id');
    }
    public function getCheckedAttribute()//此处的Pic与追加字段'pic' 相对应
    {

        return false;
    }
}
