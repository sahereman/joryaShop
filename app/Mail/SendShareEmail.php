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

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function build()
    {
        if ($this->product) {
            return $this->view('emails.share', [
                'product' => $this->product
            ]);
        }
        return false;
    }
}
