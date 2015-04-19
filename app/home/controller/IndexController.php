<?php
/**
 * IndexController.php
 *
 * @copyright    http://yanue.net/
 * @author        yanue <yanue@outlook.com>
 * @version        1.0.0 - 13-7-4
 */
namespace App\Home\Controller;

use Library\Core\Controller;
use Model\User;


class IndexController extends Controller
{

    public function indexAction()
    {
        //
        User::findFirst();
        $user = new User();
    }

    public function testAction()
    {

    }

    public function numAction()
    {
        echo 'num action';
        echo $this->uri->getParam('id');
    }

    public function anyAction()
    {
        echo 'any action';
        echo $this->uri->getParam('sub');
    }

    public function regxAction()
    {
        echo 'regx action';
        echo $this->uri->getParam('id');
    }
}