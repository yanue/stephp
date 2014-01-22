<?php
namespace Library\Core;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 视图处理类
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class View
{

    /**
     * layout布局
     *
     */
    private $_layout = '';

    /**
     * layout下当前action内容模板
     *
     */
    private $_content = '';

    /**
     * uri全局处理
     *
     */
    public $uri = null;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->uri = new Uri();
    }

    /**
     * 获取uri对象
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * render  -- to include template
     *
     * @param string $name : 当前模块视图下相对路径模块名称.
     * @return void.
     */
    public function render($name)
    {
        $file = $this->uri->getModulePath() . '/view/' . $name . '.php';
        if (file_exists($file)) {
            include $file;
        }
    }

    /**
     * set layout
     *
     * @param string $layout : 需要使用的布局模块名称
     * @param string $content : 当前action在layout内引用的内容模板
     * @return void;
     */
    public function setLayout($layout = 'layout', $content = '')
    {
        $this->_layout = $layout;
        if ($content) {
            $this->_content = $content;
        }
    }

    /**
     * set layout content
     *
     * @param string $content : 当前action在layout内引用的内容模板
     * @return void
     */
    public function setContent($content = '')
    {
        if ($content) {
            $this->_content = $content;
        }
    }

    /**
     * 禁用layout
     *
     */
    public function disableLayout()
    {
        $this->_layout = null;
    }

    /**
     * set layout
     *
     */
    public function getLayout()
    {
        return $this->_layout;
    }

    /**
     * include layout content
     *
     * 说明 : 加载layout布局下的当前action的内容模板,于layout模板内使用
     */
    public function content()
    {
        if ($this->_content) {
            include_once $this->uri->getModulePath() . '/view/' . $this->_content . '.php';
        } else {
            include_once $this->uri->getModulePath() . '/view/' . $this->uri->getController() . '/' . $this->uri->getAction() . '.php';
        }
    }

    /**
     * 模板显示功能
     *
     */
    public function display()
    {
        // 直接载入PHP模板
        if ($this->_layout) {
            $layout = $this->uri->getModulePath() . '/view/' . $this->_layout . '.php';
            if (file_exists($layout)) {
                include_once $layout;
            } else {
                if (Config::getBase('debug')) {
                    echo 'layout文件不存在：' . $layout;
                }
            }
        }
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
     * baseUrl映射到view上
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
     *
     * @param $_name
     * @param null $default
     * @param null $filter
     * @return mixed|null|string
     */
    public function get($_name, $default = null, $filter = NULL)
    {
        $data = isset($_GET[$_name]) ? trim($_GET[$_name]) : $default;
        if (!is_null($data) && is_int($filter) && $filter > 0) {
            return filter_var($data, $filter);
        } else {
            return $data;
        }
    }

    /**
     * http post 方法
     *
     * @param $_name
     * @param null $default
     * @param null $filter
     * @return mixed|null|string
     */
    public function post($_name, $default = null, $filter = NULL)
    {
        $data = isset($_POST[$_name]) ? trim($_POST[$_name]) : $default;
        if (!is_null($data) && is_int($filter) && $filter > 0) {
            return filter_var($data, $filter);
        } else {
            return $data;
        }
    }

    /**
     * http request 方法
     * @param $_name
     * @param null $default
     * @param null $filter
     * @return mixed|null|string
     */
    public function request($_name, $default = null, $filter = NULL)
    {
        $data = isset($_REQUEST[$_name]) ? trim($_REQUEST[$_name]) : $default;
        if (!is_null($data) && is_int($filter) && $filter > 0) {
            return filter_var($data, $filter);
        } else {
            return $data;
        }
    }

}