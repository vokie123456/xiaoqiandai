<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 14:19
 */

namespace App\Http\Controllers\Admin;

use App\Models\Agreement;
use App\Models\Appear;
use App\Models\Config;
use Validator;

class AppearController extends AuthController
{

    /**
     * @函数描述 轮播列表
     * @参数描述 array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:29
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function carousel(){
        $args = $this->params;
        $data['search'] = $args;
        $carousel = Appear::where('type',1)->orderBy('sort','asc')->orderBy('id','asc');
        if(!empty($args['name'])){
            $carousel->where('name','like','%'.$args['name'].'%');
        }
        $data['carousel'] = $carousel->paginate(10);
        return view($this->vname,$data);
    }

    /**
     * @函数描述  添加-编辑轮播
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:31
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addecarousel(){
        $args = $this->params;
        $data['carousel'] = null;
        if(!empty($args['carl_id'])){
            $data['carousel'] = Appear::find($args['carl_id']);
        }
        return view($this->vname,$data);
    }

    /**
     * @函数描述 保存轮播信息
     * @参数描述 array $this->params
     * @return array
     * @Created on 2017/12/25 10:32
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveCarousel(){
        $args = $this->params;
        $validator = Validator::make($args,[
            'name' => 'required|max:30',
            'show_img' => 'required',
            'desc' => 'max:50',
            'link_addr' => 'max:100',
            'sort' => 'required|numeric|max:9999',
        ],[
            'name.required' => '名字不能为空',
            'name.max' => '名字过长',
            'desc.max' => '描述过长',
            'link_addr.max' => '链接地址过长',
            'show_img.required' => '展示图不能为空',
            'sort.required' => '排序不能为空',
            'sort.numeric' => '排序应为数字',
            'sort.max' => '排序数字过大',
        ]);
        if($validator->fails()){
            return ['code' => 999,'msg' => $validator->errors()->first()];
        }
       return (new Appear())->saveCarousel($args);
    }

    /**
     * @函数描述 轮播删除
     * @参数描述 array  $this->params
     * @return array
     * @Created on 2017/12/25 10:34
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delCarousel(){
        $args = $this->params;
        $carl = Appear::find($args['carl_id']);
        $this->upStatusPic($carl->show_img,0);
        $res = $carl->delete();
        if(empty($res)){
            return ['code' => 999,'msg' => '删除失败'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述  广告列表
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:35
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function advert(){
        $args = $this->params;
        $data['search'] = $args;
        $carousel = Appear::where('type',2)->orderBy('sort','asc')->orderBy('id','asc');
        if(!empty($args['name'])){
            $carousel->where('name','like','%'.$args['name'].'%');
        }
        $data['advert'] = $carousel->paginate(10);
        return view($this->vname,$data);
    }

    /**
     * @函数描述 添加-编辑广告
     * @参数描述 array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:35
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeAdvert(){
        $args = $this->params;
        $data['advert'] = null;
        if(!empty($args['adver_id'])){
            $data['advert'] = Appear::find($args['adver_id']);
        }
        return view($this->vname,$data);
    }

    /**
     * @函数描述  保存广告信息
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:36
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveAdvert(){
        $args = $this->params;
        $validator = Validator::make($args,[
            'name' => 'required|max:30',
            'show_img' => 'required',
            'desc' => 'max:50',
            'link_addr' => 'max:100',
            'sort' => 'required|numeric|max:9999',
        ],[
            'name.required' => '名字不能为空',
            'name.max' => '名字过长',
            'desc.max' => '描述过长',
            'link_addr.max' => '链接地址过长',
            'show_img.required' => '展示图不能为空',
            'sort.required' => '排序不能为空',
            'sort.numeric' => '排序应为数字',
            'sort.max' => '排序数字过大',
        ]);
        if($validator->fails()){
            return ['code' => 999,'msg' => $validator->errors()->first()];
        }
        return (new Appear())->saveAdvert($args);
    }

    /**
     * @函数描述  广告删除
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:37
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delAdvert(){
        $args = $this->params;
        $carl = Appear::find($args['adver_id']);
        $this->upStatusPic($carl->show_img,0);
        $res = $carl->delete();
        if(empty($res)){
            return ['code' => 999,'msg' => '删除失败'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述  协议列表
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:38
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function agreement(){
        $args = $this->params;
        $agreement = Agreement::select(['id','name','alias','agree_type','link_addr','sort'])->orderBy('sort','asc');
        if(!empty($args['name'])){
            $agreement->where('name','like','%'.$args['name'].'%');
        }
        $data['agreement'] = $agreement->paginate(10);
        return view($this->vname,$data);
    }

    /**
     * @函数描述  添加-编辑协议
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:38
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeAgreement(){
        $args = $this->params;
        $data['agreement'] = null;
        if(!empty($args['agr_id'])){
            $data['agreement'] = Agreement::find($args['agr_id']);
        }
        //百度key
        $data['mapKey'] = Config::where('mold','BAIDU')->where('name','BAIDU_AK')->value('content');
        return view($this->vname,$data);
    }

    /**
     * @函数描述  保存协议
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:39
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveAgreement(){
        $args = $this->params;
        $validator = Validator::make($args,[
            'name' => 'required|max:30',
            'alias' => 'required|max:30',
            'link_addr' => 'max:100',
            'sort' => 'required|numeric|max:9999',
            'content' => 'required',
        ],[
            'name.required' => '协议名不能为空',
            'name.max' => '协议名过长',
            'alias.required' => '别名不能为空',
            'alias.max' => '别名过长',
            'link_addr.max' => '链接地址过长',
            'sort.required' => '排序不能为空',
            'sort.numeric' => '排序应为数字',
            'sort.max' => '排序数过大',
            'content.required' => '协议内容不能为空',
        ]);
        if($validator->fails()){
            return ['code' => 999,'msg' => $validator->errors()->first()];
        }
        return (new Agreement())->saveAgreement($args);
    }

    /**
     * @函数描述 协议删除
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:40
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delAgreement(){
        $args = $this->params;
        $agreement = Agreement::find($args['agr_id']);
        $imgurl = $this->getAllImgSrc($agreement->content);
        if(!empty($imgurl)){
            $this->upStatusPic($imgurl,0);
        }
        $res = $agreement->delete();
        if(empty($res)){
            return ['code' => 999,'msg' => '删除失败'];
        }
        return ['code' => 0,'msg' => ''];
    }


}