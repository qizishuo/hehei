<?php

namespace App\Entities;

use App\Observers\ChildAccountObserver;
use App\Services\Redis;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * @property int id
 * @property int weight
 * @property string name
 */
class ChildAccount extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail, Notifiable, SoftDeletes;

    public const STATUS_NORMAL = 1;
    public const STATUS_CLOSE = 2;

    public const TYPE_THIRD = 1;
    public const TYPE_PLATFORM = 2;

    public const DEFAULT_WEIGHT = 3;

    protected $attributes = [
        "amount" => "0.00",
        "location" => "110000",
        "weight" => self::DEFAULT_WEIGHT,
    ];

    protected $fillable = [
        'name',
        'password',
        'type',
        'location',
        "weight",
    ];

    protected $hidden = [
        'password',
    ];

    protected $money_redis;

    private $lucky_number;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->money_redis = new Redis("system_money");
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy("deleted_at", 'asc')->orderByDesc('id');
        });

        static::observe(ChildAccountObserver::class);
    }

    public function setPasswordAttribute($value)
    {
        if (empty($value)) {
            return false;
        }
        $this->attributes["password"] = Hash::make($value);
    }

    public function infos()
    {
        return $this->hasMany(Info::class);
    }

    public function applies()
    {
        return $this->hasMany(Apply::class);
    }

    public function getSumAmountAttribute(): string
    {
        return $this->money()->whereIn(
            "type",
            [Money::TYPE_RECHARGE, Money::TYPE_CONSUME]
        )->sum("amount");
    }

    public function getConsumeAttribute(): string
    {
        return $this->money()->where(
            "type",
            Money::TYPE_CONSUME
        )->sum("amount");
    }

    public function getPriceAttribute($value): string
    {
        return $value ?? strval($this->money_redis->get());
    }

    public function setPriceAttribute($value)
    {
        if ($value && $value == $this->money_redis->get()) {
            $this->attributes["price"] = null;
        } else {
            $this->attributes["price"] = $value;
        }
    }

    public function getLuckyNumberAttribute(): int
    {
        if (empty($this->lucky_number)) {
            $this->lucky_number = $this->goodLuck();
        }
        return $this->lucky_number;
    }

    public function money()
    {
        return $this->hasMany(Money::class);
    }

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    protected function goodLuck(): int
    {
        $result = 0;
        for ($i = 0; $i < $this->weight; ++$i) {
            $result = max($result, rand(1, 10000000));
        }
        return $result;
    }

    public static function getName(int $id): string
    {
        if (empty($id)) {
            return "未指定用户";
        }
        $data = static::find($id);
        if (empty($data)) {
            return "未知用户";
        }
        return $data->name;
    }
    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
