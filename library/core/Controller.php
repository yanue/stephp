<?php
namespace Library\Core;

use Library\Di\Injectable;

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
     * 初始化控制器
     *
     */
    public function __construct()
    {
    }
}