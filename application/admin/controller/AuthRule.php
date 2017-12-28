<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\admin\model\AuthRule as AuthRuleModel;

class AuthRule extends Common
{
	public function lst()
	{	
		$authRule = new AuthRuleModel();
		$rules = $authRule->paginate(3);
		// dump($rules);die;
		return $this->fetch('lst',['rules'=>$rules]);
	}

	public function add(){
		//查询所有
		$rules = (new AuthRuleModel)->getAuthRules();
		//把数组中的名称字段分层
		foreach ($rules as $value) {
			
			$value['title'] = '|'.str_repeat('--', $value['level']+1).$value['title'];
			
		}
		return $this->fetch('add',['rules'=>$rules]);
	}

	public function save()
	{
		if(request()->isPost())
		{
			$data = input('post.');
			//获取plevel
			$plevel = Db('AuthRule')->where('id',$data['pid'])->field('level')->find();
			// dump($plevel);exit();
			//如果为顶级权限，则没有上级权限
			if($plevel)
			{
				$data['level'] = ++$plevel['level'];
			}
			else
			{
				$data['level'] = 0;
			}

			$res = Db('AuthRule')->insert($data);

			if(empty($res))
			{
				$this->error('添加失败');
			}

			$this->success('添加权限成功','lst');
		}
	}

	public function edit()
	{
		$id = input('id','','intval');
		$rule = AuthRuleModel::get($id);

		$rules = (new AuthRuleModel)->getAuthRules();
		//把数组中的名称字段分层
		foreach ($rules as $value) {
			
			$value['title'] = '|'.str_repeat('--', $value['level']+1).$value['title'];
			
		}
		// dump($rules);die;
		return $this->fetch('edit',['rule'=>$rule,'rules'=>$rules]);
	}

	public function update()
	{
		if(request()->isPost())
		{
			$data = input('post.');
			//获取plevel
			$plevel = Db('AuthRule')->where('id',$data['pid'])->field('level')->find();
			
			//如果为顶级权限，则没有上级权限
			if($plevel)
			{
				$data['level'] = ++$plevel['level'];
				 // dump($plevel);
			}
			else
			{
				$data['level'] = 0;
			}
			// dump($data);die;
			$res = AuthRuleModel::update($data);
			if(empty($res))
			{	
				$this->error('更新失败');
			}

			$this->success('更新成功','lst');
		}
	}

	public function delete()
	{
		$id = input('id','','intval');
		$authRule = new AuthRuleModel;
		// dump($id);
		//获取所有父级id
		$ids = $authRule->getParentId($id);
		$ids = explode('-',$ids);
		// dump($ids);exit();
		$res = AuthRuleModel::destroy($ids);
		if(empty($res))
		{
			$this->error('删除失败');
		}

		$this->success('删除成功','lst');
	}
}