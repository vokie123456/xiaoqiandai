<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 14:19
 */

namespace App\Http\Controllers\Admin;


use App\Models\Coupons;
use App\Models\Raffle;

use Validator;

class ActivityController extends AuthController
{

    /**
     * @函数描述  优惠券列表
     * @参数描述
     * @return  HTML
     * @Created on 2017/12/25 10:21
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function coupons(){
        $data['coupons'] = Coupons::orderBy('sort','asc')->paginate(10);
        return view($this->vname,$data);
    }

    /**
     * @函数描述 添加-编辑优惠券
     * @参数描述 string $this->params['cp_id']
     * @return HTML
     * @Created on 2017/12/25 10:22
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeCoupons(){
        if(!empty($this->params['cp_id'])){
           $data['coupons'] = Coupons::find($this->params['cp_id']);
        }
        $data['coupons'] = empty($data['coupons'])?null:$data['coupons'];
        return view($this->vname,$data);
    }

    /**
     * @函数描述  保存优惠券
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:20
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveCoupons(){
        $args = $this->params;
        $validator = Validator::make($args,[
            'name' => 'required|max:30',
            'describe' => 'required|max:100',
            'valid_start' => 'required',
            'valid_end' => 'required',
            'satisfy_fee' => 'required|numeric',
            'reduce_fee' => 'required|numeric',
            'sort' => 'required|max:9999|numeric',
            'grant_count' => 'sometimes|required|numeric|min:1',
            'add_grant_count' => 'sometimes|numeric|min:0',
        ],[
            'name.required' => '优惠券名不能为空',
            'name.max' => '优惠券名过长',
            'describe.required' => '优惠券描述不能为空',
            'describe.max' => '优惠券描述过长',
            'valid_start.required' => '起始有效时间不能为空',
            'valid_end.required' => '结束有效时间不能为空',
            'satisfy_fee.required' => '满足最低金额不能为空',
            'satisfy_fee.numeric' => '满足最低金额应为数字',
            'reduce_fee.required' => '优惠金额不能为空',
            'reduce_fee.numeric' => '优惠金额应为数字',
            'sort.required' => '排序不能为空',
            'sort.max' => '排序数过大',
            'sort.numeric' => '排序应为数字',
            'grant_count.required' => '发放张数不能为空',
            'grant_count.numeric' => '发放张数应为数字',
            'grant_count.min' => '发放张数不能小于1',
            'add_grant_count.numeric' => '增加张数应为数字',
            'add_grant_count.min' => '增加张数不能小于0',
        ]);
        if($validator->fails()){
            return ['code' => 999,'msg' => $validator->errors()->first()];
        }
        if(bccomp(strtotime($args['valid_start']),strtotime($args['valid_end'])) >= 0){
            return ['code' => 999,'msg' => '截止时间应大于起始时间'];
        }

        return (new Coupons())->saveCoupons($args);
    }

    /**
     * @函数描述  删除优惠券
     * @参数描述  string $this->params['cp_id']
     * @return   array
     * @Created on 2017/12/25 10:25
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delCoupons(){
        $args = $this->params;
        if(empty($args['cp_id'])){
           return ['code' => 999,'msg' => '选择的优惠券为空'];
        }
        $res = Coupons::find($args['cp_id'])->delete();
        if(empty($res)){
            return ['code' => 999,'msg' => '删除失败'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述 抽奖设置列表
     * @参数描述
     * @return  HTML
     * @Created on 2017/12/25 10:26
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function raffle()
    {
        $raffle = Raffle::orderBy('sort','asc')->paginate(10);
        $data['raffle']=$raffle;
        return view($this->vname,$data);
    }

    /**
     * @函数描述 抽奖设置添加-修改
     * @参数描述 string $this->params['r_id']
     * @return HTML
     * @Created on 2017/12/25 10:26
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function raffleupdate(){
        $params = $this->params;
        $data['raffle'] = null;
        if(!empty($params['r_id'])){
            $data['raffle'] = Raffle::find($params['r_id']);
        }
        return view($this->vname,$data);
    }

    /**
     * @函数描述  抽奖设置保存
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:27
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function rafflesave()
    {
        $params = $this->params;

        $validator = Validator::make($params, [
            'name' => 'required',
            'logo' => 'required',
            'odds' => 'required|numeric',
            'prizes'=>'required',
            'value'=>'required|numeric'
        ], [
            'name.required' => '奖项名称不能为空',
            'logo.required' => '奖项logo图不能为空',
            'odds.required' => '中奖几率不能为空',
            'odds.numeric' => '中奖几率必须为数字',
            'prizes.required' => '奖励内容不能为空',
            'value.required' => '奖项的值不能为空',
            'value.numeric' => '奖项的值应为数字',
        ]);
        if ($validator->fails()) {
            return ['code' => 999, 'msg' => $validator->errors()->first()];
        }
        if(!is_int((int)$params['odds'])){
            return ['code' => 999, 'msg' => '概率应该为整数'];
        }
        if(!empty($params['id'])){
            $raffle = Raffle::find($params['r_id']);
            if($raffle->logo != $params['logo']){
                $this->upStatusPic($raffle->logo,0);
                $this->upStatusPic($params['logo'],1);
            }
        }else{
            $raffle = new Raffle();
            $raffle->id=guid();
            $raffle->created_at = time();
            $this->upStatusPic($params['logo'],1);
        }
        $raffle->name = $params['name'];
        $raffle->odds = $params['odds'];
        $raffle->logo = $params['logo'];
        $raffle->prizes = $params['prizes'];
        $raffle->sort = $params['sort'];
        $raffle->type = $params['type'];
        $raffle->value = $params['value'];


        $res = $raffle->save();

        if(empty($res))
        {
            return ['code'=>999,'msg'=>'操作失败'];
        }
        return ['code'=>0,'msg'=>''];
    }

    /**
     * @函数描述  删除抽奖
     * @参数描述 string $this->params['r_id']
     * @return  array
     * @Created on 2017/12/25 10:28
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delraffle(){

       if(!empty($this->params['r_id'])){

           return ['code' => 999,'msg' => '抽奖ID为空,不能删除'];
       }

        $raffle = Raffle::find($this->params['r_id']);

        $this->upStatusPic($raffle->logo,0);

        $res = $raffle->delete();

        if(empty($res)){

            return ['code' => 999,'msg' => '操作失败'];
        }

        return ['code' => 0,'msg' => ''];

    }


}