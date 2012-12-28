-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2012 at 11:42 PM
-- Server version: 5.1.50
-- PHP Version: 5.3.9-ZS5.6.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `looklo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `type` int(10) NOT NULL DEFAULT '0' COMMENT '操作类型(0登陆，1新增，2修改，3删除)',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '日志内容（序列化）',
  `time` int(10) NOT NULL DEFAULT '0' COMMENT '操作时间',
  `param` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义预留参数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员操作日志' AUTO_INCREMENT=8 ;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `user_id`, `type`, `content`, `time`, `param`) VALUES
(1, 1, 0, '成功登陆', 1356705779, ''),
(2, 1, 0, '成功登陆', 1356706570, ''),
(3, 1, 0, '成功登陆', 1356706591, ''),
(4, 1, 0, '成功登陆', 1356707293, ''),
(5, 1, 0, '成功登陆', 1356707445, ''),
(6, 1, 0, '成功登陆', 1356708793, ''),
(7, 1, 0, '成功登陆', 1356708809, '');

-- --------------------------------------------------------

--
-- Table structure for table `admin_right`
--

CREATE TABLE IF NOT EXISTS `admin_right` (
  `id` int(10) unsigned NOT NULL,
  `module` varchar(30) NOT NULL COMMENT '所在模块',
  `controller` varchar(30) NOT NULL COMMENT '控制器',
  `action` varchar(30) NOT NULL COMMENT '方法',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `param` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

--
-- Dumping data for table `admin_right`
--


-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

CREATE TABLE IF NOT EXISTS `admin_roles` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `create_time` int(10) NOT NULL,
  `desc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色表';

--
-- Dumping data for table `admin_roles`
--


-- --------------------------------------------------------

--
-- Table structure for table `admin_role_right`
--

CREATE TABLE IF NOT EXISTS `admin_role_right` (
  `id` int(10) DEFAULT NULL,
  `role_id` int(10) DEFAULT NULL,
  `right_id` int(10) DEFAULT NULL,
  `right_type` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_role_right`
--


-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE IF NOT EXISTS `admin_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `password` char(40) NOT NULL DEFAULT '' COMMENT '密码(md5结合sha1的hash)',
  `email` varchar(200) NOT NULL DEFAULT '' COMMENT '邮箱',
  `truename` char(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `modify_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `last_login` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '最后登录ip',
  `login_count` int(10) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='后台管理用户' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`uid`, `user_name`, `password`, `email`, `truename`, `create_time`, `modify_time`, `last_login`, `last_ip`, `login_count`) VALUES
(1, 'yanue', '7083d7aa7dc5de156fb749e91f5d61cb5f8a5216', '', '', 0, 0, 1356708809, '127.0.0.1', 7);

-- --------------------------------------------------------

--
-- Table structure for table `admin_user_right`
--

CREATE TABLE IF NOT EXISTS `admin_user_right` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL COMMENT '管理用户id',
  `right_id` int(10) NOT NULL COMMENT '权限id',
  `right_type` int(10) NOT NULL COMMENT '（0:可访问，1:可授权）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_user_right`
--


-- --------------------------------------------------------

--
-- Table structure for table `admin_user_role_relation`
--

CREATE TABLE IF NOT EXISTS `admin_user_role_relation` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色对应关系';

--
-- Dumping data for table `admin_user_role_relation`
--

