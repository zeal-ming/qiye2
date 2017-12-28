<?php

namespace app\admin\controller;

use app\admin\model\Cate as CateModel;

class Cate extends Common
{
	public function lst()
	{
		$cateModel = new CateModel;
		$cates = $cateModel->getCates();
		
		return $this->fetch('lst',['cates'=>$cates]);
	}

	public function add(){

		$cateModel = new CateModel;
		$cates = $cateModel->getCates();

		return $this->fetch('add',['cates'=>$cates]);
	}

	public function save()
	{
		if(request()->isPost())
		{
			$data = input('post.');
			if(!isset($data['status']))
			{
				$data['status'] = 0;
			}
			$Cate = new CateModel;
			$res = $Cate->save($data);

			if(!$res)
			{
				$this->error('保存失败');
			}

			$this->success('保存成功','lst');
		}
	}

	public function edit(){

		$Cate = CateModel::get(input('id'));
		$cates = (new CateModel)->getCates();
		
		return $this->fetch('edit',['cate'=>$Cate,'cates'=>$cates]);
	}

	public function update()
	{	
		if($this->request->isPost())
		{
			$data = input('post.');
			if(!isset($data['status']))
			{
				$data['status'] = 0;
			}
			$Cate = new CateModel;
			$res = $Cate->update($data);

			if(!$res)
			{
				$this->error('修改失败');
			}

			$this->success('修改成功','lst');
		}

	}

	public function delete()
	{
		//这里使用更新status的方式进行删除
		$cateid = input('id');
		$cateModel = new CateModel();
		$sonids = $cateModel->getChildrenId($cateid);
		$allDelIds = $sonids;
		$allDelIds[] = $cateid;

		$updateList = [];
		foreach ($allDelIds as $value) {
			$arr['id'] = $value;
			$arr['status'] = 0;
			$updateList[] = $arr;
		}

		// dump($sonids);die;
		//删除自身，以及所有子栏目
		$res = $cateModel->saveAll($updateList);
		if(!$res)
		{
			$this->error('删除失败');
		}

		//删除栏目下面的所有文章
		foreach($sonids as $value)
		{
			\app\admin\model\Article::where('cateid',$value)->update(['status'=>0]);
		}
		$this->success('删除成功');
	}
}