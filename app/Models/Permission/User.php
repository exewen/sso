<?php

namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;
class User extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sso_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    // 状态
    const ENABLED = 1;
    const DISABLED = 2;
    public static $allStatus = [
        self::ENABLED     => '启用',
        self::DISABLED    => '禁用',
    ];

    // 用户账号类型
    const TYPE_ROOT = 10;
    const TYPE_CUSTOMER_SUPPLIER = 3;
    const TYPE_CUSTOMER_USER = 2;
    const TYPE_INTERNAL_USER = 1;
    public static $allTypes = [
        self::TYPE_ROOT => '超级管理员',
        self::TYPE_CUSTOMER_SUPPLIER => '客户供应商',
        self::TYPE_CUSTOMER_USER => '客户用户',
        self::TYPE_INTERNAL_USER => '内部用户',
    ];

    /**
     * 公用查询条件
     *
     * @param $query
     * @param $email
     * @return mixed
     */
    public function scopeLikeEmail($query, $email)
    {
        return !empty($email) ? $query->where('email', 'like', "%{$email}%") : $query;
    }

    /**
     * 所属客户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function authUserGroup()
    {
        return $this->hasMany(AuthUserGroup::class, 'user_id', 'id');
    }
}
