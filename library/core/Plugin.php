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

    /**
     * @var string
     */
    protected $api = null;

    /**
     * @var string
     */
    protected $mvc_uri = null;


    public function __construct()
    {
        $this->setDI(new DI());
        $this->module = $this->uri->getModule();
        $this->controller = $this->uri->getController();
        $this->action = $this->uri->getAction();
        $this->api = $this->uri->getApi();
    }

    public function getCurrentMvcUri($separation = '/')
    {
        if ($this->api) {
            $this->mvc_uri = $this->module . $separation . $this->api . $separation . $this->controller . $separation . $this->action;
        } else {
            $this->mvc_uri = $this->module . $separation . $this->controller . $separation . $this->action;
        }
        return $this->mvc_uri;
    }
} 