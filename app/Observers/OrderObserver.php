<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use App\Models\UserAddress;
use Illuminate\Support\Carbon;

class OrderObserver
{
    /*Eloquent 的模型触发了几个事件，可以在模型的生命周期的以下几点进行监控：
    retrieved、creating、created、updating、updated、saving、saved、deleting、deleted、restoring、restored
    事件能在每次在数据库中保存或更新特定模型类时轻松地执行代码。*/

    public function created(Order $order)
    {
        // 更新或创建一条用户地址信息记录
        $userAddress = UserAddress::firstOrCreate([
            'user_id' => $order->user->id,
            'name' => $order->user_info['name'],
            'phone' => $order->user_info['phone'],
            'address' => $order->user_info['address'],
        ]);
        $userAddress->last_used_at = Carbon::now()->toDateTimeString();
        $userAddress->save();

        // 创建多条子订单OrderItem记录
        foreach ($order->snapshot as $sku) {
            if ($sku instanceof ProductSku) {
                $itemData = $sku->toArray();
            } else {
                $itemData = $sku;
            }
            $itemData['order_id'] = $order->id;
            $itemData['product_sku_id'] = $itemData['id'];
            OrderItem::create($itemData);
        }
    }
}
