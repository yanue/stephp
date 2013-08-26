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

    private static $_matched = null;
    /**
     * 初始化
     */
    public function __construct($rules){
        if($rules){
            $this->rules = $rules;
            $this->request = new Request();
            $this->request->getSegments();
            $this->run();
        }
    }

    /**
     * 执行
     *
     */
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
     */
    public function routeStatic($rules){
        if(self::$_matched){
            return;
        }
        $this->request->getPath();
        $requestPath = $this->request->getPath();

        foreach ($rules as $rule=>$path) {
            if($rule==$requestPath){
                $this->request->setPath($path);
                self::$_matched = array('static'=>$rule);
                return;
            }
        }
    }

    /**
     * 规则路由
     * todo
     */
    public function routeRule($rules){
        if(self::$_matched){
            return;
        }
    }

    /**
     * 正则路由
     * todo
     */
    public function routeRegex ($rules){
        if(self::$_matched){
            return;
        }
    }

    /**
     * 正则域名
     * todo
     */
    public function routeDomain ($rules){
        if(self::$_matched){
            return;
        }
    }

}