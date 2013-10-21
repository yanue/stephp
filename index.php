<?php
//
define('WEB_ROOT', dirname(__FILE__));
define('LIB_PATH', 'library');
require_once LIB_PATH.'/Bootstrap.php';

$app = new \Library\Bootstrap();
$app->init();