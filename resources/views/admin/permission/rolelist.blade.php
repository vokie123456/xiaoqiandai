@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/permission/roleList')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="rname" value="{{empty($search['rname'])?'':$search['rname']}}"  class="form-control" size="15" placeholder="角色名" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>角色列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <a href="{{u('admin/permission/addeRole')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>角色名</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$role->isEmpty())
                    @foreach($role as $item)
                    <tr>
                      <td>{{$item->id}}</td>
                      <td>{{$item->rname}}</td>
                      <td>{{date('Y-m-d H:i:s',$item->created_at)}}</td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/permission/addeRole',['role_id' => $item->id])}}">角色编辑</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/permission/deltoRole')}}','role_id',{{$item->id}});">删除角色</a>
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
                {{ $role->links() }}
            @else
                {{ $role->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection