<?php

use Faker\Generator as Faker;

$factory->define(App\Models\OrderRefund::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'refund_sn' => \App\Models\OrderRefund::generateRefundSn(),
        'seller_info' => ["name"=>"seller-name-aaa","phone"=>"18888888888","address"=>"seller-address-somewhere"],
        'type' => \App\Models\OrderRefund::ORDER_REFUND_TYPE_REFUND,
        'status' => 'checking',
        'remark_from_user' => 'remarks from user ......',
        'remark_from_seller' => 'remarks from seller ......',
        'remark_for_shipment_from_user' => 'remarks for shipment from user ......',
        'remark_for_shipment_from_seller' => 'remarks for shipment from seller ......',
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
