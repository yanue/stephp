<?php
//
define('WEB_ROOT', dirname(__FILE__));
define('LIB_PATH', 'library');
require_once LIB_PATH . '/Application.php';

$app = new \Library\Application();
$app->init();