<?php

return [
    'menus' => [
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '客户管理',
            'role_name' => 'customer.management',
            'menus'   => [
                [
                    'title'     => '客户列表',
                    'link'      => '/customer/customer',
                    'roles'     => '',
                    'role_name' => 'customercontroller.index',
                    'is_active' => false,
                ],

                [
                    'title'     => '客户物流产品管理',
                    'link'      => '/customer/logistics_product',
                    'roles'     => '',
                    'role_name' => 'logisticsproductcontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '发货规则管理',
                    'link'      => '/customer/shipping_rule',
                    'roles'     => '',
                    'role_name' => 'shippingrulecontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '供应商管理',
                    'link'      => '/customer/supplier',
                    'roles'     => '',
                    'role_name' => 'suppliercontroller.index',
                    'is_active' => false,
                ],
            ]
        ],
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '仓库配置',
            'role_name' => 'warehouse.management',
            'menus'   => [
                [
                    'title'     => '仓库列表',
                    'link'      => '/customer/warehouse',
                    'roles'     => '',
                    'role_name' => 'warehousecontroller.index',
                    'is_active' => false,
                ],
            ]
        ],
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '通用档案管理',
            'role_name' => 'currency.management',
            'menus'   => [
                [
                    'title'     => '币种管理',
                    'link'      => '/common/currency',
                    'roles'     => '',
                    'role_name' => 'currencycontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '国家管理',
                    'link'      => '/common/country',
                    'roles'     => '',
                    'role_name' => 'countrycontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '标准单位管理',
                    'link'      => '/common/standard_unit',
                    'roles'     => '',
                    'role_name' => '',
                    'is_active' => false,
                ],
                [
                    'title'     => '邮编区域管理',
                    'link'      => '/common/postcode_region',
                    'roles'     => '',
                    'role_name' => 'PostcodeRegionController.index',
                    'is_active' => false,
                ],
            ]
        ],
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '商品管理',
            'role_name' => 'attribute.management',
            'menus'   => [
                [
                    'title'     => '属性管理',
                    'link'      => '/product/attribute',
                    'roles'     => '',
                    'role_name' => 'attributecontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => 'SKU列表',
                    'link'      => '/product/sku',
                    'roles'     => '',
                    'role_name' => 'skucontroller.index',
                    'is_active' => false,
                ],
            ]
        ],
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '单据列表',
            'role_name' => 'directive.management',
            'menus'   => [
                [
                    'title'     => '指令管理',
                    'link'      => '/directive/directive_type',
                    'roles'     => '',
                    'role_name' => 'directivetypecontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '入库订单列表',
                    'link'      => '/directive/stockin',
                    'roles'     => '',
                    'role_name' => 'stockindirectivecontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '出库订单列表',
                    'link'      => '/directive/stockout',
                    'roles'     => '',
                    'role_name' => 'stockoutdirectivecontroller.index',
                    'is_active' => false,
                ],
                /*[
                    'title'     => '调度单列表',
                    'link'      => '/directive/schedule',
                    'roles'     => '',
                    'role_name' => 'scheduledirectivecontroller.index',
                    'is_active' => false,
                ],*/
                [
                    'title'     => 'SKU转换单列表',
                    'link'      => '/directive/sku_transform',
                    'roles'     => '',
                    'role_name' => 'skutransformdirectivecontroller.index',
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
                    'title'     => '系统角色管理',
                    'link'      => '/permission/group_system',
                    'roles'     => '',
                    'role_name' => 'groupsystemcontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '系统用户管理',
                    'link'      => '/permission/user_system',
                    'roles'     => '',
                    'role_name' => 'usersystemcontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '客户角色管理',
                    'link'      => '/permission/group_customer',
                    'roles'     => '',
                    'role_name' => 'groupcustomercontroller.index',
                    'is_active' => false,
                ],
                [
                    'title'     => '客户用户管理',
                    'link'      => '/permission/user_customer',
                    'roles'     => '',
                    'role_name' => 'usercustomercontroller.index',
                    'is_active' => false,
                ],
            ]
        ],
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '预约送货',
            'role_name' => 'reservationdeliver.management',
            'menus'   => [
                [
                    'title'     => '供应商预约送货',
                    'link'      => '/reservationdeliver/supplier_deliver_list',
                    'roles'     => '',
                    'role_name' => 'reservationdelivercontroller.supplierdeliverlist',
                    'is_active' => false,
                ],
                [
                    'title'     => '调度预约送货',
                    'link'      => '/reservationdeliver/dispatch_deliver_list',
                    'roles'     => '',
                    'role_name' => 'reservationdelivercontroller.dispatchdeliverlist',
                    'is_active' => false,
                ],
            ]
        ],
        [
            'roles'   => '*',
            'is_open' => false,
            'icon'    => 'fa fa-reorder',
            'title'   => '数据统计',
            'role_name' => 'data.statistics',
            'menus'   => [
                [
                    'title'     => '商品库存查询',
                    'link'      => '/statistics/stock/query_sku_stock',
                    'roles'     => '',
                    'role_name' => 'stockcontroller.queryskustock',
                    'is_active' => false,
                ],
            ]
        ],
    ]
];
