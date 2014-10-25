<?php

# ==== php配置 ====
$config['timezone'] = "PRC";
$config['display_errors'] = true;
$config['debug'] = true;

# ==== 默认应用配置 =====
$config['module'] = "home"; #默认模块
$config['controller'] = "index";
$config['action'] = "index";
$config['suffix'] = ".html"; # 请不要保护那个'.'

return $config;