<?php

namespace App\Models\Customer;

use App\Events\CustomerCreatedOrUpdatedEvent;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "sso_customer";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    // 状态
    const ENABLED = 1;
    const DISABLED = 2;
    public static $allStatus = [
        self::ENABLED     => '启用',
        self::DISABLED    => '禁用',
    ];

    /**
     * 获取客户的授权信息
     */
    public function customerAuthorization()
    {
        return $this->hasOne(CustomerAuthorization::class, 'customer_id', 'id');
    }

    /**
     * @param bool $strict
     * @return array
     */
    public static function getAll($strict=false)
    {
        if ($strict) {
            $res = self::where('status', self::ENABLED)->get()->toArray();
        } else {
            $res = self::all()->toArray();
        }
        return array_column($res, null, 'id');
    }

    public static function boot()
    {
        parent::boot();
        static::created(function (self $model) {  // 当创建模型时，触发
            event(new CustomerCreatedOrUpdatedEvent($model));
        });
        static::updated(function (self $model) {  // 当已存在的模型被更新时，触发
            event(new CustomerCreatedOrUpdatedEvent($model));
        });
    }
}
