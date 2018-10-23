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
 *        string $country_code 国家|地区码 eg. 86.
 * @return boolean
 */
function easy_sms_send($data, $phone_number)
{
    // get a universal phone number.
    // $universal_phone_number = new PhoneNumber($phone_number, $country_code);

    $config = config('easysms');
    $easy_sms = new EasySms($config);

    $template = $config['template'] ?: env('ALIYUN_SMS_TEMPLATE', '');
    $response = $easy_sms->send($phone_number, [
        'content' => '您的验证码为：' . $data['code'],
        'template' => $template,
        'data' => $data,
    ]);

    return $response;
}

function generate_order_ttl_message($datetime, $type)
{
    $timestamp = strtotime($datetime);
    $order_ttl_message = '';
    switch ($type) {
        case \App\Models\Order::ORDER_STATUS_PAYING:
            $ttl = \App\Models\Config::config('time_to_close_order') - $timestamp;
            $minutes = floor($ttl/60);
            $seconds = $ttl%60;
            $order_ttl_message = "剩余{$minutes}分{$seconds}秒";
            break;
        case \App\Models\Order::ORDER_STATUS_RECEIVING:
            $ttl = \App\Models\Config::config('time_to_complete_order') * 3600 * 24 - $timestamp;
            $days = ceil($ttl/(3600*24));
            $order_ttl_message = "剩余{$days}天";
            break;
        default:
            break;
    }
    return $order_ttl_message;
}
