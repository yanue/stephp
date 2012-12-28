<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-17
 * Time: 上午9:46
 * To change this template use File | Settings | File Templates.
 */
class TaoController extends Controller
{
	public function index(){
		$this->loadModel('tao');
		$this->view->products=$this->model->getProduct('',10000,14);

		//echo 'index';
		$this->view->setLayout();
	}

	public function plist($page=0){
		$this->loadModel('tao');
		$limit = 15;
		$this->view->products=$this->model->getProduct('',$page,$limit);
		$count = $this->model->getCount();

		$this->showPage($page,$count,$limit);
		//echo 'index';
		$this->view->setContent('tao/index');
		$this->view->setLayout();
	}
}
