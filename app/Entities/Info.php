<?php

namespace App\Entities;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int type
 * @property string location
 * @property string company_name
 * @property string boss_name
 * @property string phone_number
 * @property string industry
 * @property int child_account_id
 * @property int money_id
 * @property Phone phone
 * @property ChildAccount|null child_account
 */
class Info extends Model
{
    use SoftDeletes;

    public const TYPE_CHECK = 1;
    public const TYPE_NAMED = 2;

    protected $attributes = [
        'company_name' => '',
        'child_account_id' => 0,
    ];

    protected $fillable = [
        'phone_id',
        'type',
        'source',
        'company_name',
        'location',
        'industry',
        'boss_name',
        'boss_birth',
        "client_ip",
    ];

    protected $with = ["phone"];

    public function getType(): string
    {
        $types = Info::getTypes();
        return $types[$this->type] ?? "未知";
    }

    public static function getTypes(): array
    {
        return [
            Info::TYPE_CHECK => "核名",
            Info::TYPE_NAMED => "起名",
        ];
    }

    public function getName(): string
    {
        switch ($this->type) {
            case self::TYPE_CHECK:
                return $this->company_name ? $this->company_name : $this->boss_name;
            case self::TYPE_NAMED:
            default:
                return $this->boss_name ? $this->boss_name : $this->company_name;
        }
    }

    public function getLocation(): string
    {
        return location_name($this->location);
    }

    public function getChildAccountAttribute()
    {
        return $this->phone ? $this->phone->childAccount : null;
    }

    public function getChildAccountIdAttribute()
    {
        return $this->phone ? $this->phone->child_account_id : null;
    }

    public function getPhoneNumberAttribute()
    {
        return $this->phone ? $this->phone->phone : "";
    }

    public function setPhoneNumberAttribute($value)
    {
        return $this->phone->phone = $value;
    }

    public function money()
    {
        return $this->belongsTo(Money::class);
    }

    public function phone()
    {
        return $this->belongsTo(Phone::class)->withTrashed()->withDefault();
    }

    public function sendNotice()
    {
        Log::info("[wechat] send wechat notice start");

        if (empty($this->phone->child_account_id)) {
            Log::error("[wechat] unsend: info no account", [
                "info_id", $this->id,
            ]);
            return false;
        }

        $account = ChildAccount::find($this->phone->child_account_id);
        $app = app('wechat.official_account');
        $check_template = "KEDU1X5jXBqjaTUGnvpjodpGEDCzisupWjobAZOdqV4";
        $named_template = "v6C7wdzvvsNWXCqUaUOcAf4Fn_bMnDUXIhzCMmYigsQ";

        if (empty($account->openid)) {
            Log::info("[wechat] unsend: not bind wechat", [
                "info_id" => $this->id,
                "account_id" => $account->id,
            ]);
            return false;
        }

        switch ($this->type) {
            case Info::TYPE_CHECK:
                $template_id = $check_template;
                $data = [
                    "first" => "【大有企服平台分配】【核名线索】{$account->name}您好，有新的客户分配给您",
                    "keyword1" => $this->getLocation(),
                    "keyword2" => $this->company_name,
                    "keyword3" => $this->phone_number,
                    "keyword4" => $this->created_at,
                    "keyword5" => "核名",
                ];
                break;
            case Info::TYPE_NAMED:
                $template_id = $named_template;
                $data = [
                    "first" => "【大有企服平台分配】【起名线索】{$account->name}您好，有新的客户分配给您",
                    "keyword2" => $this->phone_number,
                    "keyword3" => $this->boss_name,
                    "keyword4" => $this->getLocation(),
                    "keyword5" => $this->industry,
                    "remark" => $this->created_at,
                ];
                break;
            default:
                return false;
        }

        $result = $app->template_message->send([
            'touser' => $account->openid,
            'template_id' => $template_id,
            "data" => $data,
        ]);
        Log::info("[wechat] send end: response", $result);

        return true;
    }

    public function autoAllocate()
    {
        if ($this->phone->child_account_id) {
            Log::info("[alloc] alread alloced", [
                "info_id" => $this->id,
            ]);
            return false;
        }

        $accounts = ChildAccount::withCount(["phones as order_count" => function ($query) {
            $query->groupBy("child_account_id");
        }])->where("amount", ">", 0)->where("location", $this->location)->where("weight", ">", 0)->get();

        $sum_weight = $accounts->sum("weight");
        $min_weight = $accounts->min("weight");
        $count = Redis::incr("count:{$this->location}");
        $dest = $count * $min_weight % $sum_weight;
        $index = 0;
        foreach ($accounts as $account) {
            $index += $account->weight;
            if ($dest < $index) {
                return $this->allocateTo($account->id);
            }
        }

        Log::info("[alloc] no account to alloc", [
            "info_id" => $this->id,
        ]);
        return false;
    }

    public function allocateTo(int $child_account_id)
    {
        $account = ChildAccount::sharedLock()->findOrFail($child_account_id);

        $money = Money::create([
            "child_account_id" => $child_account_id,
            "amount" => -$account->price,
            "type" => Money::TYPE_CONSUME,
            "comment" => "信息" . $this->id . "扣款",
        ]);

        $account->amount -= $account->price;
        $account->save();

        $this->phone->child_account_id = $child_account_id;
        $this->phone->save();

        $this->child_account_id = $child_account_id;
        $this->money_id = $money->id;
        $this->location = $account->location;
        $this->save();

        Log::info("[alloc] alloc success", [
            "info_id" => $this->id,
            "child_account_id" => $child_account_id,
        ]);

        $this->sendNotice();
    }
}
