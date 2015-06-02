<?php
namespace Library;

use Library\Core\Config;
use Library\Core\Debug;
use Library\Core\Dispatcher;
use Library\Core\Exception;
use Library\Core\Loader;
use Library\Core\Model;
use Library\Core\Request;
use Library\Core\Router;
use Library\Core\Uri;
use Library\Core\View;
use Library\Di\DI;
use Library\Di\Injectable;

define('VERSION', '2.2.0');

defined('LIB_PATH') || define('LIB_PATH', dirname(__FILE__));
defined('WEB_ROOT') || define('WEB_ROOT', dirname(__FILE__) . '/..');

require_once LIB_PATH . '/di/Injectable.php';

/**
 * 应用入口初始化 - Bootstrap.php
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @time     2013-07-11
 */
class Application extends Injectable
{
    /**
     * 记录时间和内存使用
     */
    public function __construct()
    {
        $GLOBALS['_startTime'] = microtime(TRUE);
        $GLOBALS['_error_404'] = false; // for set layout

        // 记录内存初始使用
        if (function_exists('memory_get_usage')) $GLOBALS['_startMemory'] = memory_get_usage();

        // 初始化自动加载
        require_once LIB_PATH . '/core/' . 'Loader.php';
        $loader = new Loader();
        $loader->register();
    }

    /**
     * 应用初始化
     *
     */
    public function init()
    {
        Config::load();

        // 错误异常处理
        $this->_errorSetting();

        // set time zone
        if (Config::getBase('timezone')) {
            date_default_timezone_set(Config::getBase('timezone'));
        }

        // 初始化基础服务
        $this->initService();

        // 最终运行控制器的方法
        $this->_run();
    }

    final function initService()
    {
        $di = new DI();
        $di->bind('request', new Request());
        $di->bind('uri', new Uri($di));
        $di->bind('view', new View($di));
        $this->view = $di->get('view');
        $di->bind('db', Model::connect());
        $this->di = $di;
    }

    /**
     * 错误异常处理
     *
     */
    private function _errorSetting()
    {
        # set display_errors
        ini_set('display_errors', intval(Config::getBase('display_errors')));

        if (Config::getBase('display_errors')) {
            error_reporting(E_ALL);
        }
        $exception = new Exception();
        // 监听内部错误 500 错误
        register_shutdown_function(array($exception, 'shutdown_handle'));
        // 设定错误和异常处理(调试模式有用)
        if (Config::getBase('debug')) {
            set_error_handler(array($exception, 'error_handler'));
            set_exception_handler(array($exception, 'exception_handler'));
        }
    }

    /**
     * 执行控制器并调用方法
     * --命名规则:
     * --骆驼峰命名规则,类名需要首字母大写
     * --控制器: 控制器名称+Controller.php 控制器类名和文件名相同 例: TestController.php,控制器类名:testController
     * --控制器方法: 方法名+action 例: testAction();
     * --控制器文件位于当前模块下的controller目录
     *
     *
     * param $string $modulePath 当前模块目录
     * param $string $controller 当前控制器名称
     * param $string $action 当前方法名称
     * return null
     */
    private function _run()
    {
        // 执行路由
        if ($conig = Config::getRouter()) {
            new Router($conig);
        }

        // 执行分发过程,获取mvc结构
        $disp = new Dispatcher($this->di);

        $controller = $disp->getController();
        $action = $disp->getAction();
        $module = $disp->getModule();
        $module_path = $module ? ucfirst($module) . '\\' : '';

        $_namespaceClass = '\App\\' . ucfirst($module_path) . 'Controller\\' . ucfirst($controller) . 'Controller';
        $actionName = $action . 'Action';

        // 判断当前请求的控制器,存在则自动加载
        if (class_exists($_namespaceClass, true)) {
            $controllerObj = new $_namespaceClass();
            if (method_exists($controllerObj, $actionName)) {
                $controllerObj->setDI($this->di);
                //执行action预处理方法
                if (method_exists($controllerObj, 'actionBefore')) {
                    $controllerObj->actionBefore();
                }
                // 执行action方法
                try {
                    $this->setDI($this->di);
                    $controllerObj->$actionName();

                    $this->view->display();

                } catch (\Exception $e) {
                    Debug::log($e->getFile() . ':' . $e->getMessage());
                    Debug::log('Trace:' . $e->getTraceAsString());
                }
            } else {
                Debug::log("Action does not exists:" . $_namespaceClass . '->' . $actionName . '()');
                # 方法是否存在404处理
                $this->_error(' : action is not exists', $module);
            }
        } else {
            Debug::log("Controller does not exists:" . $_namespaceClass);
            // 控制器不存在404错误处理
            $this->_error(' : controller is not exists', $module);
        }
    }

    /**
     * 404错误页面显示
     * --判断是否ajax请求
     * --判断是否开启调试
     * --判断默认module下ErrorController->indexAction是否存在
     *
     */
    private function _error($msg = '', $module)
    {
        // if ajax
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest';
        //输出404头信息
        header('HTTP/1.1 404 not found');

        // 判断是否以ajax方式请求
        if ($isAjax) {
            echo json_encode('');
            $GLOBALS['_error_404'] = true; // set error for tpl
        } else {
            // 默认以当前默认module下的ErrorController作为错误显示页面
            $module = $module ? $module : Config::getBase('module');
            $_namespaceClass = '\App\\' . ucfirst($module) . '\Controller\\' . 'ErrorController';
            $action = 'indexAction';
            // 模块方式输出,还是直接输出错误信息
            // 判断模板
            if (class_exists($_namespaceClass, true)) {
                $controllerObj = new $_namespaceClass();
                if (method_exists($_namespaceClass, $action)) {
                    $controllerObj->$action();
                    $controllerObj->view->display();
                } else {
                    echo '404 not found' . $msg;
                    $GLOBALS['_error_404'] = true; // set error for tpl
                }
            } else {
                echo '404 not found' . $msg;
                $GLOBALS['_error_404'] = true; // set error for tpl
            }

        }
    }

}