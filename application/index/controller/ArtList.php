<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Article;

class ArtList extends Common
{
    public function index()
    {
    	$cateid = input('cateid','','intval');

        $articleModel = new Article;
    	//根据栏目选出文章,如果栏目下还有子栏目也要选出
        $articles = $articleModel->getAllActicles($cateid);
                
        //dump($articles);die;
    	// dump(ArticleModel::getLastSql());die;
        return $this->fetch('artlist',[
        	'articles' => $articles,
        	]);
    }
}
