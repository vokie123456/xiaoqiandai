<?php
namespace App\Models;

class Agreement extends Base{

    /**
     * @函数描述 保存协议
     * @参数描述 array $args
     * @return array
     * @Created on 2017/12/25 11:57
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveAgreement($args){
        if(!empty($args['agr_id'])){
            $o_imgurl = [];
            $n_imgurl = [];
            $agreement =  Agreement::find($args['agr_id']);
            $o_editor = $this->getAllImgSrc($agreement->content);
            $n_editor = $this->getAllImgSrc($args['content']);
            if($o_editor != $n_editor){
                $o_imgurl = array_merge($o_imgurl,$o_editor);
                $n_imgurl = array_merge($n_imgurl,$n_editor);
            }
            if(!empty($o_imgurl)){
                $this->upStatusPic($o_imgurl,0);
                $this->upStatusPic($n_imgurl,1);
            }
        }else{
            $agreement =  new Agreement();
            $n_editor = $this->getAllImgSrc($args['content']);
            if(!empty($n_editor)){
                $this->upStatusPic($n_editor,1);
            }
        }
        $agreement->name = $args['name'];
        $agreement->alias = $args['alias'];
        $agreement->agree_type = $args['agree_type'];
        $agreement->link_addr = $args['link_addr'];
        $agreement->sort = $args['sort'];
        $agreement->content = $args['content'];
        $res = $agreement->save();
        if(empty($res)){
            return ['code' => 999,'msg' => '编辑失败'];
        }
        return ['code' => 0,'msg' => ''];
    }


}