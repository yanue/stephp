<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yansueh
 * Date: 12-12-24
 * Time: 下午5:00
 * To change this template use File | Settings | File Templates.
 */

// 左边导航
$navs = array(
	'nav1'=>'系统设置',
	'nav2'=>'模块管理',
	'nav3'=>'会员管理',
	'nav4'=>'核心设置',
	'nav5'=>'我的面板'
);

// 模块导航
$menus = array(
	'nav1'=>array(
		'menu1'=>'常规设置',
		'menu2'=>'seo设置',
	),
	'nav2'=>array(
		'menu1'=>'图片管理',
	),
	'nav3'=>array(
		'menu1'=>'查看会员',
	),
	'nav4'=>array(
		'menu1'=>'系统消息',
	),
	'nav5'=>array(
		'menu1'=>'我的信息',
	)
);

// 具体导航
$subMenus = array(
	'nav1_menu1'=>array(
		'subMenu1'=>array('网站标题','index/other'),
		'subMenu2'=>array('网站名称','')
	),

	'nav1_menu2'=>array(
		'subMenu1'=>array('seo设置',''),
		'subMenu2'=>array('网站名称','')
	),

	'nav4_menu1'=>array(
		'subMenu1'=>array('登陆日志',''),
		'subMenu2'=>array('系统提醒','')
	)

);