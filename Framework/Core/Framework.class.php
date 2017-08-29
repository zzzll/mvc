<?php
namespace Core;

//定义框架核心类
class Framework
{
    //运行框架
    public static function run()
    {
        self::initConfig();     //初始化配置文件
        self::initConst();      //初始化常量
        self::initSmarty();     //引入Smarty核心类
        self::autoloadClass();  //注册自动加载
        self::dispatch();       //分配路由
    }
    
    /**
     * 初始化Smarty类
     */
    public static function initSmarty()
    {
        require LIB_PATH . DS . 'Smarty/Smarty.class.php';
    }
    
    /**
     * 初始化配置文件 
     */
    public static function initConfig()
    {
        $GLOBALS['configs'] = require getcwd().DIRECTORY_SEPARATOR.'Application'.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.'Config.php';
    }
    
    /**
     * 初始化常量
     */
    public static function initConst()
    {
        // 定义系统常量
        define("DS", DIRECTORY_SEPARATOR);                       //目录分隔符（Linux兼容处理）
        define("ROOT_PATH", getcwd() . DS);                      //定义项目根目录
        define("APP_PATH", ROOT_PATH . 'Application' . DS);      //定义应用目录
        define("CONFIGS_PATH", APP_PATH . 'Config' . DS);        //定义配置文件目录
        define("FRAMEWORK_PATH", ROOT_PATH . 'Framework' . DS);  //定义框架目录
        define("CORE_PATH", FRAMEWORK_PATH . 'Core' . DS);       //定义框架核心文件目录
        define("LIB_PATH", FRAMEWORK_PATH . 'Libs' . DS);        //定义扩展类库目录

        define("CONTROLLER_PATH", APP_PATH . 'Controller' . DS); //定义控制器目录
        define("MODEL_PATH", APP_PATH . 'Model' . DS);     	 //定义模型目录
        define("VIEW_PATH", APP_PATH . 'View' . DS);             //定义视图目录

        //定义平台名称
        $platform = isset($_GET['p']) ? ucfirst($_GET['p']) : ucfirst($GLOBALS['configs']['default']['platform']);
        define("PLATFORM_NAME", $platform);
        //定义当前控制器名
        $controllerName = isset($_GET['c']) ? ucfirst($_GET['c']) : ucfirst($GLOBALS['configs']['default']['controller']);
        define("CONTROLLER_NAME", $controllerName);
        //定义当前方法名
        $actionName = isset($_GET['a']) ? strtolower($_GET['a']) : $GLOBALS['configs']['default']['action'];
        define("ACTION_NAME", $actionName);

        // 定义当前请求的系统常量
        define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);
        define('IS_GET',        REQUEST_METHOD =='GET' ? true : false);
        define('IS_POST',       REQUEST_METHOD =='POST' ? true : false);
    }
    
    /**
     * 注册自动加载
     */
    public static function autoloadClass()
    {
        spl_autoload_register('self::autoload');
    }
    
    /**
     * 自动加载
     */
    public static function autoload($className)
    {
	//需求自动引入控制器、模型、核心目录下的文件
	$type = basename(dirname($className));
        
	if ($type == 'Core' || $type == 'Libs') {
            //require "./Framework/Core/类名.class.php";
            require "./Framework/$className.class.php";
	} else if ($type == 'Model') {
            //require "./Application/Model/类名.class.php"
            require "./Application/$className.class.php";
	} else {
            //require "./Application/Controller/平台/类名.class.php";
            require "./Application/$className.class.php";
	}
    }
    
    /**
     * 分配路由
     */
    public static function dispatch()
    {
        //1.创建控制器对象
        //$controllerName = "\Controller\\$platform\\$controllerName";
        $controllerName = "\Controller\\".PLATFORM_NAME."\\".CONTROLLER_NAME."Controller";
        $controller = new $controllerName;
        //2.调用方法
        $actionName = ACTION_NAME;
        $controller->$actionName();
    }
}