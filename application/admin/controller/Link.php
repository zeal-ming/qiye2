<?php

namespace app\admin\controller;

use app\admin\model\Link as LinkModel;

class Link extends Common
{
	public function lst()
	{
		$links = LinkModel::paginate(3);

		return $this->fetch('lst',['links'=>$links]);
	}

	public function add(){

		return $this->fetch('add');
	}

	public function save()
	{
		if(request()->isPost())
		{
			$data = input('post.');
			$link = new LinkModel;
			$res = $link->save($data);

			if(!$res)
			{
				$this->error('保存失败');
			}

			$this->success('保存成功','lst');
		}
	}

	public function edit(){

		$link = LinkModel::get(input('id'));

		return $this->fetch('edit',['link'=>$link]);
	}

	public function update()
	{	
		if($this->request->isPost())
		{
			$data = input('post.');
			$link = new LinkModel;
			$res = $link->update($data);

			if(!$res)
			{
				$this->error('修改失败');
			}

			$this->success('修改成功','lst');
		}

	}

	public function delete()
	{
		$res = LinkModel::destroy(input('id'));

		if(!$res)
		{
			$this->error('删除失败');
		}

		$this->success('删除成功');
	}
}