<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerificationCodeRequest;
use App\Notifications\EmailVerificationCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

// class EmailsController extends Controller implements ShouldQueue
class EmailsController extends Controller
{
    use Notifiable;

    protected $email;
    protected $code;
    protected $key;
    protected $ttl = 10;

    public function getEmail()
    {
        return $this->email;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getTtl()
    {
        return $this->ttl;
    }

    // POST Send|Resend Email Verification Code
    public function send(EmailVerificationCodeRequest $request)
    {
        $this->email = $request->input('email');
        if ($request->has('key')) {
            // resend email verification code
            $this->key = $request->input('key');
            if (Cache::has($this->key . '-sent')) {
                return response()->json([
                    'code' => 403,
                    'message' => 'Request too frequently',
                    'data' => [
                        'key' => $this->key,
                    ],
                ]);
            }
        }
        // send email verification code
        $this->key = Uuid::uuid4()->getHex(); // Uuid类可以用来生成大概率不重复的字符串
        $this->code = random_int(100000, 999999);
        // $this->ttl = 10;
        try {
            $this->notify(new EmailVerificationCodeNotification());
            Cache::set($this->key, $this->code, $this->ttl);
            // 60s内不允许重复发送邮箱验证码
            Cache::set($this->key . '-sent', true, 1);
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
                'key' => $this->key,
            ],
        ]);
    }

    // POST Verify Email Verification Code With the Key
    public function verify(EmailVerificationCodeRequest $request)
    {
        $this->key = $request->input('key');
        $this->code = $request->input('code');
        if (Cache::has($this->key)) {
            if (Cache::get($this->key) == $this->code) {
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
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
