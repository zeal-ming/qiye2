<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Cate;

class Page extends Common
{
    public function index()
    {
    	//查找单页栏目，这里往往是一条数据
    	$cateModel = new Cate;
    	$cateid = input('cateid','','intval');

    	$cate = $cateModel->getPageCate($cateid);
        return $this->fetch('page',['cate'=>$cate]);
    }
}
