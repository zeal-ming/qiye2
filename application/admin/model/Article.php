<?php

namespace app\admin\model;

use think\Model;

class Article extends Model
{
	
	//开启自动写入时间
	protected $autoWriteTimestamp = true;

	//添加文章到数据库之前，应该先上传图片，这里使用钩子函数，即执行某操作之前先做其他做出
	protected static function init()
	{
		Article::event('before_insert',function($article){

			//获取上传的图片对象
			$file = request()->file('image');

			//移动public/upload目录下
			if($file)
			{
				$info = $file->move(ROOT_PATH.'public/'.'uploads');
				// dump($info);die;
				//把生成的文件夹下的文件路径保存到数据库中
				$article['thumb'] = 'uploads/'.$info->getSaveName();
			}
			else
			{
				return $this->error('请先上传图片');
			}

		});

		Article::event('before_update', function($article){

			$file = request()->file('image');
			//判断是否重新上传文件，如果重新上传则使用新的并且删除旧的，否则使用旧的
			if($file)
			{
				$info = $file->move(ROOT_PATH.'public/uploads');
				//删除旧文件，注意，这里的使用文件路径
				// dump($_SERVER['DOCUMENT_ROOT']);die;
				$delThumb = self::get($article['id']);
				
				$filePath = $_SERVER['DOCUMENT_ROOT'].'/'.$delThumb['thumb'];
				// dump($filePath);die;
				//往往路径是最容易出错，也最容易忽略的，如果文件路径出错(这里使用主机系统的绝对文件路径)，则无法删除
				if(file_exists($filePath))
				{
					// dump('haha del');die;
					@unlink($filePath);
				}
				$article['thumb'] = 'uploads/'.$info->getSaveName();
			}
			
		});

		Article::event('before_delete',function($article){

			$delThumb = self::get($article['id']);
			$filePath = $_SERVER['DOCUMENT_ROOT'].'/'.$delThumb['thumb'];
			if(file_exists($filePath))
			{
				// dump($filePath);die;
				@unlink($filePath);
			}
		});
	}
	

}