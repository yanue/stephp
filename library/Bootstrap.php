<?php

class Bootstrap {

    private	$_url				= null;
    private $_controller 		= null;

    private $_controllerPath 	= 'controllers/';
    private $_modelPath 		= 'models/';
    private $_libPath 			= 'library/';
    private $_errorFile 		= 'ErrorController.php';
    private $_defaultFile 		= 'IndexController.php';


    /**
     * Starts the Bootstrap
     *
     * @return boolean
     */
    public function init()
    {
		require_once 'config.php';
        // Sets the protected $_url
        $this->_getUrl();
        $this->__autoLoad();
        // Load the default controller if no URL is set
        // eg: Visit http://localhost it loads Default Controller
        if (empty($this->_url[0])) {
            $this->_loadDefaultController();
            return false;
        }

        $this->_loadExistingController();
        $this->_callControllerMethod();
    }

    /**
     * Fetches the $_GET from 'url'
     */
    private function _getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $this->_url = explode('/', $url);
    }

    /**
     * This loads if there is no GET parameter passed
     */
    private function _loadDefaultController()
    {
        require $this->_controllerPath . $this->_defaultFile;
        $this->_controller = new IndexController();
        $this->_controller->index();
    }

    /**
     * Load an existing controller if there IS a GET parameter passed
     *
     * @return boolean|string
     */
    private function _loadExistingController()
    {
        require_once 'config.php';
        $file = $this->_controllerPath . $this->_url[0] . 'Controller.php';

        if (file_exists($file)) {
            require_once $file;
            $controller = $this->_url[0].'Controller';
            $this->_controller = new $controller;
            $this->_controller->loadModel($this->_url[0], $this->_modelPath);
        } else {
            $this->_error();
            return false;
        }
    }

    /**
     * If a method is passed in the GET url parameter
     *
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Param
     *  url[3] = Param
     *  url[4] = Param
     */
    private function _callControllerMethod()
    {
        $length = count($this->_url);

        // Make sure the method we are calling exists
        if ($length > 1) {
            if (!method_exists($this->_controller, $this->_url[1])) {
                $this->_error();
                return false;
            }
        }

        // Determine what to load
        switch ($length) {
            case 5:
                //Controller->Method(Param1, Param2, Param3)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3],$this->_url[4]);
                break;

            case 4:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3]);
                break;

            case 3:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2]);
                break;

            case 2:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}();
                break;

            default:
                $this->_controller->index();
                break;
        }
    }

    /**
     * Display an error page if nothing exists
     *
     * @return boolean
     */
    private function _error() {
        require_once $this->_controllerPath . $this->_errorFile;
        $this->_controller = new ErrorController();
        $this->_controller->index();
        return false;
    }

    // auto load base class
    public function __autoLoad (){
        require_once $this->_libPath.'Controller.php';
        require_once $this->_libPath.'Model.php';
        require_once $this->_libPath.'View.php';
    }

}