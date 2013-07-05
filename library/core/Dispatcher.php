<?php
/**
 * 分发类，负责解析url分配mvc名称及请求数组
 * 
 * @author yanue <yanue@outlook.com>
 * @copyright http://yanue.net/
 * @version 1.0.0 2013-07-04
 */
class Dispatcher
{
    protected static $_appPath         = null;
    protected static $_moduleCurPath   = null;
    protected static $_moduleName      = 'default';
    protected static $_controllerName 	= 'index';  // 控制器
    protected static $_actionName 		= 'index';  // 方法
    protected static $_requestParams   = array();
    protected static $_requestPath     = array(); # action 后面的目录结构
    protected static $_urlSuffix       = '.html'; # path部分后面
    private static $_isRouterMatched = false; # 是否经过路由并验证通过

	public function __construct(){
        self::$_appPath = ROOT_PATH.(Loader::getConfig('application.path') ? Loader::getConfig('application.path') : 'app').'/';
        $this->urlParse();// url解析mvc
        $this->requestParam(); // 合并请求进行组合
	}

    /*
     * 获取控制器名
     *
     * @return $string
     */
    public function getController(){
        return self::$_controllerName;
    }

    /*
     * 获取模块名称
     *
     * @return $string
     */
    public function getModule(){
        return self::$_moduleName;
    }

    /*
     * 获取方法名称
     *
     * @return $string
     */
    public function getAction(){
        return self::$_actionName;
    }

    /*
     * 获取当前模块目录
     *
     * @return $string
     */
    public function getModulePath(){
        return self::$_moduleCurPath;
    }


    /**
     * url解析
     *
     * @return bool
     */
    private function urlParse(){
        if(self::$_isRouterMatched == true) return false;#经过路由就不需要下面的处理了

        $path = ltrim(Request::getPath(),'/');

        # 判断url后缀是否存在
        $_url_suffix = Loader::getConfig('application.default.suffix');
        self::$_urlSuffix =  $_url_suffix ? $_url_suffix : self::$_urlSuffix;

        # 截取后缀
        if(strlen($path)>strlen(self::$_urlSuffix)){
            $path = (false === strripos($path,self::$_urlSuffix,strlen(self::$_urlSuffix))) ? $path : substr($path,0,strlen($path)-strlen(self::$_urlSuffix));
        }

        # 解析module,controller,action去他参数
        $_requestPath = explode('/', $path);

        # 去除空项
        $_requestPath = array_values(array_diff($_requestPath, array(null)));

        $this->parseMvc($_requestPath);
        return true;
    }
    
    /**
     * 解析mvc结构
     *
     */
    private function parseMvc($_requestPath){

        # 通过'?'后面参数初步设置mvc
        $module     = Request::get('module') ? Request::get('module') : Loader::getConfig('application.default.module');
        $controller = Request::get('controller') ? Request::get('controller') : Loader::getConfig('application.default.controller');
        $action     = Request::get('action') ? Request::get('action') : Loader::getConfig('application.default.action');

        # 第一个参数与默认的module名相同
        if(isset($_requestPath[0]) && Loader::getConfig('application.default.module') == $_requestPath[0]){
            $module     = isset($_requestPath[0]) && $_requestPath[0]!='' ? $_requestPath[0] : $module ;
            $controller = isset($_requestPath[1]) && $_requestPath[1]!='' ? $_requestPath[1] : $controller ;
            $action     = isset($_requestPath[2]) && $_requestPath[2]!='' ? $_requestPath[2] : $action ;
        }else{
            # 第一个参数与默认的module名不相同,则判断以它为module是否存在,
            if(isset($_requestPath[0]) && file_exists(self::$_appPath . $_requestPath[0])){
                # 模块文件夹存在
                $module     = isset($_requestPath[0]) && $_requestPath[0]!='' ? $_requestPath[0] : $module ;
                $controller = isset($_requestPath[1]) && $_requestPath[1]!='' ? $_requestPath[1] : $controller ;
                $action     = isset($_requestPath[2]) && $_requestPath[2]!='' ? $_requestPath[2] : $action ;
            }else{
                # 模块不存在,调用默认模块
                $controller = isset($_requestPath[0]) && $_requestPath[0]!='' ? $_requestPath[0] : $controller ;
                $action     = isset($_requestPath[1]) && $_requestPath[1]!='' ? $_requestPath[1] : $action ;
            }
        }

        # mvc
        $paramMvc = array(
            'module'        => $module,
            'controller'    => $controller,
            'action'        => $action
        );

        # 去除 mvc 结构后面的目录结构数组
        $requestPath = array_values(array_diff($_requestPath,array_values($paramMvc)));

        # 静态变量赋值
        self::$_moduleName      = $module;
        self::$_controllerName  = $controller;
        self::$_actionName      = $action;
        self::$_moduleCurPath   = self::$_appPath . self::$_moduleName.'/';
        self::$_requestPath     = $requestPath;
    }

    /*
     * 合并所有请求
     *
     * @return array
     */
    protected function requestParam(){

        #1. mvc部分
        $paramMvc = array(
            'module'=>self::$_moduleName,
            'controller'=>self::$_controllerName,
            'action'=>self::$_actionName
        );

        #2. 取出mvc后的path部分
        $paramPath =array();
        if(($len = count(self::$_requestPath)) > 0 ){
            for($i=0;$i<ceil(($len)/2);$i++){
                $paramPath[self::$_requestPath[$i*2]] = isset(self::$_requestPath[$i*2+1]) ? self::$_requestPath[$i*2+1] : '';
            }
        }

        #3. 解析'?'后query部分a=b&c=d
        $paramQuery = array();
        $queryString = Request::getQuery();
        parse_str($queryString,$paramQuery);

        # 合并所有请求,'?'后面的参数如果有与path部分相同的将被覆盖
        self::$_requestParams = $requestParams = array_merge($paramQuery,$paramMvc,$paramPath);
    }
}
?>