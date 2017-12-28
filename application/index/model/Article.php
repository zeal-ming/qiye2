<?php

namespace app\index\model;

use think\Model;
use app\index\model\Cate;

class Article extends Model
{
	//获取某个栏目，以及子栏目下的最新文章
	public function getAllActicles($cateid)
	{
		$cates = (new Cate)->getChildrenId($cateid);
        $cateStr = implode(',', $cates);
        $articles = $this->where("cateId in($cateStr)")->order('id desc')->paginate(6);
        return $articles;
	}

	//获取某个栏目，以及子栏目下的热门文章
	public function getHotActicles($cateid)
	{
		$cates = (new Cate)->getChildrenId($cateid);
        $cateStr = implode(',', $cates);
        $articles = $this->where("cateId in($cateStr)")->order('click desc')->limit(6)->select();
        return $articles;
	}

	//获得最新的文章
	public function getNewActicles()
	{
		$articles = db('article')->field('a.id,a.keywords,a.title,a.click,a.zan,a.desc,a.thumb,a.create_time,c.catename')
								->alias('a')
								->join('bk_cate c', 'a.cateid=c.id')
								->order('id desc')
								->limit(10)
								->select();

		return $articles;
	}


}