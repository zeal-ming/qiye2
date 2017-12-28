<?php

namespace app\admin\controller;

use app\admin\model\Article as ArticleModel;
use app\admin\model\Cate as CateModel;
use think\Db;

class Article extends Common
{
	public function lst()
	{
		$Articles = db('article')->field('a.*,b.catename')
								->alias('a')
								->join('bk_cate b','a.cateid=b.id')
								->paginate(5);

		return $this->fetch('lst',['Articles'=>$Articles]);
	}

	public function add(){

		//获取所有分类
		$cates = (new CateModel)->getCates();

		return $this->fetch('add',['cates'=>$cates]);
	}

	public function save()
	{
		if(request()->isPost())
		{
			$data = input('post.');

			//这一段可以写在model类中的钩子函数中，表示添加前先上传图片
			// //获取上传的图片对象
			// $file = request()->file('image');
			// //移动public/upload目录下
			// if($file)
			// {
			// 	$info = $file->move(ROOT_PATH.'public/'.'uploads');

			// 	//把生成的文件夹下的文件路径保存到数据库中
			// 	$data['thumb'] = $info->getSaveName();
				
			// }

			$Article = new ArticleModel;

			$res = $Article->save($data);
			if(!$res)
			{
				
				$this->error('保存失败');
			}

			$this->success('保存成功','lst');
		}
	}

	public function edit(){

		$Article = ArticleModel::get(input('id'));
		//获取当前id,所对应的配置选项的值，以供用户选择对于值
		//$Article['values'] = explode(',',$Article['values']);
		//获取所有分类
		$cates = (new CateModel)->getCates();

		return $this->fetch('edit',['article'=>$Article,'cates'=>$cates]);
	}

	public function update()
	{	
		if($this->request->isPost())
		{
			$data = input('post.');
			
			$ArticleModel = new ArticleModel;
			//这里写在模型中的钩子函数中，表示更新前先上传
			// //判断是否重新上传文件，如果重新上传则使用新的并且删除旧的，否则使用旧的
			// if($file)
			// {
			// 	$info = $file->move(ROOT_PATH.'public/uploads');
			// 	//删除旧文件，注意，这里的使用文件路径
			// 	$article = $ArticleModel->get(['id'=>$data['id']]);
			// 	// dump($_SERVER['DOCUMENT_ROOT']);die;
			// 	$filePath = '/'.$article['thumb'];
			// 	//往往路径是最容易出错，也最容易忽略的，如果文件路径出错，则无法删除
			// 	if(file_exists($filePath))
			// 	{
			// 		@unlink($filePath);
			// 	}
			// 	$data['thumb'] = 'uploads/'.$info->getSaveName();
			// }
			
			
			$res = $ArticleModel->update($data);
			// dump($data);die;
			if(!$res)
			{
				$this->error('修改失败');
			}

			$this->success('修改成功','lst');
		}

	}

	public function delete()
	{
		$res = ArticleModel::destroy(input('id'));

		if(!$res)
		{
			$this->error('删除失败');
		}

		$this->success('删除成功');
	}
}