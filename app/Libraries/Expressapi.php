<?php
namespace App\Libs;


//快递接口类
class Expressapi
{
    public static $EBusinessID;
    public static $AppKey;
    public static $ReqURL;

    public function __construct($EBusinessID, $AppKey)
    {
        self::$EBusinessID = $EBusinessID;
        self::$AppKey = $AppKey;
        self::$ReqURL = 'http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx';
//        self::$ReqURL = 'http://api.kdniao.cc/api/dist';
//        self::$ReqURL = 'http://testapi.kdniao.cc:8081/api/dist';
    }

    /** Json方式 查询订单物流轨迹
     * @param $express    快递公司代码  HHTT
     * @param $code       快递单号   666810990803
     * @return \url响应返回的html
     */
    public static function getOrderTracesByJson($express, $code)
    {
        $requestData = "{'OrderCode':'','ShipperCode':'$express','LogisticCode':'$code'}";

        $datas = array(
            'EBusinessID' => self::$EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = self::encrypt($requestData, self::$AppKey);
        $result = self::sendPost(self::$ReqURL, $datas);

        //根据公司业务处理返回的信息......
        $result = json_decode($result);
        return self::Handle($result);
    }

    //后续数据处理
    public static function Handle($result)
    {
        if ($result->Success) {
            $return['code'] = true;
            if (!empty($result->Traces)) {
                $return['data'] = $result->Traces;
            } else {
                $return['data'] = $result->Reason;
            }
        } else {
            $return = array('code' => false, 'data' => $result->Reason);
        }
        return $return;
    }


    /**
     *  post提交数据
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据
     * @return url响应返回的html
     */
    public static function sendPost($url, $datas)
    {

        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
//	if($url_info['port']=='')
//	{
        $url_info['port'] = 80;
//	}
        //echo $url_info['port'];
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        $httpheader .= $post_data;
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
            $gets .= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容
     * @param appkey Appkey
     * @return DataSign签名
     */
    public static function encrypt($data, $appkey)
    {
        return urlencode(base64_encode(md5($data . $appkey)));
    }
}