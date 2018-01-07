<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/19
 * Time: 11:00
 */

Route::any('/',['uses' => 'IndexController@index' ,'as' => 'Wechat.Index.index']);