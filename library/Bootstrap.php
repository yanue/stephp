<?php
namespace Library;

use Library\Core\Dispatcher;
use Library\Core\Loader;
use Library\Util\Debug;
use Library\Util\Session;

if ( ! defined('LIB_PATH')) exit('No direct script access allowed');

define('VERSION', '1.1.6');


/**
 * 应用入口初始化 - Bootstrap.php
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @time     2013-07-11
 */
class Bootstrap {

    public function __construct(){
        $GLOBALS['_startTime'] = microtime(TRUE);
        // 记录内存初始使用
        if(function_exists('memory_get_usage')) $GLOBALS['_startMemory'] = memory_get_usage();
    }

    /**
     * 应用初始化
     *
     */
    public function init(){
        require_once LIB_PATH . '/core/' . 'Loader.php';

        // 初始化自动加载
        $loader = new Loader(WEB_ROOT);
        $loader->register();

        // 执行分发过程,获取mvc结构
        $disp = new Dispatcher();
        $controller = $disp->getController();
        $action     = $disp->getAction();
        $getModule = $disp->getModule();

        // 最终执行控制器的方法
        $this->_execute($getModule,$controller,$action);
        $loader->unregister($loader,'loadClass');
        spl_autoload_register(array($this, 'loadClass'));
    }
    public function loadClass($className){
        echo $className;
    }

    /**
     * 执行控制器并调用方法
     * --命名规则:
     *  -骆驼峰命名规则,类名需要首字母大写
     *  -控制器: 控制器名称+Controller.php 控制器类名和文件名相同 例: testController.php,控制器类名:testController
     *  -控制器方法: 方法名+action 例: testAction();
     * --控制器文件位于当前模块下的controller目录
     *
     *
     * @param $string $modulePath 当前模块目录
     * @param $string $controller 当前控制器名称
     * @param $string $action 当前方法名称
     * @return null
     */
    private function _execute($module,$controller,$action){
        // 控制器:首字母大写的控制器+Controller
        // 控制器文件位于当前模块下的controller目录
        // 初始化自动加载
        $_namespaceClass = '\App\\'.ucfirst($module).'\Controller\\'.ucfirst($controller).'Controller';
        $controllerObj = new $_namespaceClass();
        // 方法名+action
        $controllerObj->{$action.'Action'}();
    }

}