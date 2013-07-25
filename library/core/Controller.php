<?php
namespace Library\Core;

use Library\Util\Session;
use Library\Core\Request;

if ( ! defined('LIB_PATH')) exit('No direct script access allowed');
/**
 * 控制器处理类
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class Controller
{
    /**
     * 视图
     *
     */
    public $view = NULL;

    /**
     * url全局处理
     *
     */
    public $uri = NULL;

    /**
     * session处理
     *
     */
    public $session = null;

    /**
     * 初始化控制器
     *
     */
    public function __construct () {
        $this->view = new View();
        $this->uri = & $this->view->uri();
        $this->session = new Session();
        $this->request = new Request();
    }

    /**
     * load file
     *
     */
    public function loadFile($name) {
        $path = LIB_PATH.$name;
        if (file_exists($path)) {
            include_once $path;
            return true;
        }
        return false;
    }

	/**
	 * load configs file
	 *
	 */
	public function loadConfig ($file){
        $file = $this->uri->getModulePath().'configs/'.$file.'.php';
		if(file_exists($file)){
			include_once $file;
		}
	}

    /**
     * baseUrl快捷使用方式
     * -- controller上使用
     *
     * @param string $uri
     * @return string
     */
    public function baseUrl($uri=''){
        return $this->uri->baseUrl($uri);
    }

    /**
     * 跨module引用模型
     * --说明: 主要目的是跨模块引用,当前模块下的请直接 new 进行使用
     * //TODO
     * @param $model 模型名称(不包含'Model',如'UserModel'则输入'user').
     * @param $module 模块名称(需要跨的模块)
     * @return void.
     */
    public function loadModel($model,$module=''){
        $modelClass = ucfirst($model).'Model';
        if($module){
            $file = $this->uri->getAppPath().'/'.$module.'/model/'.$modelClass.'.php';
            if(file_exists($file)){
                require_once $file;
            }else{
                echo $modelClass.'.php 文件不存在';
            }
        }
    }

    /**
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

    /**
     * load plugins
     *
     */
    public function loadPlugin($name) {
        $path = LIB_PATH.'plugins/'.$name.'.class.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
}