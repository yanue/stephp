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
 * @time     2013-08-26
 */

class Router{

    /**
     * # 路由后的key=>val请求信息
     * @var null
     */
    private $routes = null;

    /**
     *
     */
    private $request = null;

    /**
     * 初始化
     */
    public function __construct($rules){
        if($rules){
            $this->routes = $rules;
            $this->request = new Request();
            // 解析路由
            $this->_parse_routes();
        }
    }

    /**
     * 参见ci之Router
     *
     */
    function _parse_routes()
    {
        // Turn the segment array into a URI string
        $uri = $this->request->getPath();

        // 直接匹配
        // Is there a literal match?  If so we're done
        if (isset($this->routes[$uri]))
        {
            $this->request->setPath($this->routes[$uri]);
            return ;
        }

        // Loop through the route array looking for wild-cards
        foreach ($this->routes as $key => $val)
        {
            // Convert wild-cards to RegEx
            $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));
            // Does the RegEx match?
            if (preg_match('#^'.$key.'$#', $uri))
            {
                // Do we have a back-reference?
                if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
                {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }
                $this->request->setPath($val);
                return ;
            }
        }
    }

}