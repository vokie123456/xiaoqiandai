@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ICHECK}}/skins/all.css">
    <link rel="stylesheet" href="{{BOOTSTRAP}}/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="{{WEDITOR}}/dist/css/wangEditor.min.css">
    <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/article/manageList')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($article)?'添加':'编辑'}}文章</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal"action="javascript:;" role="form">
                <input type="hidden" name="a_id" value="{{empty($article->id)?'':$article->id}}" />

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">文章标题 </label>
                    <div class="col-sm-4">
                        <input type="text" name="title" value="{{empty($article->title)?'':$article->title}}" class="form-control"  placeholder="文章标题">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">简短标题 </label>
                    <div class="col-sm-3">
                        <input type="text" name="short_title" value="{{empty($article->short_title)?'':$article->short_title}}" class="form-control"  placeholder="简短标题">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">所属栏目 </label>
                    <div class="col-sm-3">
                        <select name="column_id"  class="form-control selectpicker show-tick" data-live-search="true">
                            @if(!$column->isEmpty())
                                @foreach($column as $col)
                                    <option value="{{$col->id}}"  {{(!empty($search['column_id']) && ( $search['column_id'] == $col->id)) ?'selected':''}}>{{$col->cname}}</option>
                                    @if(!$col->column->isEmpty())
                                        @foreach($col->column as $child_col)
                                            <option value="{{$child_col->id}}"  {{(!empty($search['column_id']) && ( $search['column_id'] == $child_col->id)) ?'selected':''}}>{{'&emsp;|--'.$child_col->cname}}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">文章关键字 </label>
                    <div class="col-sm-5">
                        <input type="text" name="keywords" value="{{empty($article->keywords)?'':$article->keywords}}" class="form-control"  placeholder="文章关键字">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">标签 </label>
                    <div class="col-sm-5">
                        <input type="text" name="tags" value="{{empty($article->tags)?'':$article->tags}}" class="form-control"  placeholder="标签">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">来源 </label>
                    <div class="col-sm-5">
                        <input type="text" name="source" value="{{empty($article->source)?'':$article->source}}" class="form-control"  placeholder="来源">
                    </div>
                </div>
                @php
                   $attribute = [
                        'head' => '头条','recommend' => '推荐','link' => '链接','original' => '原创','roll' => '滚动'
                   ];
                @endphp
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">属性 </label>
                    <div class="col-sm-10">
                        @foreach($attribute as $key => $value)
                            <label for="attribute_{{$loop->index}}">
                                {{$value}}<input type="checkbox" name="attribute[]"  value="{{$key}}" {{(!empty($article->attribute) && in_array($key,explode(',',$article->attribute)))?'checked':''}}  />
                            </label>&emsp;&emsp;

                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">文章封面 </label>
                    <div class="col-sm-10">
                        <!-- upimg-start -->
                        <div class="img-box">
                            <button class="layui-btn layui-btn-primary"> <i class="layui-icon" style="color: #1EA018;">&#xe608;</i> 上传图片</button>
                            <input type="file" onchange="uploadImg(this,'cover');" data-url="{{u('admin/setting/upload',['compress' => 200])}}" data-durl="{{u('admin/setting/delpicture')}}" data-del="{{ADMINUI}}/images/delete2.png" style="position:absolute;top:0;left:16px;width: 115px;height: 38px; opacity:0">
                            <div class="pic_show">
                                <ul>
                                    @if(!empty($article->cover))
                                        <li>
                                            <div class="pic_box">
                                                <img src="{{$article->cover}}">
                                                <input type="hidden" name="cover" value="{{$article->cover}}">
                                                <div class="delete_pic" onclick="delimg(this,'{{u('admin/setting/delpicture')}}');" >
                                                    <img src="{{ADMINUI}}/images/delete2.png" />
                                                </div>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <!-- upimg-end -->
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-3">
                        <input type="text" name="sort" value="{{empty($article->sort)?100:$article->sort}}" class="form-control"  placeholder="排序">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">栏目内容 </label>
                    <div class="col-sm-10">
                        <textarea id="content" name="content" style="min-height: 250px;" class="WANGEDITOR">{!! empty($article->content)?'':$article->content !!}</textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/article/saveArticle')}}" data-jurl="{{u('admin/article/manageList')}}">确认</button>
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
    <script type="text/javascript" src="{{ADMINUI}}/js/upimg.js"></script>
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