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
}