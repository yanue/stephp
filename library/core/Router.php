<?php
namespace Library\Core;

use Library\Core\Request;
use Library\Util\Debug;

if ( ! defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * 路由分发
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */

class Router{

    /**
     * # 路由后的key=>val请求信息
     * @var null
     */
    private static $router = null;

    /**
     *
     */
    private $request = null;

    private $rules = null;

    private $segments = null;

    /**
     * 初始化
     */
    public function __construct($rules){
        if($rules){
            $this->rules = $rules;
            $this->request = new Request();
            $this->request->instance();
            $this->request->getSegments();
            $this->run();
        }
    }


    public function run(){
        foreach ($this->rules as $key => $rules) {
            if(!in_array($key,array('static','rule','regex','domain'))){
                return;
            }
            $act = 'route'.ucfirst($key);
            $this->$act($rules);
        }
    }
    

    /**
     * 静态路由
     *
     *
     */
    public function routeStatic($rules){
        $this->request->getPath();
        $requestPath = $this->request->getPath();
        foreach ($rules as $rule=>$path) {
            if($rule==$requestPath){
                $this->request->setPath($path);
                echo $path;
                return;
            }
        }
    }

    /**
     * 规则路由
     *
     *
     */
    public function routeRule($pettern){

    }

    /**
     * 正则路由
     */
    public function routeRegex ($pettern){

    }

    /**
     * 正则域名
     */
    public function routeDomain ($pettern){

    }

}