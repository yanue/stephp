<?php
namespace Library\Core;

use Library\Di\Singleton;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * Request represents an HTTP request.
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
class Request
{

    /**
     * 域名url
     */
    private static $_hostUrl = null;

    /**
     * 当前应用根url地址(针对放在子目录情况)
     *
     */
    private static $_webrootUrl = null;

    /**
     * List of uri segments
     *
     * @var array
     * @access public
     */
    private static $segments = array();

    /**
     * uri部分
     */
    private static $_requestUri = null;

    /**
     * uri中?后query部分
     */
    private static $_requestQuery = null;

    /**
     * @var string uri中path部分
     */
    private static $_requestPath = null;

    /**
     * uri中baseUrl
     */
    private $_baseUrl = null;

    /**
     * 初始化并解析
     */
    public function __construct()
    {
        # 解析url
        if (!self::$_requestUri)
            $this->parseUrl();
        if (!self::$segments)
            $this->reParseUri();
        if (!$this->_baseUrl)
            $this->baseUrl();
    }

    /**
     * 全面解析当前url
     * --说明:解析出完整url,uri,path部分,query部分
     *
     * @return void.
     */
    private function parseUrl()
    {
        # 解决通用问题
        $requestUri = '';
        if (isset($_SERVER['REQUEST_URI'])) { #$_SERVER["REQUEST_URI"] 只有 apache 才支持,
            $requestUri = $_SERVER['REQUEST_URI'];
        } else {
            if (isset($_SERVER['argv'])) {
                $requestUri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
            } else if (isset($_SERVER['QUERY_STRING'])) {
                $requestUri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
            }
        }
        $https = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = strstr(strtolower($_SERVER["SERVER_PROTOCOL"]), "/", true) . $https;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);

        # 保存地址域
        self::$_hostUrl = $protocol . "://" . $_SERVER['HTTP_HOST'] . $port;
        # 获取的完整url


        # 当前脚本名称
        $script_name = $_SERVER['SCRIPT_NAME'];
        # 当前脚本目录
        $script_dir = dirname($_SERVER['SCRIPT_NAME']);
        # 去除uri中当前脚本文件名 (如果存在)
        $script = false === strpos($requestUri, $script_name) ? $script_dir : $script_name;
        $script = str_replace('\\', '/', $script);
        # 当前应用根url
        self::$_webrootUrl = self::$_hostUrl . $script;
        self::$_requestUri = substr($requestUri, strlen($script));
    }

    private function reParseUri()
    {
        $uriParam = parse_url(self::$_requestUri);
        $requestPath = isset($uriParam['path']) ? $uriParam['path'] : '';
        $pathStr = ltrim($requestPath, '/');

        # 判断url后缀是否存在
        $_url_suffix = Config::getBase('suffix');

        # 截取后缀
        if (strlen($pathStr) > strlen($_url_suffix)) {
            # 获取到后缀的位置
            $sfxpos = strripos($pathStr, $_url_suffix);
            # 后缀的位置处于url中path部分最后
            $pathStr = (false !== $sfxpos && $sfxpos == (strlen($pathStr) - strlen($_url_suffix))) ? substr($pathStr, 0, strlen($pathStr) - strlen($_url_suffix)) : $pathStr;
        }
        self::$_requestPath = $pathStr;
        self::$_requestQuery = isset($uriParam['query']) ? $uriParam['query'] : '';

        # 解析module,controller,action去他参数
        $requestPath = explode('/', self::$_requestPath);
        # 去除空项
        self::$segments = array_values(array_diff($requestPath, array(null)));
    }

    /**
     * 获取基本地址: baseUrl
     * --说明: 返回不包含mvc结构,可以通过uri参数传入设置
     *
     * @param string $uri 包含mvc结构的uri参数
     * @return string
     * */
    private function baseUrl()
    {
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $baseUrl .= isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST');
        $dirname = isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
        $dir = $dirname == '/' ? '' : $dirname; // 避免根目录情况下多一个'/'
        $dir = str_replace($dir, '\\', '/');
        $this->_baseUrl = rtrim($baseUrl . $dir, '/');
    }


    /**
     * get Segment Array
     *
     * @return array
     */
    public function getSegments()
    {
        return self::$segments;
    }

    /**
     * set requestUri
     *
     * @param $uri
     */
    public function setUri($uri)
    {
        if ($uri) {
            self::$_requestUri = $uri;
        }
    }

    public function setPath($path)
    {
        if ($path) {
            self::$_requestPath = $path;
            self::$_requestUri = self::$_requestPath . '?' . self::$_requestQuery;
            $this->reParseUri();
        }
    }

    /**
     * get http referer
     * @return string
     */
    public function getReferer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    /**
     * 获取baseUrl部分
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * 获取uri部分
     *
     * @return string
     */
    public function getUri()
    {
        return self::$_requestUri;
    }

    /**
     * 获取完整url
     *
     * @return string : url
     */
    public function getFullUrl()
    {
        return self::$_webrootUrl . self::$_requestUri;
    }

    /**
     * 获取uri中?后面query部分
     *
     * @return string
     */
    public function getQuery()
    {
        return self::$_requestQuery;
    }

    /**
     * 获取uri中path部分
     *
     * @return string
     */
    public function getPath()
    {
        return self::$_requestPath;
    }

    public function getIP()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realip = getenv("HTTP_CLIENT_IP");
            } else {
                $realip = getenv("REMOTE_ADDR");
            }
        }
        return $realip;
    }


    /**
     * get key data
     *
     * @param $key
     * @param string $type
     * @param null $default
     * @return bool|float|int|null
     */
    public function get($key, $type = '', $default = null)
    {
        if (!$key) return false;
        if (in_array($type, ['int', 'float', 'string'])) {
            $res = isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;

            switch ($type) {
                case 'int':
                    $res = intval($res);
                    break;
                case 'float':
                    $res = floatval($res);
                    break;
                case 'string':
                    break;
            }

            return $res;
        } else {
            return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
        }
    }

    /**
     * post key data
     *
     * @param $key
     * @param string $type
     * @param null $default
     * @return bool|float|int|null
     */
    public function getPost($key, $type = '', $default = null)
    {
        if (!$key) return false;
        if (in_array($type, ['int', 'float', 'string'])) {
            $res = isset($_POST[$key]) ? $_POST[$key] : $default;

            switch ($type) {
                case 'int':
                    $res = intval($res);
                    break;
                case 'float':
                    $res = floatval($res);
                    break;
                case 'string':
                    $res = mysql_real_escape_string($res);
                    break;
            }

            return $res;
        } else {
            return isset($_POST[$key]) ? $_POST[$key] : $default;
        }
    }

    /**server info
     * @param $key
     * @return mixed
     */
    public function getServer($key)
    {
        return $_SERVER[$key];
    }
}