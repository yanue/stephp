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

    /**
     * 获取url中最后一个path部分最后一个参数
     * --说明:当path部分的数量为单数,则以key=val匹配后剩下的最后一个path参数key
     *
     * @return string
     */
    public function getLastParam()
    {
        $params = $this->getPathArray();
        $len = count($params);
        return $len > 0 ? $params[$len - 1] : '';
    }

    public function getMvcUri()
    {
        return $this->getMvcString();
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
     * 当前页面url构造(用于分页地址构造等)
     *
     * @param string $uri array(key=>val)或者/a/b?a=b 更新或添加到url中目录结构path部分
     * @param array $del_arr array(key) 删除原有path中的匹配key部分
     * @return string 新构造url
     */
    public function setUrl($uri = '', $del_arr = array())
    {
        $ori_params = $this->getPathParams();
        $paramPath = array();

        # 输入参数
        if (is_string($uri)) {
            // 输入的query部分
            $replace_uri = parse_url($uri);
            $replace_path = isset($replace_uri['path']) ? trim($replace_uri['path'], '/') : '';
            if ($replace_path) {
                $uri_params = explode('/', $replace_path);
                $len = count($uri_params);
                for ($i = 0; $i < ceil(($len) / 2); $i++) {
                    if (isset($uri_params[$i * 2 + 1]) && $uri_params[$i * 2 + 1]) {
                        $paramPath[$uri_params[$i * 2]] = $uri_params[$i * 2 + 1];
                    }
                }
                # 追加最后一个参数
                if ($len % 2 == 1) {
                    $paramPath['__last_var'] = $uri_params[$len - 1];
                }
            }
        } else if (is_array($uri)) {
            $paramPath = $uri;
        } else {
            $paramPath = [];
        }

        # 添加新的参数
        if ($paramPath) {
            $params = array_merge($ori_params, (array)$paramPath);
        } else {
            $params = $ori_params;
        }
        # 移除匹配参数
        if ($del_arr) {
            $del_arr = is_array($del_arr) ? $del_arr : [$del_arr];
            $params = array_diff_key($params, array_flip((array)$del_arr));
        }

        $mvcUri = $this->getMvcString();
        $last_param = $params['__last_var'] != '' ? '/' . $params['__last_var'] : '';
        unset($params['__last_var']);
        foreach ($params as $k => $v) {
            if ($k == "__last_var" || !$v) {
                continue;
            }
            $mvcUri .= '/' . $k . '/' . $v;
            # 全局到 $_REQUEST
            $_REQUEST[$k] = $v;
            $del_arr[] = $k;
        }
        $mvcUri = rtrim($mvcUri, '/');

        # 添加最后一个path参数和后缀
        $uriPath = $mvcUri . $last_param . $this->getSuffix();

        # 返回后面的query参数
        // 获取原始query部分
        $request_uri_query = $this->request->getQuery();
        parse_str($request_uri_query, $request_arr);
        // 输入的query部分
        $replace_uri_query = isset($replace_uri['query']) ? $replace_uri['query'] : '';
        parse_str($replace_uri_query, $replace_arr);
        // 最终query部分
        $queryParams = array_merge($request_arr, $replace_arr);
        $query_str = '';
        foreach ($queryParams as $key => $val) {
            # 需要删除或path部分已经存在
            if (in_array($key, $del_arr)) {
                continue;
            }
            $query_str .= $key . '=' . $val . '&';
        }
        $query_str = trim($query_str, '&');
        $queryStr = $query_str ? '?' . $query_str : '';
        $url = $this->request->getBaseUrl() . '/' . $uriPath . $queryStr;
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