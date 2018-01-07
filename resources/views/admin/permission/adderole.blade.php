@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/permission/roleList')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($role)?'添加':'编辑'}}角色</b></legend>

        <div class="layui-field-box">
            <form class="form-horizontal" role="form">
                <input type="hidden" name="role_id" value="{{empty($role->id)?'':$role->id}}" />
                <div class="form-group">
                    <label for="" class="control-label col-sm-1">角色名 </label>
                    <div class="col-sm-3">
                        <input type="text" name="rname" value="{{empty($role->rname)?'':$role->rname}}" class="form-control"  placeholder="角色名">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="control-label col-sm-1">权限选择 </label>
                    <div class="col-sm-5" >
                        <ul id="permissids">

                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/permission/saveRinfo')}}" data-jurl="{{u('admin/permission/roleList')}}">确认</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script>
    var nodes = {!! json_encode($nodes) !!};
    layui.use('tree',function(){
        layui.tree({
            elem: '#permissids', //传入元素选择器
            check: 'checkbox', //勾选风格
            skin: 'as', //设定皮肤
            drag: true,//点击每一项时是否生成提示信息
            checkboxName: 'permiss_ids[]',//复选框的name属性值
            checkboxStyle: "",//设置复选框的样式，必须为字符串，css样式怎么写就怎么写
            nodes: nodes
        });
    });

</script>
@endsection