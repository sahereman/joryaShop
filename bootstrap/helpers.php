<?php
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\PhoneNumber;

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

/**
 * Aliyun发送短信
 * [目前仅用于用户注册、登录、重置密码时发送验证码]
 * @param array $data 短信内容 format: ['code' => '888888']
 * @param string $phone_number 手机号码 eg. 18888888888.
 * @param string $country_code 国家|地区码 eg. 86.
 * @return boolean
 */
function easy_sms_send($data, $phone_number, $country_code)
{
    // get a universal phone number.
    $universal_phone_number = new PhoneNumber($phone_number, $country_code);

    $config = config('easysms');
    $easy_sms = new EasySms($config);

    // 国家场景判断
    if ($country_code == '86') {
        $content = '您的验证码为：' . $data['code'];
        $template = $config['domestic_template'] ?: env('ALIYUN_SMS_DOMESTIC_TEMPLATE', '');
    } else {
        $content = 'Your Verification Code is: ' . $data['code'];
        $template = $config['international_template'] ?: env('ALIYUN_SMS_INTERNATIONAL_TEMPLATE', '');
    }

    $response = $easy_sms->send($universal_phone_number, [
        'content' => $content,
        'template' => $template,
        'data' => $data,
    ]);

    return $response;
}

/**
 * Generate order ttl message
 * @param string $datetime 'Y-m-d H:i:s' formatted datetime string
 * @param string $type order status type enum('paying', 'receiving')
 * @return string
 * */
function generate_order_ttl_message($datetime, $type)
{
    $timestamp = time() - strtotime($datetime);
    $order_ttl_message = '';
    switch ($type) {
        case \App\Models\Order::ORDER_STATUS_PAYING:
            $ttl = (int)(\App\Models\Config::config('time_to_close_order')) * 3600 - $timestamp;
            $minutes = floor($ttl / 60);
            $seconds = $ttl % 60;
            if ($minutes < 0 || $seconds < 0) {
                $order_ttl_message = "剩余0分0秒";
            } else {
                $order_ttl_message = "剩余{$minutes}分{$seconds}秒";
            }
            break;
        case \App\Models\Order::ORDER_STATUS_RECEIVING:
            $ttl = (int)(\App\Models\Config::config('time_to_complete_order')) * 3600 * 24 - $timestamp;
            $days = ceil($ttl / (3600 * 24));
            if ($days < 0) {
                $order_ttl_message = "剩余0天";
            } else {
                $order_ttl_message = "剩余{$days}天";
            }
            break;
        default:
            break;
    }
    return $order_ttl_message;
}

/**
 * 快递100 实时查询API
 * @param string $shipment_company 物流公司
 * @param string $shipment_sn 物流订单序列号
 * @return array
 *
 * demo:
 * //参数设置
 * $post_data = array();
 * $post_data["customer"] = '*****';
 * $key= '*****' ;
 * $post_data["param"] = '{"com":"*****","num":"*****"}';
 *
 * $url='http://poll.kuaidi100.com/poll/query.do';
 * $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
 * $post_data["sign"] = strtoupper($post_data["sign"]);
 * $o="";
 * foreach ($post_data as $k=>$v)
 * {
 * $o.= "$k=".urlencode($v)."&"; // 默认UTF-8编码格式
 * }
 * $post_data=substr($o,0,-1);
 * $ch = curl_init();
 * curl_setopt($ch, CURLOPT_POST, 1);
 * curl_setopt($ch, CURLOPT_HEADER, 0);
 * curl_setopt($ch, CURLOPT_URL,$url);
 * curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
 * $result = curl_exec($ch);
 * $data = str_replace("\"",'"',$result );
 * $data = json_decode($data,true);
 * */
function shipment_query($shipment_company, $shipment_sn)
{
    // 其他物流公司 - 暂不支持
    if ($shipment_company == 'etc') {
        return false;
    }

    $post_data = array();
    $post_data["customer"] = env('KUAIDI100_CUSTOMER');
    $key = env('KUAIDI100_KEY');
    $post_data["param"] = json_encode(['com' => $shipment_company, 'num' => $shipment_sn]);

    $url = 'http://poll.kuaidi100.com/poll/query.do';
    $post_data["sign"] = md5($post_data["param"] . $key . $post_data["customer"]);
    $post_data["sign"] = strtoupper($post_data["sign"]);
    $o = "";
    foreach ($post_data as $k => $v) {
        $o .= "$k=" . urlencode($v) . "&"; // 默认UTF-8编码格式
    }
    $post_data = substr($o, 0, -1);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $result = curl_exec($ch);
    $data = str_replace("\"", '"', $result);
    return json_decode($data, true);
}
