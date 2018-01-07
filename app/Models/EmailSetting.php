<?php
namespace App\Models;

use Validator;
class EmailSetting extends Base{


    /**
     * @函数描述  保存邮件
     * @参数描述  array $data
     * @return  array
     * @Created on 2017/12/25 13:30
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveEmail($data){

        $check = $this->validateFields($data);

        if($check){

            return ['code' => 999,'msg' => $check];
        }

        if(empty($data['e_id'])){

            $email = new EmailSetting();
            $email->id = guid();
            $email->created_at = time();
        }else{

            $email = EmailSetting::find($data['e_id']);

        }

        $email->title = $data['title'];
        $email->sender_name = $data['sender_name'];
        $email->smtp_host = $data['smtp_host'];
        $email->smtp_port = $data['smtp_port'];
        $email->connect_method = $data['connect_method'];
        $email->login_account = $data['login_account'];
        $email->login_password = $data['login_password'];
        $email->status = $data['status'];
        $email->sort = $data['sort'];

        $res = $email->save();

        if(empty($res)){

            return ['code' => 999,'msg' => '操作失败'];
        }

        return ['code' => 0,'msg' => ''];


    }

    /**
     * @函数描述  验证
     * @参数描述  array $data
     * @return  false|string
     * @Created on 2017/12/25 13:30
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function validateFields($data){

        $validator = Validator::make($data,[
            'title' => 'required|max:30',
            'sender_name' => 'required|max:30',
            'smtp_host' => 'required|max:50',
            'smtp_port' => 'required|max:20',
            'login_account' => 'required|max:50',
            'login_password' => 'required|max:255',
            'sort' => 'required|numeric|min:1|max:9999',
        ],[

            'title.required' => '标题不能为空',
            'title.max' => '标题过长',
            'sender_name.required' => '发送人姓名不能为空',
            'sender_name.max' => '发送人姓名过长',
            'smtp_host.required' => 'smtp服务器不能为空',
            'smtp_host.max' => 'smtp服务器过长',
            'smtp_port.required' => 'smtp服务器端口不能为空',
            'smtp_port.max' => 'smtp服务器端口过长',
            'login_account.required' => '邮箱登录名不能为空',
            'login_account.max' => '邮箱登录名过长',
            'login_password.required' => '邮箱登录密码不能为空',
            'login_password.max' => '邮箱登录密码过长',
            'sort.required' => '排序不能为空',
            'sort.max' => '排序数值过大',
            'sort.min' => '排序数值太小',
            'sort.numeric' => '排序应为数字',
        ]);

        if($validator->fails()){
            return $validator->errors()->first();
        }

        return  false;

    }

}