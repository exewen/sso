<?php

return [
    'menus' => [
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '子系统管理',
            'role_name' => 'subsystem.management',
            'menus'   => [
                [
                    'title'     => '系统列表',
                    'link'      => '/subsystem/subsystem',
                    'roles'     => '',
                    'role_name' => 'subsystemController.index',
                    'is_active' => false,
                ],
            ]
        ],
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '账号及权限',
            'role_name' => 'permission.management',
            'menus'   => [
                [
                    'title'     => '权限管理',
                    'link'      => '/permission/permission_rule',
                    'roles'     => '',
                    'role_name' => 'permissionrulecontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '角色管理',
                    'link'      => '/permission/group_system',
                    'roles'     => '',
                    'role_name' => 'groupsystemcontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '用户管理',
                    'link'      => '/permission/user_system',
                    'roles'     => '',
                    'role_name' => 'usersystemcontroller.index',
                    'is_active' => false,
                ],
            ]
        ],
    ]
];
