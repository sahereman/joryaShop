<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class OrderItemObserver
{
    /*Eloquent 的模型触发了几个事件，可以在模型的生命周期的以下几点进行监控：
    retrieved、creating、created、updating、updated、saving、saved、deleting、deleted、restoring、restored
    事件能在每次在数据库中保存或更新特定模型类时轻松地执行代码。*/

    /*当模型已存在，不是新建的时候，依次触发的顺序是:
    saving -> updating -> updated -> saved(不会触发保存操作)
    当模型不存在，需要新增的时候，依次触发的顺序则是:
    saving -> creating -> created -> saved(不会触发保存操作)*/

    public function created(OrderItem $orderItem)
    {
        // 定制商品忽略库存变动
        if ($orderItem->sku->product->type != Product::PRODUCT_TYPE_CUSTOM) {
            // 更新 Sku -库存
            // $orderItem->sku->decrement('stock', $orderItem->number);

            // 更新 Product -库存 & +综合指数 & +人气|热度
            // $orderItem->sku->product->decrement('stock', $orderItem->number);
        }

        $orderItem->sku->product->increment('index', $orderItem->number);
        $orderItem->sku->product->increment('heat');

        // flush product_sku_attr_value_cache
        if (Cache::has($orderItem->sku->product->id . 'product_sku_attr_value_cache')) {
            Cache::forget($orderItem->sku->product->id . 'product_sku_attr_value_cache');
        }
    }
}
