<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');
/**
 * 控制器处理类
 *
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.3 - 13-7-9
 */
class Controller
{
    /*
     * 视图
     *
     */
    public $view = NULL;

    /*
     * url全局处理
     *
     */
    public $uri = NULL;

    /*
     * session处理
     *
     */
    public $session = null;

    /*
     * 初始化控制器
     *
     */
    public function __construct () {
        $this->view = new View();
        $this->uri = & $this->view->uri();
        $this->session = new Session();
    }

    /*
     * load file
     *
     */
    public function loadFile($name) {
        $path = ROOT_PATH.$name;
        if (file_exists($path)) {
            include_once $path;
            return true;
        }
        return false;
    }

	/*
	 * load configs file
	 *
	 */
	public function loadConfig ($file){
        $file = $this->uri->getModulePath().'configs/'.$file.'.php';
		if(file_exists($file)){
			include_once $file;
		}
	}

    /*
     * 跨module引用模型
     * --说明: 主要目的是跨模块引用,当前模块下的请直接 new 进行使用
     *
     * @param $model 模型名称(不包含'Model',如'UserModel'则输入'user').
     * @param $module 模块名称(需要跨的模块)
     * @return void.
     */
    public function loadModel($model,$module=''){
        //TODO
    }

    /*
    * 跨module引用模型
    * --说明: 主要目的是跨模块引用,当前模块下的请直接 new 进行使用
    *
    * @param $model 模型名称(不包含'Model',如'UserModel'则输入'user').
    * @param $module 模块名称(需要跨的模块)
    * @return void.
    */
    public function loadHelper($helper,$module=''){
        //TODO
    }

    /*
     * load plugins
     *
     */
    public function loadPlugin($name) {
        $path = ROOT_PATH.'plugins/'.$name.'.class.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
}