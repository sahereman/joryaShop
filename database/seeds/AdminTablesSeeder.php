<?php

use Illuminate\Database\Seeder;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Menu;

/**
 * Class AdminTablesSeeder
 */
class AdminTablesSeeder extends Seeder
{
    /*自定义添加的权限*/
    private $custom_permissions =
        [
            [
                'name' => '用户管理',
                'slug' => 'users',
                'http_method' => '',
                'http_path' => "/users\r\n/user_addresses\r\n/user_favourites",
            ],
            [
                'name' => '产品管理',
                'slug' => 'products',
                'http_method' => '',
                'http_path' => "/product_categories\r\n/products",
            ],
            [
                'name' => '订单管理',
                'slug' => 'orders',
                'http_method' => '',
                'http_path' => "/orders\r\n/order_refunds",
            ],
            [
                'name' => '广告位管理',
                'slug' => 'posters',
                'http_method' => '',
                'http_path' => "/posters",
            ],
            [
                'name' => '文章管理',
                'slug' => 'articles',
                'http_method' => '',
                'http_path' => "/articles",
            ],
            [
                'name' => 'Banner管理',
                'slug' => 'banners',
                'http_method' => '',
                'http_path' => "/banners",
            ],
            [
                'name' => '汇率管理',
                'slug' => 'exchange_rates',
                'http_method' => '',
                'http_path' => "/exchange_rates",
            ],
        ];

    /*自定义添加的菜单*/
    private $custom_menus =
        [
            //菜单组
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '用户管理',
                'icon' => 'fa-list',
                'uri' => 'users',
            ],
            [
                'parent_id' => 0,
                'order' => 2,
                'title' => '产品管理',
                'icon' => 'fa-list',
                'uri' => '',
            ],
            [
                'parent_id' => 0,
                'order' => 3,
                'title' => '订单管理',
                'icon' => 'fa-list',
                'uri' => '',
            ],
            [
                'parent_id' => 0,
                'order' => 4,
                'title' => '广告位管理',
                'icon' => 'fa-list',
                'uri' => 'posters',
            ],
            [
                'parent_id' => 0,
                'order' => 5,
                'title' => '文章管理',
                'icon' => 'fa-list',
                'uri' => 'articles',
            ],
            [
                'parent_id' => 0,
                'order' => 9,
                'title' => 'Banner管理',
                'icon' => 'fa-list',
                'uri' => 'banners',
            ],
            [
                'parent_id' => 0,
                'order' => 9,
                'title' => '汇率管理',
                'icon' => 'fa-list',
                'uri' => 'exchange_rates',
            ],


            // 产品
            [
                'parent_id' => 13,
                'order' => 1,
                'title' => '分类',
                'icon' => 'fa-list',
                'uri' => 'product_categories',
            ],
            [
                'parent_id' => 13,
                'order' => 2,
                'title' => '产品',
                'icon' => 'fa-list',
                'uri' => 'products',
            ],

            //订单
            [
                'parent_id' => 14,
                'order' => 1,
                'title' => '商品订单',
                'icon' => 'fa-list',
                'uri' => 'orders',
            ],
            [
                'parent_id' => 14,
                'order' => 2,
                'title' => '售后订单',
                'icon' => 'fa-list',
                'uri' => 'order_refunds',
            ],

        ];

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name' => 'Admin',
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => '超级管理组',
            'slug' => 'administrator',
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();
        $permissions = [
            [
                'name' => '所有权限',
                'slug' => '*',
                'http_method' => '',
                'http_path' => '*',
            ],
            [
                'name' => '首页',
                'slug' => 'index',
                'http_method' => 'GET',
                'http_path' => '/',
            ],
            [
                'name' => '登录',
                'slug' => 'auth.login',
                'http_method' => '',
                'http_path' => "/auth/login\r\n/auth/logout",
            ],
            [
                'name' => '个人设置',
                'slug' => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path' => '/auth/setting',
            ],
            [
                'name' => '系统管理',
                'slug' => 'auth.management',
                'http_method' => '',
                'http_path' => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs\r\n/media*\r\n/logs*\r\n/dashboard",
            ],
            [
                'name' => '系统设置',
                'slug' => 'configs',
                'http_method' => '',
                'http_path' => "/configs",
            ],
        ];
        $permissions = array_merge($permissions, $this->custom_permissions);
        Permission::insert($permissions);

        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        $menus = [
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '首页',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
            ],
            [
                'parent_id' => 0,
                'order' => 999,
                'title' => '系统管理',
                'icon' => 'fa-tasks',
                'uri' => '',
            ],
            [
                'parent_id' => 2,
                'order' => 2,
                'title' => '系统信息',
                'icon' => 'fa-file-text',
                'uri' => 'dashboard',
            ],
            [
                'parent_id' => 2,
                'order' => 3,
                'title' => '管理员',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
            ],
            [
                'parent_id' => 2,
                'order' => 4,
                'title' => '角色',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
            ],
            [
                'parent_id' => 2,
                'order' => 5,
                'title' => '权限',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
            ],
            [
                'parent_id' => 2,
                'order' => 6,
                'title' => '菜单',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
            ],
            [
                'parent_id' => 2,
                'order' => 7,
                'title' => '文件管理',
                'icon' => 'fa-file',
                'uri' => 'media',
            ],
            [
                'parent_id' => 2,
                'order' => 7,
                'title' => '系统日志',
                'icon' => 'fa-database',
                'uri' => 'logs',
            ],
            [
                'parent_id' => 2,
                'order' => 8,
                'title' => '操作日志',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
            ],
            [
                'parent_id' => 0,
                'order' => 998,
                'title' => '系统设置',
                'icon' => 'fa-gear',
                'uri' => '/configs',
            ],
        ];
        $menus = array_merge($menus, $this->custom_menus);
        Menu::insert($menus);

        // add role to menu.
        Menu::find(2)->roles()->save(Role::first());
        Menu::find(11)->roles()->save(Role::first());
        Menu::find(13)->roles()->save(Role::first());
        Menu::find(14)->roles()->save(Role::first());
        Menu::find(15)->roles()->save(Role::first());
        Menu::find(16)->roles()->save(Role::first());
        Menu::find(17)->roles()->save(Role::first());
        Menu::find(18)->roles()->save(Role::first());
    }
}
