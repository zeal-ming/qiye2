<?php

namespace app\index\model;

use think\Model;

class Cate extends Model
{
	//根据父id，依次获取所有子id，以数组的方式返回
	public function getChildrenId($cateId)
	{
		$cates = $this->select();

		//把自己的id加进去
		$arr = $this->_getChildrenId($cates, $cateId,true);
		$arr[] = $cateId;

		return $arr;
	}
		

	public function _getChildrenId($cates, $cateId,$clear=false)
	{
		static $arr1 = [];
		if($clear)
		{
			$arr1 = [];	
		}
		foreach ($cates as $key => $value) {
		  	if($value['pid'] == $cateId)
			{
				$arr1[] = $value['id'];
				$this->_getChildrenId($cates, $value['id'],false);
			}
		}
	
		// dump($arr1);exit();
		return $arr1;
	}

	//获取单页栏目(个人感觉这里的单页应该把内容放到文章表里，用cateid联系起来，而不是紧紧为了增加单页，加了多余的大字段，影响数据库性能)
	public function getPageCate($cateid)
	{
		$cate = $this->where('id',$cateid)->find();

		return $cate;
	}
}