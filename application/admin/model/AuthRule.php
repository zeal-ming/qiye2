<?php

namespace app\admin\model;

use think\Model;

class AuthRule extends Model
{
	//无限极
	public function getAuthRules()
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
				$value['dataid'] = $this->getParentId($value['id']);
				$arr[] = $value;
				//以自身id作为pid,继续查找
				$this->sort($data,$value['id']);
			}
		}
		return $arr;
	}

	//根据子id，依次获取父id，以数组的方式返回
	public function getParentId($authRuleId)
	{
		$authRules = $this->select();
		$arr1 = $this->_getParentId($authRules, $authRuleId,true);
		$arr1 = array_reverse($arr1);
		$arrStr = implode('-', $arr1);
		return $arrStr;
	}

	public function _getParentId($authRules, $authRuleId,$clear=false)
	{
		static $arr1 = [];
		if($clear)
		{
			$arr1 = [];	
		}
		foreach ($authRules as $key => $value) {
		  	if($value['id'] == $authRuleId)
			{
				$arr1[] = $value['id'];
				$this->_getParentId($authRules, $value['pid'],false);
			}
		}

		// dump($arr1);exit();
		
		return $arr1;
	}
	
}