<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Mail\SendOrderEmail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderPaidEventListener
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
     * @param  OrderPaidEvent $event
     * @return void
     */
    public function handle(OrderPaidEvent $event)
    {
        $order = $event->getOrder();
        $order->update([
            'status' => Order::ORDER_STATUS_SHIPPING
        ]);
        try {
            if ($order->email) {
                $user = new User();
                $user->email = $order->email;
                $subject = 'Thanks for shopping at LYRICALHAIR.com';
                Mail::to($user)->queue(new SendOrderEmail($order, $subject));
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
