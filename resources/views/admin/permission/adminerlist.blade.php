@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/permission/adminerList')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="name" value="{{empty($search['name'])?'':$search['name']}}" class="form-control" size="15" placeholder="管理员名" />
            </div>
            <div class="form-group">
                <input type="text" name="account" value="{{empty($search['account'])?'':$search['account']}}"  class="form-control" size="15" placeholder="账号" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>管理员列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">

                <a href="{{u('admin/permission/addeAdminer')}}"><button type="submit" class="btn btn-secondary  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th style="max-width: 50px;">头像</th>
                    <th>管理员名</th>
                    <th>角色类</th>
                    <th>账号</th>
                    <th>电话</th>
                    <th>创建时间</th>
                    <th>上次登录时间</th>
                    <th>超级管理员</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$adminer->isEmpty())
                    @foreach($adminer as $item)
                    <tr>
                      <td style="width: 50px;"><img src="{{$item->head_img}}" style="width: 50px;border-radius: 50%;"></td>
                      <td>{{$item->name}}</td>
                      <td>{{$item->rname}}</td>
                      <td>{{$item->account}}</td>
                      <td>{{$item->phone}}</td>
                      <td>{{date('Y-m-d H:i:s',$item->created_at)}}</td>
                      <td>{{empty($item->history)?'尚未登录':date('Y-m-d H:i:s',unserialize($item->history)[0])}}</td>
                      <td>{{empty($item->is_super)?'否':'是'}}</td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/permission/addeAdminer',['adminer_id' => $item->id])}}">编辑管理员</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/permission/delAdminer')}}','adminer_id',{{$item->id}});">删除管理员</a>
                                  </li>

                              </ul>
                          </div>
                      </td>
                    </tr>
                   @endforeach
                @endif
                </tbody>
            </table>
            @if(!empty($search))
                {{ $adminer->appends($search)->links() }}
                @else
                {{ $adminer->links() }}
            @endif

        </div>
    </fieldset>
</div>
@endsection