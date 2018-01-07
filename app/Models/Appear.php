<?php
namespace App\Models;

class Appear extends Base{

    /**
     * @函数描述 保存轮播信息
     * @参数描述 array $args
     * @return array
     * @Created on 2017/12/25 11:57
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveCarousel($args){
        if(!empty($args['carl_id'])){
            $carl = Appear::find($args['carl_id']);
            if($carl->show_img !=  $args['show_img']){
                $this->upStatusPic($carl->show_img,0);
                $this->upStatusPic($args['show_img'],1);
            }
        }else{
            $carl = new Appear();
            $this->upStatusPic($args['show_img'],1);
        }
        $carl->name = $args['name'];
        $carl->type = 1;
        $carl->page = $args['page'];
        $carl->position = $args['position'];
        $carl->link_addr = empty($args['link_addr'])?'':$args['link_addr'];
        $carl->desc = $args['desc'];
        $carl->show_img = $args['show_img'];
        $carl->sort = $args['sort'];
        $carl->status = $args['status'];
        $res = $carl->save();
        if(empty($res)){
            return ['code' => 999,'msg' => '没有修改的内容'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述 保存广告信息
     * @参数描述 array $args
     * @return array
     * @Created on 2017/12/25 11:58
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveAdvert($args){
        if(!empty($args['adver_id'])){
            $carl = Appear::find($args['adver_id']);
            if($carl->show_img !=  $args['show_img']){
                $this->upStatusPic($carl->show_img,0);
                $this->upStatusPic($args['show_img'],1);
            }
        }else{
            $carl = new Appear();
            $this->upStatusPic($args['show_img'],1);
        }
        $carl->name = $args['name'];
        $carl->type = 2;
        $carl->page = $args['page'];
        $carl->position = $args['position'];
        $carl->desc = $args['desc'];
        $carl->link_addr = empty($args['link_addr'])?'':$args['link_addr'];
        $carl->show_img = $args['show_img'];
        $carl->sort = $args['sort'];
        $carl->status = $args['status'];
        $res = $carl->save();
        if(empty($res)){
            return ['code' => 999,'msg' => '没有修改的内容'];
        }
        return ['code' => 0,'msg' => ''];
    }


}