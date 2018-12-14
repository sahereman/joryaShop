<?php

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\PhoneNumber;

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function is_wechat_browser()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * Generate An Image Url.
 * @param $image string image path or url.
 * @param $disk string image filesystem disk (options: local|public|cloud).
 * @return string image url.
 * */
function generate_image_url($image, $disk = 'public')
{
    // 如果 image 字段本身就已经是完整的 url 就直接返回
    if (Str::startsWith($image, ['http://', 'https://'])) {
        return $image;
    }
    return Storage::disk($disk)->url($image);
}

/**
 * Aliyun发送短信
 * [目前仅用于用户注册、登录、重置密码时发送验证码]
 * @param array $data 短信内容 format: ['code' => '888888']
 * @param string $phone_number 手机号码 eg. 18888888888.
 * @param string $country_code 国家|地区码 eg. 86.
 * @return array $response
 */
/**
 * Successful Response Demo:
 * $response = [
 * "aliyun" => [
 * "gateway" => "aliyun",
 * "status" => "success",
 * "result" => [
 * "Message" => "OK",
 * "RequestId" => "9DA6BFCE-8970-469E-84A3-19D76CC20FCB",
 * "BizId" => "138407340962667254^0",
 * "Code" => "OK",
 * ],
 * ],
 * ];
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
        $template = $config['domestic_template'];
    } else {
        $content = 'Your Verification Code is: ' . $data['code'];
        $template = $config['international_template'];
    }

    /*$response = $easy_sms->send($universal_phone_number, [
        'content' => $content,
        'template' => $template,
        'data' => $data,
    ], ['aliyun']);*/

    $response = [];
    try {
        $response = $easy_sms->send($universal_phone_number, [
            'content' => $content,
            'template' => $template,
            'data' => $data,
        ], ['aliyun']);
    } catch (\Exception $e) {
        /*$exceptions = $e->getExceptions();
        $aliyun_exception = $exceptions['aliyun'];
        info($exceptions['aliyun']);*/
        $aliyun_exception = $e->getException('aliyun');
        info($aliyun_exception);
        $response['aliyun'] = [
            'code' => 500,
            'message' => $aliyun_exception->getMessage(),
            'status' => 'Internal Server Error',
        ];
    }

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
    $order_ttl_message = '';
    switch ($type) {
        case \App\Models\Order::ORDER_STATUS_PAYING:
            // time_to_close_order 系统自动关闭订单时间 （单位：分钟）
            $ttl = strtotime($datetime) + \App\Models\Order::getSecondsToCloseOrder() - time();
            $minutes = floor($ttl / 60);
            $seconds = $ttl % 60;
            if ($minutes < 0 || $seconds < 0) {
                $order_ttl_message = "剩余0分0秒";
            } else {
                $order_ttl_message = "剩余{$minutes}分{$seconds}秒";
            }
            break;
        case \App\Models\Order::ORDER_STATUS_RECEIVING:
            // time_to_complete_order 系统自动确认订单时间 （单位：天）
            $ttl = strtotime($datetime) + \App\Models\Order::getSecondsToCompleteOrder() - time();
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
 * 快递鸟(kdniao.com) 即时查询API
 *
 * Request Method: HTTP POST
 * Content-Type:application/x-www-form-urlencoded;charset=utf-8
 * DataType: Json
 *
 * Url_for_development: http://sandboxapi.kdniao.cc:8080/kdniaosandbox/gateway/exterfaceInvoke.json
 * Url_for_production: http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx
 *
 * @param string $shipment_company 物流公司
 * @param string $shipment_sn 物流订单序列号
 * @param string $order_sn 订单序列号
 * @return array
 */
/**
 * Successful Response Demo:
 * $response = [
 * "LogisticCode" => "shipment_sn",
 * "ShipperCode" => "shipment_company",
 * "Traces" => [],
 * "State" => "0",
 * "Traces" => [
 * 0 => [
 * "AcceptStation" => "[四川成都得力H]【向俊如】已收件",
 * "AcceptTime" => "2018-10-28 18:04:36",
 * ],
 * 1 => [
 * "AcceptStation" => "快件已由[四川成都得力H]发往[成都分拨中心]",
 * "AcceptTime" => "2018-10-28 18:10:36",
 * ],
 * 2 => [
 * "AcceptStation" => "快件已到达[成都分拨中心],上一站是[龙泉一部]",
 * "AcceptTime" => "2018-10-28 22:09:20",
 * ],
 * 3 => [
 * "AcceptStation" => "快件已由[成都分拨中心]发往[武汉分拨中心]",
 * "AcceptTime" => "2018-10-28 22:11:44",
 * ],
 * ],
 * "State" => "2",
 * "EBusinessID" => "ebusiness_id",
 * "Reason" => "暂无轨迹信息",
 * "Success" => true,
 * ];
 */
function kdniao_shipment_query($shipment_company, $shipment_sn, $order_sn = '')
{
    // 其他物流公司 - 暂不支持
    if ($shipment_company == 'etc') {
        return [];
    }

    // 判断当前项目运行环境
    if (app()->environment('production')) {
        $ebusiness_id = config('kdniao.production.ebusiness_id', 'ebusiness_id');
        $api_key = config('kdniao.production.api_key', 'api_key');
        $request_url = config('kdniao.production.request_url', 'request_url');
    } else {
        $ebusiness_id = config('kdniao.development.ebusiness_id', 'ebusiness_id');
        $api_key = config('kdniao.development.api_key', 'api_key');
        $request_url = config('kdniao.development.request_url', 'request_url');
    }

    /*$ebusiness_id = config('kdniao.production.ebusiness_id', 'ebusiness_id');
    $api_key = config('kdniao.production.api_key', 'api_key');
    $request_url = config('kdniao.production.request_url', 'request_url');*/

    $data_type = 2; // json
    $charset = 'UTF-8';
    $request_data = json_encode([
        'OrderCode' => $order_sn,
        'ShipperCode' => $shipment_company,
        'LogisticCode' => $shipment_sn,
    ]);

    $data = [
        'EBusinessID' => $ebusiness_id,
        'RequestType' => '1002',
        'RequestData' => urlencode($request_data),
        'DataType' => $data_type,
    ];
    $data['DataSign'] = urlencode(base64_encode(md5($request_data . $api_key)));
    // $data['DataSign'] = kdniao_encrypt($request_data . $api_key);

    $guzzle_http_client = new GuzzleHttpClient([
        // Base URI is used with relative requests
        // 'base_uri' => $url,
        // You can set any number of default request options.
        'timeout' => config('kdniao.timeout', 3.0),
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ],
    ]);

    /*$response = $guzzle_http_client->request('POST', $request_url, [
        // 'json' => $data,
        // 'multipart' => $data,
        'form_params' => $data,
    ]);
    $response_body_content = json_decode($response->getBody()->getContents(), true);
    return isset($response_body_content['Traces']) ? $response_body_content['Traces'] : [];*/

    /*$response = kdniao_send_post_query($request_url, $data);
    $response_body_content = json_decode($response, true);
    return isset($response_body_content['Traces']) ? $response_body_content['Traces'] : [];*/

    try {
        $response = $guzzle_http_client->request('POST', $request_url, [
            // 'json' => $data,
            // 'multipart' => $data,
            'form_params' => $data,
        ]);
        $response_body_content = json_decode($response->getBody()->getContents(), true);
    } catch (\Exception $e) {
        Log::error('KDNiao Shipment Query Failed: order sn - ' . $order_sn . '; With Error Message: ' . $e->getMessage());
        return [];
    }
    /**
     * EBusinessID String [R] 用户 ID
     *
     * OrderCode String [O] 订单编号
     *
     * ShipperCode String [R] 快递公司编码
     *
     * LogisticCode String [R] 快递单号
     *
     * Success Bool [R] 成功与否 (true|false)
     *
     * Reason String [O] 失败原因
     *
     * State String [R] 物流状态:
     * 0 - 无轨迹
     * 1 - 已揽收
     * 2 - 在途中
     * 3 - 已签收
     * 4 - 问题件
     *
     * Traces.AcceptTime Date [R] 轨迹发生时间
     * Traces.AcceptStation String [R] 轨迹描述
     * Traces.Remark String [O] 轨迹状态描述
     */
    /*if (isset($response_body_content)) {
        if (isset($response_body_content['EBusinessID']) && $response_body_content['EBusinessID'] == $ebusiness_id
            && isset($response_body_content['ShipperCode']) && $response_body_content['ShipperCode'] == $shipment_company
            && isset($response_body_content['LogisticCode']) && $response_body_content['LogisticCode'] == $shipment_sn
            && isset($response_body_content['Success']) && $response_body_content['Success'] == true
        ) {
            // return $response_body_content['Traces'];
            return isset($response_body_content['Traces']) ? $response_body_content['Traces'] : [];
        }
    }*/
    if (isset($response_body_content) && isset($response_body_content['Success']) && $response_body_content['Success'] == true) {
        // return $response_body_content['Traces'];
        return isset($response_body_content['Traces']) ? $response_body_content['Traces'] : [];
    }
    return [];
}

/**
 * Json方式 查询订单物流轨迹
 */
function get_order_traces_by_json()
{
    $ebusiness_id = '';
    $api_key = '';
    $request_url = '';
    $request_data = "{'OrderCode':'','ShipperCode':'YTO','LogisticCode':'12345678'}";

    $data = array(
        'EBusinessID' => $ebusiness_id,
        'RequestType' => '1002',
        'RequestData' => urlencode($request_data),
        'DataType' => '2', // 2 - json: request & response in json.
    );
    $data['DataSign'] = kdniao_encrypt($request_data, $api_key);
    $result = kdniao_send_post_query($request_url, $data);

    //根据公司业务处理返回的信息......

    return $result;
}

/**
 *  post提交数据
 * @param  string $request_url 请求Url
 * @param  array $request_data 提交的数据
 * @return string url响应返回的html
 */
function kdniao_send_post_query($request_url, $request_data)
{
    /*$data_container = array();
    foreach ($request_data as $key => $value) {
        $data_container[] = sprintf('%s=%s', $key, $value);
    }
    $post_data = implode('&', $data_container);*/
    $post_data = http_build_query($request_data);
    $url_info = parse_url($request_url);
    if (empty($url_info['port'])) {
        $url_info['port'] = 80;
    }
    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
    $httpheader .= "Host:" . $url_info['host'] . "\r\n";
    $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
    $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
    $httpheader .= "Connection:close\r\n\r\n";
    $httpheader .= $post_data;
    $file_pointer = fsockopen($url_info['host'], $url_info['port']);
    fwrite($file_pointer, $httpheader);
    $response_content = "";
    $headerFlag = true;
    while (!feof($file_pointer)) {
        if (($header = @fgets($file_pointer)) && ($header == "\r\n" || $header == "\n")) {
            break;
        }
    }
    while (!feof($file_pointer)) {
        $response_content .= fread($file_pointer, 128);
    }
    fclose($file_pointer);

    return $response_content;
}

/**
 * 电商Sign签名生成
 * @param string $request_data 内容
 * @param string $api_key Appkey
 * @return string DataSign签名
 */
function kdniao_encrypt($request_data, $api_key)
{
    return urlencode(base64_encode(md5($request_data . $api_key)));
}

/**
 * Generate a qr_code through a qr_code_url.
 * @param string $qr_code_url
 * @param string $format options: ['png', 'eps', 'svg']
 * @param integer $size
 * @param string $encoding options: ['UTF-8', 'ISO-8859-1', ...]
 * @param string $errorCorrection options: ['L', 'M', 'Q', 'H']
 * @return string
 * Common usage:
 * <img src="{!! generate_qr_code($qr_code_url) !!}">
 */
function generate_qr_code($qr_code_url, $format = 'png', $size = 300, $encoding = 'UTF-8', $errorCorrection = 'H')
{
    return "data:image/png;base64, " . base64_encode(
        QrCode::format($format)->size($size)->encoding($encoding)->errorCorrection($errorCorrection)->generate($qr_code_url)
    );
}

function array_shift_assoc(array &$array_assoc)
{
    $first_key = array_keys($array_assoc)[0];
    $first_value = array_shift($array_assoc);
    return [
        $first_key => $first_value,
    ];
}

function array_unshift_assoc(array &$array_assoc, string $key, $value)
{
    $array_assoc = array_reverse($array_assoc, true);
    $array_assoc[$key] = $value;
    return array_reverse($array_assoc, true);
}
