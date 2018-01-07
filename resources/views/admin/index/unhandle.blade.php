@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{$prepage}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
            {{$handle}},<span id="dtime">4</span>秒后自动返回上一页
        </a>
    </blockquote>

</div>
@endsection
@section('js')
<script>
  var alltime = 4;
  window.setInterval(function(){
      alltime--;
      $("#dtime").html(alltime);
      if(alltime == 0){
          window.location.href = "{{$prepage}}";
      }
  },1000)
</script>
@endsection