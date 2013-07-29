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

class Router {

    /**
     * # 路由后的key=>val请求信息
     * @var null
     */
    private static $router = null;

    protected $_url_delimiter = '/';
    protected $_var_delimiter = ':';

    /**
     * 默认的路由
     *
     * @var unknown_type
     */
    protected $_default = array(':controller/:action/*',
        array('controller' => ':controller', 'action' => ':action')
    );

    /**
     * # 第几个正则匹配到
     * @var int
     */
    private static $fetchedStep = 0;

    /**
     *
     */
    private $request = null;

    private $routerConfig = null;

    /**
     * 初始化
     */
    public function __construct( ){

    }

    /**
     * run
     *
     */
    public function run($routerConfig){
        $this->request = new Request();
        $this->routerConfig = $routerConfig;
    }

    /**
     * 静态路由
     *
     *
     */
    public function routeStatic($pettern){
        $uriSufix = Loader::getConfig('application.default.suffix');
        $requestUri = ltrim($this->request->getPath(),'/');
        // 去除后缀.html
        $requestUri = false===strripos($requestUri,$uriSufix) ? $requestUri : substr($requestUri,0,(strlen($requestUri)-strlen($uriSufix)));

        $pettern = false===strripos($pettern,$uriSufix) ? $pettern : substr($pettern,0,(strlen($pettern)-strlen($uriSufix))) ;
        # 去除后缀进行判断
        if(!self::$router && $pettern==$requestUri){
            $this->request->setPath('/index/index');
        }
    }

    /**
     * 静态路由
     *
     *
     */
    public function routeRule($pettern,$params){
        if(self::$router) return;
        self::$fetchedStep += 1;
        $uriSufix = Loader::getConfig('application.default.suffix');
        echo $requestUri = ltrim($this->request->getUri(),'/');
        // 去除后缀.html
        $requestUri = false===strripos($requestUri,$uriSufix) ? $requestUri : substr($requestUri,0,(strlen($requestUri)-strlen($uriSufix)));

        $pettern = false===strripos($pettern,$uriSufix) ? $pettern : substr($pettern,0,(strlen($pettern)-strlen($uriSufix))) ;
        # 去除后缀进行判断
        if(!self::$router && $pettern==$requestUri){
            self::$router = $params;
        }
    }

    /**
     * 正则路由
     */
    public function routeRegex ($pettern,$mvc='',$req=''){
        if(self::$router) return;
        self::$fetchedStep += 1;
        $requestUri = ltrim(Bootstrap::$_requestUri,'/');
        $arr = array();
        if(preg_match($pettern,$requestUri,$uris)){
            foreach ($uris as $k=>$v) {
                if(isset($req[$k])){
                    $arr[$req[$k]] = $v;
                }
            }
            self::$router = array_merge($mvc,$arr);
        }
    }

    /**
     * 添加路由
     *
     */
    public function addRoute(){

    }

}