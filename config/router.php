<?php
// 路由设置
// 静态路由
$route['static']['welcome'] = "index/index";
$route['static']['login'] = "index/test";
// 规则路由
$route['rule']['login'] = "index/index";
$route['rule']['login/test'] = "index/test";
// 正则路由
$route['regex']['login'] = "index/index";
$route['regex']['login/test'] = "index/test";
// 域名路由
$route['domain']['login'] = "index/index";
$route['domain']['login'] = "index/index";
return $route;