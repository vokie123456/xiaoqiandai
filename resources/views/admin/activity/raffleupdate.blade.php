@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
    <link rel="stylesheet" href="{{BOOTSTRAP}}/css/bootstrap-select.min.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/activity/raffle')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($raffle)?'添加':'编辑'}}抽奖奖项</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal"action="javascript:;" role="form">
                <input type="hidden" name="r_id" value="{{empty($raffle->id)?'':$raffle->id}}" />

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">奖励名称 </label>
                    <div class="col-sm-3">
                        <input type="text" name="name" value="{{empty($raffle->name)?'':$raffle->name}}" class="form-control"  placeholder="奖励名称">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">奖项logo图 </label>
                    <div class="col-sm-10">
                        <!-- upimg-start -->
                        <div class="img-box">
                            <button class="layui-btn layui-btn-primary"> <i class="layui-icon" style="color: #1EA018;">&#xe608;</i> 上传图片</button><span style="color: #9ea6b9;margin-left: 1.5em;">[ 推荐尺寸：300 * 300 ]</span>
                            <input type="file" onchange="uploadImg(this,'logo');" data-url="{{u('admin/setting/upload',['compress' => 100])}}" data-durl="{{u('admin/setting/delpicture')}}" data-del="{{ADMINUI}}/images/delete2.png" style="position:absolute;top:0;left:16px;width: 115px;height: 38px; opacity:0">
                            <div class="pic_show">
                                <ul>
                                    @if(!empty($raffle->logo))
                                        <li>
                                            <div class="pic_box">
                                                <img src="{{$raffle->logo}}">
                                                <input type="hidden" name="logo" value="{{$raffle->logo}}">
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
                    <label for="" class="control-label col-sm-2">中奖几率(为整数)</label>
                    <div class="col-sm-3">
                        <input type="text" name="odds" value="{{empty($raffle->odds)?'':$raffle->odds}}" class="form-control"  placeholder="中奖几率">
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="control-label col-sm-2">奖品内容 </label>
                    <div class="col-sm-3">
                        <input type="text" name="prizes" value="{{empty($raffle->prizes)?'':$raffle->prizes}}" class="form-control"  placeholder="奖品内容">
                    </div>
                </div>


                <div class="form-group">
                    <label for="" class="control-label col-sm-2">奖品类型 </label>
                    <div class="col-sm-3">
                        <select name="type"  class="form-control selectpicker show-tick" data-live-search="true">
                            <option value="0" {{empty($raffle->type)?'selected':''}}>暂无</option>
                            <option value="1" {{(!empty($raffle->type) && ($raffle->type == 1))?'selected':''}}>储值金</option>
                            <option value="2" {{(!empty($raffle->type) && ($raffle->type == 2))?'selected':''}}>积分</option>
                            <option value="3" {{(!empty($raffle->type) && ($raffle->type == 3))?'selected':''}}>积点</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">奖项的值 </label>
                    <div class="col-sm-3">
                        <input type="text" name="value" value="{{empty($raffle->value)?0:$raffle->value}}" class="form-control"  placeholder="奖项的值">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-3">
                        <input type="text" name="sort" value="{{empty($raffle->sort)?100:$raffle->sort}}" class="form-control"  placeholder="排序">
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/activity/rafflesave')}}" data-jurl="{{u('admin/activity/raffle')}}">确认</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ADMINUI}}/js/upimg.js"></script>
<script type="text/javascript" src="{{BOOTSTRAP}}/js/bootstrap-select.min.js"></script>
<script>

</script>
@endsection