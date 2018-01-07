@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">

    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>优惠券列表</b></legend>
        <div class="layui-field-box">
            <div class="form-group">
                <a href="{{u('admin/activity/addeCoupons')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>券名</th>
                    <th>描述</th>
                    <th>条件最低金额</th>
                    <th>优惠金额</th>
                    <th>起始有效时间</th>
                    <th>结束有效时间</th>
                    <th>剩余有效时间</th>
                    <th>发放张数</th>
                    <th>剩余张数</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$coupons->isEmpty())
                    @foreach($coupons as $item)
                    <tr>
                        <td>{{$item->name}}</td>
                        <td class="describe" data-value="{{$item->describe}}">{{str_limit($item->describe,12,'...')}}</td>
                        <td style="color: #dd1144;">{{$item->satisfy_fee}}</td>
                        <td style="color: #dd1144;">{{$item->reduce_fee}}</td>
                        <td>{{date('Y-m-d H:i:s',$item->valid_start)}}</td>
                        <td>{{date('Y-m-d H:i:s',$item->valid_end)}}</td>
                        <td class="timer"
                            data-time-start="{{$item->valid_start}}"
                            data-time-now="{{time()}}"
                            data-time-end="{{$item->valid_end}}">
                            {{($item->valid_start > time())?'未开始':(($item->valid_end < time())?'已失效':'')}}
                        </td>
                        <td>{{$item->grant_count}}</td>
                        <td>{{$item->surplus_count}}</td>
                        <td>{{$item->sort}}</td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/activity/addeCoupons',['cp_id' => $item->id])}}">编辑优惠券</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/activity/delCoupons')}}','cp_id','{{$item->id}}');">删除优惠券</a>
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
                {{ $coupons->links() }}
            @else
                {{ $coupons->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script>
    $('.describe').mouseenter(function(){
        var data_value = $(this).data('value');
        var that = this;
        layui.use(['layer'],function(){
            layer.tips(data_value, that,{
                tips: [1, '#3595CC']
            });
        })
    }).mouseleave(function(){
        layui.use(['layer'],function(){
            layer.closeAll();
        })
    });

    $(".timer").each(function () {
        var obj = this;
        var start = parseInt($(this).attr("data-time-start"));
        var now = parseInt($(this).attr("data-time-now"))
        var end = parseInt($(this).attr("data-time-end"));

        if((now > start) && (now < end) ){
            obj.intDiff = end - now;
           var setinterval = window.setInterval(function(){
                var day=0,
                    hour=0,
                    minute=0,
                    second=0;//时间默认值
                if(obj.intDiff > 0){
                    day = Math.floor(obj.intDiff / (60 * 60 * 24));
                    hour = Math.floor(obj.intDiff / (60 * 60)) - (day * 24);
                    minute = Math.floor(obj.intDiff / 60) - (day * 24 * 60) - (hour * 60);
                    second = Math.floor(obj.intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
                }else{
                    obj.innerHTML = '已失效';
                    clearInterval(setinterval);
                    return false;
                }
                if (minute <= 9) minute = '0' + minute;
                if (second <= 9) second = '0' + second;
                var content = "<i>"+day+"</i>天<i>"+hour+"</i>时<i>"+minute+"</i>分<i>"+second+"</i>秒";
                obj.innerHTML = content;
                obj.intDiff--;
            }, 1000);
        }
    });

</script>
@endsection