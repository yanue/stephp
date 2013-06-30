<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/*
 * Request.php
 *------------------------------------------------------------------------------
 * @copyright : yanue.net
 *------------------------------------------------------------------------------
 * @author : yanue
 * @date : 13-6-18
 *------------------------------------------------------------------------------
 */


/**
 * Request represents an HTTP request.
 *
 * The methods dealing with URL accept / return a raw path (% encoded):
 *   * getBasePath
 *   * getBaseUrl
 *   * getPathInfo
 *   * getRequestUri
 *   * getUri
 *   * getUriForPath
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class Request {
    private static $_requestUri 	= null;
    private static $_fullUrl        = null;
    private static $_requestQuery  = null;
    private static $_requestPath  = null;

    public function __construct(){
        self::requestUri();
        self::uriParse();
    }

    public static function get($key){
        return isset($_GET[$key]) ? $_GET[$key] : null ;
    }

    public static function post($key){
        return isset($_POST[$key]) ? $_POST[$key] : null ;
    }

    public static function request($key){
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : null ;
    }

    /* *
     * -----------------------------------------------------------------------------------------------------------------
     * 获取基本地址: baseUrl
     * -----------------------------------------------------------------------------------------------------------------
     * 说明: 返回不包含mvc结构,可以通过uri参数传入设置
     *
     * @param string $uri 包含mvc结构的uri参数
     *
     * @return string
     * -----------------------------------------------------------------------------------------------------------------
     * */
    public static function baseUrl($uri=''){
        $baseUrl = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $baseUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $baseUrl .= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
        return $baseUrl.'/'.$uri;
    }

    public static function getUri(){
        if(!self::$_fullUrl) self::requestUri();
        return self::$_requestUri;
    }

    public static function getFullUrl(){
        if(!self::$_fullUrl) self::requestUri();
        return self::$_fullUrl;
    }

    public static function getQuery(){
        return $_SERVER["QUERY_STRING"];
    }

    public static function getPath(){
        if(!self::$_requestPath) self::uriParse();
        return self::$_requestPath;
    }

    private static function requestUri(){
        # 解决通用问题
        $requestUri = '';
        if (isset($_SERVER['REQUEST_URI'])) { #$_SERVER["REQUEST_URI"] 只有 apache 才支持,
            $requestUri = $_SERVER['REQUEST_URI'];
        } else {
            if (isset($_SERVER['argv'])) {
                $requestUri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
            } else if(isset($_SERVER['QUERY_STRING'])) {
                $requestUri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
            }
        }
        $https = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = strstr(strtolower($_SERVER["SERVER_PROTOCOL"]), "/",true).$https;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        # 获取的完整url
        self::$_fullUrl = $protocol."://".$_SERVER['SERVER_NAME'].$port.$requestUri;

        # 当前脚本名称
        $script_name  = $_SERVER['SCRIPT_NAME'];
        # 当前脚本目录
        $script_dir  = dirname($_SERVER['SCRIPT_NAME']);
        # 去除uri中当前脚本文件名 (如果存在)
        $script = false === strpos($requestUri,$script_name) ? $script_dir : $script_name ;

        self::$_requestUri = substr($requestUri,strlen($script));
    }

    private static function uriParse(){
        if(!self::$_requestUri) self::requestUri();
        # 去除uri中当前脚本目录'/'
        $uriParam = parse_url(self::$_requestUri);
        self::$_requestPath = isset($uriParam['path']) ? $uriParam['path'] : '/';
    }
}