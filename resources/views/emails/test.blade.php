Dear {{ $user }},

    This is a test mail from {{ $company }}.

<br>
<br>

    Yours, {{ $sender }}.

<br>
<br>

    {{ \Illuminate\Support\Carbon::now()->toDateTimeString() }}

<br>
<br>

    Thanks for your visit @ {{ $website }}.

{{--
    \Illuminate\Support\Facades\Mail::send('emails.test', [
        'user' => 'Elijah',
        'sender' => 'Lyrical',
        'company' => 'Lyrical Hair',
        'website' => 'lyrical.joryahair.com',
    ], function ($mailer) {
        $mailer->from('1622980477@qq.com', 'Lyrical Hair');
        $mailer->to('1198331919@qq.com', 'Elijah')->subject('Test Mail from Lyrical Hair');
    });
--}}
