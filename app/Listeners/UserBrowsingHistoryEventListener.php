<?php

namespace App\Listeners;

use App\Events\UserBrowsingHistoryEvent;
use App\Models\UserHistory;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class UserBrowsingHistoryEventListener implements ShouldQueue
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
     * @param  UserBrowsingHistoryEvent  $event
     * @return void
     */
    public function handle(UserBrowsingHistoryEvent $event)
    {
        // creating history records ...
        $user = $event->getUser();
        if (Cache::has($user->id . '-user_browsing_history_count') && Cache::has($user->id . '-user_browsing_history_list')) {
            if (Cache::get($user->id . '-user_browsing_history_count') > 0 && Cache::get($user->id . '-user_browsing_history_list') !== []) {
                $user_browsing_history_list = Cache::get($user->id . '-user_browsing_history_list');
                foreach ($user_browsing_history_list as $user_browsing_history) {
                    $user_browsing_history['user_id'] = $user->id;
                    UserHistory::create($user_browsing_history);
                }
            }
        }
        // clear or refresh cache ...
        if ($event->isLoggedOut()) {
            Cache::forget($user->id . '-user_browsing_history_count');
            Cache::forget($user->id . '-user_browsing_history_list');
        } else {
            Cache::forever($user->id . '-user_browsing_history_count', 0);
            Cache::forever($user->id . '-user_browsing_history_list', []);
        }
    }
}
