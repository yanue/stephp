<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-15
 * Time: 下午12:33
 */
class View
{
    protected $_content = '';
    protected $_enabled = true;
    protected $_layout = 'layout';
    public $controller = '';
    public $action = '';

    public static $url_params = null;
    public $view = null;

    public function __construct (){
        $this->setDefault();
    }

    // render
    public function render($name){
        $file =  'views/'.$name.'.php';
        include_once $file;
    }

    public function setContent($filename=''){
       //echo $filename='index/index';
        $this->setDefault();
        $file = $filename !='' ? $filename : $this->controller.'/'.$this->action ;
        $this->_content = $file;
    }

    // baseUrl
    public function baseUrl($uri=''){
        $baseUrl = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $baseUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $baseUrl .= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
        return $baseUrl.'/'.$uri;
    }

    // set layout
    public function setLayout($layout='layout',$enable=true)
    {
        $this->_enabled = $enable;
        if($this->_enabled==true){
            $this->setContent();
            $this->render($layout);
        }
    }

    public function content(){
        if($this->_content){
            include_once 'views/'.$this->_content.'.php';
        }

    }

    public function disableLayout()
    {
        $this->_enabled = false;
        return $this;
    }

    public function getLayout()
    {
        return $this->_layout;
    }

    public function getView()
    {
        return $this->_view;
    }

    private function setDefault(){
        $this->_getUrl();

        $url_params = self::$url_params;

        $this->controller = $url_params[0] ? $url_params[0] : 'index';
       	@$this->action = $url_params[1] ? $url_params[1] : 'index';

    }



    // get url parmas
    private function _getUrl()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : null;
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        self::$url_params = explode('/', $url);
    }

	public function url ($arr){

		$params =$this->controller.'/'.$this->action;
		return $this->baseUrl($params.'/'.$arr['page']);
	}


}
