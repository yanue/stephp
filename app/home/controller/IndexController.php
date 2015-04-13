<?php
/**
 * IndexController.php
 *
 * @copyright    http://yanue.net/
 * @author        yanue <yanue@outlook.com>
 * @version        1.0.0 - 13-7-4
 */
namespace App\Home\Controller;

use Library\Core\Application;
use Library\Core\Controller;
use Library\Core\Request;
use Library\Di\DI;


class IndexController extends Controller
{

    public function indexAction()
    {
        $di = new DI();
        $di->request = new Request();
        $app = new Application($di);
        print_r($app->request->getSegments());
        print_r($app);

// ----
        $c = new DI();
        echo $c->bar = 'Bar';
        $c->foo = function ($c) {
            return new Foo($c->bar);
        };

// 从容器中取得Foo
        $foo = $c->foo;
        $foo->doSomething(); // Bim::doSomething|Bar::doSomething|Foo::doSomething

// ----
        $di = new DI();

        $di->foo = 'Foo';

        /** @var Foo $foo */
        $foo = $di->foo;

        var_dump($foo);
        /*
        Foo#10 (1) {
          private $bar =>
          class Bar#14 (1) {
            private $bim =>
            class Bim#16 (0) {
            }
          }
        }
        */

        $foo->doSomething(); // Bim::doSomething|Bar::doSomething|Foo::doSomething
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


class Bim
{
    public function doSomething()
    {
        echo __METHOD__, '|';
    }
}

class Bar
{
    private $bim;

    public function __construct(Bim $bim)
    {
        $this->bim = $bim;
    }

    public function doSomething()
    {
        $this->bim->doSomething();
        echo __METHOD__, '|';
    }
}

class Foo
{
    private $bar;

    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }

    public function doSomething()
    {
        $this->bar->doSomething();
        echo __METHOD__;
    }
}
