<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Article;

class ImgList extends Common
{
    public function index()
    {
    	$articles = Article::where('status',1)->paginate(9);

        return $this->fetch('imglist',['articles'=>$articles]);
    }
}
