@extends('admin.layouts.base')
@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/xenon-forms.css">
    {{--http://demo.cssmoban.com/cssthemes3/mstp_115_enonadmin/forms-native.html--}}
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/setting/emailSetting')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="title"  class="form-control" size="15" placeholder='邮件标题' value="{{$search['title'] ?? '' }}" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>邮件列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <a href="{{u('admin/setting/addeEmail')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
                <a href="{{u('admin/setting/historySMail')}}"><button  class="btn  btn-grey" > <i class="layui-icon">&#xe6ed;</i>邮件记录</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>邮件连接名</th>
                    <th>发送人名</th>
                    <th>SMTP服务器</th>
                    <th>服务器端口</th>
                    <th>连接方式</th>
                    <th>邮箱登录名</th>
                    <th>排序</th>
                    <th>是否(禁用)</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$email->isEmpty())
                    @foreach($email as $item)
                    <tr>
                        <td>{{$item->title}}</td>
                        <td>{{$item->sender_name}}</td>
                        <td>{{$item->smtp_host}}</td>
                        <td>{{$item->smtp_port}}</td>
                        <td>{{$item->connect_method}}</td>
                        <td>{{$item->login_account}}</td>
                        <td>{{$item->sort}}</td>
                        <td>
                            <input class="iswitch iswitch-info"  type="checkbox"  {{($item->status == 'on')?'checked':''}}
                            onclick="modifyStatus(this,'{{$item->id}}');"  data-status="{{$item->status}}" />
                        </td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/setting/addeEmail',['e_id' => $item->id])}}" >编辑邮件连接</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a  href="{{u('admin/setting/smtpSendMail',['e_id' => $item->id])}}" >发送邮件</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/setting/delEmail')}}','e_id','{{$item->id}}');">删除邮件连接</a>
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
                {{ $email->links() }}
            @else
                {{ $email->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection
@section('js')
    <script>
        //修改状态
        function modifyStatus(obj,tar_id){
            var url = "{{u('admin/setting/modifyEmailStatus')}}";
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