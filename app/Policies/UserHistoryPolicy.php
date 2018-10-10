<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserHistory;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserHistoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the userHistory.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\UserHistory $userHistory
     * @return mixed
     */
    public function delete(User $user, UserHistory $userHistory)
    {
        return $user->id === $userHistory->user_id;
    }
}
