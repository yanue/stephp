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
//        echo $this->session->get('asda');
//        echo $this->session->get('asd').'|';
//        echo '<br />';
//        echo $this->uri->getQuery('module');
//        echo $this->uri->getUrl();
//        echo '<br />';
//        echo $this->uri->getUri(1);
        new UserModel();
    }
}