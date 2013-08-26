<?php
namespace Library;

use Library\Core\Dispatcher;
use Library\Core\Exception;
use Library\Core\Loader;
use Library\Core\Router;
use Library\Util\Debug;
use Library\Util\Session;


define('VERSION', '2.0.8');

defined('LIB_PATH') || define('LIB_PATH',dirname(__FILE__));
defined('WEB_ROOT') || define('WEB_ROOT',dirname(__FILE__).'/..');

/**
 * 应用入口初始化 - Bootstrap.php
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @time     2013-07-11
 */
class Bootstrap {

    /**
     * 记录时间和内存使用
     */
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
        // 初始化自动加载
        require_once LIB_PATH . '/core/' . 'Loader.php';
        $loader = new Loader();
        $loader->register();

        // 错误异常处理
        $this->_errorSetting();

        // 最终运行控制器的方法
        $this->_run();
    }

    /**
     * 错误异常处理
     *
     */
    private function _errorSetting(){
        # set display_errors
        ini_set('display_errors',intval(Loader::getConfig('display_errors')));
        if(Loader::getConfig('display_errors')){
            error_reporting(E_ALL);
        }
        $exception = new Exception();
        // 监听内部错误 500 错误
        register_shutdown_function(array($exception,'shutdown_handle'));
        // 设定错误和异常处理(调试模式有用)
        if(Loader::getConfig('debug')){
            set_error_handler(array($exception,'error_handler'));
            set_exception_handler(array($exception,'exception_handler'));
        }
    }

    /**
     * 执行控制器并调用方法
     * --命名规则:
     * --骆驼峰命名规则,类名需要首字母大写
     * --控制器: 控制器名称+Controller.php 控制器类名和文件名相同 例: testController.php,控制器类名:testController
     * --控制器方法: 方法名+action 例: testAction();
     * --控制器文件位于当前模块下的controller目录
     *
     *
     * @param $string $modulePath 当前模块目录
     * @param $string $controller 当前控制器名称
     * @param $string $action 当前方法名称
     * @return null
     */
    private function _run(){
        // 执行路由
        if($conig = include( WEB_ROOT."/config/router.php" )){
            new Router($conig);
        }

        // 执行分发过程,获取mvc结构
        $disp = new Dispatcher();
        $disp->run();

        $controller = $disp->getController();
        $action     = $disp->getAction();
        $module = $disp->getModule();

        $_namespaceClass = '\App\\'.ucfirst($module).'\Controller\\'.ucfirst($controller).'Controller';
        $actionName = $action.'Action';

        // 判断当前请求的控制器,存在则自动加载
        if(class_exists($_namespaceClass,true)){
            $controllerObj = new $_namespaceClass();
            if(method_exists($controllerObj,$actionName)){
                // 执行action方法
                $controllerObj->$actionName();
            }else{
                if(Loader::getConfig('debug')){
                    echo '方法不存在：'.$actionName;
                }
                # 方法是否存在404处理
                $this->_error();
            }
        }else{
            if(Loader::getConfig('debug')){
                echo '控制器不存在：'.$_namespaceClass.' (请检查命名空间及路径和文件是否存在)<br >';
            }
            // 控制器不存在404错误处理
            $this->_error();
        }
    }

    /**
     * 404错误页面显示
     * --判断是否ajax请求
     * --判断是否开启调试
     * --判断默认module下ErrorController->indexAction是否存在
     *
     */
    private function _error(){
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest';
        // 判断是否以ajax方式请求
        if($isAjax){
            echo json_encode('');
        }else{
            //输出404头信息
            header('HTTP/1.1 404 not found');
            echo '<title>404 not found</title>';

            // 默认以当前默认module下的ErrorController作为错误显示页面
            $module = Loader::getConfig('module');
            $_namespaceClass = '\App\\'.ucfirst($module).'\Controller\\'.'ErrorController';
            $action = 'indexAction';
            // 模块方式输出,还是直接输出错误信息
            // 判断模板
            if(class_exists($_namespaceClass,true)){
                $controllerObj = new $_namespaceClass();
                if(method_exists($_namespaceClass,$action)){
                    $controllerObj->$action();
                }else{
                    echo '404 not found';
                }
            }else{
                echo '404 not found';
            }

        }
    }

}