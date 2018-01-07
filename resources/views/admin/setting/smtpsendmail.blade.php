@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{WEDITOR}}/dist/css/wangEditor.min.css">

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
        <legend><b>发送邮件</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal"action="javascript:;" role="form">

                <input type="hidden" name="email_id" value="{{$email->id}}"/>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">邮件主题 </label>
                    <div class="col-sm-4">
                        <input type="text" name="theme"  class="form-control"  placeholder="邮件发送主题">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">收件人 </label>
                    <div class="col-sm-4">
                        <input type="text" name="touser"  class="form-control"  placeholder="收件人邮箱,多个收件人中间用逗号隔开">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">附件(可选) </label>
                    <div class="col-sm-10">
                            <button class="layui-btn layui-btn-primary"> <i class="layui-icon" style="color: #1EA018;">&#xe608;</i> 添加附件</button>
                            <input type="file" onchange="uploadFile(this);"   style="position:absolute;top:0;left:16px;width: 115px;height: 38px; opacity:0">
                           <span style="margin-left: 1em;border: 1px solid #c8a485;border-radius: 5px;padding: 5px;" class="fileshow" hidden>

                           </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">邮件内容 </label>
                    <div class="col-sm-10">
                        <textarea id="content" name="content" style="min-height: 250px;" class="WANGEDITOR"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/setting/saveSendMail')}}" data-jurl="{{u('admin/setting/emailSetting')}}">发送邮件</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection

@section('js')
    <script type="text/javascript" src="{{WEDITOR}}/dist/js/wangEditor.min.js"></script>
    <script>

        function uploadFile(obj) {
            var url = "{{u('admin/setting/fileUpload')}}";
            var formData = new FormData();
            formData.append('file' ,obj.files[0]);
            layui.use(['layer'], function() {
                $.ajax({
                    url: url,
                    type: "post",
                    data: formData,
                    dataType:'json',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        layer.msg('上传中...', {icon: 16, time: 30000});
                    },
                    success: function (res) {
                        if(res.code > 0){
                            layer.msg(res.msg,{icon:2});
                        }else{
                            var html = '<input type="hidden" name="enclosure" value="'+res.data.enclosure+'" >'
                                       +'<i class="layui-icon">&#xe61d;</i>'
                                       +'<span>'+res.data.org_name+'</span>&nbsp;'
                                       +'<span onclick="delInvalidFile(this,\''+res.data.enclosure+'\')"><i class="layui-icon" style="color: #a02703;">&#x1007;</i></span>';

                            $(obj).next('.fileshow').html(html).show();
                        }
                    },
                    error:function(xhr,ts,errorThrown){
                        console.log(xhr);
                    },
                    complete:function(){
                        obj.value = '';
                        layer.closeAll();
                    }
                });
            });
        }

        function delInvalidFile(obj,file_ids){

            var url = "{{u('admin/setting/delInvalidFile')}}";
            var file_ids = [file_ids];
            $.post(url,{file_ids:file_ids},function(res){
                layui.use(['layer'],function(){
                    if(res.code > 0){
                        layer.msg(res.msg,{maxWidth:260});
                    }else{
                        $(obj).parents('.fileshow').html('').hide();
                    }
                })
            },'json');

        }

        $('.WANGEDITOR').each(function(){
            var id = $(this).attr('id');
            var editor = new wangEditor(id);
            // 上传图片（举例）
            editor.config.uploadImgUrl = "{{u('admin/setting/upload')}}";

            // 配置自定义参数（举例）
            editor.config.uploadParams = {
                //上传方式
                utype: 'weditor'
            };
            editor.config.uploadImgFileName = 'file';
            editor.config.mapAk = "{{$mapKey}}";  // 此处换成自己申请的密钥
            editor.create();
        });

    </script>
@endsection