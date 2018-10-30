<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\PhoneNumber;

class SmsChannel
{
    protected $data;
    protected $country_code = 86;
    protected $phone_number;

    public function __construct($data, $phone_number, $country_code)
    {
        $this->data = $data;
        $this->country_code = $country_code;
        $this->phone_number = $phone_number;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     *
     * @return array $response
     *
     * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
     * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);
        $this->data['code'] = $message['code'];
        if (isset($message['content'])) {
            $this->data['content'] = $message['content']
        };

        // Send notification to the $notifiable instance...

        $phone_code = $notifiable->routeNotificationFor('sms');
        $this->country_code = $phone_code['country_code'];
        $this->phone_number = $phone_code['phone_number'];

        // get a universal phone number.
        $universal_phone_number = new PhoneNumber($this->phone_number, $this->country_code);

        $config = config('easysms');
        $easy_sms = new EasySms($config);

        // 国家场景判断
        if ($this->country_code == '86') {
            if(isset($this->data['content'])){
                $this->data['content'] = '您的验证码为：' . $this->data['code'];
            }
            $template = $config['domestic_template'] ?: env('ALIYUN_SMS_DOMESTIC_TEMPLATE', '');
        } else {
            if(isset($this->data['content'])){
                $this->data['content'] = 'Your Verification Code is: ' . $this->data['code'];
            }
            $template = $config['international_template'] ?: env('ALIYUN_SMS_INTERNATIONAL_TEMPLATE', '');
        }

        $response = $easy_sms->send($universal_phone_number, [
            'content' => $this->data['content'],
            'template' => $template,
            'data' => $this->data,
        ]);

        return $response;
    }
}
