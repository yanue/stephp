<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/*
 * 路由分发
 *
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @version		1.0.2 - 2013-07-09
 */

class Router {

    private static $router = null; # 路由后的key=>val请求信息

    private static $fetchedStep = 0; # 第几个正则匹配到

    public function __construct(){

    }

    /*
     * 静态路由
     *
     *
     */
    public function routeStatic($pettern,$params){
        if(self::$router) return;
        self::$fetchedStep += 1;
        $requestUri = ltrim(Bootstrap::$_requestUri,'/');
        $requestUri = false===strripos($requestUri,Bootstrap::$_urlSuffix) ? $requestUri : substr($requestUri,0,(strlen($requestUri)-strlen(Bootstrap::$_urlSuffix))) ;
        $pettern = false===strripos($pettern,Bootstrap::$_urlSuffix) ? $pettern : substr($pettern,0,(strlen($pettern)-strlen(Bootstrap::$_urlSuffix))) ;
        # 去除后缀进行判断
        if(!self::$router && $pettern==$requestUri){
            self::$router = $params;
        }
    }

    /*
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

    /*
     * 添加路由
     *
     */
    public function addRoute(){
         if(self::$router){
            Bootstrap::$_isRouterMatched = true;
            self::setUrl(self::$router);
        }
    }

    /*
     * 设置url信息
     *
     */
    private function setUrl($requestParams){
        $settings = parse_ini_file(ROOT_PATH.'configs/application.ini');
        Bootstrap::$_moduleName = isset($requestParams['module']) && $requestParams['module'] ? $requestParams['module'] : $settings['application.default.module'];
        Bootstrap::$_controllerName = isset($requestParams['controller']) && $requestParams['controller'] ? $requestParams['controller'] : $settings['application.default.controller'];
        Bootstrap::$_actionName = isset($requestParams['action']) && $requestParams['action'] ? $requestParams['action'] : $settings['application.default.action'];
        Bootstrap::$_moduleCurPath   = Bootstrap::$_appPath.Bootstrap::$_moduleName.'/';
    }
}