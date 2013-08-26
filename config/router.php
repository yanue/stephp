<?php
// 路由设置
// 注意: url之path部分无需加后缀.html等

// 静态路由
$route['welcome'] = "index/test";
$route['login'] = "index/test";

// 通配符
$route['num/(:num)'] = "index/num/id/$1.html";
$route['any/(:any)'] = "index/any/sub/t3sss.html";
$route['any/(:any)'] = "";

// 正则路由
$route['wel/([a-z]+)_(\d+)'] = "index/regx/$1/$2";


return $route;