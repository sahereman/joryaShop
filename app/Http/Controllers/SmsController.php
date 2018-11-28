<?php

namespace App\Http\Controllers;

use App\Http\Requests\SmsVerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class SmsController extends Controller
{
    // POST Send|Resend Sms Verification Code [for Ajax request]
    public function send(SmsVerificationCodeRequest $request)
    {
        // resend sms verification code
        $country_code = $request->input('country_code');
        $phone = $request->input('phone');
        if (Cache::has($country_code . '-' . $phone)) {
            return response()->json([
                'code' => 403,
                'message' => 'Request too frequently',
                'data' => [
                    'key' => Cache::get($country_code . '-' . $phone),
                ],
            ]);
        }
        // send|resend sms verification code
        $code = random_int(100000, 999999);
        $key = Uuid::uuid4()->getHex(); // Uuid类可以用来生成大概率不重复的字符串
        $data['code'] = $code;
        $response = easy_sms_send($data, $phone, $country_code);
        if ($response['aliyun']['status'] == 'success') {
            // 60s内不允许重复发送邮箱验证码
            Cache::set($country_code . '-' . $phone, $key, 1);
            $ttl = 10;
            Cache::set($key, $code, $ttl);
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'key' => $key,
                ],
                'response' => $response,
            ]);
        }
        return response()->json($response, 500);
    }

    // POST Verify Sms Verification Code With the Key [for Ajax request]
    public function verify(SmsVerificationCodeRequest $request)
    {
        $key = $request->input('key');
        $code = $request->input('code');
        if (Cache::has($key)) {
            if (Cache::get($key) == $code) {
                Cache::forget($key);
                return response()->json([
                    'code' => 200,
                    'message' => 'success',
                ]);
            }
            return response()->json([
                'code' => 400,
                'message' => 'Sms verification code is wrong',
            ]);
        }
        return response()->json([
            'code' => 400,
            'message' => 'Sms verification code is expired',
        ]);
    }
}
