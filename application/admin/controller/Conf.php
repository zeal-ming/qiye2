<?php

namespace app\admin\controller;

use app\admin\model\Conf as ConfModel;

class Conf extends Common
{
	public function lst()
	{
		$confs = ConfModel::paginate(3);

		return $this->fetch('lst',['confs'=>$confs]);
	}

	public function add(){

		return $this->fetch('add');
	}

	public function save()
	{
		if(request()->isPost())
		{
			$data = input('post.');
			$Conf = new ConfModel;
			$res = $Conf->save($data);

			if(!$res)
			{
				$this->error('保存失败');
			}

			$this->success('保存成功','lst');
		}
	}

	public function edit(){

		$conf = ConfModel::get(input('id'));
		//获取当前id,所对应的配置选项的值，以供用户选择对于值
		//$conf['values'] = explode(',',$conf['values']);

		return $this->fetch('edit',['conf'=>$conf]);
	}

	public function update()
	{	
		if($this->request->isPost())
		{
			$data = input('post.');
			$Conf = new ConfModel;
			$res = $Conf->update($data);

			//id为空也能返回当前对象？
			// dump($res);die;
			if(!$res)
			{
				$this->error('修改失败');
			}

			$this->success('修改成功','lst');
		}

	}

	public function delete()
	{
		$res = ConfModel::destroy(input('id'));

		if(!$res)
		{
			$this->error('删除失败');
		}

		$this->success('删除成功');
	}

	public function conf()
	{

		$confModel = new \app\admin\model\Conf;
		if(request()->isPost())
		{
			$data = input('post.');
			if(!isset($data['comment']))
			{
				$data['comment'] = '不允许';
			}

			if(!isset($data['code']))
			{
				$data['code'] = '否';
			}
			// dump($data);die;	
			foreach ($data as $key => $value) {
				$res = $confModel->where('enname',$key)->update(['value'=>$value]);
			}
			// dump($res);die;
			// if(!$res)
			// {
			// 	$this->error('更新失败');
			// }

			$this->success('更新成功','lst');
		}

		//get方法进来走的流程
		$confs = $confModel->all();
		//重新组装数组
		foreach ($confs as $value) {

			if($value['values'])
			{
				$value['values'] = explode(',',$value['values']);
			}
		}
		// dump($confs);die;
		return $this->fetch('conf',['confs'=>$confs]);
	}
}