<?php
/**
 * IndexController.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.0 - 13-7-4
 */

class IndexController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function indexAction(){
        $this->view->setLayout('layout');
        $this->view->aa = 'asds';
        $this->uri->getUriString();
    }
}