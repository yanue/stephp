<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-15
 * Time: 下午12:15
 */
class IndexController extends Controller
{

    public function __construct() {
        parent::__construct();
		$this->setNav(4,1,1);
		if(!$_SESSION['_CUID']){
			$loginUrl = $this->view->baseUrl('login');
			header('location:'.$loginUrl);
			die('你还没有登录！');
		}
    }

    public function index(){
        //$this->loadModel('Index');
       // $this->view->test =  $this->model->testList();
        $this->view->setLayout('layout');

    }

    public function other(){
        $this->view->setContent('index/index');
        $this->view->setLayout('layout');
    }

    public function test(){
        $this->setLayout();
        //$this->view->setContent($this->controller,$this->action);
        $this->view->render('index/other');
    }
}
