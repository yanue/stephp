<?php
/*
 * MsgCode.php
 *------------------------------------------------------------------------------
 * @copyright : yanue.net
 *------------------------------------------------------------------------------
 * @author : yanue
 * @date : 13-6-24
 *------------------------------------------------------------------------------
 */

class MsgCode {
    // echo right json data
    public static function outRight($data){
        $result = array(
            'error'=>array('code'=>0,'msg'=>''),
            'data'=>$data
        );
        echo json_encode($result);
        exit;
    }

    // echo error json data
    public static function outError($code,$msg='',$exit=true){
        $result = array(
            'error'=>array('code'=>$code,'msg'=>urlencode(self::getErrorMsg($code).$msg)),
            'data'=>''
        );
        echo urldecode(json_encode($result));
        if($exit) exit;
    }

    private static function getErrorMsg($code){

    }
}