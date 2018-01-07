<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/21
 * Time: 14:20
 */
//文章管理
Route::group(['prefix' => 'article'],function(){

    //文章列表
    Route::any('manageList',['uses' => 'ArticleController@manageList' ,'as' => 'admin.article.manageList']);
    //添加-编辑文章
    Route::any('addeArticle',['uses' => 'ArticleController@addeArticle' ,'as' => 'admin.article.addeArticle']);
    //保存文章
    Route::any('saveArticle',['uses' => 'ArticleController@saveArticle' ,'as' => 'admin.article.saveArticle']);
    //删除文章
    Route::any('delArticle',['uses' => 'ArticleController@delArticle' ,'as' => 'admin.article.delArticle']);
    //审核文章
    Route::any('auditArticle',['uses' => 'ArticleController@auditArticle' ,'as' => 'admin.article.auditArticle']);



    //栏目列表
    Route::any('columnList',['uses' => 'ArticleController@columnList' ,'as' => 'admin.article.columnList']);
    //添加-编辑栏目
    Route::any('addeColumn',['uses' => 'ArticleController@addeColumn' ,'as' => 'admin.article.addeColumn']);
    //保存栏目
    Route::any('saveColumn',['uses' => 'ArticleController@saveColumn' ,'as' => 'admin.article.saveColumn']);
    //删除栏目
    Route::any('delColumn',['uses' => 'ArticleController@delColumn' ,'as' => 'admin.article.delColumn']);
    //修改栏目状态
    Route::any('modifyColumnStatus',['uses' => 'ArticleController@modifyColumnStatus' ,'as' => 'admin.article.modifyColumnStatus']);

    //评论列表
    Route::any('commentList',['uses' => 'ArticleController@commentList' ,'as' => 'admin.article.commentList']);
    //设置个人评论时间间隔
    Route::any('commentInterval',['uses' => 'ArticleController@commentInterval' ,'as' => 'admin.article.commentInterval']);

});