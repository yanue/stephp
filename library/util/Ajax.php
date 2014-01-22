<?php
namespace Library\Util;

use Library\Core\Config;

/**
 * ajax handle for web api
 *
 * @author     yanue <yanue@outlook.com>
 * @link     http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class Ajax
{

    /**
     * error msg for defined code
     *
     * @source 参见当前module下configs/errorCode.php
     * @var null
     */
    public static $errmsg = null;

    /**
     * init session
     *
     */
    public function __construct()
    {
        self::$errmsg = Config::getSite('errcode');
    }

    /**
     * echo right json data
     *
     * @param string $msg
     * @param string $data
     */
    public static function outRight($msg = '', $data = '')
    {
        self::setHead();
        $result = array(
            'error' => array('code' => 0, 'msg' => $msg, 'more' => $msg),
            'result' => 1,
            'data' => $data
        );

        if (isset($_REQUEST['callback']) && $_REQUEST['callback']) {
            echo $_REQUEST["callback"] . '(' . json_encode($result, JSON_UNESCAPED_UNICODE) . ')'; // php 5.4
        } else {
            echo json_encode($result, JSON_UNESCAPED_UNICODE); // php 5.4
        }
        exit;
    }


    /**
     * echo error json data
     *
     * @param $code
     * @param string $msg
     */
    public static function outError($code, $msg = '')
    {
        self::setHead();
        $result = array(
            'error' => array('code' => $code, 'msg' => self::getErrorMsg($code), 'more' => $msg),
            'result' => 0,
        );
        if (isset($_REQUEST['callback']) && $_REQUEST['callback']) {
            echo $_REQUEST["callback"] . '(' . json_encode($result, JSON_UNESCAPED_UNICODE) . ')'; // php 5.4
        } else {
            echo json_encode($result, JSON_UNESCAPED_UNICODE); // php 5.4
        }
        exit;
    }

    /**
     * get error msg by defined code
     * @param $code
     * @return string
     */
    public static function getErrorMsg($code)
    {
        return isset($code) && isset(self::$errmsg[$code]) ? self::$errmsg[$code] : '';
    }

    /**
     * 设置ajax跨域head
     */
    public static function setHead()
    {
        header("content-type: text/javascript; charset=utf-8");
        header("Access-Control-Allow-Origin: *"); # 跨域处理
        header("Access-Control-Allow-Headers: content-disposition, origin, content-type, accept");
        header("Access-Control-Allow-Credentials: true");

        // Make sure file is not cached (as it happens for example on iOS devices)
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }
}