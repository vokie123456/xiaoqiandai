<?php

namespace App\Models;

use Session,DB;

class WxConfig extends Base
{

   //获取配置信息
    public function getWxConfig(){
      $result =  Config::where("mold","WECHAT")->get();
      $wxconfig = new \stdClass();
      foreach ($result as $item){
          switch ($item->name){
              case "APPID":
                  $wxconfig->app_id = $item->content;
                  break;
              case "APPSECRET":
                  $wxconfig->appsecret = $item->content;
                  break;
              case "BACCESS_TOKEN":
                  $wxconfig->access_token = $item->content;
                  $wxconfig->geted_at = $item->created_at;
                  break;
              case "JSAPI_TICKET":
                  $wxconfig->jsapi_ticket = $item->content;
                  $wxconfig->js_gettime = $item->created_at;
                  break;
              case "MCH_ID":
                  $wxconfig->mch_id = $item->content;
                  break;
              case "PAY_KEY":
                  $wxconfig->pay_key = $item->content;
                  break;
          }
      }
      return $wxconfig;
    }

    //get方式的curl
    public function getCurl($url){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        //     curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }
    //授权登录
    public  function getauthToken($code){
        $result = $this->getWxConfig();
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$result->app_id}&secret={$result->appsecret}&code={$code}&grant_type=authorization_code";
        return $this->getCurl($url);
    }

    //获取token
    public function getToken(){
        $config = $this->getWxConfig();
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$config->app_id."&secret=".$config->appsecret;
        return $this->getCurl($url);
    }
    //检测 ,刷新token
    public function flashToken(){
        $result = $this->getWxConfig();
        if( empty($result->access_token) || (abs($result->geted_at - time()) >= 7000)  ){
            $getToken = $this->getToken();
            $getToken = json_decode($getToken);
            if(!empty($getToken->access_token)){
                $res = Config::where("name","BACCESS_TOKEN")->update([
                    "content" => $getToken->access_token,
                    "created_at" => time()
                ]);
                if($res == 0){
                    return ["code" => 999,"msg" => "保存失败"];
                }
            }else{
                return ["code" => 999,"msg" => "获取token失败"];
            }

            $token = $getToken->access_token;
        }else{
            $token = $result->access_token;
        }

        return ["code" => 0,"token" => $token];
    }
    //获取关注列表
    public function getNoticerList(){
        $res = $this->flashToken();
        if($res["code"] == 0){
            $token = $res["token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=".$token;
        return $this->getCurl($url);
    }
    //获取用户的基本信信息
    public function getInfoByOpenid($openid){
        $res = $this->flashToken();
        if($res["code"] == 0){
            $token = $res["token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openid."&lang=zh_CN";
        return $this->getCurl($url);
    }
    public function postUrl($url,$post_data){
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);

        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }



    //获取场景二维码
    public function getQrcode($scene_id){
        $post_data = array(
             "action_name" => "QR_LIMIT_SCENE",
             "action_info" => [
             "scene" => ["scene_id" => $scene_id]
            ]
        );
        $post_data = json_encode($post_data);
        $result = $this->flashToken();
        if($result["code"] == 0){
            $token = $result["token"];
        }

        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
        $getticket =  $this->postUrl($url,$post_data);
        $getticket = json_decode($getticket,true);
        $ticket = urlencode($getticket['ticket']);
        $getqr = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
        $path = "/upload/qrcode/".date("Y-m-d")."/".md5(microtime()).".jpg";
        $savepath = base_path("public_html{$path}");
        if(!is_dir(base_path("public_html/upload/qrcode/".date("Y-m-d")))){
            mkdir(base_path("public_html/upload/qrcode/".date("Y-m-d")));
        }
        file_put_contents($savepath,$this->getCurl($getqr));
        return $path;
    }
    //获取自定菜单
    public function getcustomMenu(){
        $result = $this->flashToken();
        if($result['code'] == 0){
            $token = $result['token'];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token={$token}";
        $result  = $this->getCurl($url);
        $deres = json_decode($result);
      /*  if($deres->is_menu_open > 0){
            return ["code" => 0, "content" => $deres->selfmenu_info] ;
        }else{
            return ["code" => 999,"msg" => "自定义菜单尚未开启" ] ;
        }*/
        return ["code" => 0,"data" => $deres->menu];

    }

    //自定义菜单
    public function defined_menus($body){
       $result = $this->flashToken();
       if($result['code'] == 0){
           $token = $result['token'];
       }
       $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}";
       $resmsg = json_decode($this->postUrl($url,$body),true);
       return ['code' =>  $resmsg['errcode'],'msg' => $resmsg['errmsg'] ];
    }


    //微信js配置
    public function getJsConfig($a_url){
        $wxconfig = $this->getWxConfig();
        $result = $this->flashToken();
        $token = $result["token"];
        $distance = abs(time() - $wxconfig->js_gettime);
        if($distance >=7000 ){
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$token}&type=jsapi";
            $restiket = json_decode($this->getCurl($url),true);
            if($restiket['errcode'] == 0){
                $ticket = $restiket['ticket'];
                $timestamp = time();
                $upjsconfig = Config::where("name","JSAPI_TICKET")->update([
                    "created_at" =>$timestamp,
                    "content" =>$ticket
                ]);
                if($upjsconfig == 0){
                    return ["code" =>999,"msg" => "更新jsconfig配置失败"];
                }
                $noncestr = $this->randString(6);
            }else{
                return ["code" =>999,"msg" => $restiket['errmsg']];
            }
            $jsapi_ticket = "jsapi_ticket={$ticket}&noncestr={$noncestr}&timestamp={$timestamp}&url={$a_url}";
            $signature = sha1($jsapi_ticket);
        }else{
            $ticket = $wxconfig->jsapi_ticket;
            $timestamp = time();
            $noncestr = $this->randString(6);
            $jsapi_ticket = "jsapi_ticket={$ticket}&noncestr={$noncestr}&timestamp={$timestamp}&url={$a_url}";
            $signature = sha1($jsapi_ticket);
        }
        $jsconfig = [
            "appId" => $wxconfig->app_id,
            "timestamp" =>(string)$timestamp,
            "nonceStr" =>$noncestr,
            "signature" =>$signature
        ];
        return  $jsconfig;
    }

    //随机数
    public function randString($count,$number = false){
        $str = "";
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        if($number){
            $strPol = "1234567890";
        }
        $max = strlen($strPol)-1;

        for($i=0;$i<$count;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
   //从微信端下载图片
    public function downloadpic($serverId){
        $result = $this->flashToken();
        if($result["code"] == 0){
            $token = $result['token'];
        }
       $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token={$token}&media_id={$serverId}";
        $path = "/upload/avatars/".date("Y-m-d")."/".md5(microtime()).".jpg";
        $savepath = base_path()."/public_html".$path;
        if(!is_dir(base_path()."/public_html/upload/avatars/".date("Y-m-d"))){
            mkdir(base_path()."/public_html/upload/avatars/".date("Y-m-d"));
        }
        $media = $this->getCurl($url);
        if(!empty($media['errcode'])){
            return ["code" => 999, "msg" => $media['errmsg']];
        }
        file_put_contents($savepath,$media);
        return ["code" => 0,"path" =>$path];
    }
    //微信统一下单
    public function  unifiedOrder($order_code,$tatol_fee,$body,$notify_url,$type = 0){
        $wxuser = WxUser::where("id",Session::get("wxuser_id"))->first();
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $wxconfig = $this->getWxConfig();
        //$type = 0 为普通支付 1:为充值
        $order_code = empty($type)?("N".(string)$this->randString(5,true).$order_code):$order_code;
        $param = [
            "appid" =>$wxconfig->app_id,
            "mch_id" =>$wxconfig->mch_id,
            "device_info" =>"WEB",
            "nonce_str" =>$this->randString(32),
            "body" =>$body,
            "out_trade_no" => $order_code,
            "total_fee" =>  $tatol_fee * 100,
//            "total_fee" =>  1,
            "spbill_create_ip" =>$_SERVER['REMOTE_ADDR'],
            "notify_url" =>$notify_url,
            "trade_type" =>"JSAPI",
            "openid" => $wxuser->openid
        ];
        ksort($param);

        $sign = $this->signFunc($param,$wxconfig->pay_key);
        $param["sign"] = $sign;
        $paramXml = $this->arrayToXml($param);
        $result = $this->postUrl($url,$paramXml);
        $result = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($result->return_code == 'SUCCESS'){
            return ['code' => 0,'jsconfig' => $this->payJsConfig($result->prepay_id)];
        }else{
            return ['code' => 999,'msg' => $result->return_msg ];
        }

    }
   //支付js配置信息
    public function payJsConfig($prepay_id){
        $wxconfig = $this->getWxConfig();
        $config = [
            "appId" => $wxconfig->app_id,
            "timeStamp" => (string)time(),
            "nonceStr" => $this->randString(32),
            "package" => "prepay_id=".$prepay_id,
            "signType" => "MD5",
        ];
        ksort($config);
        $sign = $this->signFunc($config,$wxconfig->pay_key);
        $config["paySign"] = $sign;
        return $config;
    }
    //支付签名写法
    public function signFunc($param,$key){
        $paramStr = urldecode(http_build_query($param));
        return strtoupper(md5($paramStr."&key={$key}"));
    }
    //将数组转成xml格式
    public function arrayToXml($array){
       $xml = "<xml>";
       foreach($array as $key => $value){
           $xml .= "<{$key}>{$value}</{$key}>";
       }
        $xml = $xml."</xml>";
        return $xml;
    }
    //查询微信支付订单
    public function queryPayOrder($order_code){
        $url = "https://api.mch.weixin.qq.com/pay/orderquery";
        $wxconfig = $this->getWxConfig();
        $param = [
            "appid" =>$wxconfig->app_id,
            "mch_id" =>(int)$wxconfig->mch_id,
            "nonce_str" =>$this->randString(32),
            "out_trade_no" =>$order_code,

        ];
        ksort($param);
        $sign = $this->signFunc($param,$wxconfig->pay_key);
        $param["sign"] = $sign;
        $paramXml = $this->arrayToXml($param);
        return $this->postUrl($url,$paramXml);

    }
    //上传临时图片素材
    public function uploadPic($tempfile){

        $res = $this->flashToken();
        if($res["code"] == 0){
            $token = $res["token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type=image";
        return $this->postUrl($url,$tempfile);
    }
    //获取模板消息
    public function getAllTempMsg(){
        $res = $this->flashToken();
        if($res["code"] == 0){
            $token = $res["token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$token}";
        return $this->getCurl($url);
    }
    //推送文字信息
   public function sendTextMsg($openid,$content){
       $res = $this->flashToken();
       if($res["code"] == 0){
           $token = $res["token"];
       }
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$token}";
       $post_data = [
           "touser" => $openid,
           "msgtype" => "text",
           "text" => ["content" => $content],
       ];
       $this->postUrl($url,json_encode($post_data,JSON_UNESCAPED_UNICODE));
   }
    //上传临时素材图片
    public function uploadPicWx($url,$file){
        $buffer = new \CurlFile(realpath($file));
        $post_data = [
            "buffer" =>$buffer,
        ];
        return $this->postUrl($url,$post_data);
    }
    //加载全部的消息模板
    public function loadAllTemp(){
        $res = $this->flashToken();
        if($res["code"] == 0){
            $token = $res["token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$token}";
        $result = json_decode($this->getCurl($url),true);
        if(!empty($result["template_list"])){
            DB::beginTransaction();
            foreach ($result["template_list"] as $list){
                $config = Config::where("name",$list["template_id"])->first();
               $data = [
                   "name" => $list["template_id"],
                   "means" => $list['title'],
                   "mold" => "WXTMPMSG",
                   "content" => serialize(["content" => $list['content'],"example" => $list['example']])
                  ];
               if(empty($config)){
                   $resconfig =  Config::insert($data);
               }else{
                   unset($data['name']);
                   $resconfig =  Config::where("name",$list["template_id"])->update($data);
               }
                if($resconfig == 0){
                    DB::rollback();
                    return ["code" => 999,"msg" => "获取失败"];
                }
            }
            DB::commit();
        }

        return ["code" => 0,"msg" => null];
    }
    //设置行业
    public function setIndustry(){
        $res = $this->flashToken();
        if($res["code"] == 0){
            $token = $res["token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token={$token}";
        $post_data = json_encode(["industry_id1" => 2,"industry_id2" =>10 ]);
        return $this->postUrl($url,$post_data);
    }
    //推送订单提醒模板消息
    public function sendOrderRemind($open_id,$means,$order){
        $res = $this->flashToken();
        if($res["code"] == 0){
            $token = $res["token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
        $config = Config::where("means",$means)->first();
        $post_data = [
            "touser" => $open_id,
            "template_id" => $config->name,
            "url" => u("wechat/user/orderInfo",["order_id" => $order->id]),
            "data" => [
                "first" => [ "value" => "铝行天下订单状态更新提醒","color" => "#cd3e44"],
                "keyword1" => [ "value" => date("Y-m-d H:i:s"),"color" => "#40bbeb"],
                "keyword2" => [ "value" => "采购下单","color" => "#40bbeb"],
                "keyword3" => [ "value" => config("order.status")[$order->status],"color" => "#cd3e44"],
                "keyword4" => [ "value" => "铝行天下有限公司","color" => "#173177"],
                "keyword5" => [ "value" => $order->notes,"color" => "#173177"],
                "remark" => [ "value" => "如有不清楚的请点击详情或者拨打联系电话133233199375","color" => "#173177"],
            ]

        ];
        $this->postUrl($url,json_encode($post_data));
    }

    //获取临时二维码
    public function getTmpQrcode($extension){
        $res = $this->flashToken();
        if($res["code"] == 0){
            $token = $res["token"];
        }
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$token}";
        $data = [
            "expire_seconds" => 2592000,
//            "action_name" => "QR_SCENE", 整型参数值
//            二维码类型，QR_SCENE为临时的整型参数值，QR_STR_SCENE为临时的字符串参数值
            "action_name" => "QR_STR_SCENE",
            "action_info" => [
                "scene" => ["scene_str" => $extension]
            ]
        ];
        $result = $this->postUrl($url,json_encode($data));
        //直接返回二维码解析的链接地址
        return json_decode($result)->url;

    }

    //凌凯短信查询
    public function selectMsg(){
        $result = Config::where("mold","SMSGAPI")->get();
        $smsgapi = new \stdClass();
        foreach ($result as $item){
            switch ($item->name){
                case "CORPID":
                    $smsgapi->CorpID = $item->content;
                    break;
                case "PWD":
                    $smsgapi->Pwd = $item->content;
                    break;
                case "SEARCH_URI":
                    $smsgapi->SEARCH_URI = $item->content;
                    break;
            }
        }
        $url  = $smsgapi->SEARCH_URI."?CorpID={$smsgapi->CorpID}&Pwd={$smsgapi->Pwd}";
        return $this->getCurl($url);
    }
    //凌凯短信发送
    public function sendMsg($mobile,$random,$way = 0){
        $result = Config::where("mold","SMSGAPI")->get();
        $smsgapi = new \stdClass();
        foreach ($result as $item){
            switch ($item->name){
                case "CORPID":
                    $smsgapi->CorpID = $item->content;
                    break;
                case "PWD":
                    $smsgapi->Pwd = $item->content;
                    break;
                case "REQUEST_URI":
                    $smsgapi->REQUEST_URI = $item->content;
                    break;
            }
        }
        switch ($way){
            case 0:
                $content = "您的申请验证码为：{$random}，请于120秒内输入验证，该验证码需提供给申请人，除此以外其他人员向您索要短信验证码都可能是欺诈行为。";
                break;
            case 1:
                $content = "你的申请已被你的审核者拒绝了，联系电话({$random})，具体详情可以与其电话沟通。";
                break;
            case 2:
                $content = "您的密码重置验证码为：{$random}，有效时间为两分钟，该验证码其他人员向您索要短信验证码都可能是欺诈行为。";
                break;
        }

        $content = iconv('UTF-8','GB2312',$content);
        $url = $smsgapi->REQUEST_URI."?CorpID={$smsgapi->CorpID}&Pwd={$smsgapi->Pwd}&Mobile={$mobile}&Content={$content}";
        return $this->getCurl($url);
    }

    //发送阿里短信
    public function sendAliMsg($mobile,$random,$way = 0){
        $result = Config::where("mold","ALIMSG")->get();
        $smsgapi = new \stdClass();
        foreach ($result as $item){
            switch ($item->name){
                case "ALIMSG_SIGN":
                    $smsgapi->ALIMSG_SIGN = $item->content;
                    break;
                case "ALIMSG_KEY_ID":
                    $smsgapi->ALIMSG_KEY_ID = $item->content;
                    break;
                case "ALIMSG_KEY_SECRET":
                    $smsgapi->ALIMSG_KEY_SECRET = $item->content;
                    break;
            }
        }
        //模板id
        switch ($way){
            case 0:
                $template_id = 'SMS_100300061';
                break;
            case 1:
                $template_id = 'SMS_100300061';
                break;
        }
        $response = (new SendAliMsg($smsgapi->ALIMSG_KEY_ID,$smsgapi->ALIMSG_KEY_SECRET))->sendSms($smsgapi->ALIMSG_SIGN,$template_id,$mobile, ["code"=> $random]);
        if($response->Code == 'OK' ){
            return ['code' => 0,'msg' => ''];
        }else{

            return ['code' => 999,'msg' => $response->Message];
        }

    }


    //创建会员卡
    public function createMemberCard(){
       $data = Config::where('mold','WECHAT')->where('name','WECHAT_MEMBER_CARD')->value('content');

       $token = $this->flashToken()['token'];
       $url = "https://api.weixin.qq.com/card/create?access_token={$token}";
       return $this->postUrl($url,$data);
    }

    //设置测试白名单
    public function setWhiteList($openid){
        $token = $this->flashToken()['token'];
        $url = "https://api.weixin.qq.com/card/testwhitelist/set?access_token={$token}";
        $data = [
            "openid" => [$openid]
        ];
        $res = json_decode($this->postUrl($url,json_encode($data)));
        if($res->errcode != 0){
            return ['code' => 999,'msg' => $res->errmsg];
        }
        return ['code' => 0,'msg' => ''];
    }
    //生成获取会员卡的二维码
    public function receiveCard($code){
        $token = $this->flashToken()['token'];
        $url = "https://api.weixin.qq.com/card/qrcode/create?access_token={$token}";
        $data = [
            "action_name" => "QR_CARD",
            "action_info" => [
                'card' => [
                    'code' => $code,
                    'card_id' => Config::where('name','WECHAT_MEMBER_CARD_ID')->where('mold','WECHAT')->value('content')
                ]
            ]
        ];
        $res = json_decode($this->postUrl($url,json_encode($data)),true);
        if($res['errcode'] != 0){
            return ['code' => 999,'msg' => $res['errmsg']];
        }
        return $res;
    }

    //更新会员信息
    public function updateMemberInfo($code,$integral){
        $card_id = Config::where('name','WECHAT_MEMBER_CARD_ID')->where('mold','WECHAT')->value('content');
        $data = [
            'code' =>$code,
            'card_id' => $card_id,
            'bonus' => bcmul($integral,100)
        ];
        $token = $this->flashToken()['token'];
        $url = "https://api.weixin.qq.com/card/membercard/updateuser?access_token={$token}";
        $res = json_decode($this->postUrl($url,json_encode($data)),true);
        if($res['errcode'] != 0){
            return ['code' => 999,'msg' => $res['errmsg']];
        }
        return ['code' => 0,'msg' => ''];
    }
}
