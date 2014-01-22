<?php
namespace Library\Core;

use Library\Util\Session;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 控制器处理类
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class Controller
{
    /**
     * @var string
     */
    public $controller = null;

    /**
     * @var Request
     */
    public $request = null;

    /**
     * @var string
     */
    public $action = null;

    /**
     * @var string
     */
    public $module = null;

    /**
     * 视图
     * @var View
     */
    public $view = NULL;

    /**
     * url全局处理
     * @var Uri
     */
    public $uri = NULL;

    /**
     * session处理
     * @var Session
     */
    public $session = null;

    /**
     * 初始化控制器
     *
     */
    public function __construct()
    {
        $this->view = new View();
        $this->uri = $this->view->uri();
        $this->session = new Session();
        $this->request = new Request();
        $this->controller = $this->view->controller = $this->uri->getController();
        $this->action = $this->view->action = $this->uri->getAction();
        $this->module = $this->view->module = $this->uri->getModule();
    }

    /**
     *
     * @param array $add_arr
     * @param array $rm_arr
     * @param bool $getQueryString
     * @return string
     */
    public function setUrl($add_arr = array(), $rm_arr = array(), $getQueryString = false)
    {
        return $this->uri->setUrl($add_arr, $rm_arr, $getQueryString);
    }

    /**
     * baseUrl映射到controller上
     *
     */
    public function baseUrl($uri = '', $setSuffix = true)
    {
        return $this->uri->baseUrl($uri, $setSuffix);
    }

    /**
     * ControllerUrl映射到controller上
     *
     */
    public function moduleUrl($uri = '', $setSuffix = true)
    {
        return $this->uri->getModuleUrl($uri, $setSuffix);
    }


    /**
     * ControllerUrl映射到controller上
     *
     */
    public function controllerUrl($uri = '', $setSuffix = true)
    {
        return $this->uri->getControllerUrl($uri, $setSuffix);
    }

    /**
     * actionUrl映射到controller上
     *
     */
    public function actionUrl($uri = '', $setSuffix = true)
    {
        return $this->uri->getActionUrl($uri, $setSuffix);
    }

    /**
     * http get 方法
     * @param $_name
     * @param null $default
     * @param null $filter
     * @return mixed|null|string
     */
    public function get($_name, $default = null, $filter = NULL)
    {
        return $this->view->get($_name, $default, $filter);
    }

    /**
     * http post 方法
     * @param $_name
     * @param null $default
     * @param null $filter
     * @return mixed|null|string
     */
    public function post($_name, $default = null, $filter = NULL)
    {
        return $this->view->post($_name, $default, $filter);
    }

    /**
     * http request 方法
     *
     * @param $_name
     * @param null $default
     * @param null $filter
     * @return mixed|null|string
     */
    public function request($_name, $default = null, $filter = NULL)
    {
        return $this->view->request($_name, $default, $filter);
    }
}