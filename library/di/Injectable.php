<?php

namespace Library\Di;

abstract class Injectable
{

    protected $_dependencyInjector;

    /**
     * @var \Library\Core\Uri
     */
    protected $uri;

    /**
     * @var \Library\Core\Request
     */
    protected $request;

    /**
     * @var \Library\Core\View
     */
    protected $view;

    /**
     * @var \Library\Util\Session
     */
    protected $session;

    /**
     * @var \Library\Db\FluentPDO
     */
    protected $db;

    protected $di;

    public function setDI($dependencyInjector)
    {
        $this->request = $dependencyInjector->get('request');
        $this->uri = $dependencyInjector->get('uri');
        $this->view = $dependencyInjector->get('view');
        $this->db = $dependencyInjector->get('db');
    }

    public function getDI()
    {
        if (!$this->di instanceof DI) {
            $this->di = new DI();
        }

        return $this->di;
    }

    public function __get($property)
    {
    }
}
