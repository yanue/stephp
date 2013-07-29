<?php
/**
 * IndexController.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.0 - 13-7-4
 */
namespace App\Home\Controller;

use App\Home\Model\UserModel;
use Library\Core\Controller;
use Library\Util\Debug;


class IndexController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function indexAction(){
        echo 'home';
        echo '<br />';
        echo $this->uri->getFullUrl();

//        $this->view->setLayout('layout');
//        Debug::trace();
//        $this->uri->getAction();
//        echo $this->uri->getFullUrl();
    }
}

class a {
    static $a = 5;

    public function test(){
        self::$a = 8;
    }

}