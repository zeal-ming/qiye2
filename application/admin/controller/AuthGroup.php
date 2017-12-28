<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\AuthGroup as AuthGroupModel;

class AuthGroup extends Common
{
	public function lst()
	{
		$users = AuthGroupModel::where(['status'=>1])->paginate(3);

		return $this->fetch('lst',['users'=>$users]);
	}

	public function add(){
		//获取所有权限
		$authRule = new \app\admin\model\AuthRule;
		$authRules = $authRule->getAuthRules();
		// dump($authRules);exit();
		return $this->fetch('add',['authRules'=>$authRules]);
	}

	public function save(){
		if(request()->isPost())
		{
			$data = input('post.');
			// dump($data);die;
			// 如果点击了关闭按钮，则status不会发送到后台
			if(!isset($data['status']))
			{
				$data['status'] = 0;
			}
			if(!empty($data['rules']))	
			{
				$data['rules'] = implode(',',$data['rules']);
			}
			// dump($data);die;
			$res = AuthGroupModel::create($data);
			if(empty($res))
			{
				$this->error('添加失败');
			}

			$this->success('添加成功','lst');
		}
	}

	public function edit()
	{
		$id = input('id','','intval');
		$authGroup = AuthGroupModel::get($id);
		//获取所有权限
		$authRule = new \app\admin\model\AuthRule;
		$authRules = $authRule->getAuthRules();
		return $this->fetch('edit',['authGroup'=>$authGroup,'authRules'=>$authRules]);
	}

	public function update()
	{
		$data = input('post.');
		
		if(!empty($data['rules']))
		{
			$data['rules'] = implode(',',$data['rules']);
		}	
		$res = AuthGroupModel::update($data);

		if(empty($res))
		{
			$this->error('修改失败');
		}

		$this->success('修改成功','lst');
	}

	public function delete()
	{
		$id = input('id','','intval');
		$res = AuthGroupModel::update(['id'=>$id,'status'=>0]);
		if(empty($res))
		{
			$this->error('删除失败');
		}

		$this->success('删除成功','lst');
	}
}