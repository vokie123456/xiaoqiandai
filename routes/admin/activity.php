<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/19
 * Time: 17:53
 * 活动设置
 */
Route::group(['prefix' => 'activity'],function(){

    //优惠券列表
    Route::any('coupons',['uses' => 'ActivityController@coupons','as' => 'admin.activity.coupons']);
//添加-编辑优惠券
    Route::any('addeCoupons',['uses' => 'ActivityController@addeCoupons','as' => 'admin.activity.addeCoupons']);
//保存优惠券
    Route::any('saveCoupons',['uses' => 'ActivityController@saveCoupons','as' => 'admin.activity.saveCoupons']);
//删除优惠券
    Route::any('delCoupons',['uses' => 'ActivityController@delCoupons','as' => 'admin.activity.delCoupons']);


//抽奖设置
    Route::any('raffle',['uses' => 'ActivityController@raffle','as' => 'admin.activity.raffle']);

//抽奖设置添加 编辑
    Route::any('raffleupdate',['uses' => 'ActivityController@raffleupdate','as' => 'admin.activity.raffleupdate']);
//抽奖设置保存
    Route::any('rafflesave',['uses' => 'ActivityController@rafflesave','as' => 'admin.activity.rafflesave']);
//删除抽奖
    Route::any('delraffle',['uses' => 'ActivityController@delraffle','as' => 'admin.activity.delraffle']);

});

