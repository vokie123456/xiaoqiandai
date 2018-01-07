<?php
//外观设置
Route::group(['prefix' =>'appear' ],function(){
    //轮播列表
    Route::any('carousel',['uses' => 'AppearController@carousel','as' => 'admin.appear.carousel']);
    //添加-编辑
    Route::any('addecarousel',['uses' => 'AppearController@addecarousel','as' => 'admin.appear.addecarousel']);
    //保存轮播信息
    Route::any('saveCarousel',['uses' => 'AppearController@saveCarousel','as' => 'admin.appear.saveCarousel']);
    //删除轮播信息
    Route::any('delCarousel',['uses' => 'AppearController@delCarousel','as' => 'admin.appear.delCarousel']);

    //广告设置 Advert
    Route::any('advert',['uses' => 'AppearController@advert','as' => 'admin.appear.advert']);
    //添加-编辑广告
    Route::any('addeAdvert',['uses' => 'AppearController@addeAdvert','as' => 'admin.appear.addeAdvert']);
    //保存广告
    Route::any('saveAdvert',['uses' => 'AppearController@saveAdvert','as' => 'admin.appear.saveAdvert']);
    //删除广告
    Route::any('delAdvert',['uses' => 'AppearController@delAdvert','as' => 'admin.appear.delAdvert']);

    //协议介绍
    Route::any('agreement',['uses' => 'AppearController@agreement','as' => 'admin.appear.agreement']);
    //添加-编辑协议
    Route::any('addeAgreement',['uses' => 'AppearController@addeAgreement','as' => 'admin.appear.addeAgreement']);
    //保存协议
    Route::any('saveAgreement',['uses' => 'AppearController@saveAgreement','as' => 'admin.appear.saveAgreement']);
    //删除协议
    Route::any('delAgreement',['uses' => 'AppearController@delAgreement','as' => 'admin.appear.delAgreement']);

});