<?php
define('VERSION', '1.0.1');

if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

class Bootstrap {
    public static $_fullUrl         = null;
    public static $_moduleName      = 'default';
    public static $_appPath         = null;
    public static $_moduleCurPath   = null;
    public static $_controllerName 	= 'index';  // 控制器
    public static $_actionName 		= 'index';  // 方法
    public static $_requestString   = array(); # ?后面的部分 a=b&c=d
    public static $_requestParams   = array();
    public static $_requestPath     = array(); # action 后面的目录结构
    public static $_urlSuffix       = '.html'; # path部分后面
    public static $_isRouterMatched = false; # 是否经过路由并验证通过

    private $_libPath 			= 'library/';
    private $_errorFile 		= 'ErrorController.php';

    public function __construct(){
        $GLOBALS['_startTime']=microtime(true);
        $GLOBALS['_startMemory']=memory_get_usage();
    }

    /**
     * 初始化应用
     *
     */
    public function init() {
        $this->__autoLoad();
        if($timeZone = $this->_getIni('phpSettings.date.timezone')){
            date_default_timezone_set($timeZone);
        }
        if($display_errors = $this->_getIni('phpSettings.display_errors')){
            ini_set('display_errors',$display_errors);
        }
        // 设定错误和异常处理
        if($this->_getIni('phpSettings.debug')){
            register_shutdown_function(array('Debug','fatalError'));
            set_error_handler(array('Debug','appError'));
            set_exception_handler(array('Debug','appException'));
        }

        self::$_appPath = ROOT_PATH.($this->_getIni('application.path') ? $this->_getIni('application.path') : 'app').'/';
        $routerFile = self::$_appPath.'AppRouter.php';
        # 路由设置
        if(file_exists($routerFile)){
            require_once $routerFile;
            if(method_exists('AppRouter','__construct')){
                new AppRouter();
            }
        }

        $this->urlParse();
        $this->_execute();
    }

    /**
     * url解析并分配mvc
     *
     *
     */
    private function urlParse(){
        if(self::$_isRouterMatched == true) return false;#经过路由就不需要下面的处理了
        $settings = parse_ini_file(ROOT_PATH.'configs/application.ini');
        $path = ltrim(Request::getPath(),'/');
        # 判断url后缀是否存在
        self::$_urlSuffix = $settings['application.default.suffix'] ? $settings['application.default.suffix'] : self::$_urlSuffix;
        # 截取后缀
        if(strlen($path)>strlen(self::$_urlSuffix)){
            $path = (false === strripos($path,self::$_urlSuffix,strlen(self::$_urlSuffix))) ? $path : substr($path,0,strlen($path)-strlen(self::$_urlSuffix));
        }
        # 解析module,controller,action去他参数
        $pathArr = explode('/', $path);
        # 取出控制
        $pathArr = array_values(array_diff($pathArr, array(null)));
        $this->parseMvc($pathArr);
    }

    /**
     * 解析mvc结构
     */
    private function parseMvc($pathArr){
        $settings = parse_ini_file(ROOT_PATH.'configs/application.ini');
        # 通过'?'后面参数初步设置mvc
        $module = Request::get('module') ? Request::get('module') : $this->_getIni('application.default.module');
        $controller = Request::get('controller') ? Request::get('controller') : $this->_getIni('application.default.controller');
        $action = Request::get('action') ? Request::get('action') : $this->_getIni('application.default.action');

        # 截取mvc部分后面的path请求key=>val
        $paramPath = array();
        # 获取action后面的目录结构参数起始位置:第一个是不是module决定是否删除第3个元素,剩下作为path部分的除mvc外的请求参数
        $unset_pathArr_pos2 = false;
        # 第一个参数与默认的module名相同
        if(isset($pathArr[0]) && $settings['application.default.module'] == $pathArr[0]){
            $module = isset($pathArr[0]) && $pathArr[0]!='' ? $pathArr[0] : $module ;
            $controller = isset($pathArr[1]) && $pathArr[1]!='' ? $pathArr[1] : $controller ;
            $action = isset($pathArr[2]) && $pathArr[2]!='' ? $pathArr[2] : $action ;
            if(($len = count($pathArr)) > 2){
                $unset_pathArr_pos2 = true;
                for($i=0;$i<ceil(($len-3)/2);$i++){
                    $paramPath[$pathArr[$i*2+3]] = isset($pathArr[$i*2+4]) ? $pathArr[$i*2+4] : '';
                }
            }
        }else{
            # 第一个参数与默认的module名不相同,则判断以它为module是否存在,
            if(isset($pathArr[0]) && file_exists(self::$_appPath.$pathArr[0])){
                # 模块文件夹存在
                $module = isset($pathArr[0]) && $pathArr[0]!='' ? $pathArr[0] : $module ;
                $controller = isset($pathArr[1]) && $pathArr[1]!='' ? $pathArr[1] : $controller ;
                $action = isset($pathArr[2]) && $pathArr[2]!='' ? $pathArr[2] : $action ;
                if(($len = count($pathArr)) >2 ){
                    $unset_pathArr_pos2 = true;
                    for($i=0;$i<ceil(($len-3)/2);$i++){
                        $paramPath[$pathArr[$i*2+3]] = isset($pathArr[$i*2+4]) ? $pathArr[$i*2+4] : '';
                    }
                }
            }else{
                # 模块不存在,调用默认模块
                $controller = isset($pathArr[0]) && $pathArr[0]!='' ? $pathArr[0] : $controller ;
                $action = isset($pathArr[1]) && $pathArr[1]!='' ? $pathArr[1] : $action ;
                if(($len = count($pathArr)) >2 ){
                    for($i=0;$i<ceil(($len-2)/2);$i++){
                        if($pathArr[$i*2+2] && !is_numeric($pathArr[$i*2+2])){
                            $paramPath[$pathArr[$i*2+2]] = isset($pathArr[$i*2+3]) ? $pathArr[$i*2+3] : '';
                        }
                    }
                }
            }
        }
        $paramMvc = array(
            'module'        =>$module,
            'controller'    =>$controller,
            'action'        =>$action
        );
        # 静态变量赋值
        self::$_moduleName      = $module;
        self::$_controllerName  = $controller;
        self::$_actionName      = $action;
        self::$_moduleCurPath   = self::$_appPath.self::$_moduleName.'/';

        unset($pathArr[0]);
        unset($pathArr[1]);
        if($unset_pathArr_pos2) unset($pathArr[2]);
        $requestPath = array_values($pathArr);


        # 解析'?'后query部分a=b&c=d
        $paramQuery = array();
        $queryString = isset($urlParam['query']) ? $urlParam['query'] : '' ;
        parse_str($queryString,$paramQuery);
        # 合并所有请求
        $requestParams = array_merge($paramMvc,$paramQuery,$paramPath);

        self::$_requestString   = $queryString;
        self::$_requestPath     = $requestPath;
        self::$_requestParams   = $requestParams;
    }

    /**
     * 根据url引入控制器并调用方法
     *
     * @return null
     */
    private function _execute(){
        $controller = ucfirst(self::$_controllerName) . 'Controller';
        $action = self::$_actionName.'Action';
        $file = self::$_moduleCurPath . 'controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            if (! method_exists($controller, $action)) {
                $this->_error();
            }else{
                $controllerObj = new $controller();
                $controllerObj->{$action}();
            }
        } else {
            $this->_error();
        }
    }

    /**
     * 获取ini配置文件的参数
     */
    private function _getIni($key){
        $settings = parse_ini_file(ROOT_PATH.'configs/application.ini');
        return isset($settings[$key]) ? $settings[$key] : '' ;
    }
    
    /**
     * 错误提示
     *
     * @return null
     */
    private function _error($msg='') {
        $file = self::$_moduleCurPath . $this->_errorFile;
        if(file_exists($file)){
            require_once $file;
            $controllerObj = new ErrorController();
            $controllerObj->indexAction();
        }else{
            echo '错误:访问地址不存在!'.$msg;
            Debug::trace();
        }
    }

    /**
     * 加载基本核心类文件
     *
     * @return null
     */
    private  function __autoLoad (){
        require_once ROOT_PATH.$this->_libPath.'db/Db.php';
        require_once ROOT_PATH.$this->_libPath.'core/Model.php';
        require_once ROOT_PATH.$this->_libPath.'core/View.php';
        require_once ROOT_PATH.$this->_libPath.'core/Controller.php';
        require_once ROOT_PATH.$this->_libPath.'core/Uri.php';
        require_once ROOT_PATH.$this->_libPath.'util/Debug.php';
        require_once ROOT_PATH.$this->_libPath.'core/Router.php';
        require_once ROOT_PATH.$this->_libPath.'core/Loader.php';
        require_once ROOT_PATH.$this->_libPath.'func/Func.php';
        require_once ROOT_PATH.$this->_libPath.'core/Request.php';
        require_once ROOT_PATH.$this->_libPath.'util/MsgCode.php';
        require_once ROOT_PATH.$this->_libPath.'util/Hash.php';
        require_once ROOT_PATH.$this->_libPath.'util/Session.php';
        require_once ROOT_PATH.$this->_libPath.'util/Cookie.php';
        require_once ROOT_PATH.$this->_libPath.'util/Csrf.php';
    }

    public function __destruct(){
//        Debug::memAndTime();
    }
}