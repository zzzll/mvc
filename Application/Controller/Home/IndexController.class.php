<?php
namespace Controller\Home;

//定义后台控制器（默认控制器）
class IndexController extends \Core\Controller
{
    //创建默认方法
    public function index()
    {
        echo 'welcome';
    }
    
    //显示添加视图
    public function add()
    {
        //加载视图
        $this->smarty->display('add.html');
    }
}