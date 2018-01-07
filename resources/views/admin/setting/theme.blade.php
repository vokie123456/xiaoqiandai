@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ICHECK}}/skins/all.css">
@endsection
@section('content')
<div style="margin: 15px;">
    <fieldset class="layui-elem-field">
        <legend><b>设置主题</b></legend>
        <div class="layui-field-box">
            <form action="javascript:;">
                <div class="row">
                    @foreach($theme as $item)
                    <div class="col-xs-6">
                        <div class="thumbnail">
                            <img src="{{$item->content}}" alt="{{$item->means}}">
                            <div class="caption" style="text-align: center;">
                                <input type="radio" name="theme" {{($item->show == 1)?'checked':''}} value="{{$item->name}}" />
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="submitNdata(this);" data-url="{{u('admin/setting/themeSave')}}" data-jurl="{{u('admin/public/login')}}">确认</button>
                </div>
            </form>
        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ICHECK}}/icheck.min.js"></script>
<script>
    $('input').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });
</script>
@endsection