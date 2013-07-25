<?php
error_reporting(E_ALL);
define('WEB_ROOT', dirname(__FILE__));
define('LIB_PATH', dirname(__FILE__).'/library');
require_once LIB_PATH.'/Bootstrap.php';
use Library\Bootstrap;
$app = new Bootstrap();
$app->init();