<?php

namespace App\Listeners;

use App\Events\OrderSnapshotEvent;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

// class OrderSnapshotEventListener implements ShouldQueue
class OrderSnapshotEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderSnapshotEvent $event
     * @return void
     */
    public function handle(OrderSnapshotEvent $event)
    {
        // override order snapshot.
        $order = $event->getOrder();
        $order->snapshot = DB::transaction(function () use ($order) {
            $order_items = $order->items()->with('sku.product')->get()->toArray();
            return $order_items;
        });
        $order->save();
    }
}
