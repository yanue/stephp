<?php

/**
 * Hash 输出响应json信息
 *
 * @author 	 yanue <yanue@outlook.com>
 * @link	 http://stephp.yanue.net/
 * @package  lib/util
 * @time     2013-07-11
 */
class MsgCode {
    /**
     * echo right json data
     *
     * @param $data
     */
    public static function outRight($data){
        $result = array(
            'error'=>array('code'=>0,'msg'=>''),
            'data'=>$data
        );
        echo json_encode($result);
        exit;
    }

    /**
     * echo error json data
     *
     * @param $code
     * @param string $msg
     * @param bool $exit
     */
    public static function outError($code,$msg='',$exit=true){
        $result = array(
            'error'=>array('code'=>$code,'msg'=>urlencode(self::getErrorMsg($code).$msg)),
            'data'=>''
        );
        echo urldecode(json_encode($result));
        if($exit) exit;
    }
}