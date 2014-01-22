<?php
/**
 * IndexController.php
 *
 * @copyright    http://yanue.net/
 * @author        yanue <yanue@outlook.com>
 * @version        1.0.0 - 13-7-4
 */
namespace App\Home\Controller;

use Helper\Post\Sinablog;
use Library\Core\Controller;
use Library\Util\Debug;


class IndexController extends Controller
{

    public function indexAction()
    {
        echo $this->actionUrl('a/ccc//?asd=11');

//        $sina = new Sinablog();
//        $post = new \StdClass();
//        $post->title = '11111';
//        $post->content = 'wwwww';
//        $sina->test($post);
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