<?php
namespace App\Models\Permission;

use Illuminate\Database\Eloquent\Model;

class AuthRule extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "sso_auth_rule";

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

    // 类型
    const TYPE_MENU = 1;
    const TYPE_CONTROLLER = 2;
    const TYPE_POLICY = 3;
    public static $allTypes = [
        self::TYPE_MENU => '一级菜单',
        self::TYPE_CONTROLLER => '二级菜单',
        self::TYPE_POLICY => '按钮',
    ];

    /**
     * @param bool $strict
     * @return array
     */
    public static function getAll($strict=false)
    {
        if ($strict) {
            $res = self::where('status', self::ENABLED)->selectRaw('id,pid,name,cn_name,type,status')->get();
        } else {
            $res = self::selectRaw('id,pid,name,cn_name,type,status')->get();
        }
        return $res;
    }
}
