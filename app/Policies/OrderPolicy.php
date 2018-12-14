<?php

namespace App\Policies;

use App\Models\OrderRefund;
use App\Models\ProductComment;
use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        // 不可查看已删除订单
        if (!$order->deleted_at) {
            return $user->id === $order->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can pay the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function pay(User $user, Order $order)
    {
        /*if ($this->update($user, $order)) {
            return $order->status === Order::ORDER_STATUS_PAYING;
        }
        return false;*/
        return $this->update($user, $order);
    }

    /**
     * Determine whether the user can update the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can close the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function close(User $user, Order $order)
    {
        if ($this->update($user, $order)) {
            return $order->status === Order::ORDER_STATUS_PAYING;
        }
        return false;
    }

    /**
     * Determine whether the user can complete the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function complete(User $user, Order $order)
    {
        if ($this->update($user, $order)) {
            return $order->status === Order::ORDER_STATUS_RECEIVING;
        }
        return false;
    }

    /**
     * Determine whether the user can show comment of the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function show_comment(User $user, Order $order)
    {
        if ($this->update($user, $order)) {
            return $order->status === Order::ORDER_STATUS_COMPLETED;
        }
        return false;
    }

    /**
     * Determine whether the user can store comment of the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function store_comment(User $user, Order $order)
    {
        /*if ($this->update($user, $order) && $order->status === Order::ORDER_STATUS_COMPLETED) {
            return !ProductComment::where([
                'user_id' => $user->id,
                'order_id' => $order->id,
            ])->exists();
        }*/
        if ($this->update($user, $order)) {
            return $order->status === Order::ORDER_STATUS_COMPLETED;
        }
        return false;
    }

    /**
     * Determine whether the user can append comment of the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function append_comment(User $user, Order $order)
    {
        if ($this->update($user, $order) && $order->status === Order::ORDER_STATUS_COMPLETED) {
            return ProductComment::where([
                'user_id' => $user->id,
                'order_id' => $order->id,
            ])->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can refund the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function refund(User $user, Order $order)
    {
        /*
         * order status： shipping -> refund [仅退款]
         * */
        if ($this->update($user, $order)) {
            return in_array($order->status, [Order::ORDER_STATUS_SHIPPING, Order::ORDER_STATUS_REFUNDING]);
        }
        return false;
    }

    /**
     * Determine whether the user can refund the order with shipment.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function refund_with_shipment(User $user, Order $order)
    {
        /*
         * order status： receiving -> refund_with_shipment [退货并退款]
         * */
        if ($this->update($user, $order)) {
            return in_array($order->status, [Order::ORDER_STATUS_RECEIVING, Order::ORDER_STATUS_REFUNDING]);
        }
        return false;
    }

    /**
     * Determine whether the user can revoke the order refund.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function revoke_refund(User $user, Order $order)
    {
        if ($this->update($user, $order) && $order->status === Order::ORDER_STATUS_REFUNDING) {
            return !in_array($order->refund->status, [OrderRefund::ORDER_REFUND_STATUS_REFUNDED, OrderRefund::ORDER_REFUND_STATUS_DECLINED]);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the order.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        if (in_array($order->status, [Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_COMPLETED])) {
            return $user->id === $order->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can query the order's shipment information.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Order $order
     * @return mixed
     */
    public function shipment_query(User $user, Order $order)
    {
        if ($this->update($user, $order)) {
            return !in_array($order->status, [Order::ORDER_STATUS_PAYING, Order::ORDER_STATUS_CLOSED, Order::ORDER_STATUS_SHIPPING]);
        }
        return false;
    }
}
