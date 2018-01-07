<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 14:19
 */

namespace App\Http\Controllers\Admin;

use App\Models\Adminer;
use App\Models\Config as SetConfig;
use App\Models\EmailSetting;
use App\Models\HistoryFile;
use App\Models\HistoryImg;
use App\Models\SendMailRecord;
use App\Models\SmsgSetting;
use Session,Validator,Image,Cache,Response,Mail,Config;

class SettingController extends AuthController
{
    /**
     * @函数描述  个人信息页面
     * @参数描述
     * @return  HTML
     * @Created on 2017/12/25 11:15
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function pinfo(){
       $data['adminer'] = Adminer::select('adminer.*','role.rname')->leftJoin('role',function($join){
           $join->on('role.id','=','adminer.role_id');
       })->where('adminer.id',Session::get('identity')['adminer_id'])->first();
       return view($this->vname,$data);
    }

    /**
     * @函数描述  修改管理员密码
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:15
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function updateAdminPwd(){
        $args = $this->params;
        $validator = Validator::make($args,[
            'old_pwd' => 'required',
            'password' => 'required|regex:/^[\w\?%&=\-_\.]{6,20}$/|confirmed',
        ],[
            'old_pwd.required' => '原密码不能为空',
            'password.required' => '新密码不能为空',
            'password.regex' => '新密码的格式不对',
            'password.confirmed' => '两次密码不能一致',
        ]);
        if($validator->fails()){
            return ['code' => 999,'msg' => $validator->errors()->first()];
        }

       $password = Adminer::where('id',Session::get('identity')['adminer_id'])->value('password');
        if(decrypt($password) != $args['old_pwd']){
            return ['code' => 999,'msg' => '旧密码不对'];
        }
        $res = Adminer::where('id',Session::get('identity')['adminer_id'])->update(['password' => encrypt($args['password'])]);
        if(empty($res)){
            return ['code' => 999,'msg' => '修改密码失败'];
        }
        return ['code' => 0,'msg' => ''];
    }


    /**
     * @函数描述  登录主题展示
     * @参数描述
     * @return HTML
     * @Created on 2017/12/25 11:16
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function theme(){
        $data['theme'] = SetConfig::where('mold','THEME')->orderBy('sort','asc')->orderBy('id','asc')->get();
        return view($this->vname,$data);
    }

    /**
     * @函数描述 保存主题
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:16
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function themeSave(){
        $args = $this->params;
        SetConfig::where('mold','THEME')->update(['show' => 3]);
        SetConfig::where('mold','THEME')->where('name',$args['theme'])->update(['show' => 1]);
        Session::flash('overdue',true);
        return ['code' => 0,'msg' => ''];
    }


    /**
     * @函数描述  修改个人头像
     * @参数描述 object $this->request
     * @return  array|string
     * @Created on 2017/12/25 11:17
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function modifyhi(){

        $result = $this->upload($this->request);
        if($result['code'] == 0){
           $adminer = Adminer::find(Session::get('identity')['adminer_id']);
            $where[] = $adminer->head_img;
            if(strpos($adminer->head_img,'/cpre') !== false){
                $where[] = str_replace('/cpre','/',$adminer->head_img);
            }
            HistoryImg::whereIn('src',$where)->update(['status' => 0]);

            $adminer->head_img = $result['data']['src'];
            $res = $adminer->save();
           if(empty($res)){
                return ['code' => 999,'msg' => '修改头像失败'];
           }
            $where = [$result['data']['src']];
            if(strpos($result['data']['src'],'/cpre') !== false){
                $where[] = str_replace('/cpre','/',$result['data']['src']);
            }
            HistoryImg::whereIn('src',$where)->update(['status' => 1]);

        }

        return  $result;
    }


    /**
     * @函数描述  网站配置列表
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 11:18
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function webset(){
        $args = $this->params;
        $config = SetConfig::where('show','<>',2)->orderBy('sort','asc');
        if(!empty($args['mold'])){
            $config->where('mold','like','%'.$args['mold'].'%');
        }
        if(!empty($args['means'])){
            $config->where('means','like','%'.$args['means'].'%');
        }
        $data['config'] = $config->paginate(10);
        $data['search'] = $args;
        return view($this->vname,$data);
    }

    /**
     * @函数描述  增加-编辑配置
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 11:19
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeConfig(){
        $args = $this->params;
        $config = null;
        if(!empty($args['c_id'])){
            $config = SetConfig::find($args['c_id']);
        }
        return view($this->vname,['config' => $config]);
    }

    /**
     * @函数描述  保存配置信息
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:19
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveConfig(){
        $args = $this->params;
        $validator = Validator::make($args,[
            'name' => 'required|max:100',
            'mold' => 'required|max:20',
            'means' => 'required|max:50',
            'sort' => 'required|numeric|max:9999',
            'show' => 'required',
        ],[
            'name.required' => '别名不能为空',
            'name.max' => '别名过长',
            'mold.required' => '类型不能为空',
            'mold.max' => '类型过长',
            'means.required' => '配置名不能为空',
            'means.max' => '配置名过长',
            'sort.required' => '排序不能为空',
            'sort.numeric' => '排序应为数字',
            'sort.max' => '排序数字过大',
        ]);
        if($validator->fails()){
            return ['code' => 999,'msg' => $validator->errors()->first()];
        }
        if(!empty($args['c_id'])){
            $config = SetConfig::find($args['c_id']);
        }else{
            $config = new SetConfig;
        }
        $config->name = $args['name'];
        $config->mold = $args['mold'];
        $config->content = $args['content'];
        $config->means = $args['means'];
        $config->sort = $args['sort'];
        $config->show = $args['show'];
        $res = $config->save();
        if(empty($res)){
            return ['code' => 999,'msg' => '没有修改的内容'];
        }
        return ['code' => 0,'msg' => ''];
    }


    /**
     * @函数描述  删除网站配置
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:20
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delConfig(){
        $args = $this->params;
        $res = SetConfig::where('id',$args['c_id'])->delete();
        if(empty($res)){
            return ['code' =>999, 'msg' => '删除失败'];
        }
        return ['code' => 0,'msg' => ''];

    }

    /**
     * @函数描述  图片上传管理
     * @参数描述
     * @return  HTML
     * @Created on 2017/12/25 11:20
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function uploadMange(){
        return $this->view();
    }


    /**
     * @函数描述  提交图片上传
     * @参数描述  array $this->params
     * @return  array | string
     * @Created on 2017/12/25 11:21
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function upload(){

        $args = $this->params;

        if(!$this->request->hasFile('file')){
            if(empty($args['utype'])){
                return ['code' => 999,'msg' => '上传图片丢失'];
            }else{
                return "error|上传图片丢失";
            }
        }

        $file = $this->request->file('file');

        $filetype = $file->getClientMimeType();

        if(strpos($filetype,'image') === false){

            if(empty($args['utype'])){
                return ['code' => 999,'msg' => '上传的不是图片类型'];
            }else{
                return "error|上传的不是图片类型";
            }
        }

        //获得的是文件的字节大小
        $size = $file->getSize();

        if($size > bcmul(str_replace('M','',ini_get('upload_max_filesize')),  1024 * 1024) ){

            return ['code' => 999,'msg' => '上传文件超出限制大小'];
        }

        $ratio = null;
        //压缩的比例
        if(!empty($args['compress'])){

            $ratio = ($size <=  bcmul($args['compress'],1024))?null:bcmul($args['compress'],1024);
        }

        $path = 'uploads'.DIRECTORY_SEPARATOR.date('Y-m-d');

        $filename = md5(microtime());

        //后缀名
        $extension = $file->getClientOriginalExtension();

        $savepath = $file->storeAs($path,$filename.'.'.$extension);

        $timestamp = time();

        HistoryImg::insert([
            'src' => '/'.$savepath,
            'created_at' => $timestamp,
            'status' => 0
        ]);
        if(!empty($ratio)){

            $d_ratio = bcdiv($size,$ratio);  //应该压缩的比例
            $width = Image::make(public_path($savepath))->width();  //图片的宽度

            $img = Image::make(public_path($savepath))->resize((int)bcdiv($width,$d_ratio), null,function($constraint){
                  $constraint->aspectRatio();
                  $constraint->upsize();
          });

          //压缩后保存的路径
          $savepath =  $path.DIRECTORY_SEPARATOR.'cpre'.$filename.'.'.$extension;

          $img->save($savepath);

          HistoryImg::insert([
                'src' => '/'.$savepath,
                'created_at' => $timestamp,
                'status' => 0
          ]);
        }

        if(empty($args['utype'])){
            return ['code' => 0,'data' => ['src' => '/'.$savepath]];
        }else{
            return  '/'.$savepath;
        }
    }

    /**
     * @函数描述  文件管理
     * @参数描述
     * @return  HTML
     * @Created on 2017/12/26 16:07
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function fileManage(){

       $history_file = HistoryFile::where('status','invalid')->orderBy('created_at','desc')->get();

        return $this->view(null,compact('history_file'));
    }

    /**
     * @函数描述 文件上传
     * @参数描述 object $this->request
     * @return array
     * @Created on 2017/12/25 11:22
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function fileUpload(){

        if(!$this->request->hasFile('file')){

            return ['code' => 999,'msg' => '文件丢失'];
        }

        $file = $this->request->file('file');

        $filetype = $file->getClientMimeType();

        if(strpos($filetype,'image') !== false){

            return ['code' => 999,'msg' => '上传的类型不能为图片'];

        }

        //获得的是文件的字节大小
        $size = $file->getSize();

        if($size > bcmul(str_replace('M','',ini_get('upload_max_filesize')),  1024 * 1024) ){

            return ['code' => 999,'msg' => '上传文件超出限制大小'];
        }

        $originalName = $file->getClientOriginalName(); // 文件原名

        $path = 'files'.DIRECTORY_SEPARATOR.date('Y-m-d');

        $filename = md5(microtime());

        //后缀名
        $extension = $file->getClientOriginalExtension();

        $savepath = $file->storeAs($path,$filename.'.'.$extension);

        $history_file = new HistoryFile();

        $enclosure = guid();
        $history_file->id = $enclosure;
        $history_file->org_name = $originalName;
        $history_file->filepath = $savepath;
        $history_file->created_at = time();
        $history_file->status = 'invalid';

        $history_file->save();
        return ['code' => 0, 'data' => ['enclosure' => $enclosure, 'org_name' => $originalName],'msg' => ''];
    }

    /**
     * @函数描述  删除无效的文件
     * @参数描述  array $this->params['file_ids']
     * @return  array
     * @Created on 2017/12/26 15:47
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delInvalidFile(){

        if(empty($this->params['file_ids'])){

            return ['code' => 999,'msg' => '文件ID为空,无法删除'];
        }

        $files = HistoryFile::whereIn('id',$this->params['file_ids'])->get();

        foreach($files as $item){

            @unlink(public_path($item->filepath));
        }

        HistoryFile::whereIn('id',$this->params['file_ids'])->delete();

        return ['code' => 0,'msg' => ''];
    }



    /**
     * @函数描述  图片删除
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:23
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delpicture(){

        $args = $this->params;

        if(empty($args['imgurl'])){
            return ['code' => 999,'msg' => '文件路径不存在'];
        }
        $where = [$args['imgurl']];
        if(strpos($args['imgurl'],'/cpre') !== false){
            $where[] = str_replace('/cpre','/',$args['imgurl']);
        }
        HistoryImg::whereIn('src',$where)->delete();
        foreach ($where as $img){
            @unlink(public_path($img));
        }

        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述  系统清除
     * @参数描述
     * @return  HTML
     * @Created on 2017/12/25 11:23
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function clearSystem(){

        $data['himg'] = HistoryImg::where('status',0)->orderBy('created_at','desc')->get();

        return $this->view(null,$data);
    }

    /**
     * @函数描述  清除废弃图片
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:24
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function deleteHimg(){
        $args = $this->params;
        if(empty($args['himg_id'])){
            return ['code' => 999,'msg' => '没有可以删除的'];
        }
        $himg = HistoryImg::whereIn('id',$args['himg_id'])->get();
        foreach ($himg as $img){
            @unlink(public_path($img->src));
        }
        $res = HistoryImg::whereIn('id',$args['himg_id'])->delete();
        if(empty($res)){
            return ['code' => 999,'msg' => '删除失败'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述  清除缓存和日志
     * @参数描述  $this->params
     * @return array
     * @Created on 2017/12/25 11:24
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function clearCache(){

        $type = $this->params['type'] ?? null;

        switch ($type){
            case 'cache':
                $this->delSuffixFile(storage_path('framework/views'),'php');
                Cache::flush();
                break;

            case 'log':
                $this->delSuffixFile(storage_path('logs'),'log');
                break;

            case 'session':
//                Session::flush();
                $this->delSuffixFile(storage_path('framework/sessions'));
                break;

            default :
                break;
        }

        return ['code' => 0,'msg' => ''];
    }


    /**
     * @函数描述  清理文件夹下特定后缀的文件,不可递归
     * @参数描述 string $path  文件夹的绝对路径
     * @参数描述 string $delfile_type  文件后缀
     * @return  void
     * @Created on 2017/12/25 11:25
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delSuffixFile($path,$delfile_type = ''){

        $all_files = scandir($path);
        //遍历当前目录下所有文件
        foreach($all_files as $filename){

            $full_name = $path.DIRECTORY_SEPARATOR.$filename;

            if(!empty($delfile_type)){

                preg_match("/(.*)\.$delfile_type/",$filename,$match);

                if(!empty($match[0][0])){

                    @unlink($full_name);
                }
            }else{
                if($filename != "." && $filename != '..' && $filename != '.gitignore'){

                    @unlink($full_name);
                }

            }
        }

    }


    /**
     * @函数描述 短信设置列表
     * @参数描述 array $this->params
     * @return HTML
     * @Created on 2017/12/25 11:26
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function smsgSetting(){

        $data['search'] = $this->params;

        $smsg = SmsgSetting::orderBy('sort','asc')->orderBy('created_at','desc');

        if(!empty($this->params['title'])){

            $smsg->where('title','like','%'.$this->params['title'].'%');
        }
        $data['smsg'] = $smsg->paginate(10);

        return $this->view(null,$data);

    }

    /**
     * @函数描述 添加-编辑短信设置
     * @参数描述 array $this->params
     * @return  HTML
     * @Created on 2017/12/25 11:26
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeSmsg(){

        $data['smsg'] = null;

        if(!empty($this->params['s_id'])){

           $data['smsg'] = SmsgSetting::find($this->params['s_id']);
        }

        return $this->view(null,$data);
    }

    /**
     * @函数描述  保存短信
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:26
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveSmsg(){

       return (new SmsgSetting)->saveSmsg($this->params);

    }


    /**
     * @函数描述 删除短信设置
     * @参数描述 array $this->params
     * @return  array
     * @Created on 2017/12/25 11:27
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delSmsg(){

        if(empty($this->params['s_id'])){

            return ['code' => 999,'msg' => '短信ID为空'];
        }

        $smsg = SmsgSetting::find($this->params['s_id']);

        $res = $smsg->delete();

        if(empty($res)){

            return ['code' => 999,'msg' => '操作失败'];
        }

        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述 修改短信设置状态
     * @参数描述 array $this->params
     * @return array
     * @Created on 2017/12/25 11:27
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function modifySmsgStatus(){

        if($this->params['status'] == 'on'){
            //status = 1 ,原本是上架的,改变成下架
            $res = SmsgSetting::where('id',$this->params['tar_id'])->update(['status' => 'off']);
        }else{
            $res = SmsgSetting::where('id',$this->params['tar_id'])->update(['status' => 'on']);
        }
        if(empty($res)){
            return ['code' => 999,'msg' => '设置失败'];
        }
        return ['code' => 0,'msg' => ''];
    }


    /**
     * @函数描述 邮箱连接列表
     * @参数描述 array $this->params
     * @return HTML
     * @Created on 2017/12/25 11:28
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function emailSetting(){

        $data['search'] = $this->params;

        $email = EmailSetting::orderBy('sort','asc')->orderBy('created_at','desc');

        if(!empty($this->params['title'])){

            $email->where('title','like','%'.$this->params['title'].'%');
        }
        $data['email'] = $email->paginate(10);

        return $this->view(null,$data);

    }

    /**
     * @函数描述 添加-编辑邮件连接
     * @参数描述 array $this->params
     * @return  HTML
     * @Created on 2017/12/25 11:29
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeEmail(){

        $data['email'] = null;

        if(!empty($this->params['e_id'])){

            $data['email'] = EmailSetting::find($this->params['e_id']);
        }

        return $this->view(null,$data);

    }


    /**
     * @函数描述 保存邮件连接
     * @参数描述 array $this->params
     * @return array
     * @Created on 2017/12/25 11:29
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveEmail(){

        return (new EmailSetting)->saveEmail($this->params);
    }


    /**
     * @函数描述 删除邮件连接
     * @参数描述 array $this->params
     * @return  array
     * @Created on 2017/12/25 11:29
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delEmail(){

        if(empty($this->params['e_id'])){

            return ['code' => 999,'msg' => '邮件ID为空'];
        }

        $email = EmailSetting::find($this->params['e_id']);

        $res = $email->delete();

        if(empty($res)){

            return ['code' => 999,'msg' => '操作失败'];
        }

        return ['code' => 0,'msg' => ''];
    }


    /**
     * @函数描述 修改邮件连接状态
     * @参数描述 array $this->params
     * @return array
     * @Created on 2017/12/25 11:30
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function modifyEmailStatus(){

        if($this->params['status'] == 'on'){
            //status = 1 ,原本是上架的,改变成下架
            $res = EmailSetting::where('id',$this->params['tar_id'])->update(['status' => 'off']);
        }else{
            $res = EmailSetting::where('id',$this->params['tar_id'])->update(['status' => 'on']);
        }
        if(empty($res)){
            return ['code' => 999,'msg' => '设置失败'];
        }
        return ['code' => 0,'msg' => ''];
    }


    /**
     * @函数描述 SMTP协议发送邮件
     * @参数描述 object $this->request
     * @参数描述 array $this->params
     * @return HTML
     * @Created on 2017/12/25 11:30
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function smtpSendMail(){

        $prepage = $this->request->header()['referer'][0];

        if(empty($this->params['e_id'])){

           $handle = '邮件连接ID为空';

           return $this->view('admin.index.unhandle',compact('prepage','handle'));
        }

        $email = EmailSetting::find($this->params['e_id']);

        if($email->status == 'off'){

            $handle = '此邮件连接已关闭';

            return $this->view('admin.index.unhandle',compact('prepage','handle'));
        }

        $data['email'] = $email;

        //百度key
        $data['mapKey'] = SetConfig::where('mold','BAIDU')->where('name','BAIDU_AK')->value('content');


        return $this->view(null,$data);

    }


    /**
     * @函数描述 保存邮件并发送邮件
     * @参数描述 array $this->params
     * @return HTML
     * @Created on 2017/12/25 11:31
     * @Author: ZhangHao <247073050@qq.com>
     */
    public  function saveSendMail(){

        //验证
        $validator = Validator::make($this->params,[
            'email_id' => 'required',
            'theme' => 'required|max:50',
            'touser' => 'required|regex:/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+[,]?$/',
            'content' => 'required',
        ],[
            'email_id.required' => '邮箱连接不能为空',
            'theme.required' => '发件的主题不能为空',
            'theme.max' => '发件的主题过长',
            'touser.required' => '收件人不能为空',
            'touser.regex' => '收件人格式不对',
            'content.required' => '发送内容不能为空',
        ]);

        if($validator->fails()){
            return ['code' => 999,'msg' => $validator->errors()->first()];
        }

        //保存发送记录
        $sendmailrecord = new SendMailRecord();
        $sendmailrecord->id = guid();
        $sendmailrecord->email_id = $this->params['email_id'];
        $sendmailrecord->theme = $this->params['theme'];
        $sendmailrecord->touser = $this->params['touser'];
        $sendmailrecord->enclosure = $this->params['enclosure'] ?? null;
        $sendmailrecord->content = $this->params['content'];
        $sendmailrecord->created_at = time();
        $sendmailrecord->formuser = Session::get('identity.adminer_id');

        $res = $sendmailrecord->save();

       if(!$res){

           return ['code' => 999,'msg' => '保存邮件记录失败'];
       }

       //发送邮件

        $mail = EmailSetting::find($this->params['email_id']);

        $config = array(
            'driver' => 'smtp',
            'host' => $mail->smtp_host,
            'port' => $mail->smtp_port,
            'from' => array('address' => $mail->login_account, 'name' => $mail->sender_name),
            'encryption' => $mail->connect_method,
            'username' => $mail->login_account,
            'password' => $mail->login_password,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'markdown' => [
                'theme' => 'default',
                'paths' => [
                    resource_path('views/vendor/mail'),
                ],
            ]
        );

        Config::set('mail',$config);

        $touser = explode(',',$this->params['touser']);

        $theme = $this->params['theme'];

        $content = $this->prefixImgs($this->params['content']);

        $attachment = '';

        if(!empty($this->params['enclosure'])){

             $history_file = HistoryFile::find($this->params['enclosure']);

             $history_file->status = 'valid';
             $history_file->save();

            $attachment =  $history_file;
        }


        $flag = Mail::send('admin.setting.emailview',['content'=> $content],function($message) use($touser,$theme,$attachment){

            $message ->to($touser)->subject($theme);

            if($attachment){

                // 在邮件中上传附件
                $message->attach(public_path($attachment->filepath),['as'=> $attachment->org_name]);
            }

        });

        if($flag){
            $sendmailrecord->status = 'failed';
            $sendmailrecord->save();
            return ['code' => 999,'msg' => '发送邮件失败，请重试！'];
        }else{

            return ['code' => 0,'msg' => ''];
        }
    }

    /**
     * @函数描述 邮件发送的历史记录
     * @参数描述 array $this->params
     * @return  HTML
     * @Created on 2017/12/26 22:44
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function historySMail(){

        $data['search'] = $this->params;

        $his_email = SendMailRecord::select(['send_mail_record.*','adminer.name as a_name'])
                                    ->leftJoin('adminer','adminer.id','=','send_mail_record.formuser')
                                    ->orderBy('send_mail_record.created_at','desc');

        if(!empty($this->params['status'])){
            $his_email->where('send_mail_record.status',$this->params['status']);
        }

        if(!empty($this->params['start_time'])){
            $his_email->where('send_mail_record.created_at','>=',strtotime($this->params['start_time']));
        }

        if(!empty($this->params['end_time'])){
            $his_email->where('send_mail_record.created_at','<=',strtotime($this->params['end_time']));
        }

        $data['his_email'] = $his_email->paginate(10);

        return $this->view(null,$data);
    }

    /**
     * @函数描述 处理日志
     * @参数描述 array $this->params
     * @return  HTML
     * @Created on 2017/12/25 11:32
     * @Author: ZhangHao <247073050@qq.com>
     */
   public function dealLogs(){


       if(empty($this->params['filename'])){

           return ['code' => 999,'msg' => '文件名为空'];
       }

       $type = $this->params['type'] ?? null;

       switch ($type){

           case 'browse':
               return Response::file(storage_path('logs').DIRECTORY_SEPARATOR.$this->params['filename']);
               break;

           case 'download':
               return Response::download(storage_path('logs').DIRECTORY_SEPARATOR.$this->params['filename']);
               break;

           case 'clear':
               @unlink(storage_path('logs').DIRECTORY_SEPARATOR.$this->params['filename']);
               break;

           default:

               break;
       }

       return ['code' => 0,'msg' => ''];

   }

    /**
     * @函数描述 查看日志列表
     * @参数描述
     * @return array
     * @Created on 2017/12/25 11:33
     * @Author: ZhangHao <247073050@qq.com>
     */
   public function lookLogs(){

       //获取日志列表(10个)
       $all_files = scandir(storage_path('logs'));

       $all_logs = [];

       foreach ($all_files as $filename){

           preg_match("/(.*)\.log/",$filename,$match);

           if(!empty($match[0][0])){

               $all_logs[] = $filename;
           }
       }

       $collection = collect(array_reverse($all_logs));

       $chunk = $collection->forPage($this->params['page'], 10)->toArray();

       if(empty($chunk)){

           return ['code' => 999,'msg' => '已加载完全'];
       }

       return ['code' => 0,'data' => $chunk,'msg' => ''];

   }

}