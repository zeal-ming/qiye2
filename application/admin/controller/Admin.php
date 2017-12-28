<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\admin\model\Admin as AdminModel;

class Admin extends Common
{
	public function lst()
	{
		$users = Db('admin')->paginate(3);
		return $this->fetch('lst',['users'=>$users]);
	}

	public function add(){
		$authGroups = Db('auth_group')->where('status',1)->select();

		return $this->fetch('add',['authGroups'=>$authGroups]);
	}

	public function save()
	{
		
		if(request()->isPost())
		{
			$data = input('post.');

			//添加数据到admin表
			$admin['name'] = $data['name'];
			$admin['password'] = md5($data['password']);
			$res = AdminModel::create($admin);

			if($res)
			{
				//还要把数据添加到用户明细表
				$authGroupAccess['group_id'] = $data['group_id'];
				$authGroupAccess['uid'] = $res['id'];

				$res = Db('auth_group_access')->insert($authGroupAccess);

				if(!$res)
				{
					$this->error('添加失败');
				}

			}
			

			$this->success('添加成功','lst');
		}
	}

	public function edit(){
		$id = input('id','','intval');

		$admin = Db('admin')->where('id',$id)->find();
		// dump($authGroups);die;
		//通过用户id获取用户组信息
		$authGroupAccess = Db('auth_group_access')->where('uid',$admin['id'])->find();

		//获取所有用户组信息
		$authGroups = Db('auth_group')->where('status',1)->select();
		
		return $this->fetch('edit',[
			'admin'=>$admin,
			'authGroupAccess'=>$authGroupAccess,
			'authGroups' => $authGroups
			]);
	}

	public function update()
	{
		if(request()->isPost())
		{
			$data = input('post.');

			//添加数据到admin表
			$admin['id'] = $data['id'];
			$admin['name'] = $data['name'];
			$admin['password'] = md5($data['password']);
			$res = AdminModel::update($admin);

			if($res)
			{
				//还要把数据添加到用户明细表
				$authGroupAccess['group_id'] = $data['group_id'];
				$authGroupAccess['uid'] = $res['id'];

				$res = Db('auth_group_access')->where('uid',$authGroupAccess['uid'])->update($authGroupAccess);

			}
			

			$this->success('修改成功','lst');
		}
	}

	public function delete()
	{
		$id = input('id','','intval');
		$res = Db('admin')->delete(['id'=>$id]);

		if(!$res)
		{
			$this->error('删除失败');
		}

		$this->success('删除成功','lst');
	}
}