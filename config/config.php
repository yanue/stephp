<?php

# ==== php配置 ====
$config['timezone'] = "PRC";
$config['display_errors'] = false;
$config['debug'] = false;

# ==== 默认应用配置 =====
$config['module'] = "home"; #默认模块
$config['controller'] = "index";
$config['action'] = "index";
$config['suffix'] = ".html";# 请不要保护那个'.'


# ==== smarty配置 ====
$config['db.type']         = 'mysql';
$config['db.host']         = 'localhost';
$config['db.port']         = '3306';
$config['db.username']     = 'root';
$config['db.password']     = 'root';
$config['db.dbname']       = 'looklo_pay';

return $config;