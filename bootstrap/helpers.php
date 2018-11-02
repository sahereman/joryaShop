<?php

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\Exception as EasySmsException;
use Overtrue\EasySms\Exceptions\GatewayErrorException;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
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
 *
 * @return array $response
 *
 * @throws \Overtrue\EasySms\Exceptions\InvalidArgumentException
 * @throws \Overtrue\EasySms\Exceptions\NoGatewayAvailableException
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

    try {
        $response = $easy_sms->send($universal_phone_number, [
            'content' => $content,
            'template' => $template,
            'data' => $data,
        ], ['aliyun']);
    } catch (GatewayErrorException $e) {
        $response = $e->getMessage();
    } catch (InvalidArgumentException $e) {
        $response = $e->getMessage();
    } catch (NoGatewayAvailableException $e) {
        // $response = $e->getMessage();
        $response = $e->getExceptions();
    } catch (EasySmsException $e) {
        $response = $e->getMessage();
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
    $post_data["customer"] = env('KUAIDI100_CUSTOMER', 'KUAIDI100_CUSTOMER');
    $key = env('KUAIDI100_KEY', 'KUAIDI100_KEY');
    // $post_data["param"] = '{"com":"' . $shipment_company . '","num":"' . $shipment_sn . '"}';
    $post_data["param"] = json_encode(['com' => $shipment_company, 'num' => $shipment_sn]);
    // $url = 'http://poll.kuaidi100.com/poll/query.do';
    $url = 'https://poll.kuaidi100.com/poll/query.do';
    // $url_backup = 'http://www.kuaidi100.com/query';
    $url_backup = 'https://www.kuaidi100.com/query';
    $post_data["sign"] = md5($post_data["param"] . $key . $post_data["customer"]);
    $post_data["sign"] = strtoupper($post_data["sign"]);

    $guzzle_http_client = new GuzzleHttpClient([
        // Base URI is used with relative requests
        // 'base_uri' => $url,
        // You can set any number of default request options.
        'timeout' => 3.0,
    ]);
    // dd(123);
    /*$response = $guzzle_http_client->request('GET', $url_backup, [
        'query' => [
            'type' => $shipment_company,
            'postid' => $shipment_sn,
        ],
    ]);
    dd($response->getBody()->getContents());*/

    try {
        $response = $guzzle_http_client->request('POST', $url, [
            // 'multiparts' => $post_data,
            'form_params' => $post_data,
        ]);
    } catch (ClientException $e) {
        /*echo Psr7\str($e->getRequest());
        if ($e->hasResponse()) {
            echo Psr7\str($e->getResponse());
        }*/
        $response = $guzzle_http_client->request('GET', $url_backup, [
            'query' => [
                'type' => $shipment_company,
                'postid' => $shipment_sn,
            ],
        ]);
    } catch (RequestException $e) {
        /*echo Psr7\str($e->getRequest());
        if ($e->hasResponse()) {
            echo Psr7\str($e->getResponse());
        }*/
        $response = $guzzle_http_client->request('GET', $url_backup, [
            'query' => [
                'type' => $shipment_company,
                'postid' => $shipment_sn,
            ],
        ]);
    }
    $response_body_content = json_decode($response->getBody()->getContents(), true);
    if ($response_body_content['result'] == false) {
        $response = $guzzle_http_client->request('GET', $url_backup, [
            'query' => [
                'type' => $shipment_company,
                'postid' => $shipment_sn,
            ],
        ]);
    }
    dd($response->getBody()->getContents());

    /*$o = "";
    foreach ($post_data as $k => $v) {
        $o .= "$k=" . urlencode($v) . "&"; // 默认UTF-8编码格式
    }
    $post_data = substr($o, 0, -1);*/
    $post_data = http_build_query($post_data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $result = curl_exec($ch);
    $data = str_replace("\"", '"', $result);
    return json_decode($data, true);
}

/**
 * Generate An Image Url.
 * @param $image string image path or url.
 * @return string image url.
 * */
function generate_image_url($image)
{
    // 如果 image 字段本身就已经是完整的 url 就直接返回
    if (Str::startsWith($image, ['http://', 'https://'])) {
        return $image;
    }
    return Storage::disk('public')->url($image);
}

/**
 * 快递鸟 即时查询API
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
function kdniao_shipment_query($shipment_company, $shipment_sn, $order_sn = '')
{
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

    $ebusiness_id = config('kdniao.production.ebusiness_id', 'ebusiness_id');
    $api_key = config('kdniao.production.api_key', 'api_key');
    $request_url = config('kdniao.production.request_url', 'request_url');

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

    $guzzle_http_client = new GuzzleHttpClient([
        // Base URI is used with relative requests
        // 'base_uri' => $url,
        // You can set any number of default request options.
        'timeout' => config('kdniao.timeout'),
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ],
    ]);

    $response = $guzzle_http_client->request('POST', $request_url, [
        // 'json' => $data,
        // 'json' => $data,
        // 'multipart' => $data,
        'form_params' => $data,
    ]);

    // $response_body_content = json_decode($response->getBody()->getContents(), true);
    // dd($response->getBody()->getContents());

    $response = sendPost($request_url, $data);
    dd($response);

    try {
        $response = $guzzle_http_client->request('POST', $request_url, [
            // 'json' => $data,
            // 'multipart' => $data,
            'form_params' => $data,
        ]);
        $response_body_content = json_decode($response->getBody()->getContents(), true);
        dd($response->getBody()->getContents());
    } catch (ClientException $e) {
        echo Psr7\str($e->getRequest());
        if ($e->hasResponse()) {
            echo Psr7\str($e->getResponse());
        }
    } catch (RequestException $e) {
        echo Psr7\str($e->getRequest());
        if ($e->hasResponse()) {
            echo Psr7\str($e->getResponse());
        }
    }
}

/**
 * Json方式 查询订单物流轨迹
 */
function getOrderTracesByJson(){
    $requestData= "{'OrderCode':'','ShipperCode':'YTO','LogisticCode':'12345678'}";

    $datas = array(
        'EBusinessID' => EBusinessID,
        'RequestType' => '1002',
        'RequestData' => urlencode($requestData) ,
        'DataType' => '2',
    );
    $datas['DataSign'] = encrypt($requestData, AppKey);
    $result=sendPost(ReqURL, $datas);

    //根据公司业务处理返回的信息......

    return $result;
}

/**
 *  post提交数据
 * @param  string $url 请求Url
 * @param  array $datas 提交的数据
 * @return url响应返回的html
 */
function sendPost($url, $datas) {
    $temps = array();
    foreach ($datas as $key => $value) {
        $temps[] = sprintf('%s=%s', $key, $value);
    }
    $post_data = implode('&', $temps);
    $url_info = parse_url($url);
    if(empty($url_info['port']))
    {
        $url_info['port']=80;
    }
    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
    $httpheader.= "Host:" . $url_info['host'] . "\r\n";
    $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
    $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
    $httpheader.= "Connection:close\r\n\r\n";
    $httpheader.= $post_data;
    $fd = fsockopen($url_info['host'], $url_info['port']);
    fwrite($fd, $httpheader);
    $gets = "";
    $headerFlag = true;
    while (!feof($fd)) {
        if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
            break;
        }
    }
    while (!feof($fd)) {
        $gets.= fread($fd, 128);
    }
    fclose($fd);

    return $gets;
}

/**
 * 电商Sign签名生成
 * @param string $data 内容
 * @param $appkey Appkey
 * @return DataSign签名
 */
/*function encrypt($data, $appkey) {
    return urlencode(base64_encode(md5($data.$appkey)));
}*/
