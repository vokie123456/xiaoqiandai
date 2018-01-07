@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
    <link rel="stylesheet" href="{{ICHECK}}/skins/all.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/permission/adminerList')}}" >
            <button class="btn btn-secondary btn-default" style="color: #1AA094;">
            <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($adminer)?'添加':'编辑'}}管理员</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal" role="form" action="javascript:;">
                <input type="hidden" name="adminer_id" value="{{empty($adminer->id)?'':$adminer->id}}" />

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">管理员名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="name" value="{{empty($adminer->name)?'':$adminer->name}}" class="form-control"  placeholder="管理员名">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-sm-2">账号 </label>
                    <div class="col-sm-3">
                        <input type="text" name="account" value="{{empty($adminer->account)?'':$adminer->account}}" class="form-control"  placeholder="账号">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">密码 </label>
                    <div class="col-sm-3">
                        <div class="col-sm-8" style="padding: 0px;">
                            <input type="password" name="password" id="password" value="{{empty($adminer->password)?'':decrypt($adminer->password)}}" class="form-control"  placeholder="(以数字字母或?%&=_-.)6到20位">
                        </div>
                        <div class="col-sm-4">
                            <button class="btn btn-danger"  onmousedown="$('#password').attr('type','text')" onmouseup="$('#password').attr('type','password')">查看</button>
                        </div>

                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">所属角色 </label>
                    <div class="col-sm-3">
                        <select name="role_id" class="form-control" >
                            @if(!$role->isEmpty())
                                @foreach($role as $r)
                                 <option value="{{$r->id}}" {{(!empty($adminer) && ($r->id == $adminer->role_id) )?'selected':''}}>{{$r->rname}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">头像 </label>
                    <div class="col-sm-10">
                        <!-- upimg-start -->
                        <div class="img-box">
                            <button class="layui-btn layui-btn-primary"> <i class="layui-icon" style="color: #1EA018;">&#xe608;</i> 上传图片</button>
                            <input type="file" onchange="uploadImg(this,'head_img');" data-url="{{u('admin/setting/upload',['compress' => 50])}}" data-durl="{{u('admin/setting/delpicture')}}" data-del="{{ADMINUI}}/images/delete2.png" style="position:absolute;top:0;left:16px;width: 115px;height: 38px; opacity:0">
                            <div class="pic_show">
                                <ul>
                                    @if(!empty($adminer->head_img))
                                        <li>
                                            <div class="pic_box">
                                                <img src="{{$adminer->head_img}}">
                                                <input type="hidden" name="head_img" value="{{$adminer->head_img}}">
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
                    <label for="" class="control-label col-sm-2">电话 </label>
                    <div class="col-sm-3">
                        <input type="text" name="phone" value="{{empty($adminer->phone)?'':$adminer->phone}}" class="form-control"  placeholder="电话(可填)">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-2">个人邮箱 </label>
                    <div class="col-sm-3">
                        <input type="text" name="email" value="{{empty($adminer->email)?'':$adminer->email}}" class="form-control"  placeholder="邮箱(可填)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/permission/saveAdata')}}" data-jurl="{{u('admin/permission/adminerList')}}">确认</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ADMINUI}}/js/upimg.js"></script>
<script type="text/javascript" src="{{ICHECK}}/icheck.min.js"></script>
<script>
    $(document).ready(function(){
        $('input').iCheck({
            checkboxClass: 'icheckbox_minimal-green',
            radioClass: 'iradio_minimal-green',
            increaseArea: '20%' // optional
        });
    });
</script>
@endsection