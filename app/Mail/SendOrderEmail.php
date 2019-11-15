<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

// class SendOrderEmail extends Mailable
class SendOrderEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $order = false;
    // public $subject = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, string $subject = '')
    {
        $this->order = $order;
        if ($subject !== '') {
            $this->subject = $subject;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        if ($this->order) {
            return $this->subject($this->subject)->view('emails.order', [
                'order' => $this->order
            ]);
        }
        return false;
    }
}
