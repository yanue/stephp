<?php
if ( ! defined('ROOT_PATH')) exit('No direct script access allowed');

/*==============================================================================
 * 错误调试调试
 *------------------------------------------------------------------------------
 * @copyright : yanue.net
 *------------------------------------------------------------------------------
 * @author : yanue
 * @date : 13-6-6
 *==============================================================================*/


class Debug {

    private static $isInitCss = false;
    private static $isInitErrinfo = false;

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

    public static function dump($array){
        self::css();
        echo '<h2 class="traceTitle">Dump Info:</h2>';
        echo '<pre class="trane trance_array">';
        echo var_export($array);
        echo '</pre>';
//        self::memAndTime();
    }

    /**
     * 分段运行时间
     */
    public static function runtime() {
        self::css();
        $du = round((microtime(true) - $GLOBALS['_startTime']),6);
        $debug = debug_backtrace ();
        //打印代码执行的行数.以及执行花费时间.
        echo  '<p class="trance"><code>File:</code>'.$debug[0]['file']." | <code>Line</code>:".$debug[0] ['line'] . "  | <code>" . $du . "</code> 秒</p>";
    }

    public static function trace(){
        self::css();
        $e = new Exception();
        echo '<h2 class="traceTitle">Stack trace :</h2>';
        echo '<pre class="trance">';
        echo debug_print_backtrace();
        echo '</pre>';
        echo '<h2 class="traceTitle">Request Parameters :</h2>';
        echo '<pre class="trance">';
        var_export(Bootstrap::$_requestParams);
        echo '</pre>';
    }

    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    static public function appError($errno, $errMsg, $errfile, $errline) {
        self::css();
        # 为了安全起见，不暴露出真实物理路径，下面两行过滤实际路径
        # $errfile=str_replace(getcwd(),"",$errfile);
        # $errMsg=str_replace(getcwd(),"",$errMsg);
        self::half($errno, $errMsg, $errfile, $errline);
    }

    public static function fatalError(){
        if($err = error_get_last()){
            self::css();
            self::half($err['type'],$err['message'],$err['file'],$err['line']);
        }
    }

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

    public static function memAndTime(){
        self::css();
        $mem = memory_get_usage()-($GLOBALS['_startMemory']);
        $time = round(microtime(true)-$GLOBALS['_startTime'],6);
        $str = '<p class="trance">';
        $str .= '内存:<code>'.self::convertSize($mem).'</code> ';
        $str .= '耗时:<code>'.$time.'</code> 秒';
        $str .= '</p>';
        echo $str;
    }

    private static function convertSize($size){
        $unit=array('byte','kb','mb','gb','tb','pb');
        return round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

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

    public static function show($tip='错误:',$msg){
        self::css();
        echo  '<p class="trance" style="border:1px solid #e0e0e0;padding:5px;"><code>'.$tip.'</code><span>'.$msg.'</span></p>';
    }
}