<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

class Controller
{
    public $uri = NULL;
    public $view = NULL;
    public $session = null;

    /*
     * 将
     *
     */
    public function __construct () {
        $this->uri = new Uri();
        $this->session = new Session();
        $this->view = new View();
    }

    // load file
    public function loadFile($name) {
        $path = ROOT_PATH.$name;
        if (file_exists($path)) {
            include_once $path;
            return true;
        }
        return false;
    }

	// load configs file
	public function loadConfig ($file){
        $file = ROOT_PATH.'configs/'.$file.'.php';
		if(file_exists($file)){
			include_once $file;
		}
	}

    // load plugins
    public function loadPlugin($name) {
        $path = ROOT_PATH.'plugins/'.$name.'.class.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

}
