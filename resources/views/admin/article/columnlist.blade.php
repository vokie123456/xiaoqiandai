@extends('admin.layouts.base')

@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/xenon-forms.css">
    {{--http://demo.cssmoban.com/cssthemes3/mstp_115_enonadmin/forms-native.html--}}
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form action="{{u('admin/article/columnList')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="cname"  class="form-control" size="15" placeholder='栏目名' value="{{$search['cname'] ?? '' }}" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>栏目列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <a href="{{u('admin/article/addeColumn')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>栏目名</th>
                    <th>seo搜索标题</th>
                    <th>seo搜索key</th>
                    <th>seo描述</th>
                    <th>创建时间</th>
                    <th>排序</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$column->isEmpty())
                    @foreach($column as $item)
                   <tr>
                        <td>{{$item->cname}}</td>
                        <td>{{str_limit($item->seo_title,15,'...')}}</td>
                        <td>{{str_limit($item->seo_key,15,'...')}}</td>
                        <td>{{str_limit($item->seo_desc,15,'...')}}</td>
                        <td>{{date('Y-m-d',$item->created_at)}}</td>
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
                                      <a  href="{{u('admin/article/addeColumn',['c_id' => $item->id])}}" >编辑栏目</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/article/delColumn')}}','c_id','{{$item->id}}');">删除栏目</a>
                                  </li>
                              </ul>
                          </div>
                      </td>
                    </tr>
                    @if(!$item->column->isEmpty())
                        @foreach($item->column as $child)
                            <tr>
                                <td>{{'&emsp;|--'.$child->cname}}</td>
                                <td>{{str_limit($child->seo_title,15,'...')}}</td>
                                <td>{{str_limit($child->seo_key,15,'...')}}</td>
                                <td>{{str_limit($child->seo_desc,15,'...')}}</td>
                                <td>{{date('Y-m-d',$child->created_at)}}</td>
                                <td>{{$child->sort}}</td>
                                <td>
                                    <input class="iswitch iswitch-orange"  type="checkbox"  {{($child->status == 'on')?'checked':''}}
                                    onclick="modifyStatus(this,'{{$child->id}}');"  data-status="{{$child->status}}" />
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                            操作 <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                            <li>
                                                <a  href="{{u('admin/article/addeColumn',['c_id' => $child->id])}}" >编辑栏目</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a href="javascript:;" onclick="getpdel('{{u('admin/article/delColumn')}}','c_id','{{$child->id}}');">删除栏目</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                   @endforeach
                @endif
                </tbody>
            </table>
            @if(empty($search))
                {{ $column->links() }}
            @else
                {{ $column->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script>

    //修改状态
    function modifyStatus(obj,tar_id){
        var url = "{{u('admin/article/modifyColumnStatus')}}";
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