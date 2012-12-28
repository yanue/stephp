-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.24-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4219
-- Date/time:                    2012-12-28 17:58:47
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for looklo
DROP DATABASE IF EXISTS `looklo`;
CREATE DATABASE IF NOT EXISTS `looklo` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `looklo`;


-- Dumping structure for table looklo.admin_logs
DROP TABLE IF EXISTS `admin_logs`;
CREATE TABLE IF NOT EXISTS `admin_logs` (
  `id` int(10) unsigned NOT NULL COMMENT '日志id',
  `user_id` int(10) NOT NULL COMMENT '管理员id',
  `type` int(10) NOT NULL COMMENT '操作类型(0登陆，1新增，2修改，3删除)',
  `content` varchar(255) NOT NULL COMMENT '日志内容（序列化）',
  `time` int(10) NOT NULL COMMENT '操作时间',
  `param` varchar(255) NOT NULL COMMENT '自定义预留参数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员操作日志';

-- Data exporting was unselected.


-- Dumping structure for table looklo.admin_right
DROP TABLE IF EXISTS `admin_right`;
CREATE TABLE IF NOT EXISTS `admin_right` (
  `id` int(10) unsigned NOT NULL,
  `module` varchar(30) NOT NULL COMMENT '所在模块',
  `controller` varchar(30) NOT NULL COMMENT '控制器',
  `action` varchar(30) NOT NULL COMMENT '方法',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `param` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

-- Data exporting was unselected.


-- Dumping structure for table looklo.admin_roles
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE IF NOT EXISTS `admin_roles` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `create_time` int(10) NOT NULL,
  `desc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- Data exporting was unselected.


-- Dumping structure for table looklo.admin_role_right
DROP TABLE IF EXISTS `admin_role_right`;
CREATE TABLE IF NOT EXISTS `admin_role_right` (
  `id` int(10) DEFAULT NULL,
  `role_id` int(10) DEFAULT NULL,
  `right_id` int(10) DEFAULT NULL,
  `right_type` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table looklo.admin_users
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `uid` int(10) unsigned NOT NULL COMMENT '用户id',
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `password` char(32) DEFAULT NULL COMMENT '密码',
  `email` varchar(200) NOT NULL COMMENT '邮箱',
  `truename` char(32) NOT NULL COMMENT '真实姓名',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `modify_time` int(10) NOT NULL COMMENT '修改时间',
  `last_login` int(10) NOT NULL COMMENT '最后登录时间',
  `last_ip` varchar(50) NOT NULL COMMENT '最后登录ip',
  `login_count` int(10) NOT NULL COMMENT '登陆次数',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='后台管理用户';

-- Data exporting was unselected.


-- Dumping structure for table looklo.admin_user_right
DROP TABLE IF EXISTS `admin_user_right`;
CREATE TABLE IF NOT EXISTS `admin_user_right` (
  `id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL COMMENT '管理用户id',
  `right_id` int(10) NOT NULL COMMENT '权限id',
  `right_type` int(10) NOT NULL COMMENT '（0:可访问，1:可授权）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table looklo.admin_user_role_relation
DROP TABLE IF EXISTS `admin_user_role_relation`;
CREATE TABLE IF NOT EXISTS `admin_user_role_relation` (
  `id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户角色对应关系';

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
