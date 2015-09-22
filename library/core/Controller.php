<?php
namespace Library\Core;

use Library\Di\DI;
use Library\Di\Injectable;
use library\Util\Asset;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 控制器处理类
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class Controller extends Injectable
{
    /**
     * @var string
     */
    protected $controller = null;

    /**
     * @var string
     */
    protected $action = null;

    /**
     * @var string
     */
    protected $module = null;

    /**
     * @var Asset
     */
    protected $asset = null;

    /**
     * 初始化控制器
     *
     */
    public function __construct()
    {
        $this->setDI(new DI());
        $this->module = $this->uri->getModule();
        $this->controller = $this->uri->getController();
        $this->action = $this->uri->getAction();
        $this->asset = new Asset();
    }

    /**
     * 获取ob-cache内容
     *
     * @param $item
     * @param $path
     * @return string
     */
    protected function getFromOB($item, $path)
    {
        ob_start();
        $this->view->render($path, ['item' => $item]);
        $str = ob_get_contents();
        ob_get_clean();
        return $str;
    }

    /**
     * 获取ob-cache内容
     *
     * @param $keyVal
     * @param $path
     * @return string
     */
    protected function getFromOBExtract($path, $keyVal)
    {
        ob_start();
        $this->view->render($path, $keyVal);
        $str = ob_get_contents();
        ob_get_clean();
        return $str;
    }
}