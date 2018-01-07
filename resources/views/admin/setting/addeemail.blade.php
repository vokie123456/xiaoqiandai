@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ICHECK}}/skins/all.css">
    <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/setting/emailSetting')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($email)?'添加':'编辑'}}邮件连接</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal"action="javascript:;" role="form">
                <input type="hidden" name="e_id" value="{{empty($email->id)?'':$email->id}}" />

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">邮件连接名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="title" value="{{empty($email->title)?'':$email->title}}" class="form-control"  placeholder="邮件标题">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">发送人姓名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="sender_name" value="{{empty($email->sender_name)?'':$email->sender_name}}" class="form-control"  placeholder="发送人姓名">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">SMTP服务器的名称 </label>
                    <div class="col-sm-3">
                        <input type="text" name="smtp_host" value="{{empty($email->smtp_host)?'':$email->smtp_host}}" class="form-control"  placeholder="smtp.qq.com或者smtp.163.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">SMTP服务器端口 </label>
                    <div class="col-sm-3">
                        <input type="text" name="smtp_port" value="{{empty($email->smtp_port)?465:$email->smtp_port}}" class="form-control"  placeholder="SMTP服务器端口">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">是否开启 </label>
                    <div class="col-sm-10">
                        <label class="checkbox-inline">
                            <input type="radio" name="connect_method"  value="normal" {{(!empty($email->connect_method)&&($email->connect_method == 'normal'))?'checked':''}} />&nbsp;普通方式</label>
                        <label class="checkbox-inline">
                            <input type="radio" name="connect_method"  value="ssl" {{(empty($email->connect_method)||($email->connect_method == 'ssl'))?'checked':''}} />&nbsp;SSL连接方式</label>
                        <label class="checkbox-inline">
                            <input type="radio" name="connect_method"  value="tls" {{(!empty($email->connect_method)&&($email->connect_method == 'tls'))?'checked':''}} />&nbsp;TLS连接方式</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">邮箱登录名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="login_account" value="{{empty($email->login_account)?'':$email->login_account}}" class="form-control"  placeholder="邮箱账号">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">邮箱密码 </label>
                    <div class="col-sm-3">
                        <input type="text" name="login_password" value="{{empty($email->login_password)?'':$email->login_password}}" class="form-control"  placeholder="授权密码,需要先打开smtp服务">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-3">
                        <input type="text" name="sort" value="{{empty($email->sort)?100:$email->sort}}" class="form-control"  placeholder="排序">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">是否开启 </label>
                    <div class="col-sm-4">
                        <label class="checkbox-inline">
                            <input type="radio" name="status"  value="on" {{(empty($email->status)||($email->status == 'on'))?'checked':''}} />&nbsp;开启</label>
                        <label class="checkbox-inline">
                            <input type="radio" name="status"  value="off" {{(!empty($email->status)&&($email->status == 'off'))?'checked':''}} />&nbsp;关闭</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/setting/saveEmail')}}" data-jurl="{{u('admin/setting/emailSetting')}}">确认</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection
<script type="text/javascript" src="{{ADMINUI}}/js/upimg.js"></script>
@section('js')
    <script type="text/javascript" src="{{ICHECK}}/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('input').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });


    </script>
@endsection