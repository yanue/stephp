<?php
/**
 * IndexController.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.0 - 2013-07-05
 */

class IndexController extends Controller{
    public function indexAction(){
        $this->view->setLayout();
    }
}