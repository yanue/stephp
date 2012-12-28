<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-15
 * Time: 下午12:33
 */
class Controller
{

	public $view = null;

    public function __construct () {
        $this->view = new View();
		$this->view->startime = microtime(true);
		@session_start();
    }


    public function loadModel($name, $modelPath = 'models/') {

        $path = $modelPath . ucfirst($name).'Model.php';

        if (file_exists($path)) {
            require_once $path;

            $modelName = ucfirst($name) . 'Model';
            $this->model = new $modelName();
        }
    }

	// load configs file
	public function loadConfig ($file){
		if($file){
			include_once 'configs/'.$file.'.php';
		}
	}

	public function loadLib($file){
		if($file){
			include_once 'library/'.ucfirst($file).'.php';
		}
	}

	// echo right json data
	public function outRight($data){
		$result = array(
			'error'=>array('code'=>0,'msg'=>''),
			'data'=>$data
		);
		echo json_encode($result);
		exit;
	}

	// echo error json data
	public function outError($code){
		$this->loadConfig('errcode');
		$result = array(
			'error'=>array('code'=>$code,'msg'=>getErrorMsg($code)),
			'data'=>''
		);
		echo json_encode($result);
		exit;
	}
	// set what left side nav show
	public function setNav($nav=1,$menu=1,$subMenu=1){
		$this->view->curNav=$nav;
		$this->view->curMenu=$menu;
		$this->view->curSubMenu=$subMenu;
	}

	// 公用分页显示
	public function showPage($page, $count, $limit = 14, $range = 4) {

		$total = ceil($count/$limit);
		// 总页数
		$page = $page > $total ? $total : $page;
		$page = $page <= 0 ? 1 : $page;
		// 上一页
		if ($page > 1) {
			$this->view->previous = $page - 1;
			$this->view->first = 1;
		}
		// 下一页
		if ($total > $page) {
			$this->view->next = $page + 1;
			$this->view->last = $total;
		}
		$this->view->current = $page;
		// $range表示显示条数的一半-1
		if ($page <= $range) {
			if ($total > $range * 2) {
				$pagesInRange = $this->getPagesInRange ( 1, $range * 2 );
			} else {
				$pagesInRange = $this->getPagesInRange ( 1, $total );
			}

		} elseif ($total - $page < $range) {
			$pagesInRange = $this->getPagesInRange ( $total - $range * 2, $total );
		} else {
			$pagesInRange = $this->getPagesInRange ( $page - $range, $page + $range );
		}
		$this->view->pagesInRange = $pagesInRange;
		$this->view->total = $total;
		$this->view->page = $page;
		$this->view->perpage = $limit;
		$this->view->pageCount = $count;

	}

	// 设置页码功能数组处理
	private function getPagesInRange($lowerBound, $upperBound) {
		$pages = array ();
		for($pageNumber = $lowerBound; $pageNumber <= $upperBound; $pageNumber ++) {
			$pages [$pageNumber] = $pageNumber;
		}
		return $pages;
	}
}
