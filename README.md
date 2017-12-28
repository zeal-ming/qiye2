企业网站项目开发总结
========================
开发框架：thinkphp5.0.10
------------------------
开发用时：6天
------------------------

##后台模块
###第一天：
 * 内容：
   > 模板导入，公共模板分离，样式路径调整，数据表的导入，		  基于auth的权限控制管理。
   
 * 技术点：
   >使用thinkphp5中CUDR操作。
   
 *  新技术点：
  > 基于auth的权限控制管理，大白话就是，给不同的用户分配不同的系统权限。
  
   > * **auth原理：**
   >   > 1.建立四张表，用户表，用户组表，用户组明细表，规则表。四张表的关系，一句话概述：规则表为用户组指定规则，而属于某                                          个用户组的用户就享有该规则，如何知道用户属于哪个用户组？,用户明细表就是用户与用户组表的桥梁。
   >   > 2.导入已写好的auth.class 类，这个类你可以理解为是验证类，即验证用户是否拥有某个权限，验证原理是：当用户登录时，系统立                                     即判断属于哪个用户组，以此判断该用户拥有的规则即：rule表的name字段（例：‘cate/list’），关键一点来了，知道用户拥有                                     什么规则后，又改如何做出判断呢？这里用到thinkphp5的一个知识点，控制器的两个属性：controller(当前控制器名                                                 称),action（当前方法名），两者相结合后判断是否在规则数组中，如果在则验证通过，不在则无权限访问。
 ---------------------------------------------------------------------------
 
###第二天：
* 内容： 
> 管理员模块，用户组模块，规则模块。

* 技术点：
> 使用thinkphp5中CUDR操作。

* 重点：
> 规则模块的无限极分类，使用递归的思想进行分类，最后效果，从数据库中查找出的数据，按照父-子....-子的数据结构排出。

---------------------------------------------------------------------------

### 第三天：
* 内容：    
> 链接模块，文章模块。

* 技术点：
> 使用thinkphp5中CUDR操作，文章模块调用栏目的无限极分类。

* 新技术点：
> 钩子函数的使用：在进行cudr操作前后先进行某个操作，在文章模块的添加时，先进行模块的上传，在添加文章，删除，更新，也是如此，                    关键点：thinkphp5,提供了module(模块名)::event('before_insert',fuction(模块){});

 ---------------------------------------------------------------------------

###第四天：
* 内容： 
> 栏目模块，系统模块。

* 技术点：
> 使用thinkphp5中CUDR操作，栏目模块的无限极分类，连续删除，即删除父栏目的同时要删除相关的子栏目。'

* 新技术点：
> 系统模块表的构建，和以往表不同是，以往表中的字段名为属性名，字段值为属性值，系统表先是字段值即使属性名，又是属性值，虽然这里都在一张表里。

---------------------------------------------------
##前台模块

 ---------------------------------------------------------------------------

###第五天：
* 内容： 
> 首页模块，文章列表，图片列表，文章详情页，单页

* 技术点：
> 使用thinkphp5中CUDR操作。1，首页：热门文章分页获取，最新文章分页获取，轮播推荐的获取，链接的获取，栏目的重新组装获取，顶                     部推荐栏目获取，顶部推荐栏目的获取。2，图片列表，文章列表页，单页这三个模块都是根据栏目id, 获取相关数据。3，文章详情，这个根                      据文章id获取相关文章

* 重点：
> 公共模块的书写，往往很多页面需要用到相同的数据，所以公共模块的思想就出来了：一次获取，到处使用
公共模块：热点文章，菜单栏目，底部栏目，站点信息，站点位置
 
  ---------------------------------------------------------------------------
   
###第六天：
* 内容： 
> 搜索模块，

* 技术点：
> 使用thinkphp5的curl。根据用户搜索，对数据库中的titile字段继续模糊查找

* 重点：
> 如何在返回的分页中带入搜索的表里，以至于不会再点击分页时出错? 
`paginate(3,true,$config=['query'=>['keywords'=>$keywords]])`;
              

