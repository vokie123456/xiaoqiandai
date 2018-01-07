<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 21:56
 */

namespace App\Http\Controllers\Admin;

use App\Models\Adminer;
use App\Models\Articles;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Config;
use Validator,Session;
class IndexController extends BaseController
{

    /**
     * @函数描述 iframe 框架外壳
     * @参数描述
     * @return HTML
     * @Created on 2017/12/25 10:52
     * @Author: ZhangHao <247073050@qq.com>
     */
   public function index(){
       if(Session::get('identity')['role_id']){
           $role = Role::where('id',Session::get('identity')['role_id'])->first();
           $res_permiss = Permission::whereIn('id',unserialize($role->permiss_ids))->where('level','<=',2)->orderBy('pid','asc')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
       }else{
           $res_permiss = Permission::where('level','<=',2)->orderBy('pid','asc')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
       }
       $nav = [];
       foreach ($res_permiss as $key=>$pper){
           if($pper['pid'] == 0){
               $navelem = ['title' => $pper['pname'],'icon' => $pper['font'],'spread' =>false];
               $navelem['children'] = array();
               foreach ($res_permiss as $value){
                   if($value['pid'] == $pper['id']){
                       array_push($navelem['children'],[
                           'title' => $value['pname'],
                           'icon' => $value['font'],
                           'href' => u($value['route'])
                       ]);
                   }
               }
               if(empty($navelem['children'])){
                   unset($navelem['children']);
               }
               array_push($nav,$navelem);
           }
       }
       $data['adminer'] = Adminer::where('id',Session::get('identity')['adminer_id'])->first();
       $data['nav'] = $nav;
       //后台名
       $data['admin_name'] = Config::where('mold','ADMIN')->where('name','ADM_NAME')->value('content');

       return view($this->vname,$data);
   }

    /**
     * @函数描述  首页展示
     * @参数描述
     * @return  HTML
     * @Created on 2017/12/25 10:53
     * @Author: ZhangHao <247073050@qq.com>
     */
   public function  main(){

       $data['serverInfo'] = array(
           'server' => PHP_OS, //操作系统
           '1' => $_SERVER["SERVER_SOFTWARE"],  //运行环境
           '2' => $_SERVER['SERVER_NAME'],  //主机名
           '3' => $_SERVER['SERVER_PORT'], //WEB服务端口
           '4' => $_SERVER["DOCUMENT_ROOT"], //网站文档目录
           '5' => substr($_SERVER['HTTP_USER_AGENT'], 0, 40), //浏览器信息
           '6' => $_SERVER['SERVER_PROTOCOL'],  //通信协议
           '7' => $_SERVER['REQUEST_METHOD'],  //请求方法
           '8' => app()::VERSION,           //ThinkPHP版本
           '9' => ini_get('upload_max_filesize'),  //上传附件限制
           '10' => ini_get('memory_limit'),  //允许运行最大内存
           '11' => ini_get('max_execution_time') . '秒',  //执行时间限制
           '12' => date("Y年n月j日 H:i:s"),   //服务器时间
           '13' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),  //北京时间
           '14' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',//服务器域名/IP
           '15' => $_SERVER['REMOTE_ADDR'],  //用户的IP地址
           '16' => round((disk_free_space(".") / (1024 * 1024)), 2) . 'M', //剩余空间
       );
       $data['adminer'] = Adminer::where('id',Session::get('identity')['adminer_id'])->first();
       //获取日志的数量和大小
       $log_path = storage_path('logs');

       $all_files = scandir($log_path);

       $all_files_size = 0;

       $all_files_count = 0;

       foreach ($all_files as $filename){

           preg_match("/(.*)\.log/",$filename,$match);

           if(!empty($match[0][0])){

               $all_files_count++;
               $all_files_size = bcadd($all_files_size,filesize($log_path.DIRECTORY_SEPARATOR.$filename)) ;
           }
       }
       $data['logs_info'] = ['size' => bcdiv($all_files_size,1024),'count' => $all_files_count];

       $all_artcile = Articles::get();

       $data['all_artcile_count'] = count($all_artcile);

       $data['today_article'] = $all_artcile->where('created_at','>=',strtotime(date('Y-m-d')))->where('created_at','<=',(strtotime(date('Y-m-d')) + 86400));

       $yesterday = $all_artcile->where('created_at','>=',(strtotime(date('Y-m-d')) - 86400))->where('created_at','<=',strtotime(date('Y-m-d')));

       $data['decline_rate'] = (count($yesterday) == 0)?0:bcdiv((count($yesterday) - count($data['today_article'])),count($yesterday))*100;

       //热门文章
       $data['hot_artciles'] = $all_artcile->sortByDesc('hits')->take(10);

       return view($this->vname,$data);
   }

    /**
     * @函数描述  个人解锁
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:54
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function unlock(){
       $args = $this->params;
       $adminer = Adminer::where('id',Session::get('identity')['adminer_id'])->first();
       if(decrypt($adminer->password) != $args['password']){
            return ['code' => 999,'msg' => '密码错误...'];
       }
        return ['code' => 0,'msg' => ''];
    }
}