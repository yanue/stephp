<?php
namespace Library\Core;

use Library\Di\DI;
use Library\Di\Injectable;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 分发类，负责解析url分配mvc名称及请求数组
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class Dispatcher extends Injectable
{
    private $_moduleName = 'home'; // 模块
    private $_controllerName = 'index'; // 控制器
    private $_actionName = 'index'; // 方法
    private $_requestParams = array(); # action 后面的目录结构
    private $_requestPath = array(); # path部分后面
    private $_urlSuffix = '.html';
    private $_appPath = null;
    private $_apiName = null;

    const __last_var = '__last_var';

    /**
     * 初始化请求
     *
     */
    public function __construct()
    {
        $this->setDI(new DI());
        $this->_appPath = WEB_ROOT . '/app';
        $this->parseMvc(); // url解析mvc

        $this->requestParam(); // 合并请求进行组合
        // set Debug
        Debug::setRequestParam($this->_requestParams);
    }

    /**
     * 解析mvc结构
     * 新增api直接解析
     * -- home/api/controller/action
     */
    private function parseMvc()
    {
        $requestPath = $this->request->getSegments();

        # 通过'?'后面参数初步设置mvc
        $module = isset($_GET['m']) && $_GET['m'] ? $_GET['m'] : Config::getBase('module');
        $controller = isset($_GET['c']) && $_GET['c'] ? $_GET['c'] : Config::getBase('controller');
        $action = isset($_GET['a']) && $_GET['a'] ? $_GET['a'] : Config::getBase('action');
        $api = isset($_GET['api']) && $_GET['api'] ? $_GET['api'] : "";

        # 第一个参数与默认的module名相同
        if (isset($requestPath[0]) && Config::getBase('module') == $requestPath[0]) {
            $module = isset($requestPath[0]) && $requestPath[0] != '' ? $requestPath[0] : $module;
            $api = isset($requestPath[1]) && $requestPath[1] == 'api' ? 'api' : '';
            # 形如admin/api
            if ($api) {
                $controller = isset($requestPath[2]) && $requestPath[2] != '' ? $requestPath[2] : $controller;
                $action = isset($requestPath[3]) && $requestPath[3] != '' ? $requestPath[3] : $action;
            } else {
                $controller = isset($requestPath[1]) && $requestPath[1] != '' ? $requestPath[1] : $controller;
                $action = isset($requestPath[2]) && $requestPath[2] != '' ? $requestPath[2] : $action;
            }
        } else {
            # 第一个参数与默认的module名不相同,则判断以它为module是否存在,
            if (isset($requestPath[0]) && file_exists($this->_appPath . '/' . $requestPath[0])) {
                # 模块文件夹存在
                $module = isset($requestPath[0]) && $requestPath[0] != '' ? $requestPath[0] : $module;
                $api = isset($requestPath[1]) && $requestPath[1] == 'api' ? 'api' : '';
                # 形如admin/api
                if ($api) {
                    $controller = isset($requestPath[2]) && $requestPath[2] != '' ? $requestPath[2] : $controller;
                    $action = isset($requestPath[3]) && $requestPath[3] != '' ? $requestPath[3] : $action;
                } else {
                    $controller = isset($requestPath[1]) && $requestPath[1] != '' ? $requestPath[1] : $controller;
                    $action = isset($requestPath[2]) && $requestPath[2] != '' ? $requestPath[2] : $action;
                }
            } else {
                # 模块不存在,调用默认模块
                $controller = isset($requestPath[0]) && $requestPath[0] != '' ? $requestPath[0] : $controller;
                $action = isset($requestPath[1]) && $requestPath[1] != '' ? $requestPath[1] : $action;
            }
        }

        # mvc
        $paramMvc = array(
            'module' => $module,
            'api' => $api,
            'controller' => $controller,
            'action' => $action,
        );

        # 去除 mvc 结构后面的目录结构数组
        $_requestPath = array_values(array_diff($requestPath, array_values($paramMvc)));

        # 静态变量赋值
        $this->_moduleName = $module;
        $this->_controllerName = $controller;
        $this->_actionName = $action;
        $this->_apiName = $api;

        $this->_requestPath = $_requestPath;
    }

    /**
     * 合并所有请求
     *
     * @return array
     */
    protected function requestParam()
    {

        #1. mvc部分
        $paramMvc = array(
            'module' => $this->_moduleName,
            'controller' => $this->_controllerName,
            'action' => $this->_actionName
        );

        #2. 取出mvc后的path部分
        $paramPath = array();
        $paramPath['__last_var'] = ''; # 最后一个无key参数
        if (($len = count($this->_requestPath)) > 0) {
            for ($i = 0; $i < ceil(($len) / 2); $i++) {
                if (isset($this->_requestPath[$i * 2 + 1]) && $this->_requestPath[$i * 2 + 1]) {
                    $paramPath[$this->_requestPath[$i * 2]] = $this->_requestPath[$i * 2 + 1];
                }
            }
            # 追加最后一个参数
            if ($len % 2 == 1) {
                $paramPath['__last_var'] = $this->_requestPath[$len - 1];
            }
        }

        #3. 解析'?'后query部分a=b&c=d
        $paramQuery = array();
        $queryString = $this->request->getQuery();
        parse_str($queryString, $paramQuery);
        $requestParams = array_merge($paramQuery, $paramMvc, $paramPath);

        # 合并所有请求,'?'后面的参数如果有与path部分相同的将被覆盖
        $this->_requestParams = $requestParams;
    }

    /**
     * 获取控制器名
     *
     * @return string
     */
    public function getController()
    {
        return $this->_controllerName;
    }

    /**
     * 获取模块名称
     *
     * @return string
     */
    public function getModule()
    {
        return $this->_moduleName;
    }

    /**
     * 获取api
     *
     * @return string
     */
    public function getApi()
    {
        return $this->_apiName;
    }

    /**
     * 获取方法名称
     *
     * @return string
     */
    public function getAction()
    {
        return $this->_actionName;
    }

    /**
     * 获取当前模块目录
     *
     * @return string
     */
    public function getModulePath()
    {
        return $this->_appPath . '/' . $this->_moduleName;
    }

    /**
     * 获取应用目录
     *
     * @return string
     */
    public function getAppPath()
    {
        return $this->_appPath;
    }

    /**
     * 获取请求参数组
     *
     * @return array
     */
    public function getParams()
    {
        return $this->_requestParams;
    }

    /**
     * 获取剩余请求参数
     *
     * @return array
     */
    public function getPathArray()
    {
        return $this->_requestPath;
    }

    /**
     * 获取当前模块的url
     *
     * @param string $uri
     * @param bool $setSuffix
     * @return string
     */
    public function getModuleUrl($uri = '', $setSuffix = true)
    {
        $moduleUri = $this->_moduleName != Config::getBase('module') ? '/' . $this->_moduleName : '';
        $moduleUrl = $this->request->getBaseUrl() . $moduleUri;

        // add uri to url
        return $this->addUri($moduleUrl, $uri, $setSuffix);
    }

    /**
     * 获取当前控制器的url
     *
     * @param string $uri
     * @param bool $setSuffix
     * @return string
     */
    public function getControllerUrl($uri = '', $setSuffix = true)
    {
        $moduleUri = $this->_moduleName != Config::getBase('module') ? '/' . $this->_moduleName : '';
        $moduleUri = $this->_apiName == 'api' ? $moduleUri . '/api' : $moduleUri;
        $controllerUrl = $this->request->getBaseUrl() . $moduleUri . '/' . $this->_controllerName;

        // add uri to url
        return $this->addUri($controllerUrl, $uri, $setSuffix);
    }

    /**
     * 获取当前方法的url
     *
     * @param string $uri
     * @param bool $setSuffix
     * @return string
     */
    public function getActionUrl($uri = '', $setSuffix = true)
    {
        $moduleUri = $this->_moduleName != Config::getBase('module') ? '/' . $this->_moduleName : '';
        $moduleUri = $this->_apiName == 'api' ? $moduleUri . '/api' : $moduleUri;
        $actionUrl = $this->request->getBaseUrl() . $moduleUri . '/' . $this->_controllerName . '/' . $this->_actionName;

        // add uri to url
        return $this->addUri($actionUrl, $uri, $setSuffix);
    }

    /**
     * 获取后缀
     *
     * @return string
     */
    public function getSuffix()
    {
        return $this->_urlSuffix;
    }

    /**
     * add uri to current url
     *
     * @param $curUrl
     * @param $uri
     * @param bool $setSuffix
     * @return string
     */
    protected function addUri($curUrl, $uri, $setSuffix = true)
    {
        // parse uri
        $urlArr = parse_url($uri);
        $urlPath = isset($urlArr['path']) ? $urlArr['path'] : '';
        $urlQuery = isset($urlArr['query']) ? $urlArr['query'] : '';

        // set uri path to url
        $url = rtrim($curUrl, '/') . '/' . ltrim($urlPath, '/');

        // url
        $queryString = $urlQuery ? '?' . $urlQuery : '';
        if ($setSuffix) {
            $url = rtrim($url, '/') . $this->getSuffix() . $queryString;
        } else {
            $url = rtrim($url, '/') . $queryString;
        }

        return $url;
    }

    /**
     * 获取mvc段
     *
     * @return string
     */
    public function getMvcString()
    {
        $moduleUri = $this->getModule() != Config::getBase('module') ? $this->getModule() . '/' : '';
        $moduleUri = $this->getApi() == 'api' ? $moduleUri . 'api/' : $moduleUri;

        return $moduleUri . $this->getController() . '/' . $this->getAction();
    }
}
