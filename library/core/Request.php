<?php
namespace Library\Core;

if ( ! defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * Request represents an HTTP request.
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class Request {

    /**
     * 域名url
     */
    private $_hostUrl       = null;

    /**
     * 当前应用根url地址(针对放在子目录情况)
     *
     */
    private $_webrootUrl       = null;

    /**
     * uri部分
     */
    private static $_requestUri 	= null;

    /**
     * uri中?后query部分
     */
    private static $_requestQuery  = null;

    /**
     * uri中path部分
     */
    private static $_requestPath   = null;

    /**
     * uri中baseUrl
     */
    private $_baseUrl       = null;

    /**
     * 初始化并解析
     */
    public function __construct(){
    }

    /**
     * 初始化
     */
    public function instance(){
        # 解析url
        $this->parseUrl();
        $this->baseUrl();
    }

    /**
     * return get method val by key($_name)
     *
     * @param $_name
     * @param null $default
     * @return null
     */
    public function get($_name,$default=null){
        return isset($_GET[$_name]) ? $_GET[$_name] : $default;
    }


    /**
     * return post method val by key($_name)
     *
     * @param $_name
     * @param null $default
     * @return null
     */
    public function post($_name,$default=null){
        return isset($_POST[$_name]) ? $_POST[$_name] : $default;
    }

    /**
     * return $_REQUEST (get or post) val by key($_name)
     *
     * @param $_name
     * @param null $default
     * @return null
     */
    public function request($_name,$default=null){
        return isset($_REQUEST[$_name]) ? $_REQUEST[$_name] : $default;
    }

    /**
     * get http referer
     * @return string
     */
    public function getReferer(){
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }
    
    /** *
     * 获取基本地址: baseUrl
     * --说明: 返回不包含mvc结构,可以通过uri参数传入设置
     *
     * @param string $uri 包含mvc结构的uri参数
     * @return string
     * */
    private function baseUrl(){
        $baseUrl = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $baseUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $baseUrl .= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
        $this->_baseUrl = $baseUrl.'/';
    }

    /**
     * 判断是不是ajax方式请求.
     * @return bool
     */
    function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest';
    }


    /**
     * 获取baseUrl部分
     *
     * @return string
     */
    public function getBaseUrl(){
        return $this->_baseUrl;
    }

    /**
     * 获取uri部分
     *
     * @return string
     */
    public function getUri(){
        return self::$_requestUri;
    }

    /**
     * 获取完整url
     *
     * @return string : url
     */
    public function getFullUrl(){
        return $this->_webrootUrl.self::$_requestUri;
    }

    /**
     * 获取uri中?后面query部分
     *
     * @return string
     */
    public function getQuery(){
        return self::$_requestQuery;
    }

    /**
     * 获取uri中path部分
     *
     * @return string
     */
    public function getPath(){
        return self::$_requestPath;
    }

    /**
     * 全面解析当前url
     * --说明:解析出完整url,uri,path部分,query部分
     *
     * @return void.
     */
    private function parseUrl(){
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

        # 保存地址域
        $this->_hostUrl = $protocol."://".$_SERVER['SERVER_NAME'].$port;
        # 获取的完整url


        # 当前脚本名称
        $script_name  = $_SERVER['SCRIPT_NAME'];
        # 当前脚本目录
        $script_dir  = dirname($_SERVER['SCRIPT_NAME']);
        # 去除uri中当前脚本文件名 (如果存在)
        $script = false === strpos($requestUri,$script_name) ? $script_dir : $script_name ;
        # 当前应用根url
        $this->_webrootUrl = $protocol."://".$_SERVER['SERVER_NAME'].$port.$script;

        self::$_requestUri = substr($requestUri,strlen($script));

        # 去除uri中当前脚本目录 '/'
        $uriParam = parse_url(self::$_requestUri);
        self::$_requestPath = isset($uriParam['path']) ? $uriParam['path'] : '';
        self::$_requestQuery = isset($uriParam['query']) ? $uriParam['query'] : '';
    }

    /**
     * set requestUri
     *
     * @param $uri
     */
    public function setUri($uri){
        if($uri){ self::$_requestUri = $uri; }
    }

    public function setPath($path){
        if($path){
            self::$_requestPath = $path;
            self::$_requestUri = self::$_requestPath.'?'.self::$_requestQuery;
        }
    }
}