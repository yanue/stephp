<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

class Controller
{
    public $uri = NULL;
    public $view = NULL;
    public $_curModulePath = null;

    public function __construct () {
        $this->uri = new Uri();
        $this->view = new View();
        $this->_curModulePath = Bootstrap::$_moduleCurPath;
    }

    // load file
    public function loadFile($name) {
        $path = ROOT_PATH.$name;
        if (file_exists($path)) {
            include_once $path;
            return true;
        }
        return false;
    }

    // load model file
    public function loadModel($name, $modelPath = './') {
        $path = Bootstrap::$_moduleCurPath.$modelPath .'models/'. ucfirst($name).'Model.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

	// load configs file
	public function loadConfig ($file){
        $file = ROOT_PATH.'configs/'.$file.'.php';
		if(file_exists($file)){
			include_once $file;
		}
	}

    // load lib file
	public function loadLib($file){
        $file = ROOT_PATH.'library/'.ucfirst($file).'.php';
		if(file_exists($file)){
			include_once $file;
		}
	}

    // load plugins
    public function loadPlugin($name) {
        $path = ROOT_PATH.'plugins/'.$name.'.class.php';
        if (file_exists($path)) {
            require_once $path;
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
	public function outError($code,$msg='',$exit=true){
        $result = array(
            'error'=>array('code'=>$code,'msg'=>urlencode(getErrorMsg($code).$msg)),
            'data'=>''
        );
        echo urldecode(json_encode($result));
		if($exit) exit;
	}

    // 接收参数
    public function request($str,$mod=''){
        $val = isset($_REQUEST[$str]) ? $_REQUEST[$str] : null;
        if($mod=='int'){
            return intval($val);
        }
        return $val;
    }

    // 判断变量是否存在
    public function exists($mixed,$default=null){
        $val = isset($mixed) ? $mixed : $default;
        return $val;
    }

    public function curl_get_contents($url,$timeout=20) {
        $curlHandle = curl_init();
        curl_setopt( $curlHandle , CURLOPT_URL, $url );
        curl_setopt( $curlHandle , CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $curlHandle , CURLOPT_TIMEOUT, $timeout );
        $result = curl_exec( $curlHandle );
        curl_close( $curlHandle );
        return $result;
    }

}
