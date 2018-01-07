@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ICHECK}}/skins/all.css">
    <link rel="stylesheet" href="{{BOOTSTRAP}}/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{WEDITOR}}/dist/css/wangEditor.min.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/article/columnList')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($column)?'添加':'编辑'}}栏目</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal"action="javascript:;" role="form">
                <input type="hidden" name="c_id" value="{{empty($column->id)?'':$column->id}}" />

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">栏目名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="cname" value="{{empty($column->cname)?'':$column->cname}}" class="form-control"  placeholder="栏目名">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">父级栏目 </label>
                    <div class="col-sm-2">
                        <select name="pid" class="form-control selectpicker show-tick"  data-live-search="true">
                            <option value="0" >顶级</option>
                            @if(!$p_column->isEmpty())
                                 @foreach($p_column as $pcol)
                                     @if((!empty($column->id) && ($column->id == $pcol->id)) )
                                         @continue
                                     @endif
                                     <option value="{{$pcol->id}}" {{ (!empty($column->pid) && ($column->pid == $pcol->id))?'selected':'' }}>{{$pcol->cname}}</option>

                                 @endforeach
                            @endif
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="control-label col-sm-2">SEO搜索标题 </label>
                    <div class="col-sm-3">
                        <input type="text" name="seo_title" value="{{empty($column->seo_title)?'':$column->seo_title}}" class="form-control"  placeholder="SEO搜索标题">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">SEO搜索KEY </label>
                    <div class="col-sm-3">
                        <input type="text" name="seo_key" value="{{empty($column->seo_key)?'':$column->seo_key}}" class="form-control"  placeholder="SEO搜索KEY">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">SEO描述 </label>
                    <div class="col-sm-5">
                        <textarea name="seo_desc"  cols="30" rows="10" class="form-control"  placeholder='SEO描述'>{!! empty($column->seo_desc)?'':$column->seo_desc !!}</textarea>
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-3">
                        <input type="text" name="sort" value="{{empty($column->sort)?100:$column->sort}}" class="form-control"  placeholder="排序">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">是否开启 </label>
                    <div class="col-sm-4">
                        <label class="checkbox-inline">
                            <input type="radio" name="status"  value="on" {{(empty($column->status)||($column->status == 'on'))?'checked':''}} />&nbsp;开启</label>
                        <label class="checkbox-inline">
                            <input type="radio" name="status"  value="off" {{(!empty($column->status)&&($column->status == 'off'))?'checked':''}} />&nbsp;关闭</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">栏目内容 </label>
                    <div class="col-sm-10">
                        <textarea id="content" name="content" style="min-height: 250px;" class="WANGEDITOR">{!! empty($column->content)?'':$column->content !!}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/article/saveColumn')}}" data-jurl="{{u('admin/article/columnList')}}">确认</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection

@section('js')
    <script type="text/javascript" src="{{BOOTSTRAP}}/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="{{WEDITOR}}/dist/js/wangEditor.min.js"></script>
    <script type="text/javascript" src="{{ICHECK}}/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('input').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
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