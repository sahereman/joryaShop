<?php

use Illuminate\Database\Seeder;

use App\Models\Config;

class ConfigsSeeder extends Seeder
{
    private $config_groups =
        [
            //站点设置
            [
                'parent_id' => '0',
                'name' => '站点设置',
                'type' => "group",
                'sort' => 1000,
                'configs' =>
                    [
                        ['name' => '网站标题', 'code' => 'title', 'type' => "text", 'sort' => 10, 'value' => '卓雅美业'],
                        ['name' => '网站关键字', 'code' => 'keywords', 'type' => "text", 'sort' => 20, 'value' => '卓雅美业 keywords'],
                        ['name' => '网站描述', 'code' => 'description', 'type' => "text", 'sort' => 30, 'value' => '卓雅美业 description'],
                        //                        ['name' => '网站Logo', 'code' => 'logo', 'type' => "image", 'sort' => 40,
                        //                            'help' => '网站首页的Logo',],
                        //                        ['name' => '网站关闭', 'code' => 'site_close', 'type' => "radio", 'sort' => 50,
                        //                            'select_range' => [['value' => 0, 'name' => '开启'], ['value' => 1, 'name' => '关闭']],
                        //                            'help' => '网站开启临时维护时,请关闭站点',
                        //                        ],
                    ]
            ],

            // 订单设置
            [
                'name' => '订单设置',
                'type' => "group",
                'sort' => 2000,
                'configs' =>
                    [
                        ['name' => '用户保存收货地址数目上限', 'code' => 'max_user_address_count', 'type' => "text", 'sort' => 10, 'value' => '20'],
                        ['name' => '系统自动关闭订单时间', 'code' => 'time_to_close_order', 'type' => "text", 'sort' => 20, 'value' => 5, 'help' => '用户下单后未支付，系统自动关闭订单的时间（单位：分钟）'],
                        ['name' => '系统自动确认订单时间', 'code' => 'time_to_complete_order', 'type' => "text", 'sort' => 30, 'value' => 10, 'help' => '卖家发货后，买家未及时确认订单，系统自动确认订单的时间（单位：天）'],
                        ['name' => '系统自动拒绝售后申请时间', 'code' => 'time_to_decline_order_refund', 'type' => "text", 'sort' => 40, 'value' => 5, 'help' => '对于退货并退款的售后申请，卖家审核通过后，买家未及时发货，系统自动拒绝售后申请的时间（单位：天）'],
                    ]
            ],

            //站点设置2
            //            [
            //                'name' => '站点设置2',
            //                'type' => "group",
            //                'sort' => 2000,
            //                'configs' =>
            //                    [
            //                        ['name' => '网站标题', 'code' => 'title2', 'type' => "text", 'sort' => 10, 'value' => '网站标题'],
            //                        ['name' => '网站关键字', 'code' => 'keywords2', 'type' => "text", 'sort' => 20],
            //                        ['name' => '网站描述', 'code' => 'description2', 'type' => "text", 'sort' => 30],
            //                        ['name' => '网站Logo', 'code' => 'logo2', 'type' => "image", 'sort' => 40],
            //                        ['name' => '网站关闭', 'code' => 'site_close2', 'type' => "radio", 'sort' => 50,
            //                            'select_range' => [['value' => 0, 'name' => '开启'], ['value' => 1, 'name' => '关闭']],
            //                        ],
            //                    ]
            //            ],
        ];

    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        Config::truncate();
        Cache::forget(Config::$cache_key);

        foreach ($this->config_groups as $item) {
            $group = Config::create(array_except($item, 'configs'));

            foreach ($item['configs'] as $config) {
                Config::create(array_merge($config, ['parent_id' => $group->id]));
            }
        }

    }
}
