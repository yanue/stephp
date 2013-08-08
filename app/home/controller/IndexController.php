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
        echo '<br >';
        echo 'home controller';
        echo '<br >';
//        $this->view->setLayout('layout');
//        Debug::trace();
//        $this->uri->getAction();
//        echo $this->uri->getFullUrl();
    }

    public function testAction(){
        echo 'here is home controller test action';
//        echo $this->uri->getUriString();
    }
}