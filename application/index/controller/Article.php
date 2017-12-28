<?php
namespace app\index\controller;

use think\Controller;
use app\common\model\Article as ArticleModel;

class Article extends Common
{
    public function detail()
    {
    	//根据id,查出具体文章
    	$id = input('artid','','intval');

    	// dump($this->hotArtcles);die;
    	$article = ArticleModel::get($id);
    	$this->assign('article',$article);
        // dump($article);die;
        return $this->fetch('article');
    }

}
