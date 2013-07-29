<?php
namespace Library\Util;
use Exception;
use Library\Core\Loader;
if ( ! defined('LIB_PATH')) exit('No direct script access allowed');

/**
 * Debug 调试输出信息
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class Debug {

    private static $isInitCss = false;
    private static $requestParam = null;

    public function __construct(){
        self::startMemoryAndTime();
    }

    /**
     * 设置url上请求参数数组便于输出.
     * --url分发完成后设置
     *   参见来自Library/Core/Dispatcher.
     *
     * @param $params
     */
    public static function setRequestParam(& $params){
        self::$requestParam = $params;
    }

    /**
     * 从当前开始监测时间和内存
     *
     */
    public static function startMemoryAndTime(){
        $GLOBALS['_startMemory'] = memory_get_usage();
        $GLOBALS['_startTime'] = microtime(true);
    }

    /**
     * 输出内存和时间消耗信息
     * --注意:如果需要检测某个位置,先注册 Debug::startMT() 进行检测再使用
     *
     * @return void
     */
    public static function traceMemoryAndTime(){
        self::css();
        $mem = memory_get_usage()-($GLOBALS['_startMemory']);
        $time = round(microtime(true)-$GLOBALS['_startTime'],6);
        $str = '<p class="trance">';
        $str .= '内存:<code>'.self::convertSize($mem).'</code> ';
        $str .= '耗时:<code>'.$time.'</code> 秒';
        $str .= '</p>';
        echo $str;
    }

    /**
     * 默认trace的css样式
     *
     */
    private static function css(){
        $css = '<style>';
        $css .= 'body{position:relative;}';
        $css .= '.trance{font-size:12px;font-family:"monospace";line-height:180%;margin:0;passing:0;}';
        $css .= 'h2.traceTitle{font-size:14px;}';
        $css .= '.trance code{background:#ddf0dd;color:#0066ff;}';
        $css .= '.trance_array{background:#f0f0f0;color:#06c;}';
        $css .='</style>';
        if(self::$isInitCss==false){
            echo $css;
            self::$isInitCss=true;
        }else{
            self::$isInitCss=false;
        }
    }

    /**
     * 结构化输出信息
     *
     * @param $mixed mixed : 字串,数组..
     * @return void
     */
    public static function dump($mixed){
        self::css();
        echo '<h2 class="traceTitle">Dump Info:</h2>';
        echo '<pre class="trane trance_array">';
        echo var_export($mixed);
        echo '</pre>';
        self::traceMemoryAndTime();
    }

    /***
     * 分段运行时间
     * --说明: 打印代码执行的行数.以及执行花费时间.
     *
     * @return void
     */
    public static function runtime() {
        self::css();
        $debug = debug_backtrace ();
        $du = round((microtime(true) - $GLOBALS['_startTime']),6);
        $mem = memory_get_usage()-($GLOBALS['_startMemory']);
        //打印代码执行的行数.以及执行花费时间.
        echo  '<p class="trance"><code>File:</code>'.$debug[0]['file'].' | <code>Line</code>:'.$debug[0] ['line'] .
            '  | <code>' . $du . '</code> 秒 '.self::convertSize($mem).'</p>';
    }

    /**
     * 输出代码追踪信息
     *
     * @return void
     */
    public static function trace(){
        self::css();
        echo '<h2 class="traceTitle">Stack trace :</h2>';
        echo '<pre class="trance">';
        echo debug_print_backtrace();
        echo '</pre>';
        echo '<h2 class="traceTitle">Request Parameters :</h2>';
        echo '<pre class="trance">';
        echo var_export(self::$requestParam);
        echo '</pre>';
    }

    /**
     * 字节转换
     *
     * @return string.
     */
    private static function convertSize($size){
        $unit=array('byte','kb','mb','gb','tb','pb');
        return round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

}