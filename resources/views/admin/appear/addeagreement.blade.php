@extends('admin.layouts.base')
@section('css')
<link rel="stylesheet" href="{{BOOTSTRAP}}/css/bootstrap-select.min.css">
<link rel="stylesheet" href="{{WEDITOR}}/dist/css/wangEditor.min.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/appear/agreement')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($agreement)?'添加':'编辑'}}协议</b></legend>
        <div class="layui-field-box">
            <form class="form-horizontal" role="form">
                <input type="hidden" name="agr_id" value="{{empty($agreement->id)?'':$agreement->id}}" />
                <div class="form-group has-warning">
                    <label for="" class="control-label col-sm-2">协议名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="name" value="{{empty($agreement->name)?'':$agreement->name}}" class="form-control"  placeholder="协议名">
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="" class="control-label col-sm-2">协议别名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="alias" value="{{empty($agreement->alias)?'':$agreement->alias}}" class="form-control"  placeholder="协议别名">
                    </div>
                </div>
                @php
                  $agreeArr = [1 => '普通',2 => '特殊'];
                @endphp
                <div class="form-group  has-success">
                    <label for="" class="control-label col-sm-2">协议类型 </label>
                    <div class="col-sm-3">
                        <select name="agree_type"  class="form-control selectpicker show-tick" data-live-search="true">
                            @foreach($agreeArr as $key=>$item)
                                <option value="{{$key}}" {{(!empty($agreement->agree_type) && ($agreement->agree_type == $key))?'selected':''}}>{{$item}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">链接路由 </label>
                    <div class="col-sm-4">
                        <input type="text" name="link_addr" style="font-weight: bold;" placeholder="链接路由(可填)" class="form-control" value="{{empty($agreement->link_addr)?'':$agreement->link_addr}}" />
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-4">
                        <input type="text" name="sort" class="form-control" value="{{empty($agreement->sort)?100:$agreement->sort}}" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">协议介绍 </label>
                    <div class="col-sm-10">
                        <textarea id="content" name="content" style="min-height: 250px;" class="WANGEDITOR">{!! empty($agreement->content)?'':$agreement->content !!}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/appear/saveAgreement')}}" data-jurl="{{u('admin/appear/agreement')}}">确认</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{BOOTSTRAP}}/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="{{WEDITOR}}/dist/js/wangEditor.min.js"></script>
<script type="text/javascript" >
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