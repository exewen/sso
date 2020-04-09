<?php

namespace App\Services;

use App\Models\Customer\Customer;
use App\Models\Permission\AuthRule;
use App\Models\Permission\AuthGroup;
use App\Models\Permission\User;
use Route;
use Cache;
use DB;


class PermissionService
{
    public $abilityAlias = 'ability';

    /**
     * 验证controller权限
     * @param $permission
     * @param int $type 1菜单 2控制器 3其他 4请求
     * @return bool
     */
    public function hasPermission($permission, $type = 1)
    {
        $check = false;
        if (auth()->check() && !empty($permission)) {
            $permission = strtolower($permission);// 全部转换为小写
            $user = auth()->user();
            $currentPermissions = $this->getCurrentPermission($user);
            $check = in_array($permission, $currentPermissions['permissions']);
            if ($currentPermissions['userType'] == User::TYPE_ROOT && !$check) {
                if (empty($currentPermissions['permissions'])) {
                    $this->setMenusPermission();
                } else {
                    $this->setNewPermission($permission, $type);// ROOT创建权限
                }
                $this->setUserPermissions($user);// 刷新权限
                $check = true;
            }
        }
        return $check;
    }

    /**
     * 是否客户用户，返回 false或者客户ID
     * 客户用户账号类型只能查看和操作属于自己的数据
     * @return bool
     */
    public function isCustomerUser()
    {
        $user = auth()->user();
        return $user->type == 2 ? $user->customer_id : false;
    }

    /**
     * 获取用户权限
     * @param $user
     * @return mixed
     */
    public function getCurrentPermission($user)
    {
        $key = 'user_' . $user->id;
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        $this->setUserPermissions($user);
        return Cache::get($key);
    }

    /**
     * 刷新用户权限、角色
     * @param $user
     */
    public function setUserPermissions($user)
    {
        $ruleIds = [];
        $isRoot = $user->type === User::TYPE_ROOT ? true : false;
        $isSupplier = $user->type === User::TYPE_CUSTOMER_SUPPLIER ? true : false;
        $userAuthGroup = $this->getUserAuthGroup($user, $isRoot, $isSupplier);
        $authGroup = array_pluck($userAuthGroup, 'name');
        foreach ($userAuthGroup as $item) {
            $ruleIds = array_unique(array_merge(explode(',', $item->rule_ids), $ruleIds));
        }
        $userPermissions = $this->getUserPermissions($ruleIds, $isRoot);
        $internalCustomer = Customer::query()->where('name', AuthGroup::INTERNAL_CUSTOMER_NAME)->first(['id']);
        $authsCache = [
            'internalCustomerId' => isset($internalCustomer->id) ? $internalCustomer->id : 0,// 内部平台客户ID
            'userType' => $user->type,// 用户账号类型
            'authGroup' => $authGroup,// 用户所属分组
            'permissions' => array_pluck($userPermissions, 'name'),// 该账号权限
            'permissionDetails' => $userPermissions->toArray(),// 该账号权限全部数据
        ];
        // 缓存用户权限
        Cache::forever('user_' . $user->id, $authsCache);
    }

    /**
     * 验证是否超级管理员
     * @param $user
     * @return bool
     */
    public function isSuperRoot($user)
    {
        $currentPermissions = $this->getCurrentPermission($user);
        return $user->type == $currentPermissions['userType'];
    }


    private function getAuthRule($permission)
    {
        return AuthRule::where('name', $permission)->select('id')->first();
    }

    /**
     * 为ROOT用户新增控制器权限
     * @param $permission
     * @param int $type
     * @param string $cn_name
     * @param int $pid
     * @return bool
     */
    private function setNewPermission($permission, $type = 0, $cn_name = '', $pid = 0)
    {
        try {
            $is_exist = $this->getAuthRule($permission);
            if (!$is_exist) {
                if ($type === 3) {//  查询页面权限ID
                    $module = $this->getCurrentControllerModule();
                    $actionName = $this->getCurrentControllerName();
                    $method = $this->getCurrentControllerMethod();
                    $res = $this->getAuthRule(strtolower($module . '.' . $actionName . '.' . $method));
                    !empty($res) && $pid = $res->id;
                }
                $authRuleId = DB::table('sms_auth_rule')->insertGetId([
                    'name' => $permission,
                    'cn_name' => $cn_name,
                    'type' => $type,
                    'status' => 2,
                    'pid' => $pid,
                ]);
            }
            return isset($authRuleId) ? $authRuleId : 0;
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * 初始化菜单权限
     * @return bool
     */
    private function setMenusPermission()
    {
        $templates = config('template');
        if (isset($templates['menus'])) {
            foreach ($templates['menus'] as $menu) {
                if (isset($menu['menus']) && isset($menu['title']) && isset($menu['role_name'])) {
                    $pid = $this->setNewPermission($menu['role_name'], 1, $menu['title']);
                    foreach ($menu['menus'] as $item) {
                        isset($item['title']) && isset($item['role_name']) && $this->setNewPermission($item['role_name'], 2, $item['title'], $pid);
                    }
                }
            }
        }
        return true;
    }

    /**
     * 获取用户权限列表
     * @param $user
     * @param $ruleIds
     * @param bool $isSystemRoot
     * @return mixed
     */
    private function getUserPermissions($ruleIds, $isSystemRoot = false)
    {
        $res = AuthRule::selectRaw('id,pid,name,cn_name,url_path');
        if (!$isSystemRoot) {
            $res->whereIn('id', $ruleIds);
            $res->where('status', AuthRule::ENABLED);
        }
        $res->where('name', '<>', '');
        return $res->get();
    }

    /**
     * 返回当前用户的权限分组
     * @param $user
     * @param bool $isSystemRoot
     * @return AuthGroup[]|array|\Illuminate\Database\Query\Builder[]
     */
    private function getUserAuthGroup($user, $isSystemRoot = false, $isSupplier = false)
    {
        $res = AuthGroup::selectRaw('id,rule_ids,cn_name,name');
        // 获取当前用户权限分组
        if (!$isSystemRoot) {
            if (!$isSupplier) {
                $res->whereHas('authUserGroup', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->where('customer_id', $user->customer_id);
                $res->where('status', AuthGroup::ENABLED);
            } else {
                $res->where('name', AuthGroup::NAME_CUSTOMER_SUPPLIER);
                $res->where('customer_id', $this->getInternalCustomerId(true));
                $res->where('status', AuthGroup::ENABLED);
            }
        }
        return $res->get();
    }


    public function getCurrentControllerModule()
    {
        return $this->getCurrentActionAttribute()['module'];
    }

    public function getCurrentControllerMethod()
    {
        return $this->getCurrentActionAttribute()['method'];
    }

    public function getCurrentControllerName()
    {
        return $this->getCurrentActionAttribute()['controller'];
    }

    private function getCurrentActionAttribute()
    {
        $action = Route::currentRouteAction();
        list($class, $method) = explode('@', $action);
        preg_match('/^App\\\Modules\\\(\w+?)\\\.*\\\(\w+)@(\w+)$/', $action, $match);
        $module = isset($match[1]) ? $match[1] : '';
        return ['module' => $module, 'controller' => class_basename($class), 'method' => $method];
    }

    private function getPermissionCnName($permission, $cnName = '')
    {
        $user = auth()->user();
        $permissionDetails = $this->getCurrentPermission($user)['permissionDetails'];
        $permissionCnNames = array_column($permissionDetails, 'cn_name', 'name');
        $res = isset($permissionCnNames[$permission]) && !empty($permissionCnNames[$permission]) ? $permissionCnNames[$permission] : $cnName;
        return $res;
    }

    /**
     * 替换菜单相关权限标题
     * @param $menus
     * @return mixed
     */
    public function replaceMenusCnName($menus)
    {
        foreach ($menus as &$menu) {
            isset($menu['title']) && isset($menu['role_name']) && $menu['title'] = $this->getPermissionCnName($menu['role_name'], $menu['title']);
            foreach ($menu['menus'] as &$item) {
                isset($item['title']) && isset($item['role_name']) && $item['title'] = $this->getPermissionCnName($item['role_name'], $item['title']);
            }
        }
        return $menus;
    }

    /**
     * 获取菜单template配置
     * @return array
     */
    public function menusTemplate()
    {
        $menusTemplate = ['menus' => []];
        $user = auth()->user();
        $permissionDetails = $this->getCurrentPermission($user)['permissionDetails'];
        foreach ($permissionDetails as $key => &$item) {
            if ($item['pid'] == 0 && empty($item['cn_name'])) {//不展示未配置的权限
                unset($permissionDetails[$key]);
            }
        }
        $data = self::getTree($permissionDetails, 0);
        foreach ($data as $key => $menus) {
            $menusTemplate['menus'][$key] = [
                'roles' => '*',
                'is_open' => false,
                'icon' => 'fa fa-reorder',
                'title' => $menus['cn_name'],
                'menus' => []
            ];
            foreach ($menus['children'] as $item) {
                !empty($item['url_path']) && $menusTemplate['menus'][$key]['menus'][] = [
                    'title' => $item['cn_name'],
                    'link' => $item['url_path'],
                    'roles' => '',
                    'is_active' => false,
                ];
            }
        }
        return $menusTemplate;
    }

    /**
     * 获取(指定分组ID)菜单权限数结构
     * group_id=0 返回当前用户可以管理的权限
     * @param bool $isDisabled
     * @return array
     */
    public function getMenusRuleTree($params, $isDisabled = false)
    {
        $user = auth()->user();
        $currentPermission = $this->getCurrentPermission($user);
        $permissionDetails = $currentPermission['permissionDetails'];
        $groupData = AuthGroup::find($params['group_id']);
        $groupRules = isset($groupData->rule_ids) ? explode(',', $groupData->rule_ids) : [];
        foreach ($permissionDetails as $key => &$item) {
            if ($item['pid'] == 0 && empty($item['cn_name'])) {//不展示未配置的权限
                unset($permissionDetails[$key]);
            }
            $item['state'] = [
                'selected' => in_array($item['id'], $groupRules) ? true : false,
                'disabled' => $isDisabled
            ];
            $item['text'] = $item['cn_name'];
            $item['icon'] = $item['pid'] == 0 ? 'glyphicon glyphicon-menu-hamburger' : 'glyphicon glyphicon-user';
        }
        $data = self::getTree($permissionDetails, 0);
        self::setTreeSelected($data);
        return $data;
    }

    /**
     * jstree存在子权限 父级选项默认不选中
     * @param $data
     */
    private static function setTreeSelected(&$data)
    {
        foreach ($data as $key => &$item) {
            if (!empty($item['children'])) {
                $hasRule = $item['state']['selected'];
                $item['state']['selected'] = false;
                $temp = [
                    'pid' => $item['id'],
                    'text' => '列表浏览',
                    'cn_name' => '列表浏览',
                    'url_path' => $item['url_path'],
                    'state' => [
                        'selected' => $hasRule,
                        'disabled' => $item['state']['disabled']
                    ],
                    'icon' => $item['icon'],
                    'children' => []
                ];
                $item['pid'] > 0 && array_unshift($item['children'], $temp);// 新增列表浏览选项 支持仅浏览权限
                self::setTreeSelected($item['children']);
            }
        }
    }

    /**
     * ROOT用户获取权限数关系
     * @return array
     */
    public function getMenusTree()
    {
        $menus = AuthRule::getAll()->toArray();
        return self::getTree($menus, 0);
    }

    private static function getTree($data, $pid)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {// 父亲找到儿子
                $v['children'] = self::getTree($data, $v['id']);
                $tree[] = $v;
            }
        }
        return $tree;
    }

    /**
     * 获取内部平台ID
     * @return mixed
     */
    public function getInternalCustomerId($forceQuery = false)
    {
        if ($forceQuery) {
            $platform = Customer::query()->where('name', AuthGroup::INTERNAL_CUSTOMER_NAME)->first(['id']);
            return $platform->id;
        } else {
            $user = auth()->user();
            $currentPermissions = $this->getCurrentPermission($user);
            if (!empty($currentPermissions['internalCustomerId'])) {
                return $currentPermissions['internalCustomerId'];
            } else {
                $platform = Customer::query()->where('name', AuthGroup::INTERNAL_CUSTOMER_NAME)->first(['id']);
                return $platform->id;
            }
        }
    }
}