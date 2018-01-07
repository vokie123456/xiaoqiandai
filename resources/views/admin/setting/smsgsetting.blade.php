@extends('admin.layouts.base')

@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/xenon-forms.css">
    {{--http://demo.cssmoban.com/cssthemes3/mstp_115_enonadmin/forms-native.html--}}
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/setting/smsgSetting')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="title"  class="form-control" size="15" placeholder='短信标题' value="{{$search['title'] ?? '' }}" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>短信列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <a href="{{u('admin/setting/addeSmsg')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>标题</th>
                    <th>AccessKeyId</th>
                    <th>accessKeySecret</th>
                    <th>模板CODE</th>
                    <th>短信签名</th>
                    <th>模板内容</th>
                    <th>排序</th>
                    <th>是否(禁用)</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$smsg->isEmpty())
                    @foreach($smsg as $item)
                    <tr>
                        <td>{{$item->title}}</td>
                        <td>{{$item->AccessKeyId}}</td>
                        <td>{{$item->accessKeySecret}}</td>
                        <td>{{$item->template_code}}</td>
                        <td>{{$item->sign}}</td>
                        <td>{{str_limit($item->template_desc,15,'...')}}</td>
                        <td>{{$item->sort}}</td>
                        <td>
                            <input class="iswitch iswitch-orange"  type="checkbox"  {{($item->status == 'on')?'checked':''}}
                            onclick="modifyStatus(this,'{{$item->id}}');"  data-status="{{$item->status}}" />
                        </td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/setting/addeSmsg',['s_id' => $item->id])}}" >编辑短信</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/setting/delSmsg')}}','s_id','{{$item->id}}');">删除短信</a>
                                  </li>
                              </ul>
                          </div>
                      </td>

                    </tr>
                   @endforeach
                @endif
                </tbody>
            </table>
            @if(empty($search))
                {{ $smsg->links() }}
            @else
                {{ $smsg->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script>
    //修改状态
    function modifyStatus(obj,tar_id){
        var url = "{{u('admin/setting/modifySmsgStatus')}}";
        var status = $(obj).attr('data-status');
        $.post(url,{tar_id:tar_id,status:status},function(res){
            layui.use(['layer'],function(){
                if(res.code > 0){
                    if(parseInt(status) == 'on'){
                        //原本是上架的,选中的
                        $(obj).prop('checked',true);
                    }else{
                        //原本下架的,未选中的
                        $(obj).prop('checked',false);
                    }
                    layer.msg(res.msg,{maxWidth:150,time:700});
                }else{
                    if(parseInt(status) == 'on'){
                        //原本是上架的,选中的
                        $(obj).attr('data-status',2);
                    }else{
                        //原本下架的,未选中的
                        $(obj).attr('data-status',1);
                    }
                    layer.msg('操作成功',{maxWidth:150,time:700});
                }
            })
        },'json');
    }
</script>
@endsection