@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ICHECK}}/skins/all.css">
    <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/setting/webset')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($config)?'添加':'编辑'}}配置</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal"action="javascript:;" role="form">
                <input type="hidden" name="c_id" value="{{empty($config->id)?'':$config->id}}" />

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">配置名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="means" value="{{empty($config->means)?'':$config->means}}" class="form-control"  placeholder="配置名">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">类型 </label>
                    <div class="col-sm-3">
                        <input type="text" name="mold" value="{{empty($config->mold)?'':$config->mold}}" class="form-control"  placeholder="类型">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">别名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="name" value="{{empty($config->name)?'':$config->name}}" class="form-control"  placeholder="别名">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">配置值 </label>
                    <div class="col-sm-5">
                        <textarea name="content" @if(!empty($config->name) && (($config->name == 'CARD_LOGO') || ($config->name == 'CARD_BACKGROUND'))) readonly @endif cols="30" rows="10" class="form-control"  placeholder='配置值'>{!! empty($config->content)?'':$config->content !!}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-3">
                        <input type="text" name="sort" value="{{empty($config->sort)?100:$config->sort}}" class="form-control"  placeholder="排序">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">是否展示 </label>
                    <div class="col-sm-4">
                        <label class="checkbox-inline">
                            <input type="radio" name="show"  value="1" {{(empty($config->show)||($config->show == 1))?'checked':''}} />&nbsp;展示</label>
                        <label class="checkbox-inline">
                            <input type="radio" name="show"  value="2" {{(!empty($config->show)&&($config->show == 2))?'checked':''}} />&nbsp;不展示</label>
                        <label class="checkbox-inline">
                            <input type="radio" name="show"  value="3" {{(!empty($config->show)&&($config->show == 3))?'checked':''}} />不展示内容</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/setting/saveConfig')}}" data-jurl="{{u('admin/setting/webset')}}">确认</button>
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