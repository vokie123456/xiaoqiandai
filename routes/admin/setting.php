<?php

Route::group(['prefix' => 'setting'],function (){
    //管理员自认资料
    Route::any('pinfo',['uses' => 'SettingController@pinfo','as' => 'admin.setting.pinfo']);
    //修改密码
    Route::any('updateAdminPwd',['uses' => 'SettingController@updateAdminPwd','as' => 'admin.setting.updateAdminPwd']);

    //登录主题
    Route::any('theme',['uses' => 'SettingController@theme','as' => 'admin.setting.theme']);
    //确认主题
    Route::any('themeSave',['uses' => 'SettingController@themeSave','as' => 'admin.setting.themeSave']);

    //网站设置
    Route::any('webset',['uses' => 'SettingController@webset','as' => 'admin.setting.webset']);
    //添加修改配置
    Route::any('addeConfig',['uses' => 'SettingController@addeConfig','as' => 'admin.setting.addeConfig']);
    //保存配置
    Route::any('saveConfig',['uses' => 'SettingController@saveConfig','as' => 'admin.setting.saveConfig']);

    //删除配置
    Route::any('delConfig',['uses' => 'SettingController@delConfig','as' => 'admin.setting.delConfig']);

    //修改头像
    Route::any('modifyhi',['uses' => 'SettingController@modifyhi','as' => 'admin.setting.modifyhi']);

    //图片管理
    Route::any('uploadMange',['uses' => 'SettingController@uploadMange','as' => 'admin.setting.uploadMange']);
    //上传图片
    Route::any('upload',['uses' => 'SettingController@upload','as' => 'admin.setting.upload']);

   //删除图片
    Route::any('delpicture',['uses' => 'SettingController@delpicture','as' => 'admin.setting.delpicture']);
    //系统清除
    Route::any('clearSystem',['uses' => 'SettingController@clearSystem','as' => 'admin.setting.clearSystem']);
    //删除全部废弃的图片
    Route::any('deleteHimg',['uses' => 'SettingController@deleteHimg','as' => 'admin.setting.deleteHimg']);
    //清除缓存
    Route::any('clearCache',['uses' => 'SettingController@clearCache','as' => 'admin.setting.clearCache']);

    //文件管理
    Route::any('fileManage',['uses' => 'SettingController@fileManage','as' => 'admin.setting.fileManage']);
    //上传文件
    Route::any('fileUpload',['uses' => 'SettingController@fileUpload','as' => 'admin.setting.fileUpload']);
    //删除无效的文件
    Route::any('delInvalidFile',['uses' => 'SettingController@delInvalidFile','as' => 'admin.setting.delInvalidFile']);


    //短信设置
    Route::any('smsgSetting',['uses' => 'SettingController@smsgSetting','as' => 'admin.setting.smsgSetting']);
    //添加-编辑短信
    Route::any('addeSmsg',['uses' => 'SettingController@addeSmsg','as' => 'admin.setting.addeSmsg']);
    //保存短信
    Route::any('saveSmsg',['uses' => 'SettingController@saveSmsg','as' => 'admin.setting.saveSmsg']);
    //删除短信
    Route::any('delSmsg',['uses' => 'SettingController@delSmsg','as' => 'admin.setting.delSmsg']);
    //修改短信状态
    Route::any('modifySmsgStatus',['uses' => 'SettingController@modifySmsgStatus','as' => 'admin.setting.modifySmsgStatus']);

    //邮件连接
    Route::any('emailSetting',['uses' => 'SettingController@emailSetting','as' => 'admin.setting.emailSetting']);
    //添加-编辑邮件连接
    Route::any('addeEmail',['uses' => 'SettingController@addeEmail','as' => 'admin.setting.addeEmail']);
    //保存邮件连接
    Route::any('saveEmail',['uses' => 'SettingController@saveEmail','as' => 'admin.setting.saveEmail']);
    //删除邮件连接
    Route::any('delEmail',['uses' => 'SettingController@delEmail','as' => 'admin.setting.delEmail']);
    //修改邮件状态
    Route::any('modifyEmailStatus',['uses' => 'SettingController@modifyEmailStatus','as' => 'admin.setting.modifyEmailStatus']);
    //SMTP协议发送邮件
    Route::any('smtpSendMail',['uses' => 'SettingController@smtpSendMail','as' => 'admin.setting.smtpSendMail']);
    //保存邮件并发送邮件
    Route::any('saveSendMail',['uses' => 'SettingController@saveSendMail','as' => 'admin.setting.saveSendMail']);
    //邮件发送的历史记录
    Route::any('historySMail',['uses' => 'SettingController@historySMail','as' => 'admin.setting.historySMail']);

    //查看日志
    Route::any('lookLogs',['uses' => 'SettingController@lookLogs','as' => 'admin.setting.lookLogs']);
    //处理日志,下载和删除
    Route::any('dealLogs',['uses' => 'SettingController@dealLogs','as' => 'admin.setting.dealLogs']);


});
