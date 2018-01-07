<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/21
 * Time: 14:41
 */
namespace App\Http\Controllers\Admin;


use App\Models\ArticleColumn;
use App\Models\ArticleComment;
use App\Models\Articles;
use App\Models\Config;

class ArticleController extends  AuthController{

    /**
     * @函数描述 文章列表
     * @参数描述 array $this->params
     * @return HTML
     * @Created on 2017/12/25 10:41
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function manageList(){

        $data['search'] = $this->params;

        $data['column'] = ArticleColumn::where('status','on')->with('column')->orderBy('sort','asc')->get();

        $article = Articles::select(['articles.*','article_column.cname','adminer.name as author'])
                            ->leftJoin('article_column','article_column.id','=','articles.column_id')
                            ->leftJoin('adminer','adminer.id','=','articles.author')
                            ->orderBy('articles.sort','asc');

        if(!empty($this->params['title'])){

            $article->where('articles.title','like','%'.$this->params['title'].'%');
        }

        if(!empty($this->params['column_id'])){

            $article->where('articles.column_id',$this->params['column_id']);
        }

        if(!empty($this->params['start_time'])){

            $article->where('articles.created_at','>=',strtotime($this->params['start_time']));
        }

        if(!empty($this->params['end_time'])){

            $article->where('articles.created_at','<=',strtotime($this->params['end_time']));
        }

        $data['article'] = $article->orderBy('articles.created_at','desc')->paginate(10);

        return $this->view(null,$data);

    }

    /**
     * @函数描述  添加-编辑文章
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:42
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeArticle(){

        $data['article'] = empty($this->params['a_id'])?null:Articles::find($this->params['a_id']);

        //栏目
        $data['column'] = ArticleColumn::where('status','on')->with('column')->orderBy('pid','asc')->orderBy('sort','asc')->get();

        //百度key
        $data['mapKey'] = Config::where('mold','BAIDU')->where('name','BAIDU_AK')->value('content');

        return $this->view(null,$data);
    }

    /**
     * @函数描述  保存文章
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:43
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveArticle(){

        return (new Articles)->saveArticle($this->params);
    }

    /**
     * @函数描述 删除文章
     * @参数描述 array $this->params
     * @return  array
     * @Created on 2017/12/25 10:44
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delArticle(){

        if(empty($this->params['a_id'])){

            return ['code' => 999,'msg' => '文章ID为空不能删除'];
        }

        $article = Articles::find($this->params['a_id']);

        $old_imgs = $this->getAllImgSrc($article->content);

        array_push($old_imgs,$article->cover);

        $this->upStatusPic($old_imgs,0);

        $res = $article->delete();

        if(empty($res)){

            return ['code' => 999,'msg' => '删除失败'];
        }

        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述 审核文章
     * @参数描述 array $this->params
     * @return array
     * @Created on 2017/12/25 10:45
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function auditArticle(){

        if(empty($this->params['tar_id'])){

            return ['code' => 999,'msg' => '审核文章ID丢失'];
        }

        $article = Articles::find($this->params['tar_id']);

        if($this->params['status'] == 'on'){
            //status = on ,
            $article->status = 'off';

        }else{
            $article->status = 'on';

            if(empty($article->publish_time)){

                $article->publish_time = time();
            }
        }

        $res = $article->save();

        if(empty($res)){
            return ['code' => 999,'msg' => '审核失败'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述  栏目列表
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:47
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function columnList(){

        $data['search'] = $this->params;

        $column = ArticleColumn::where('pid',0)->with('column')->orderBy('sort','asc')->orderBy('created_at','desc');

        if(!empty($this->params['cname'])){

            $column->where('cname','like','%'.$this->params['cname'].'%');
        }

        $data['column'] = $column->paginate(10);

        return $this->view(null,$data);
    }

    /**
     * @函数描述  添加-编辑栏目
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:48
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeColumn(){

        $data['column'] = empty($this->params['c_id'])?null:ArticleColumn::find($this->params['c_id']);

        $data['p_column'] = ArticleColumn::where('pid',0)->where('status','on')->orderBy('sort','asc')->get();

        //百度key
        $data['mapKey'] = Config::where('mold','BAIDU')->where('name','BAIDU_AK')->value('content');

        return $this->view(null,$data);

    }

    /**
     * @函数描述 保存栏目
     * @参数描述 array $this->params
     * @return  array
     * @Created on 2017/12/25 10:48
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveColumn(){

        return (new ArticleColumn)->saveColumn($this->params);
    }


    /**
     * @函数描述  删除栏目
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:49
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delColumn(){

        if(empty($this->params['c_id'])){

            return ['code' => 999,'msg' => '删除的栏目ID为空'];
        }

       $child = ArticleColumn::where('pid',$this->params['c_id'])->get();

      if(!$child->isEmpty()){

          return ['code' => 999,'msg' => '含有子级栏目无法直接删除'];
      }

       $column = ArticleColumn::find($this->params['c_id']);

        //从富文本里面把图片筛选出来
        $all_imgs = $this->getAllImgSrc($column->content);

        if(!empty($all_imgs)){

            $this->upStatusPic($all_imgs,0);
        }

        $res = $column->delete();

        if(empty($res)){

            return ['code' => 999,'msg' => '删除失败'];
        }

        return ['code' => 0,'msg' => ''];

    }


    /**
     * @函数描述 修改栏目状态
     * @参数描述 array $this->params
     * @return  array
     * @Created on 2017/12/25 10:49
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function modifyColumnStatus(){

        if($this->params['status'] == 'on'){
            //status = 1 ,原本是上架的,改变成下架
            $res = ArticleColumn::where('id',$this->params['tar_id'])->update(['status' => 'off']);
        }else{
            $res = ArticleColumn::where('id',$this->params['tar_id'])->update(['status' => 'on']);
        }
        if(empty($res)){
            return ['code' => 999,'msg' => '设置失败'];
        }
        return ['code' => 0,'msg' => ''];
    }


    /**
     * @函数描述 评论列表
     * @参数描述
     * @return  HTML
     * @Created on 2017/12/25 10:50
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function commentList(){

       $data['article'] = Config::where('mold','ARTICLE')->get()->toArray();

       $data['comment'] =  ArticleComment::orderBy('created_at','desc')->paginate(10);

       return $this->view(null,$data);
    }


    /**
     * @函数描述  设置个人评论时间间隔
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:50
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function commentInterval(){

        Config::where('mold','ARTICLE')->where('name','COMMENT_INTERVAL')->update(['content' => $this->params['COMMENT_INTERVAL']]);
        Config::where('mold','ARTICLE')->where('name','COMMENT_TIMES')->update(['content' => $this->params['COMMENT_TIMES']]);

        return ['code' => 0,'msg' => ''];
    }





}