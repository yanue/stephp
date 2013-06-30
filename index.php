<?php
error_reporting(0);
define('ROOT_PATH', dirname(__FILE__).'/');
require_once ROOT_PATH.'library/Bootstrap.php';
$app = new Bootstrap();
$app->init();