<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Article;

class Search extends Common
{
    public function index()
    {
    	$keywords = input('keywords');

        if(!$keywords)
        {
            $this->error('请输入搜索词');
        }
        $articleModel = new Article;
    	//根据栏目选出文章,如果栏目下还有子栏目也要选出
        $articles = $articleModel->where('title','like','%'.$keywords.'%')->paginate(2,false,$config=[
                'query' => ['keywords'=>$keywords],
            ]);
        
        //dump($articles);die;
    	// dump(ArticleModel::getLastSql());die;
        return $this->fetch('search',[
        	'articles' => $articles,
            'keywords' => $keywords,
        	]);
    }
}
