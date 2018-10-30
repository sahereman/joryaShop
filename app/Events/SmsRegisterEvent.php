<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SmsRegisterEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $country_code = 86;
    protected $phone_number;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($country_code, $phone_number)
    {
        $this->country_code = $country_code;
        $this->phone_number = $phone_number;
    }

    public function getCountryCode()
    {
        return $this->country_code;
    }

    public function getPhoneNumber()
    {
        return $this->phone_number;
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
