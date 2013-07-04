<?php

/**
 * Bootstrap.php
 *
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.0 - 13-7-4
 */

if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

define('VERSION', '1.1.2');

class Bootstrap {

    public function __construct(){
        $this->_autoLoad();
    }

    public function init(){
        require_once ROOT_PATH.'library/core/'.'Loader.php';
        $loader = new Loader();

        $disp = new Dispatcher();
        $controller = $disp->getController();
        $action = $disp->getAction();
        $modulePath = $disp->getModulePath();
        $this->_execute($modulePath,$controller,$action);
        // 设置当前模块的模型目录
        $loader->setModelPath($modulePath.'models');
    }

    /**
     * 根据url引入控制器并调用方法
     *
     * @return null
     */
    private function _execute($modulePath,$controller,$action){
        $controller = ucfirst($controller) . 'Controller';
        $action = $action.'Action';
        $file = $modulePath . 'controllers/' . $controller . '.php';
        if (file_exists($file)) {
            require_once $file;
            if (! method_exists($controller, $action)) {
                $this->_error($modulePath,'方法不存在!');
            }else{
                $controllerObj = new $controller();
                $controllerObj->{$action}();
            }
        } else {
            $this->_error($modulePath,'控制器不存在!');
        }
    }

    /**
     * 错误提示
     *
     * @return null
     */
    private function _error($modulePath,$msg='') {
        $file = $modulePath . 'ErrorController.php';
        $msg = $msg ? $msg : '访问地址不存在!';
        if(file_exists($file)){
            require_once $file;
            $controllerObj = new ErrorController();
            $controllerObj->indexAction();
        }else{
            Debug::show('访问错误:',$msg);
            Debug::trace();
        }
    }

    public function _autoLoad(){

    }
}