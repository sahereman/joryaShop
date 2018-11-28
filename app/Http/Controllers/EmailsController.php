<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class EmailsController extends Controller
{
    // POST Send|Resend an Email
    public function send(EmailVerificationCodeRequest $request)
    {
        $email = $request->input('email');
        if ($request->has('key')) {
            // resend email verification code
            $key = $request->input('key');
            if (Cache::has($key . '-sent')) {
                return response()->json([
                    'code' => 403,
                    'message' => 'Request too frequently',
                ]);
            }
        } else {
            // send email verification code
            $key = Uuid::uuid4()->getHex(); // Uuid类可以用来生成大概率不重复的字符串
        }
        $code = random_int(100000, 999999);
        $ttl = 10;
        Cache::set($key, $code, $ttl);
        // 60s内不允许重复发送邮箱验证码
        Cache::set($key . '-sent', true, 1);
        $mailMessage = new MailMessage();
        try {
            if (App::isLocale('en')) {
                $mailMessage->subject('Email Verification Code')
                    ->greeting('Dear Customer:')
                    ->line('Your Email Verification Code is:')
                    ->line($code)
                    ->line('Note: This verification code will be expired in ' . $ttl . 'minutes.')
                    ->line('-- From: Jorya Hair --');
            } else {
                $mailMessage->subject('邮箱验证码')
                    ->greeting('您好:')
                    ->line('您的邮箱验证码为:')
                    ->line($code)
                    ->line('该验证码将于' . $ttl . '分钟后失效。')
                    ->line('-- 来自：卓雅美业 --');
            }
        } catch (\Exception $e) {
            Log::error('Email Message Sending Failed: ' . $e->getMessage());
            return response()->json([
                'code' => $e->getCode(),
                'message' => 'Email Message Sending Failed: ' . $e->getMessage(),
            ]);
        }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'email' => $email,
                'key' => $key,
            ],
        ]);
    }

    // POST Verify an Email Verification Code With the Email
    public function verify(EmailVerificationCodeRequest $request)
    {
        $key = $request->input('key');
        $code = $request->input('code');
        if (Cache::has($key)) {
            if (Cache::get($key) == $code) {
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                    'data' => [
                        'code' => $code,
                    ],
                ]);
            }
            return response()->json([
                'code' => 400,
                'message' => 'Email verification code is wrong',
            ]);
        }
        return response()->json([
            'code' => 400,
            'message' => 'Email verification code is expired',
        ]);
    }
}
