<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yanue
 * Date: 12-12-28
 * Time: 下午2:57
 * To change this template use File | Settings | File Templates.
 */
// define error code
define('ERROR_USER_HAS_NOT_LOGIN',10001);
define('ERROR_USER_IS_NOT_EXISTS',10002);
define('ERROR_PASSWD_IS_NOT_CORRECT',10003);
define('ERROR_USER_HAS_NO_PERMISSION',10004);
define('ERROR_INVALID_REQUEST_PARAM',10004);

function getErrorMsg($code){
	$errmsg = array(
		'10001'=>'您还没有未登陆！',
		'10002'=>'用户不存在！',
		'10003'=>'密码不正确！',
		'10004'=>'您没有权限！',
		'10004'=>'缺少参数'
	);
	return isset($errmsg[$code])?$errmsg[$code]:'未知错误！';
}