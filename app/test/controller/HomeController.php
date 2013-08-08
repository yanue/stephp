<?php
/**
 * HomeController.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @date        2013-07-25
 */
use Library\Core\Controller;
class HomeController extends Controller{
    public function __construct(){
        parent::__construct();
    }

    public function indexAction(){
        echo 'here is test home page';
    }
}