<?php

namespace App\Policies;

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
        if(! $order->deleted_at){
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
        if ($this->update($user, $order)) {
            return $order->status === 'paying';
        }
        return false;
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
            return $order->status === 'paying';
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
            return $order->status === 'receiving';
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
        if ($this->update($user, $order)) {
            return $order->status === 'receiving';
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
        if(in_array($order->status, ['closed', 'completed'])){
            return $user->id === $order->user_id;
        }
        return false;
    }
}
