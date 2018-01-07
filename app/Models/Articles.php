<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/21
 * Time: 14:47
 */
namespace App\Models;

use Validator,Session;

class Articles extends Base{


    /**
     * @函数描述  保存文章
     * @参数描述  array $data
     * @return  array
     * @Created on 2017/12/25 12:00
     * @Author: ZhangHao <247073050@qq.com>
     */
   public function saveArticle($data){

       $check = $this->validateFields($data);

       if($check){

           return ['code' => 999,'msg' => $check];
       }

       if(!empty($data['a_id'])){

           $article =  Articles::find($data['a_id']);

           $old_imgs = $this->getAllImgSrc($article->content);

           array_push($old_imgs,$article->cover);

           $new_imgs = $this->getAllImgSrc($data['content']);

           array_push($new_imgs,$data['cover']);

           if($old_imgs != $new_imgs){

               $this->upStatusPic($new_imgs,1);
               $this->upStatusPic($old_imgs,0);
           }

       }else{

          $article = new Articles();

          $article->id = guid();
          $article->created_at = time();

          $new_imgs = $this->getAllImgSrc($data['content']);

          array_push($new_imgs,$data['cover']);

          $this->upStatusPic($new_imgs,1);
       }

       $article->column_id = $data['column_id'];
       $article->short_title = $data['short_title'];
       $article->title = $data['title'];
       $article->keywords = $data['keywords'];
       $article->author = Session::get('identity.adminer_id');
       $article->tags = $data['tags'];
       $article->source = $data['source'];
       $article->cover = $data['cover'];
       $article->attribute = empty($data['attribute'])?null:implode(',',$data['attribute']);
       $article->content = $data['content'];
       $article->sort = $data['sort'];

       $res = $article->save();

       if(empty($res)){

           return ['code' => 999,'msg' => '操作失败'];
       }

       return ['code' => 0,'msg' => ''];
   }

    /**
     * @函数描述  验证
     * @参数描述 array $data
     * @return  false | string
     * @Created on 2017/12/25 13:27
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function validateFields($data){

       $validator = Validator::make($data,[
           'column_id' => 'required',
           'short_title' => 'required|max:50',
           'title' => 'required|max:100',
           'keywords' => 'required|max:100',
           'tags' => 'required|max:100',
           'source' => 'nullable|max:50',
           'cover' => 'required',
           'content' => 'required',
           'attribute' => 'required',
       ],[
            'column_id.required' => '所属栏目不能为空',
            'short_title.required' => '简短标题不能为空',
            'short_title.max' => '简短标题过长',
            'title.required' => '文章标题不能为空',
            'title.max' => '文章标题过长',
            'keywords.required' => '文章关键字不能为空',
            'keywords.max' => '文章关键字过长',
            'tags.required' => '文章标签不能为空',
            'tags.max' => '文章标签过长',
            'source.max' => '文章来源过长',
            'cover.required' => '文章封面不能为空',
            'content.required' => '文章内容不能为空',
            'attribute.required' => '文章属性不能为空',

       ]);

       if($validator->fails()){

           return $validator->errors()->first();
       }

       return false;
    }




}