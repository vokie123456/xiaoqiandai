<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/21
 * Time: 14:47
 */
namespace App\Models;

use Validator;

class ArticleColumn extends Base{


        /**
         * @函数描述 保存栏目
         * @参数描述 array $data
         * @return array
         * @Created on 2017/12/25 11:58
         * @Author: ZhangHao <247073050@qq.com>
         */
        public function saveColumn($data){

            $check = $this->validateFields($data);

            if($check){

                return ['code' => 999,'msg' => $check];
            }

            if(!empty($data['c_id'])){

                $column = ArticleColumn::find($data['c_id']);

                $old_imgs = $this->getAllImgSrc($column->content);

                $new_imgs = $this->getAllImgSrc($data['content']);

                if(!empty($old_imgs)){

                    $this->upStatusPic($old_imgs,0);
                }
                if(!empty($new_imgs)){

                    $this->upStatusPic($new_imgs,1);
                }

            }else{

                $column = new ArticleColumn();
                $column->id = guid();
                $column->created_at = time();
                $new_imgs = $this->getAllImgSrc($data['content']);
                if(!empty($new_imgs)){

                    $this->upStatusPic($new_imgs,1);
                }
            }

            $column->pid = $data['pid'];
            $column->cname = $data['cname'];
            $column->seo_title = $data['seo_title'];
            $column->seo_key = $data['seo_key'];
            $column->seo_desc = $data['seo_desc'];
            $column->sort = $data['sort'];
            $column->status = $data['status'];
            $column->content = $data['content'];

            $res = $column->save();

            if(empty($res)){

                return ['code' => 999,'msg' => '操作失败'];
            }

            return ['code' => 0,'msg' => ''];

        }


        /**
         * @函数描述 验证字段
         * @参数描述 array $data
         * @return false | string
         * @Created on 2017/12/25 11:59
         * @Author: ZhangHao <247073050@qq.com>
         */
        public function validateFields($data){

            $validator = Validator::make($data,[
                'cname' => 'required|max:30',
                'seo_title' => 'required|max:50',
                'seo_key' => 'required|max:200',
                'seo_desc' => 'required|max:200',
                'sort' => 'required|numeric|min:1|max:9999',
            ],[
                'cname.required' => '栏目名不能为空',
                'cname.max' => '栏目名过长',
                'seo_title.required' => 'seo搜索标题不能为空',
                'seo_title.max' => 'seo搜索标题过长',
                'seo_key.required' => 'seo搜索key不能为空',
                'seo_key.max' => 'seo搜索key过长',
                'seo_desc.required' => 'seo描述描述不能为空',
                'seo_desc.max' => 'seo描述描述过长',
                'sort.required' => '排序不能为空',
                'sort.max' => '排序数值过大',
                'sort.min' => '排序数值太小',
                'sort.numeric' => '排序应为数字',
            ]);

            if($validator->fails()){

                return $validator->errors()->first();
            }

            return false;
        }



    /**
     * @函数描述 自关联
     * @参数描述
     * @return collect
     * @Created on 2017/12/25 11:59
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function column(){

       return $this->hasMany('App\Models\ArticleColumn','pid','id');
    }


}