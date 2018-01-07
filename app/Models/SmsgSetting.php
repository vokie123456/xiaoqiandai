<?php
namespace App\Models;

use Validator;
class SmsgSetting extends Base{


    /**
     * @函数描述  保存短信设置
     * @参数描述  array $data
     * @return  array
     * @Created on 2017/12/25 13:31
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveSmsg($data){

        $check = $this->validateFields($data);

        if($check){

            return ['code' => 999,'msg' => $check];
        }

        if(empty($data['s_id'])){

            $smsg = new SmsgSetting();

            $smsg->id = guid();
            $smsg->created_at = time();
        }else{

            $smsg = SmsgSetting::find($data['s_id']);


        }

        $smsg->title = $data['title'];
        $smsg->AccessKeyId = $data['AccessKeyId'];
        $smsg->accessKeySecret = $data['accessKeySecret'];
        $smsg->template_code = $data['template_code'];
        $smsg->template_desc = $data['template_desc'];
        $smsg->sign = $data['sign'];
        $smsg->status = $data['status'];
        $smsg->sort = $data['sort'];

        $res = $smsg->save();

        if(empty($res)){

            return ['code' => 999,'msg' => '操作失败'];
        }

        return ['code' => 0,'msg' => ''];


    }

    /**
     * @函数描述  验证
     * @参数描述  array $data
     * @return  array
     * @Created on 2017/12/25 13:32
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function validateFields($data){

        $validator = Validator::make($data,[
            'title' => 'required|max:30',
            'AccessKeyId' => 'required|max:50',
            'accessKeySecret' => 'required|max:100',
            'template_code' => 'required|max:30',
            'template_desc' => 'nullable|max:255',
            'sign' => 'required|max:255',
            'sort' => 'required|numeric|min:1|max:9999',
        ],[

            'title.required' => '标题不能为空',
            'title.max' => '标题过长',
            'AccessKeyId.required' => 'AccessKeyId不能为空',
            'accessKeySecret.required' => 'accessKeySecret不能为空',
            'template_code.required' => '模板CODE不能为空',
            'template_desc.max' => '模板内容过长',
            'sign.required' => '短信签名不能为空',
            'sign.max' => '短信签名过长',
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