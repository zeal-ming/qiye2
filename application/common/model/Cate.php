<?php

namespace app\common\model;

use think\Model;

class Cate extends Model
{
	//无限极
	public function getCates()
	{
		$data = $this->order('sort desc')->select();
		return $this->sort($data);
	}	

	//分类
	public function sort($data, $pid=0)
	{
		static $arr = array();
		foreach ($data as $key => $value)
		{
			if($value['pid'] == $pid)
			{
				$arr[] = $value;
				//以自身id作为pid,继续查找
				$this->sort($data,$value['id']);
			}
		}
		return $arr;
	}

	//根据子id，依次获取所有父id，以数组的方式返回
	public function getParentId($cateId)
	{
		$cates = $this->select();

		$arr = $this->_getParentId($cates, $cateId,true);

		return $arr;
	}
		

	public function _getParentId($cates, $cateId,$clear=false)
	{
		static $arr1 = [];
		if($clear)
		{
			$arr1 = [];	
		}
		foreach ($cates as $key => $value) {
		  	if($value['id'] == $cateId)
			{
				$arr1[] = $value;
				$this->_getParentId($cates, $value['pid'],false);
			}
		}
	
		// dump($arr1);exit();
		return $arr1;
	}
	
}