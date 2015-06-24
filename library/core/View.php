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
     *模板文件后缀
     * @var string
     */
    private $suffix = '.phtml';

    /**
     * layout布局
     * @var string
     */
    private $_layout = '';

    /**
     * layout下当前action内容模板
     *
     */
    private $_content = '';


    /**
     * 模板变量
     *
     * @var array
     */
    private $var = array();
    /**
     * @var string
     */
    public $controller = null;

    /**
     * @var string
     */
    public $action = null;

    /**
     * @var string
     */
    public $module = null;

    /**
     * 初始化
     */
    public function __construct()
    {
        $this->setDI(new DI());
        $this->module = $this->uri->getModule();
        $this->controller = $this->uri->getController();
        $this->action = $this->uri->getAction();
        $this->view = $this;
    }

    /**
     * render  -- to include template
     *
     * @param string $name : 当前模块视图下相对路径模块名称.
     * @return void.
     */
    public function render($name)
    {
        $file = $this->uri->getModulePath() . '/view/' . $name . $this->suffix;
        if (file_exists($file)) {
            extract($this->var);

            include $file;
        }
    }

    public function partial($name)
    {
        $file = $this->uri->getModulePath() . '/view/' . $name . $this->suffix;
        if (file_exists($file)) {
            extract($this->var);

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
        $this->_layout = $layout ? $layout : 'layout';
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

    /**设置变量
     * @param $key
     * @param $value
     */
    public function setVar($key, $value)
    {
        $this->var[$key] = $value;
    }

    /**
     * include layout content
     *
     * 说明 : 加载layout布局下的当前action的内容模板,于layout模板内使用
     */
    public function content()
    {
        extract($this->var);

        if ($this->_content) {
            include_once $this->uri->getModulePath() . '/view/' . $this->_content . $this->suffix;

        } else {
            include_once $this->uri->getModulePath() . '/view/' . $this->uri->getController() . '/' . $this->uri->getAction() . $this->suffix;
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
            $layout = $this->uri->getModulePath() . '/view/' . $this->_layout . $this->suffix;
            if (file_exists($layout)) {
                /*解析变量*/
                extract($this->var);
                include_once $layout;
            } else {
                if (Config::getBase('debug')) {
                    echo 'layout文件不存在：' . $layout;
                }
            }
        }
    }

    public function assignCss()
    {
        if (!empty($this->css) && is_array($this->css)) {
            foreach ($this->css as $css) {
                echo ' <link rel="stylesheet" href="' . $css . '"/>' . "\n";
            }
        }
    }

    public function assignJs()
    {
        if (!empty($this->js) && is_array($this->js)) {
            foreach ($this->js as $js) {
                echo '<script src="' . $js . '"></script>' . "\n";
            }
        }
    }
}