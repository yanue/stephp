<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-17
 * Time: 上午9:46
 * To change this template use File | Settings | File Templates.
 */
class ReviewController extends Controller
{

	public function index($page=0){
		$this->loadModel('review');
		$limit = 15;
		$this->view->reviews=$this->model->getReview('',$page,$limit);
		$count = $this->model->getCount();

		$this->showPage($page,$count,$limit);

		$this->view->setContent('review/index');
		$this->view->setLayout();
	}

	public function from (){

	}

	public function to (){

	}

	public function view($id=0){
		$this->loadModel('review');
		$limit = 15;
		$this->view->review=$this->model->getOne($id);


		$this->view->setContent('tao/index');
		$this->view->setLayout();
	}

	/*------------------------------- api for pass ----------------------------------------------------------*/
	public function pass(){

		$rid = $_REQUEST['rid'];
		$desc = $_REQUEST['desc'];

		$data = array('user'=>$_SESSION['user'],'time'=>time(),'desc'=>$desc);

		//sleep(2);
		echo json_encode($data,true);
	}

}
