<?php

namespace app\admin\controller;

use think\Controller;
use app\admin\controller\Auth;
use think\Request;

class Common extends Controller
{

	public function _initialize()
	{
		//进入后台页面时，先判断是否登录
		if(!session('id'))
		{
			return $this->error('请先登录','login/login');
		}
		//权限认证
		$this->confirmAuth();
	}

	public function confirmAuth()
	{
		//判断用户权限
		$auth = new Auth;
		$request = Request::instance();

		$con = $request->controller();
		$action = $request->action();

		// dump($auth->getGroups(1));exit();
		$name = strtolower($con.'/'.$action);

		// dump($name);die;
		//noAuth-array
		$noAuth = array('index/index','admin/lst','admin/edit','admin/update');
		//不需要权限的公共模块
		if(!in_array($name, $noAuth))
		{
			if(!$auth->check($name,session('id')))
			{
				$this->error('无权访问','index/index');
			}

		}
	}
}