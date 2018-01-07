<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 21:56
 */

namespace App\Http\Controllers\Admin;

use App\Models\Adminer;
use App\Models\Config;
use Illuminate\Http\Request;
use Validator,Session,Captcha,Cache,Cookie;
class PublicController extends BaseController
{

    /**
     * @函数描述 登录页面
     * @参数描述 object $this->request
     * @return  HTML
     * @Created on 2017/12/25 11:07
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function login(){
        //风格
       $theme = Config::where('mold','THEME')->where('show',1)->first();
       $this->request->offsetSet('theme',strtolower($theme->name));
       $data = $this->getVerify($this->request);
       //后台名
       $data['admin_name'] = Config::where('mold','ADMIN')->where('name','ADM_NAME')->value('content');
       $data['theme'] = $theme;
       return view($this->vname.'.'.strtolower($theme->name),$data);
    }

    /**
     * @函数描述 获取图形验证码
     * @参数描述 array  $this->params
     * @return array
     * @Created on 2017/12/25 11:09
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function getVerify(Request $request){
        $args = empty($this->params)?$request->all():$this->params;
        if($args['theme'] == 'default'){
            return ['verify' => captcha_src('inverse')];
        }else{
            return ['verify' => captcha_src('default')];
        }
    }


    /**
     * @函数描述 验证登录信息
     * @参数描述 array $this->params
     * @return array
     * @Created on 2017/12/25 11:10
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function postLogin(){
       $args = $this->params;
       $validator = Validator::make($args,[
           'code' => 'required',
           'account' => 'required',
           'password' => 'required'
       ],[
           'code.required' => '验证码不能为空',
           'account.required' => '账号不能为空',
           'password.required' => '密码不能为空',
       ]);
       if($validator->fails()){
           return ['code' => 999,'msg' => $validator->errors()->first()];
       }
       $adminer = Adminer::where('account',$args['account'])->first();

       if(empty($adminer)){
           return ['code' => 999,'msg' => '账号不存在'];
       }

       if(decrypt($adminer->password) != $args['password']){
           return ['code' => 999,'msg' => '密码错误'];
       }

       if(!Captcha::check($args['code'])){
           return ['code' => 999,'msg' => '验证码错误'];
       }

       $adminer->token = md5($this->request->ip().$adminer->account.$adminer->id);
       $history = empty($adminer->history)?serialize([time()]):$this->storage($adminer->history);
       $adminer->history = $history;
       $adminer->login_times = $adminer->login_times + 1;
       $adminer->save();
       Session::put('identity',['adminer_id' => $adminer->id,'role_id' =>$adminer->role_id, 'token' => $adminer->token]);
       Cookie::queue('account',trim($args['account']),43200);
       return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述 储存登录时的记录
     * @参数描述 string $history 序列化的字符串
     * @return string 序列化的字符串
     * @Created on 2017/12/25 11:10
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function storage($history){
        $history = unserialize($history);
        array_push($history,time());
        if(count($history) > 2){
            array_shift($history);
        }
        return serialize($history);
    }

    /**
     * @函数描述  退出登录
     * @参数描述
     * @return redirct
     * @Created on 2017/12/25 11:12
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function loginOut(){
        Session::flush();
        return redirect('admin/public/login');
    }
}