<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin as AdminModel;
use think\Session;

class Login extends Controller
{

	//登入验证
	public function login()
	{
		//进来前判断是否登录，避免用户重复登录

		if(Session::has('id'))
		{
			return $this->success('您已经登录','admin/lst');
		}
		if(request()->isPost())
		{
			
			$data = input('post.');

			//验证码验证
			$this->checkCode($data['code']);
			$admin = AdminModel::get(['name'=>$data['name']]);
			if(empty($admin))
			{
				$this->error('用户名不正确');
			}
			else
			{
				if($admin->password != md5($data['password']))
				{
					$this->error('密码错误');
				}
			}

			Session::set('id',$admin->id);
			Session::set('username',$admin->name);

			return $this->success('登录成功','index/index');
		}

		return $this->fetch();
	}

	//退出登录
	public function logout()
	{
		Session::delete('id');
		Session::delete('username');

		// dump(Session::get('id'));
		return $this->success('成功退出','login/login');
	}

	//进项验证码验证
	public function checkCode($code)
	{
		$captcha = new \think\captcha\Captcha();
		if(!$captcha->check($code))
		{
			$this->error('验证码错误');
		}

	}
}