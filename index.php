<?php
error_reporting(E_ALL);
// for autoload
define('WEB_ROOT', dirname(__FILE__));
define('LIB_PATH', '/var/www/stephp-core/library');
require_once LIB_PATH.'/Bootstrap.php';

$app = new \Library\Bootstrap();
$app->init();