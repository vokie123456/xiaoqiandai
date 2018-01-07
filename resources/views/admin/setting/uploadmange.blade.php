@extends('admin.layouts.base')

@section('title','个人资料')
@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
@endsection

@section('content')
    <div style="margin: 15px;padding-top: 20px;">

        <div class="form-group has-success">
            <label for="" class="control-label col-sm-1">单图上传 </label>
            <div class="col-sm-11">
               <!-- upimg-start -->
                <div class="img-box">
                    <button class="layui-btn layui-btn-primary"> <i class="layui-icon" style="color: #1EA018;">&#xe608;</i> 上传图片</button>
                    <input type="file" onchange="uploadImg(this,'mlogo');" data-url="{{u('admin/setting/upload')}}" data-durl="{{u('admin/setting/delpicture')}}" data-del="{{ADMINUI}}/images/delete2.png" style="position:absolute;top:0;left:16px;width: 115px;height: 38px; opacity:0">
                    <div class="pic_show">
                        <ul>

                        </ul>
                    </div>
                </div>
                <!-- upimg-end -->
           </div>

        </div>
        <div style="clear: both"></div>
        <hr>
        <div class="form-group has-error" style="margin-top: 10px;">
            <label for="" class="control-label col-sm-1">多图上传 </label>
            <div class="col-sm-11">
                <!-- upimg-start -->
                <div class="img-box">
                    <button class="layui-btn layui-btn-primary"> <i class="layui-icon" style="color: #1EA018;">&#xe608;</i> 上传图片</button>
                    <input type="file" onchange="uploadImg(this,'mlogo[]');" data-url="{{u('admin/setting/upload')}}" data-durl="{{u('admin/setting/delpicture')}}" data-del="{{ADMINUI}}/images/delete2.png" style="position:absolute;top:0;left:16px;width: 115px;height: 38px; opacity:0">
                    <div class="pic_show">
                        <ul>

                        </ul>
                    </div>
                </div>
                <!-- upimg-end -->
            </div>
        </div>

    </div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ADMINUI}}/js/upimg.js"></script>
@endsection