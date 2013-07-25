<?php
/**
 * IndexController.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.0 - 2013-07-05
 */
namespace App\Test\Controller;

use Library\Core\Controller;

class IndexController extends Controller{
    public function indexAction(){
        echo $this->uri->getAction();
    }
}