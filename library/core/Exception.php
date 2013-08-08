<?php
/**
 * Exception.php
 * 
 * @copyright	http://yanue.net/
 * @author 		yanue <yanue@outlook.com>
 * @date        2013-07-29
 */

namespace Library\Core;
use Library\Core\Loader;
/**
 *
 * Class Exception
 * @package Library\Core
 */
class Exception {
    private static $isInitCss = false;
    private static $isInitErrinfo = false;

    /**
     * 自定义错误处理
     *
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */
    public function error_handler($errno, $errMsg, $errfile, $errline) {
        self::css();
        # 为了安全起见，不暴露出真实物理路径，下面两行过滤实际路径
        # $errfile=str_replace(getcwd(),"",$errfile);
        # $errMsg=str_replace(getcwd(),"",$errMsg);
        self::outMsg($errno, $errMsg, $errfile, $errline);
    }


    /**
     * 错误异常输出
     *
     * @return void
     */
    public function exception_handler($e){
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
     * 错误终止 handleShutdown
     *
     * @return void
     */
    public function shutdown_handle(){
        if($err = error_get_last()){
            if(!in_array($err['type'],array(E_PARSE,E_CORE_ERROR,E_USER_ERROR,E_RECOVERABLE_ERROR))){
                return ;
            }
            header('HTTP/1.1 500 Internal Server Error');
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHttpRequest';
            if(Loader::getConfig('phpSettings.debug')){
                self::css();
                self::outMsg($err['type'],$err['message'],$err['file'],$err['line']);
            }else{
                if($isAjax){
                    echo json_encode('');
                }else{
                    echo '<title>500 Internal Server Error</title>';
                    echo '500 Internal Server Error';
                }
            }
        }
    }

    /**
     * 输出错误信息
     *
     * @return void
     */
    private static function outMsg($errno,$errMsg,$errfile,$errline){
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
}