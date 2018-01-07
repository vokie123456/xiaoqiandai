@extends('admin.layouts.base')


@section('css')

    <link rel="stylesheet" href="{{ADMINUI}}/ace/css/ace.min.css">
    <link rel="stylesheet" href="{{ADMINUI}}/ace/icon/css/font-awesome.min.css">
@endsection
@section('content')
    @php
        $hot_color = ['pink','yellow','danger','warning','success','inverse','purple','info','grey',''];
    @endphp
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- 提示 -->
                <div class="alert alert-block alert-success">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    <i class="ace-icon fa fa-user green"></i>
                    @php
                        $weekarray=array("日","一","二","三","四","五","六");
                    @endphp
                    <!-- TODO -->
                    欢迎您：{{$adminer->name}} ！<span class="timer">现在的时间为：{{date('Y年m月d日')}}&emsp;星期{{ $weekarray[date('w')]}}&emsp;{{date('H时i分s秒')}}</span>&emsp; <button class="btn btn-xs btn-default maintain" onclick="lookWeath();">查看天气</button>
                </div>
                <div class="row">
                    <div class="space-6"></div>
                    <div class="col-sm-7 infobox-container">
                        <div class="infobox infobox-green col-xs-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-folder"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="infobox-data-number">{{count($today_article)}}</span>
                                <div class="infobox-content">今日普通文章数</div>
                            </div>
                            <div class="stat stat-important">{{$decline_rate}}%</div>
                        </div>
                        <div class="infobox infobox-blue col-xs-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-user"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="infobox-data-number">0</span>
                                <div class="infobox-content">今日增加会员</div>
                            </div>
                            <div class="stat stat-important">0%</div>
                        </div>
                        <div class="infobox infobox-pink col-xs-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-comment"></i>
                            </div>
                            <div class="infobox-data">
                                <span class="infobox-data-number">0</span>
                                <div class="infobox-content">今日评论</div>
                            </div>
                            <div class="stat stat-important">0%</div>
                        </div>
                        <div class="infobox infobox-orange infobox-dark col-xs-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-folder"></i>
                            </div>

                            <div class="infobox-data">
                                <div class="infobox-content">总文章数</div>
                                <a href="#"><div class="infobox-content">{{$all_artcile_count}}</div></a>
                            </div>
                        </div>
                        <div class="infobox infobox-green infobox-dark col-xs-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-users"></i>
                            </div>
                            <div class="infobox-data">
                                <div class="infobox-content">总会员数</div>
                                <a href="#"><div class="infobox-content">0</div></a>
                            </div>
                        </div>
                        <div class="infobox infobox-orange2 infobox-dark col-xs-12 col-sm-6 col-md-6 col-lg-4">
                            <div class="infobox-icon">
                                <i class="ace-icon fa fa-comment"></i>
                            </div>
                            <div class="infobox-data">
                                <div class="infobox-content">总评论数</div>
                                <a href="#"><div class="infobox-content">0</div></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="space-6"></div>
                            </div>
                        </div>
                       	<div class="widget-box sl-indextop10 text-left">
                            <div class="widget-header">
                                <h5 class="widget-title"><span style="font-size:14px; font-family:Microsoft YaHei">框架&amp;系统信息</span></h5>

                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <p class="alert alert-danger sl-line-height25">

                                        操作系统：{{$serverInfo['server']}}<br/>
                                        运行环境：{{$serverInfo['1']}}<br/>
                                        主机名：{{$serverInfo['2']}}<br/>
                                        WEB服务端口：{{$serverInfo['3']}}<br/>
                                        网站文档目录：{{$serverInfo['4']}}<br/>
                                        浏览器信息：{{$serverInfo['5']}}<br/>
                                        通信协议：{{$serverInfo['6']}}<br/>
                                        请求方法：{{$serverInfo['7']}}<br/>
                                        Laravel版本：{{$serverInfo['8']}}<br/>
                                        上传附件限制：{{$serverInfo['9']}}<br/>
                                        允许最大内存：{{$serverInfo['10']}}<br/>
                                        执行时间限制：{{$serverInfo['11']}}<br/>
                                        服务器时间：{{$serverInfo['12']}}<br/>
                                        北京时间：{{$serverInfo['13']}}<br/>
                                        服务器域名：{{$serverInfo['14']}}<br/>
                                        用户的IP地址：{{$serverInfo['15']}}<br/>
                                        剩余空间：{{$serverInfo['16']}}<br/>

                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="widget-box sl-indextop10 text-left">
                            <div class="widget-header">
                                <h5 class="widget-title"><span style="font-size:14px; font-family:Microsoft YaHei">开发团队&amp;贡献者</span></h5>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <p class="alert alert-info sl-line-height25">

                                        开发团队：wang（<i class="ace-icon fa fa-qq"></i>:107465726）、zhang（<i class="ace-icon fa fa-qq"></i>:247073050）、yuan（<i class="ace-icon fa fa-qq"></i>:9572763）、liang（<i class="ace-icon fa fa-qq"></i>:1932375599）<br>
                                        代码贡献：zhanghao<br>
                                    </p>

                                </div>
                            </div>
                        </div>					</div>
                    <div class="vspace-12-sm"></div>
                    <div class="col-sm-5">
                        <!-- 安全检测开始 -->
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <i class="ace-icon fa fa-bolt"></i>
                                <span class="icon-dashboard"></span> 系统安全检测
                            </div>
                            <div class="panel-body">
                                <p class="text-danger"><span class="glyphicon glyphicon-info-sign"></span> 数据库连接密码为弱密码，安全起见，增强密码！</p>
                                <p class="text-warning"><span class="glyphicon glyphicon-info-sign"></span> 当前系统运行在调试模式，可能会影响运行性能及安全！</p>

                                <p class="text-success"><span class="glyphicon glyphicon-info-sign"></span>  当前系统{{ (getenv('APP_DEBUG') == "true")?'已开启APP_DEBUG模式':'已关闭APP_DEBUG模式' }}</p>
                                <!--[if lte IE 8]>
                                <p class="text-warning">
                                    <span class="glyphicon glyphicon-info-sign"></span> 浏览器版本过低！
                                </p>
                                <![endif]-->
                            </div>
                        </div>
                        <!-- 安全检测结束 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="ace-icon fa fa-wrench"></i>
                                <span class="icon-desktop"></span> 日常维护
                            </div>
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td colspan="2">
                                        <a href="javascript:;"  class="btn btn-default maintain" onclick="lookLogs();">下载日志</a>
                                        <a href="javascript:;"  class="btn btn-default maintain" onclick="lookLogs();">查看日志</a>
                                        <a href="javascript:;"  class="btn btn-default  maintain" onclick="lookLogs();">清除日志</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>日志大小 : {{$logs_info['size']}}KB</td>
                                    <td>日志数 : {{$logs_info['count']}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>						<!-- 文章排行开始 -->
                        <div class="widget-box widget-color-blue">
                            <div class="widget-header">
                                <h5 class="widget-title bigger lighter sl-font14">
                                    <i class="ace-icon fa fa-table"></i>
                                    <span style="font-size:14px; font-family:Microsoft YaHei">热门文章排行</span>
                                </h5>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main no-padding">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead class="thin-border-bottom">
                                        <tr>
                                            <th width="68%">标题</th>
                                            <th width="17%"><em>点击数</em></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!$hot_artciles->isEmpty())
                                            @foreach($hot_artciles as $ht_art)
                                        <tr>
                                            <td height="25"><span class="badge badge-{{$hot_color[$loop->index]}}">{{$loop->index + 1}}</span><a href="javascript:;" target="_blank">{{str_limit($ht_art->title,30,'...')}}</a></td>
                                            <td>{{empty($ht_art->hits)?0:$ht_art->hits}}</td>
                                        </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                        <!-- 文章排行结束 -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal add-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document" action="javascript:;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><b>Laravel日志列表</b></h4>
                </div>
                <div class="modal-body">
                    <table class="layui-table" lay-skin="nob">
                        <thead>
                        <tr>
                            <th></th>
                            <th>文件名</th>
                            <th>浏览</th>
                            <th>下载</th>
                            <th>删除</th>
                        </tr>
                        </thead>
                        <tbody id="logstbody">

                            <tr>
                                <td><i class="layui-icon">&#xe622;</i></td>
                                <td>laravel-2017-12-20.log</td>
                                <td><i class="layui-icon" onclick="dealLogs(this,'browse','filename');">&#xe635;</i></td>
                                <td><i class="layui-icon" onclick="dealLogs(this,'download','filename');">&#xe61e;</i></td>
                                <td><i class="layui-icon" onclick="dealLogs(this,'clear','filename');">&#xe640;</i></td>
                            </tr>

                        </tbody>

                    </table>
                </div>

                <div class="modal-footer">
                    <ul class="pagination" style="float: left; margin: 0 !important;">
                        <li><span onclick="lookLogs('prev')">«</span></li>
                        <li><span onclick="lookLogs('next')">»</span></li>
                    </ul>
                    <button type="button" class="btn btn-xs btn-default" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        window.setInterval(function(){
           var  dt = new Date();
            var year = dt.getFullYear(); //获取完整的年份(4位,2014)
            var month = dt.getMonth()+1; //获取当前月份(0-11,0代表1月)
            var date = dt.getDate(); //获取当前日(1-31)
            var day = dt.getDay(); //获取当前星期X(0-6,0代表星期天)
            switch(day){
                case 0:
                    day = '日'; break;
                case 1:
                    day = '一'; break;
                case 2:
                    day = '二'; break;
                case 3:
                    day = '三'; break;
                case 4:
                    day = '四'; break;
                case 5:
                    day = '五'; break;
                case 6:
                    day = '六'; break;
            }
            var h=dt.getHours();
            var m=dt.getMinutes();
            var s=dt.getSeconds();
            $('.timer').html("现在的时间为："+year+"年"+month+"月"+date+"日"+"&emsp;星期"+day+"&emsp;"+h+"时"+m+"分"+s+"秒");
        },1000)
      function lookWeath(){
        layui.use('layer',function(){
            layer.open({
                title:'360天气',
                type: 2,
                anim:4,
                area: ['700px','500px'],
                maxmin: true,
                content: "http://tq.360.cn/",
                zIndex: layer.zIndex, //重点1
                success: function(layero){
                    layer.setTop(layero); //重点2
                }
            });
        });
     }

     var page = 1;

     function lookLogs(method){

         if(!method){

             page = page;
         }else{

            if((method == 'prev') && (page == 1)){
                return false;
            }else{

                page = (method == 'prev')?(page - 1):(page + 1);
            }
         }

         layui.use('layer',function(){
             $.ajax({
                 url:"{{u('admin/setting/lookLogs')}}",
                 type:'post',
                 data:{page:page},
                 dataType:'json',
                 success:function(res){
                     if(res.code > 0){
                           page--;
                           layer.msg(res.msg);

                     }else{
                         var html = '';

                         $.each(res.data,function(k,v){

                             html += '<tr>'
                                  +'<td><i class="layui-icon">&#xe622;</i></td>'
                                  +'<td>'+v+'</td>'
                                  +'<td><i class="layui-icon" onclick="dealLogs(this,\'browse\',\''+v+'\');">&#xe635;</i></td>'
                                  +'<td><i class="layui-icon" onclick="dealLogs(this,\'download\',\''+v+'\');">&#xe61e;</i></td>'
                                  +'<td><i class="layui-icon" onclick="dealLogs(this,\'clear\',\''+v+'\');">&#xe640;</i></td>'
                                  +'</tr>';
                         });

                         $('#logstbody').html(html);
                         $('#myModal').modal('show');
                     }
                 }

             });
         });


     }

     function dealLogs(obj,type,filename){

         var url = "{{u('admin/setting/dealLogs')}}";
         switch (type){
             case 'browse':
                 window.open(url+'?type=browse'+'&filename'+'='+filename );
                 break;

             case 'download':
                 window.open(url+'?type=download'+'&filename'+'='+filename );
                 break;
             case 'clear':
                 $.post(url,
                     {
                         type:"clear",
                         filename:filename
                     },function(res){
                         layui.use(['layer'],function(){
                             if(res.code > 0){
                                 layer.msg(res.msg,{maxWidth:260});
                             }else{
                                 $(obj).parents('tr').remove();
                             }
                         })
                     },'json');

                 break;
             default:
                 break;
         }
     }

    </script>
@endsection