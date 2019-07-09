<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendTemplateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_template;

    public function __construct(EmailTemplate $emailTemplate)
    {
        $this->email_template = $emailTemplate;
    }

    public function build()
    {
        if (empty($this->email_template->attachments))
        {
            return $this->view('emails.template', [
                'email_template' => $this->email_template
            ])->subject($this->email_template->name);

        } else
        {
            $ste = $this->view('emails.template', [
                'email_template' => $this->email_template
            ])->subject($this->email_template->name);
            foreach ($this->email_template->attachments as $item)
            {
                $ste->attach(Storage::disk('public')->path($item));
            }

            return $ste;
        }

    }
}
