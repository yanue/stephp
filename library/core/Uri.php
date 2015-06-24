<?php
namespace Library\Core;

if (!defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * uri各种参数获取综合类
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/core
 * @time     2013-07-11
 */
final class Uri extends Dispatcher
{

    /**
     * 获取url中匹配key的值
     * --说明: 可以通过key获取包含path部分和?后面的query部分以key=>val结构的val
     *  -例http://localhost/mvc/index/index/te/ed/test/a/p/2/a.html?c=d 通过te可以获取ed,c获取d
     *
     * @param $key
     * @param null $default
     * @return null
     */
    public function getParam($key, $default = null)
    {
        $params = $this->getParams();
        return isset($params[$key]) ? $params[$key] : $default;
    }

    /**
     * 获取url中path部分的除去mvc结构的第n个参数
     * --说明:
     *  -例/index/index/a/b, 如果默认module是home,那么控制器就是index,方法是index,因此后面的为/a/b,取1时为a,以此类推
     *  -例/home/index/index/c/d 默认module为home,那么mvc结构部分为/home/index/index,其他同上
     *  -例/admin/index/index/e/f/g/h 默认module为home,那么mvc结构部分为/admin/index/index,其他同上
     *
     * @param int $n 除去mvc结构的第n个参数(以0开始)
     * @return string
     */
    public function getUriSegment($n)
    {
        $params = $this->getPathArray();
        return isset($params[$n]) ? $params[$n] : false;
    }

    public function getMvcString()
    {
        $moduleUri = $this->getModule() != Config::getBase('module') ? $this->getModule() . '/' : '';
        $moduleUri = $this->getApi() == 'api' ? $moduleUri . 'api/' : $moduleUri;

        return $moduleUri . $this->getController() . '/' . $this->getAction();
    }

    /**
     * 获取url中最后一个path部分的参数key
     * --说明:当path部分的数量为单数,则以key=val匹配后剩下的最后一个path参数key
     *
     * @return string
     */
    public function getLastParam()
    {
        $params = $this->getPathArray();
        $len = count($params);
        return $len % 2 == 1 ? $params[$len - 1] : null;
    }

    public function getMvcUri()
    {
        $moduleUri = $this->getModule() ? $this->getModule() . '/' : '';
        $moduleUri = $this->getApi() == 'api' ? $moduleUri . 'api/' : $moduleUri;

        return $moduleUri . $this->getController() . '/' . $this->getAction();
    }

    /**
     * 获取url中?后面query部分(a=b&c=d)的值
     * --说明:当怕怕path部分的数量为单数,则以key=val匹配后剩下的最后一个path参数key
     *
     * @param string $key 例:(a=b&c=d)中=前面参数a
     * @return string 匹配的值
     */
    public function getQuery($key)
    {
        $params = $this->request->getQuery();
        parse_str($params, $paramQuery);
        return isset($paramQuery[$key]) ? $paramQuery[$key] : '';
    }

    /**
     * 返回当前页面完整url
     *
     * @return string
     */
    public function getFullUrl()
    {
        return $this->request->getFullUrl();
    }

    /**
     * 获取uri部分
     *
     * @return string
     */
    public function getUriString()
    {
        return $this->request->getUri();
    }

    /**
     * 获取uri中?后面query部分
     *
     * @return string
     */
    public function getQueryString()
    {
        return $this->request->getQuery();
    }

    /**
     * 获取uri中path部分
     *
     * @return string
     */
    public function getPathString()
    {
        return $this->request->getPath();
    }

    /**
     * url构造(用于分页地址构造等)
     *
     * @param array $url_arr array(key=>val) 更新或添加到url中目录结构path部分
     * @param array $rm_arr array(key) 删除原有path中的匹配key部分
     * @param bool $getQueryString true/false 是否返回url中?后query部分
     * @return string 新构造url
     */
    /**
     *
     * @param array $add_arr array(key=>val) 更新或添加到url中目录结构path部分
     * @param array $rm_arr array(key) 删除原有path中的匹配key部分(querystring)
     * @param bool $getQueryString
     * @return string
     */
    public function setUrl($add_arr = array(), $rm_arr = array(), $getQueryString = false)
    {
        $params = $this->getPathArray();
        $paramPath = array();
        $lastParam = $this->getLastParam();
        if (($len = count($params)) > 0) {
            for ($i = 0; $i < ceil(($len) / 2); $i++) {
                if (isset($params[$i * 2 + 1]) && $params[$i * 2 + 1]) { #去掉空值的部分
                    if ($params[$i * 2]) {
                        $paramPath[$params[$i * 2]] = $params[$i * 2 + 1];
                    }
                }
            }
        }
        # 添加新的参数
        if ($add_arr) {
            $params = array_merge($paramPath, (array)$add_arr);
        } else {
            $params = $paramPath;
        }
        # 移除匹配参数
        if ($rm_arr) {
            $params = array_diff_key($params, array_flip((array)$rm_arr));
        }
        $moduleUri = $this->getModule() != Config::getBase('module') ? '/' . $this->getModule() : '';
        $moduleUri = $this->getApi() == 'api' ? $moduleUri . '/api' : $moduleUri;

        $mvcUri = $moduleUri . '/' . $this->getController() . '/' . $this->getAction();
        foreach ($params as $k => $v) {
            $mvcUri .= '/' . $k . '/' . $v;
        }
        $mvcUri = rtrim($mvcUri, '/');
        # 添加最后一个path参数和后缀
        $uriPath = $mvcUri . $lastParam . $this->getSuffix();
        # 返回后面的query参数
        $request_uri = $getQueryString == true && $this->request->getQuery() ? $uriPath . '?' . $this->request->getQuery() : $uriPath;
        $url = $this->request->getBaseUrl() . '/' . ltrim($request_uri, '/');
        return $url;
    }


    /**
     * baseUrl
     *
     * @param string $uri
     * @param bool $setSuffix
     * @return string
     */
    public function baseUrl($uri = '', $setSuffix = true)
    {
        $baseUrl = rtrim($this->request->getBaseUrl(), '/');

        // 避免根上加.html后缀
        if (!ltrim($uri, '/')) {
            return $baseUrl;
        }

        // add uri to url
        return $this->addUri($baseUrl, $uri, $setSuffix);
    }
}