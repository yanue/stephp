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
        echo $this->module.'<br >';
        echo $this->controller.'<br >';
        echo $this->action.'<br >';
    }

    public function testAction(){
        echo 'sd';
//        $userModel = new UserModel();
//        $userModel->getTest();
    }

    public function numAction(){
        echo 'num action';
        echo $this->uri->getParam('id');
    }

    public function anyAction(){
        echo 'any action';
        echo $this->uri->getParam('sub');
    }

    public function regxAction(){
        echo 'regx action';
        echo $this->uri->getParam('id');
    }
}