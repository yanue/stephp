<?php
error_reporting(E_ALL);
define('ROOT_PATH', dirname(__FILE__).'/');
require_once ROOT_PATH.'library/Bootstrap.php';
$app = new Bootstrap();
$app->init();