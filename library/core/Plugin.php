<?php
/**
 * Created by PhpStorm.
 * User: yanue
 * Date: 10/25/14
 * Time: 9:31 AM
 */

namespace Library\Core;


use Library\Di\DI;
use Library\Di\Injectable;

class Plugin extends Injectable
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

    public function __construct()
    {
        $this->setDI(new DI());
        $this->module = $this->uri->getModule();
        $this->controller = $this->uri->getController();
        $this->action = $this->uri->getAction();
    }
} 