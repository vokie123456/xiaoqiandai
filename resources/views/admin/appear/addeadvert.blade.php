@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
    <link rel="stylesheet" href="{{ICHECK}}/skins/all.css">
    <link rel="stylesheet" href="{{BOOTSTRAP}}/css/bootstrap-select.min.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/appear/advert')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($advert)?'添加':'编辑'}}广告</b></legend>
        <div class="layui-field-box">
            <form class="form-horizontal" role="form">
                <input type="hidden" name="adver_id" value="{{empty($advert->id)?'':$advert->id}}" />
                <div class="form-group has-error">
                    <label for="" class="control-label col-sm-2">广告名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="name" value="{{empty($advert->name)?'':$advert->name}}" class="form-control"  placeholder="广告名">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">对应页面 </label>
                    <div class="col-sm-3">
                        <select name="page" class="form-control selectpicker show-tick" data-live-search="true">
                            @foreach(config('appear.page') as $key=>$value)
                                <option value="{{$key}}" {{(!empty($advert->page) && ($advert->page == $key))?'selected':''}}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">页面位置 </label>
                    <div class="col-sm-3">
                        <select name="position" class="form-control selectpicker show-tick" data-live-search="true">
                            @foreach(config('appear.position') as $k=>$v)
                                <option value="{{$k}}" {{(!empty($advert->position) && ($advert->position == $k))?'selected':''}}>{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">描述 </label>
                    <div class="col-sm-3">
                        <textarea name="desc"  cols="15" rows="5" class="form-control"  placeholder='描述(可填)'>{!! empty($advert->desc)?'':$advert->desc !!}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">链接地址 </label>
                    <div class="col-sm-4">
                        <input type="text" name="link_addr" style="font-weight: bold;" placeholder="链接地址(可填)" class="form-control" value="{{empty($advert->link_addr)?'javascript:void(0);':$advert->link_addr}}" />
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">展示图 </label>
                    <div class="col-sm-10">
                        <!-- upimg-start -->
                        <div class="img-box">
                            <button class="layui-btn layui-btn-primary"> <i class="layui-icon" style="color: #1EA018;">&#xe608;</i> 上传图片</button>
                            <input type="file" onchange="uploadImg(this,'show_img');" data-url="{{u('admin/setting/upload',['compress' => 200])}}" data-durl="{{u('admin/setting/delpicture')}}" data-del="{{COMIMG}}/delete2.png" style="position:absolute;top:0;left:16px;width: 115px;height: 38px; opacity:0">
                            <div class="pic_show">
                                <ul>
                                    @if(!empty($advert->show_img))
                                        <li>
                                            <div class="pic_box">
                                                <img src="{{$advert->show_img}}">
                                                <input type="hidden" name="show_img" value="{{$advert->show_img}}">
                                                <div class="delete_pic" onclick="delimg(this,'{{u('admin/setting/delpicture')}}');" >
                                                    <img src="{{COMIMG}}/delete2.png" />
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
                    <label for="" class="control-label col-sm-2">状态 </label>
                    <div class="col-sm-4">
                        <label class="checkbox-inline">
                            <input type="radio" name="status"  value="1" {{(empty($advert->status)||($advert->status == 1))?'checked':''}} />&nbsp;正常
                        </label>
                        <label class="checkbox-inline">
                            <input type="radio" name="status"  value="2" {{(!empty($advert->status)&&($advert->status == 2))?'checked':''}} /> 禁用
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-4">
                        <input type="text" name="sort" class="form-control" value="{{empty($advert->sort)?100:$advert->sort}}" />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/appear/saveAdvert')}}" data-jurl="{{u('admin/appear/advert')}}">确认</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ICHECK}}/icheck.min.js"></script>
<script type="text/javascript" src="{{ADMINUI}}/js/upimg.js"></script>
<script type="text/javascript" src="{{BOOTSTRAP}}/js/bootstrap-select.min.js"></script>
<script type="text/javascript" >
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
    });
</script>
@endsection