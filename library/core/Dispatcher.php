<?php
namespace Library\Core;

use Library\Core\Request;
use Library\Util\Debug;

if ( ! defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 分发类，负责解析url分配mvc名称及请求数组
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class Dispatcher
{
    protected $_moduleName      = 'default';
    protected $_controllerName 	= 'index';  // 控制器
    protected $_actionName 		= 'index';  // 方法
    protected $_requestParams   = array();
    protected $_requestPath     = array(); # action 后面的目录结构
    protected $_urlSuffix       = '.html'; # path部分后面
    private $_isRouterMatched = false; # 是否经过路由并验证通过
    private $_appPath = null;

    protected $request = null;


	public function __construct(){
        $this->_appPath = WEB_ROOT.'/app';
        $this->request = new Request();
        $this->urlParse();// url解析mvc
        $this->requestParam(); // 合并请求进行组合
        // set Debug
        Debug::setRequestParam($this->_requestParams);
	}

    /**
     * url解析
     *
     * @return bool
     */
    private function urlParse(){
        if($this->_isRouterMatched == true) return false;#经过路由就不需要下面的处理了

        $path = ltrim($this->request->getPath(),'/');

        # 判断url后缀是否存在
        $_url_suffix = Loader::getConfig('application.default.suffix');
        $this->_urlSuffix =  $_url_suffix ? $_url_suffix : $this->_urlSuffix;

        # 截取后缀
        if(strlen($path)>strlen($this->_urlSuffix)){
            $path = (false === strripos($path,$this->_urlSuffix,strlen($this->_urlSuffix))) ? $path : substr($path,0,strlen($path)-strlen($this->_urlSuffix));
        }

        # 解析module,controller,action去他参数
        $requestPath = explode('/', $path);

        # 去除空项
        $requestPath = array_values(array_diff($requestPath, array(null)));

        $this->parseMvc($requestPath);
        return true;
    }

    /**
     * 解析mvc结构
     *
     */
    private function parseMvc($requestPath){

        # 通过'?'后面参数初步设置mvc
        $module     = isset($_GET['module']) && $_GET['module'] ? $_GET['module'] : Loader::getConfig('application.default.module');
        $controller = isset($_GET['controller']) && $_GET['controller'] ? $_GET['controller'] : Loader::getConfig('application.default.controller');
        $action     = isset($_GET['action']) && $_GET['action'] ? $_GET['action'] : Loader::getConfig('application.default.action');

        # 第一个参数与默认的module名相同
        if(isset($requestPath[0]) && Loader::getConfig('application.default.module') == $requestPath[0]){
            $module     = isset($requestPath[0]) && $requestPath[0]!='' ? $requestPath[0] : $module ;
            $controller = isset($requestPath[1]) && $requestPath[1]!='' ? $requestPath[1] : $controller ;
            $action     = isset($requestPath[2]) && $requestPath[2]!='' ? $requestPath[2] : $action ;
        }else{
            # 第一个参数与默认的module名不相同,则判断以它为module是否存在,
            if(isset($requestPath[0]) && file_exists($this->_appPath .'/'. $requestPath[0])){
                # 模块文件夹存在
                $module     = isset($requestPath[0]) && $requestPath[0]!='' ? $requestPath[0] : $module ;
                $controller = isset($requestPath[1]) && $requestPath[1]!='' ? $requestPath[1] : $controller ;
                $action     = isset($requestPath[2]) && $requestPath[2]!='' ? $requestPath[2] : $action ;
            }else{
                # 模块不存在,调用默认模块
                $controller = isset($requestPath[0]) && $requestPath[0]!='' ? $requestPath[0] : $controller ;
                $action     = isset($requestPath[1]) && $requestPath[1]!='' ? $requestPath[1] : $action ;
            }
        }

        # mvc
        $paramMvc = array(
            'module'        => $module,
            'controller'    => $controller,
            'action'        => $action
        );

        # 去除 mvc 结构后面的目录结构数组
        $_requestPath = array_values(array_diff($requestPath,array_values($paramMvc)));

        # 静态变量赋值
        $this->_moduleName      = $module;
        $this->_controllerName  = $controller;
        $this->_actionName      = $action;
        $this->_requestPath     = $_requestPath;
    }

    /**
     * 合并所有请求
     *
     * @return array
     */
    protected function requestParam(){

        #1. mvc部分
        $paramMvc = array(
            'module'=>$this->_moduleName,
            'controller'=>$this->_controllerName,
            'action'=>$this->_actionName
        );

        #2. 取出mvc后的path部分
        $paramPath =array();
        if(($len = count($this->_requestPath)) > 0 ){
            for($i=0;$i<ceil(($len)/2);$i++){
                $paramPath[$this->_requestPath[$i*2]] = isset($this->_requestPath[$i*2+1]) ? $this->_requestPath[$i*2+1] : '';
            }
        }

        #3. 解析'?'后query部分a=b&c=d
        $paramQuery = array();
        $queryString = $this->request->getQuery();
        parse_str($queryString,$paramQuery);
        $requestParams = array_merge($paramQuery,$paramMvc,$paramPath);

        # 合并所有请求,'?'后面的参数如果有与path部分相同的将被覆盖
        $this->_requestParams = $requestParams;
    }

    /**
     * 获取控制器名
     *
     * @return $string
     */
    public function getController(){
        return $this->_controllerName;
    }

    /**
     * 获取模块名称
     *
     * @return $string
     */
    public function getModule(){
        return $this->_moduleName;
    }

    /**
     * 获取方法名称
     *
     * @return $string
     */
    public function getAction(){
        return $this->_actionName;
    }

    /**
     * 获取当前方法名的url
     *
     * @return $string
     */
    public function getActionUrl(){
        return $this->request->getBaseUrl().$this->_moduleName.'/'.$this->_controllerName.'/'.$this->_actionName;
    }

    /**
     * 获取当前模块目录
     *
     * @return $string
     */
    public function getModulePath(){
        return $this->_appPath.'/'.$this->_moduleName;
    }

    /**
     * 获取应用目录
     *
     * @return $string
     */
    public function getAppPath(){
        return $this->_appPath;
    }
}
?>