<?php

namespace App\Listeners;

use App\Events\OrderSnapshotEvent;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderSnapshotEventListener implements ShouldQueue
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
        $order->snapshot = $order->items()->with('sku.product')->get()->toJson();
        $order->save();
    }
}
