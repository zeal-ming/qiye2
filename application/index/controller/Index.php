<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Article;
use app\common\model\Cate;

class Index extends Common
{
    public function index()
    {

        $actModel = new Article;
        //获取最新文章
        $articles = $actModel->getNewActicles();

        $cate = new Cate;
        //获取推荐栏目
        $recIndexCates = $cate->all(['rec_index'=>1]);
        //获取推荐文章，把文章图片当做轮播图显示
        $showPics = $actModel->where('rec',1)->paginate(4);

        //获取友情链接
        $links = \app\common\model\Link::all();

        // dump($recCates);die;
    	// echo "<pre>";
    	// print_r($this->cates);
        // 
        
        return $this->fetch('index',[
        	'articles'=>$articles,
            'recIndexCates' => $recIndexCates,
            'links' => $links,
            'showPics' => $showPics,
        	]);
    }
}
