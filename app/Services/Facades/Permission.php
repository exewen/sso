<?php


namespace App\Services\Facades;
use Illuminate\Support\Facades\Facade;
use App\Services\PermissionService;
class Permission extends Facade
{
    /**
     * 获取组件注册名称
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'permission';
    }
}