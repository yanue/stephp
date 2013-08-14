<?php
namespace Library\Util;

use Library\Util\Session;
use Library\Util\Hash;
use Library\Util\Cookie;

/**
 * ajax api 信息处理
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class Ajax {

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
    public function __construct(){
        $this->session = new Session();
    }
    
    /**
     * set api signature
     *
     * @return string
     */
    public function createSign(){
        $sign = Hash::create('sha1',md5('stephp'.time()));
        $this->session->set('sign',$sign);
        return $sign;
    }


    /**
     * clear api signature for safety
     *
     */
    public function clearSign(){
        $this->session->del('sign');
    }

    /**
     * check api signature
     *
     * @param $request_sign
     * @return bool
     */
    public function checkSign(){
        $request_sign = isset($_REQUEST['sign']) ? $_REQUEST['sign'] : null;
        $sess_sign = $this->session->get('sign');
        $res = $request_sign == $sess_sign ? true : false ;
        if(!$res){
            self::outError(ERROR_ILLEGAL_API_SIGNATURE);
        }
        return $res;
    }

    /**
     * check Login Status
     *
     */
    public function checkLogin(){
        $cert = Cookie::get('_CERT');
        $uid  = Cookie::get('_CUID');
        if(Hash::create('sha1','stephp'.$uid) != $cert){
            $uid = 0;
            Cookie::del('_CUID');
            Cookie::del('_CUSR');
            Cookie::del('_CERT');
        }
        if($uid == 0){
            $this->outError(ERROR_USER_HAS_NOT_LOGIN);
        }
        return $uid;
    }

    /**
     * echo right json data
     *
     * @param $data
     */
    public function outRight($msg='',$data=''){
        $data = is_array($data) ? $this->urlEncodeArray($data) : urlencode($data);
        $result = array(
            'error'=>array('code'=>0,'msg'=>urlencode($msg)),
            'data'=>$data
        );
        echo urldecode(json_encode($result));
        exit;
    }

    /**
     * 递归多维数组进行 urlencode
     *
     * @param $arr
     * @return array
     */
    private function urlEncodeArray(& $arr){
        if(is_array($arr)){
            foreach ($arr as $k=>$v) {
                if(is_array($v)){
                    // 递归子数组
                    self::urlEncodeArray($arr[$k]);
                }else{
                    // url encode for value
                    $arr[$k] = urlencode($v);
                }
            }
        }
        return $arr;
    }
    
    /**
     * echo error json data
     *
     * @param $code
     * @param string $msg
     * @param bool $exit
     */
    public function outError($code,$msg='',$exit=true){
        $msg = is_array($msg) ? $this->urlEncodeArray($msg) : urlencode($msg);
        $result = array(
            'error'=>array('code'=>$code,'msg'=>urlencode($this->getErrorMsg($code)),'more'=>$msg)
        );
        echo urldecode(json_encode($result));
        if($exit) exit;
    }

    /**
     * get error msg by defined code
     * @param $code
     * @return string
     */
    private function getErrorMsg($code){
        return isset($code) && isset(self::$errmsg[$code]) ? self::$errmsg[$code] : '';
    }
}