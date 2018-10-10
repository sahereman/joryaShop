<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the userAddress.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\UserAddress $userAddress
     * @return mixed
     */
    public function update(User $user, UserAddress $userAddress)
    {
        return $user->id === $userAddress->user_id;
    }

    /**
     * Determine whether the user can delete the userAddress.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\UserAddress $userAddress
     * @return mixed
     */
    public function delete(User $user, UserAddress $userAddress)
    {
        return $user->id === $userAddress->user_id;
    }
}
