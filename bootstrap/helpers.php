<?php
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\PhoneNumber;

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

/**
 * Aliyun发送短信
 * @param array $data 短信内容 format: ['code' => '888888']
 * @param string $phone_number 手机号码 eg. 18888888888.
 * @param string $country_code 国家|地区码 eg. 86.
 * @return boolean
 */
function easy_sms_send($data, $phone_number, $country_code = '86')
{
    // get a universal phone number.
    $universal_phone_number = new PhoneNumber($phone_number, $country_code);

    $config = config('easysms');
    $easy_sms = new EasySms($config);

    $template = $config['template'] ? : env('ALIYUN_SMS_TEMPLATE', '');
    $response = $easy_sms->send($universal_phone_number, [
        'content' => '您的验证码为：' . $data['code'],
        'template' => $template,
        'data' => $data,
    ]);

    return $response;
}
