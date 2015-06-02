<?php
namespace Library\Core;

use Library\Di\DI;
use Library\Di\Injectable;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 视图处理类
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class View extends Injectable
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
     * 初始化
     */
    public function __construct()
    {
        $this->setDI(new DI());
    }

    public function setVar($key, $data)
    {
        $this->view->$key = $data;
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
}