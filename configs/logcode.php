<?php
// log code
define('LOG_USER_LOGIN',1001);
define('LOG_INSERT_ONE',1002);
define('LOG_INSERT_BATCH',1003);
define('LOG_UPDATE_ONE',1004);
define('LOG_UPDATE_BATCH',1005);
define('LOG_DELETE_ONE',1006);
define('LOG_DELETE_BATCH',1007);

function getLogConent($code,$content=''){
	$logcode = array(
		'1001'=>'成功登陆',
		'1002'=>'添加了',
		'1003'=>'批量添加了',
		'1004'=>'更新了',
		'1005'=>'批量更新了',
		'1006'=>'删除了',
		'1007'=>'批量删除了',
	);
	$log = isset($logcode[$code]) ? $logcode[$code] : '未知操作';
	return $log.$content;
}