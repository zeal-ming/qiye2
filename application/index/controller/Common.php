<?php
namespace app\index\controller;

use think\Controller;
use app\common\model\Cate;
use app\common\model\Article as ArticleModel;
use think\Db;

class Common extends Controller
{
	
    public function _initialize()
    {
    	
        //获取右侧热门文章
    	$hotArtcles = (new ArticleModel)->order('click desc')->limit(6)->select();
        //获取推荐底部菜单
        $recBottomCates = Cate::all(['rec_bottom'=>1]);
        
        $this->assign([
            //获取栏目
            'cates' => $this->getCates(),
            //获取所有热门文章
            'hotArtcles' => $hotArtcles,
            //获取当前位置
            'position' => $this->getPosition(),
            //获取底部菜单
            'recBottomCates'=> $recBottomCates,
            //获取站点名称
            'sitename' => $this->getConf(),
            ]);
    }

    //获取栏目
    public function getCates()
    {
        //方法1
        //获取顶级分类组成的数组
        $arrTopCates = Db::table('bk_cate')->where(['pid'=>0])->select();
        //获取所有数组
        $arrAll = Db('cate')->select();
        //二级栏目数组
        $twoCates = [];

        //获取顶级分类下的栏目
        foreach ($arrTopCates as $key => $value) 
        {
            foreach ($arrAll as $key2 => $value2) 
            {
                if($value['id'] == $value2['pid'])
                {
                    $twoCates[] = $value2;
                }
            }
            // dump($twoCates);
            //判断是否存在二级栏目
            if($twoCates)
            {
                //把顶级栏目下的子栏目数组放到顶级数组中
                $arrTopCates[$key]['children'] = $twoCates;
            }
            else
            {
                $arrTopCates[$key]['children'] = 0;
            }
            
            //清空二级栏目数组，以便下次再用
            $twoCates = [];

            
        }

        return $arrTopCates;
        //方法2
    //获取所有栏目,并重新组装数组，把每一分支组成一个数组
        // $cates = (new CateModel)->getCates();
        // $arr1 = [];
        // $arr2 = [];
        // foreach ($cates as $value) 
        // {
        //  foreach ($cates as $value2) 
        //  {
        //      if($value['id'] == $value2['pid'])
        //      {
        //          //把自己也放进去
        //          $arr2[] = $value;
        //          $arr2[] = $value2;
        //      }
        //  }

        //  // dump($arr2);die;
        //  if($arr2)
        //  {
        //      $arr1[$value['catename']] = $arr2;
        //  }
        //  else if($value['pid'] == 0)
        //  {
        //      $arr1[$value['catename']] = $value['id'];
        //  }
            

     //         //清空数组
        //  $arr2 = [];
        // }
        // $this->cates = $arr1;
        // dump($arrTopCates);die;
    }

    //获取站点配置
    public function getConf()
    {
        //获取站点名称
        $sitename = \app\common\model\Conf::get(['enname'=>'sitename']);

        return $sitename['value'];
    }

    //获取当前位置
    public function getPosition()
    {
       
        $cateid = '';
        //判断进来的栏目id,还是文章id,如果是文章，那么先要获取文章
        if(!empty(input('cateid')))
        {
            $cateid = input('cateid');
        }
        else if (!empty(input('artid')))
        {
            //获取文章
            $cateid = ArticleModel::get(input('artid'))['cateid'];

        }

        //根据子id，获取父id
        $cate = (new Cate)->getParentId($cateid);

        //将原来数组的值反向排序
        $cate = array_reverse($cate);
        
        // dump($cate);die;
        return $cate;
    
    }
}
