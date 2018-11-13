<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserBrowsingHistoryEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $user;
    protected $isLoggedOut;

    /**
     * Create a new event instance.
     * @param \App\Models\User $user
     * @param boolean $isLoggedOut
     * @return void
     */
    public function __construct(User $user, $isLoggedOut = false)
    {
        $this->user = $user;
        $this->isLoggedOut = $isLoggedOut;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function isLoggedOut()
    {
        return $this->isLoggedOut;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
