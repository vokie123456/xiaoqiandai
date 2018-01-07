<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'logonauth:admin'],function(){

    //首页
    Route::any('/',['uses' => 'IndexController@index','as' =>'admin.index.index']);
    //首页内容
    Route::any('main',['uses' => 'IndexController@main','as' =>'admin.index.main']);
    //解锁
    Route::any('index/unlock',['uses' => 'IndexController@unlock','as' =>'admin.index.unlock']);

});

//后台登录
Route::any('public/login',['uses' => 'PublicController@login','as' =>'admin.public']);

//获取验证码
Route::any('public/getVerify',['uses' => 'PublicController@getVerify','as' =>'admin.public.getVerify']);

//提交登录
Route::any('public/postLogin',['uses' => 'PublicController@postLogin','as' =>'admin.public.postLogin']);

//退出登录
Route::any('public/loginOut',['uses' => 'PublicController@loginOut','as' =>'admin.public.loginOut']);

//后台权限管理
Route::group(['prefix' => 'permission'],function (){
    //管理员列表
    Route::any('adminerList',['uses' => 'PermissionController@adminerList','as' => 'admin.permission.adminerList']);
    //管理员添加-编辑页面
    Route::any('addeAdminer',['uses' => 'PermissionController@addeAdminer','as' => 'admin.permission.addeAdminer']);
    //提交管理员编辑信息
    Route::any('saveAdata',['uses' => 'PermissionController@saveAdata','as' => 'admin.permission.saveAdata']);
    //删除管理员
    Route::any('delAdminer',['uses' => 'PermissionController@delAdminer','as' => 'admin.permission.delAdminer']);

    //角色列表
    Route::any('roleList',['uses' => 'PermissionController@roleList','as' => 'admin.permission.roleList']);
    //添加-编辑角色
    Route::any('addeRole',['uses' => 'PermissionController@addeRole','as' => 'admin.permission.addeRole']);
    //保存提交信息
    Route::any('saveRinfo',['uses' => 'PermissionController@saveRinfo','as' => 'admin.permission.saveRinfo']);

    //删除角色
    Route::any('deltoRole',['uses' => 'PermissionController@deltoRole','as' => 'admin.permission.deltoRole']);

    //权限列表
    Route::any('permissionList',['uses' => 'PermissionController@permissionList','as' => 'admin.permission.permissionList']);
    //提交权限
    Route::any('submitPermiss',['uses' => 'PermissionController@submitPermiss','as' => 'admin.permission.submitPermiss']);
    //删除权限
    Route::any('delPermission',['uses' => 'PermissionController@delPermission','as' => 'admin.permission.delPermission']);
});

include  'appear.php';
include  'setting.php';
include  'activity.php';
include  'article.php';
