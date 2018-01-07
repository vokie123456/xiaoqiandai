@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ICHECK}}/skins/all.css">
    <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/setting/smsgSetting')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($smsg)?'添加':'编辑'}}短信</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal"action="javascript:;" role="form">
                <input type="hidden" name="s_id" value="{{empty($smsg->id)?'':$smsg->id}}" />

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">标题 </label>
                    <div class="col-sm-3">
                        <input type="text" name="title" value="{{empty($smsg->title)?'':$smsg->title}}" class="form-control"  placeholder="标题">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">AccessKeyId </label>
                    <div class="col-sm-3">
                        <input type="text" name="AccessKeyId" value="{{empty($smsg->AccessKeyId)?'':$smsg->AccessKeyId}}" class="form-control"  placeholder="AccessKeyId">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">accessKeySecret </label>
                    <div class="col-sm-3">
                        <input type="text" name="accessKeySecret" value="{{empty($smsg->accessKeySecret)?'':$smsg->accessKeySecret}}" class="form-control"  placeholder="accessKeySecret">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">短信模板的CODE </label>
                    <div class="col-sm-3">
                        <input type="text" name="template_code" value="{{empty($smsg->template_code)?'':$smsg->template_code}}" class="form-control"  placeholder="短信模板的模板CODE">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">短信签名（状态必须是验证通过） </label>
                    <div class="col-sm-3">
                        <input type="text" name="sign" value="{{empty($smsg->sign)?'':$smsg->sign}}" class="form-control"  placeholder="短信签名（状态必须是验证通过）">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">模板内容 </label>
                    <div class="col-sm-5">
                        <textarea name="template_desc"  cols="30" rows="10" class="form-control"  placeholder='短信模板的模板内容(可填)'>{!! empty($smsg->template_desc)?'':$smsg->template_desc !!}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-3">
                        <input type="text" name="sort" value="{{empty($smsg->sort)?100:$smsg->sort}}" class="form-control"  placeholder="排序">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">是否开启 </label>
                    <div class="col-sm-4">
                        <label class="checkbox-inline">
                            <input type="radio" name="status"  value="on" {{(empty($smsg->status)||($smsg->status == 'on'))?'checked':''}} />&nbsp;开启</label>
                        <label class="checkbox-inline">
                            <input type="radio" name="status"  value="off" {{(!empty($smsg->status)&&($smsg->status == 'off'))?'checked':''}} />&nbsp;关闭</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/setting/saveSmsg')}}" data-jurl="{{u('admin/setting/smsgSetting')}}">确认</button>
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