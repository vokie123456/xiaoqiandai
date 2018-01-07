<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
//withTrashed  查询出软删除
//所有配置信息
class Coupons extends Base
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * @函数描述  保存优惠券
     * @参数描述  array $args
     * @return  array
     * @Created on 2017/12/25 13:28
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveCoupons($args){
        if(!empty($args['cp_id'])){
            $coupons = Coupons::find($args['cp_id']);
            $coupons->grant_count = bcadd($coupons->grant_count,(empty($args['add_grant_count'])?0:$args['add_grant_count']));
            $coupons->surplus_count = bcadd($coupons->surplus_count,(empty($args['add_grant_count'])?0:$args['add_grant_count']));
        }else{
            $coupons = new Coupons();
            $coupons->id = guid();
            $coupons->created_at = time();
            $coupons->grant_count = $args['grant_count'];
            $coupons->surplus_count = $args['grant_count'];
        }

        $coupons->name = $args['name'];
        $coupons->describe = $args['describe'];
        $coupons->valid_start = strtotime($args['valid_start']);
        $coupons->valid_end = strtotime($args['valid_end']);
        $coupons->satisfy_fee = $args['satisfy_fee'];
        $coupons->reduce_fee = $args['reduce_fee'];
        $coupons->sort = $args['sort'];
        $res = $coupons->save();
        if(empty($res)){
            return ['code' => 999,'msg' => '编辑失败'];
        }
        return ['code' => 0,'msg' => ''];
    }
}
