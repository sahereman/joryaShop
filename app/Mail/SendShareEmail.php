<?php

namespace App\Mail;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendShareEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $product = false;
    protected $from_email = '';
    // public $subject = '';
    protected $body = '';

    public function __construct(Product $product, $from_email, $subject, $body)
    {
        $this->product = $product;
        $this->from_email = $from_email;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function build()
    {
        if ($this->product) {
            return $this->subject($this->subject)->view('emails.share', [
                'product' => $this->product,
                'from_email' => $this->from_email,
                'body' => $this->body
            ]);
        }
        return false;
    }
}
