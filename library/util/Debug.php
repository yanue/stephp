<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/**
 * Debug 错误调试调试
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class Debug {

    private static $isInitCss = false;
    private static $isInitErrinfo = false;

    public function __construct(){
        self::startMT();
    }

    /**
     * 开始监测时间和内存
     *
     */
    public static function startMT(){
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
//        self::memAndTime();
    }

    /***
     * 分段运行时间
     * --说明: 打印代码执行的行数.以及执行花费时间.
     *
     * @return void
     */
    public static function runtime() {
        self::css();
        $du = round((microtime(true) - $GLOBALS['_startTime']),6);
        $debug = debug_backtrace ();
        //打印代码执行的行数.以及执行花费时间.
        echo  '<p class="trance"><code>File:</code>'.$debug[0]['file']." | <code>Line</code>:".$debug[0] ['line'] . "  | <code>" . $du . "</code> 秒</p>";
    }

    /**
     * 输出代码追踪信息
     *
     * @return void
     */
    public static function trace(){
        self::css();
        $e = new Exception();
        echo '<h2 class="traceTitle">Stack trace :</h2>';
        echo '<pre class="trance">';
        echo debug_print_backtrace();
        echo '</pre>';
        echo '<h2 class="traceTitle">Request Parameters :</h2>';
        echo '<pre class="trance">';

        echo '</pre>';
    }

    /**
     * 自定义错误处理
     *
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    public static function appError($errno, $errMsg, $errfile, $errline) {
        self::css();
        # 为了安全起见，不暴露出真实物理路径，下面两行过滤实际路径
        # $errfile=str_replace(getcwd(),"",$errfile);
        # $errMsg=str_replace(getcwd(),"",$errMsg);
        self::half($errno, $errMsg, $errfile, $errline);
    }

    /**
     * 错误终止
     *
     * @return void
     */
    public static function fatalError(){
        if($err = error_get_last()){
            self::css();
            self::half($err['type'],$err['message'],$err['file'],$err['line']);
        }
    }

    /**
     * 错误异常输出
     *
     * @return void
     */
    public static function appException($e){
        self::css();
        $error = array();
        $error['message']   = $e->getMessage();
        $trace  =   $e->getTrace();
        if('throw_exception'==$trace[0]['function']) {
            $error['file']  =   $trace[0]['file'];
            $error['line']  =   $trace[0]['line'];
        }else{
            $error['file']      = $e->getFile();
            $error['line']      = $e->getLine();
        }
        echo  '<p class="trance">[第'.$error['line'].'行] '.$error['file'].' <br />错误信息: '.$error['message'].'</p>';
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

    /**
     * 输出错误信息
     *
     * @return void
     */
    private static function half($errno,$errMsg,$errfile,$errline){
        // 定义错误字符串的关联数组
        // 在这里我们只考虑
        // E_WARNING, E_NOTICE, E_USER_ERROR,
        // E_USER_WARNING 和 E_USER_NOTICE
        $errType = array (
            E_ERROR              => 'Error',
            E_WARNING            => 'Warning',
            E_PARSE              => 'Parsing Error',
            E_NOTICE             => 'Notice',
            E_CORE_ERROR         => 'Core Error',
            E_CORE_WARNING       => 'Core Warning',
            E_COMPILE_ERROR      => 'Compile Error',
            E_COMPILE_WARNING    => 'Compile Warning',
            E_USER_ERROR         => 'User Error',
            E_USER_WARNING       => 'User Warning',
            E_USER_NOTICE        => 'User Notice',
            E_STRICT             => 'Runtime Notice',
            E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'
        );
        $errTypeName = array_key_exists($errno,$errType) ? $errType[$errno] : 'Unknown Error Type';
        if(self::$isInitErrinfo==false){
            echo '<h2 class="traceTitle">Exception information :</h2>';
            self::$isInitErrinfo=true;
        }else{
            self::$isInitErrinfo=false;
        }
        echo  '<p class="trance">[第'.$errline.'行] '.$errfile.' <code>'.$errTypeName .'['.$errno.']</code> : '.$errMsg.'</p>';
    }
}