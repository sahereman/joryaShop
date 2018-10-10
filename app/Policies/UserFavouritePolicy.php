<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserFavourite;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserFavouritePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the userFavourite.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserFavourite  $userFavourite
     * @return mixed
     */
    public function delete(User $user, UserFavourite $userFavourite)
    {
        return $user->id === $userFavourite->user_id;
    }
}
