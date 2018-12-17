<?php

namespace App\Observers;

use App\Events\OrderSnapshotEvent;
use App\Models\Config;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use App\Models\UserAddress;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    /*Eloquent 的模型触发了几个事件，可以在模型的生命周期的以下几点进行监控：
    retrieved、creating、created、updating、updated、saving、saved、deleting、deleted、restoring、restored
    事件能在每次在数据库中保存或更新特定模型类时轻松地执行代码。*/

    /*当模型已存在，不是新建的时候，依次触发的顺序是:
    saving -> updating -> updated -> saved(不会触发保存操作)
    当模型不存在，需要新增的时候，依次触发的顺序则是:
    saving -> creating -> created -> saved(不会触发保存操作)*/

    public function created(Order $order)
    {
        // 开启事务
        DB::transaction(function () use ($order) {
            // 创建多条子订单OrderItem记录
            foreach ($order->snapshot as $item) {
                $itemData['order_id'] = $order->id;
                $itemData['product_sku_id'] = $item['sku_id'];
                $itemData['price'] = $item['price'];
                $itemData['number'] = $item['number'];
                OrderItem::create($itemData);
            }
        });

        event(new OrderSnapshotEvent($order));
    }
}
