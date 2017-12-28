<?php

namespace app\admin\model;

use think\Model;

class Cate extends Model
{
	//无限极
	public function getCates()
	{
		$data = $this->order('sort desc')->where('status',1)->select();
		return $this->sort($data);
	}	

	//分类
	public function sort($data, $pid=0,$level=0)
	{
		static $arr = array();
		foreach ($data as $key => $value)
		{
			if($value['pid'] == $pid)
			{
				$value['catename'] = '|--'.str_repeat('--', $level).$value['catename'];
				$arr[] = $value;
				//以自身id作为pid,继续查找
				$this->sort($data,$value['id'],$level+1);
			}
		}
		return $arr;
	}

	//获取子栏目
	public function getChildrenId($cateid)
	{
		$cates = $this->where(['status'=>1])->select();

		return $this->_getChildrenId($cates,$cateid);
	}

	public function _getChildrenId($data,$cateid)
	{
		static $arr = [];
		foreach ($data as $value) 
		{
			if($value['pid'] == $cateid)
			{
				$arr[] = $value['id'];
				$this->_getChildrenId($data,$value['id']);
			}
		}


		return $arr;
	}
	
}