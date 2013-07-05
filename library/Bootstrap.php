<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

define('VERSION', '1.1.4');

/**
 * 应用入口初始化 - Bootstrap.php
 *
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.1.4 - 13-7-5
 */

class Bootstrap {

    public $loader = null;

    public function __construct(){

        // 自动加载library
        $this->_autoLoad();

    }

    /*
     * 应用初始化
     *
     */
    public function init(){

        if(!$this->loader) $this->_autoLoad();

        // 执行分发过程,获取mvc结构
        $disp = new Dispatcher();
        $controller = $disp->getController();
        $action     = $disp->getAction();
        $modulePath = $disp->getModulePath();

        // models的路径根据不同的module会变,因此需要解析玩url才分配
        // 并且只能分配当前模块models的路径.
        $this->loader->addIncludePath($modulePath.'models');

        // 最终执行控制器的方法
        $this->_execute($modulePath,$controller,$action);
    }

    /**
     * 执行控制器并调用方法
     * --命名规则:
     *  -骆驼峰命名规则,类名需要首字母大写
     *  -控制器: 控制器名称+Controller.php 控制器类名和文件名相同 例: testController.php,控制器类名:testController
     *  -控制器方法: 方法名+action 例: testAction();
     * --控制器文件位于当前模块下的controllers目录
     *
     *
     * @param $string $modulePath 当前模块目录
     * @param $string $controller 当前控制器名称
     * @param $string $action 当前方法名称
     * @return null
     */
    private function _execute($modulePath,$controller,$action){

        // 控制器:首字母大写的控制器+Controller
        $controller = ucfirst($controller) . 'Controller';
        // 方法名+action
        $action = $action.'Action';
        // 控制器文件位于当前模块下的controllers目录
        $file = $modulePath . 'controllers/' . $controller . '.php';

        // 判断并执行
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

    /*
     * 自动加载library
     *
     */
    public function _autoLoad(){
        require_once ROOT_PATH.'library/core/'.'Loader.php';
        $this->loader = new Loader();
    }
}