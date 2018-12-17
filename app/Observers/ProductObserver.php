<?php

namespace App\Observers;


use App\Models\Product;

class ProductObserver
{
    /*Eloquent 的模型触发了几个事件，可以在模型的生命周期的以下几点进行监控：
    retrieved、creating、created、updating、updated、saving、saved、deleting、deleted、restoring、restored
    事件能在每次在数据库中保存或更新特定模型类时轻松地执行代码。*/

    /*当模型已存在，不是新建的时候，依次触发的顺序是:
    saving -> updating -> updated -> saved(不会触发保存操作)
    当模型不存在，需要新增的时候，依次触发的顺序则是:
    saving -> creating -> created -> saved(不会触发保存操作)*/

    public function created(Product $product)
    {
        //
    }

    public function saving(Product $product)
    {
        // 缩略图
        if (!empty($product->photos))
        {
            $product->thumb = $product->photos[0];
        } else
        {
            $product->thumb = asset('defaults/default_product.jpeg');
            $product->photos = [asset('defaults/default_product.jpeg')];
        }

        /*dd($product->skus()->newQuery()->where('product_id', $product->id)
            ->min('price'));

        $product->price = $product->skus()->newQuery()->where('product_id', $product->id)
            ->min('price'); // 生成商品价格 - 最低SKU价格


        $product->price = $product->skus()->min('price'); // 生成商品价格 - 最低SKU价格
        $product->stock = $product->skus()->sum('stock'); // 生成商品库存 - 求和SKU库存*/
    }

    /**
     * @param Product $product
     * @throws \Exception
     * @throws \Throwable
     */
    public function saved(Product $product)
    {
        /*$product->price = $product->skus()->min('price'); // 生成商品价格 - 最低SKU价格
        $product->stock = $product->skus()->sum('stock'); // 生成商品库存 - 求和SKU库存
        $product->update([
            'price' => $product->skus()->min('price'),
            'stock' => $product->skus()->sum('stock'),
        ]);

        $skus = ProductSku::where('product_id', $product->id)->get();

        DB::transaction(function () use ($skus, $product) {
            DB::table('products')->where('id', $product->id)->update([
                'price' => $skus->min('price'),
                'stock' => $skus->sum('stock'),
            ]);
        });

        $product->update([
            'price' => '11',
        ]);*/
    }

    public function deleted(Product $product)
    {
        //
    }
}
