<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer\Customer;
class AuthGroup extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "sso_auth_group";

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
        self::ENABLED => '启用',
        self::DISABLED => '禁用',
    ];

    const NAME_SYSTEM_ROOT = 'system_root'; // 固定超级管理员英文名称
    const NAME_CUSTOMER_ADMIN = 'customer_admin';// 固定客户管理员英文名称
    const NAME_CUSTOMER_SUPPLIER = 'customer_supplier';// 固定客户供应商英文名称
    const INTERNAL_CUSTOMER_NAME = '平台公共客户';// 内部用户归属客户名称(固定) 区分内部用户和客户用户

    public static $allDisableNames = [// 禁止修改的英文名称
        self::NAME_SYSTEM_ROOT,
        self::NAME_CUSTOMER_ADMIN,
    ];
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

    public function authUserGroup()
    {
        return $this->hasMany(AuthUserGroup::class, 'group_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
