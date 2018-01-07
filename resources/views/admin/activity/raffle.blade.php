@extends('admin.layouts.base')

@section('content')
    @php
      $type = [
        0 => '暂无',
        1 => '储值金',
        2 => '积分',
        3 => '积点',
      ];
    @endphp
<div style="margin: 15px;">

    <fieldset class="layui-elem-field">
        <legend><b>抽奖设置</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <a href="{{u('admin/activity/raffleupdate')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>奖励名称</th>
                    <th>奖项logo</th>
                    <th>中奖几率(*/{{($raffle->isEmpty())?0:$raffle->sum('odds')}})</th>
                    <th>奖品内容类型</th>
                    <th>奖项的值</th>
                    <th>奖品内容</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$raffle->isEmpty())
                    @foreach($raffle as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td><img style="max-width: 100px;" src="{{$item->logo}}" alt=""></td>
                        <td>{{$item->odds}}</td>
                        <td>{{$type[$item->type]}}</td>
                        <td>{{$item->value}}</td>
                        <td>{{$item->prizes}}</td>
                        <td>{{$item->sort}}</td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/activity/raffleupdate',['r_id' => $item->id])}}" >编辑抽奖设置</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a  href="javascript:;" onclick="getpdel('{{u('admin/activity/delraffle')}}','r_id','{{$item->id}}')" >删除抽奖</a>
                                  </li>
                              </ul>
                          </div>
                      </td>

                    </tr>
                   @endforeach
                @endif
                </tbody>
            </table>
                {{ $raffle->links() }}

        </div>
    </fieldset>
</div>
@endsection