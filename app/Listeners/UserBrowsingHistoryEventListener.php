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
     * @param  UserBrowsingHistoryEvent $event
     * @return void
     */
    public function handle(UserBrowsingHistoryEvent $event)
    {
        // creating history records ...
        $user = $event->getUser();
        $user_browsing_history_list_stored = [];
        if (Cache::has($user->id . '-user_browsing_history_count') && Cache::has($user->id . '-user_browsing_history_list') && Cache::has($user->id . '-user_browsing_history_list_stored')) {
            if (Cache::get($user->id . '-user_browsing_history_count') > 0 && Cache::get($user->id . '-user_browsing_history_list') !== []) {
                $user_browsing_history_list = Cache::get($user->id . '-user_browsing_history_list');
                $user_browsing_history_list_stored = Cache::get($user->id . '-user_browsing_history_list_stored');
                foreach ($user_browsing_history_list as $browsed_at => $product_ids) {
                    foreach ($product_ids as $product_id) {
                        $user_browsing_history['user_id'] = $user->id;
                        $user_browsing_history['product_id'] = $product_id;
                        $user_browsing_history['browsed_at'] = $browsed_at;
                        UserHistory::create($user_browsing_history);
                    }
                    $user_browsing_history_list_stored[$browsed_at] = array_merge($user_browsing_history_list_stored[$browsed_at], $user_browsing_history_list[$browsed_at]);
                }
            }
        }
        // clear or refresh cache ...
        if ($event->isLoggedOut()) {
            Cache::forget($user->id . '-user_browsing_history_count');
            Cache::forget($user->id . '-user_browsing_history_list');
            Cache::forget($user->id . '-user_browsing_history_list_stored');
        } else {
            // refresh cache ...
            Cache::forever($user->id . '-user_browsing_history_count', 0);
            Cache::forever($user->id . '-user_browsing_history_list', [
                today()->toDateString() => [],
            ]);
            Cache::forever($user->id . '-user_browsing_history_list_stored', $user_browsing_history_list_stored);
        }
    }
}
