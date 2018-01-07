<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/19
 * Time: 11:12
 */
//检测设备是否为手机
if(!function_exists('isMobile')){

    function isMobile(){

        $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
        $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
        $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
        $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||
            CheckSubstrs($mobile_token_list,$useragent);
        if ($found_mobile){
            return true;
        }else{
            return false;
        }
    }
}

if(!function_exists('CheckSubstrs')){
    function CheckSubstrs($substrs,$text){
        foreach($substrs as $substr)
            if(false!==strpos($text,$substr)){
                return true;
            }
        return false;
    }
}


//随机字符串
if(!function_exists('randStr')){
    function randStr($len,$num = false)
    {
        $str = "";
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        if($num){
            $strPol = "1234567890";
        }
        $max = strlen($strPol)-1;

        for($i=0;$i<$len;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
}


/**新增U函数
 * @param $url
 * @param array $args
 * @return string
 */
if(!function_exists('u')){
    function u($url, $args = array()){
        $url = URL::to($url) . (count($args) > 0 ? '?' : '') . http_build_query($args);
        URL::forceRootUrl('');
        return $url;
    }
}


if( !function_exists('guid') ){

    function guid(){

        if (function_exists ( 'com_create_guid' )) {
            return com_create_guid ();
        } else {
            mt_srand ( ( double ) microtime () * 10000 ); //optional for php 4.2.0 and up.随便数播种，4.2.0以后不需要了。
            $charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) ); //根据当前时间（微秒计）生成唯一id.
            $hyphen = chr ( 45 ); // "-"
            $uuid = '' . //chr(123)// "{"
                substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 );
            //.chr(125);// "}"
            return $uuid;
        }
    }

}

/**

 * @desc 根据两点间的经纬度计算距离

 * @param float $lat 纬度值

 * @param float $lng 经度值

 */
if(!function_exists('getDistance')){
    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000; //近似地球半径

        /**

         * 将这些数据转换为弧度

         * 与公式一起工作

         **/

        $lat1 = ($lat1 * pi()) / 180;

        $lng1 = ($lng1 * pi()) / 180;


        $lat2 = ($lat2 * pi()) / 180;

        $lng2 = ($lng2 * pi()) / 180;


        $calcLongitude = $lng2 - $lng1;

        $calcLatitude = $lat2 - $lat1;

        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);

        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));

        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }

}
