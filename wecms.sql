-- phpMyAdmin SQL Dump
-- version 4.2.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-01-19 18:44:35
-- 服务器版本： 5.5.40-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wecms`
--

-- --------------------------------------------------------

--
-- 表的结构 `login_log`
--

CREATE TABLE IF NOT EXISTS `login_log` (
  `log_id` int(10) unsigned NOT NULL COMMENT '自增字段',
  `user_id` int(6) unsigned NOT NULL COMMENT '用户id',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登陆时间',
  `total_login` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '总登陆次数'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='登陆日志';

-- --------------------------------------------------------

--
-- 表的结构 `mail_link`
--

CREATE TABLE IF NOT EXISTS `mail_link` (
  `link_id` int(6) unsigned NOT NULL COMMENT '自增',
  `link_key` char(8) COLLATE latin1_bin NOT NULL COMMENT 'link key值',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型（1=注册确认，2=找回密码）',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '生成时间'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- 表的结构 `post`
--

CREATE TABLE IF NOT EXISTS `post` (
  `post_id` int(10) unsigned NOT NULL COMMENT '自增字段',
  `post_key` varchar(8) COLLATE latin1_bin NOT NULL COMMENT '日志唯一key',
  `user_id` int(6) unsigned NOT NULL COMMENT '发布账号'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

-- --------------------------------------------------------

--
-- 表的结构 `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
`user_id` int(6) unsigned NOT NULL COMMENT '自增字段',
  `user_email` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '注册邮箱',
  `user_pwd` varchar(32) COLLATE latin1_bin NOT NULL COMMENT '登陆密码',
  `user_domain` varchar(20) COLLATE latin1_bin NOT NULL DEFAULT '' COMMENT '个性域名',
  `user_nick` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户昵称',
  `user_note` varchar(120) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '一句话签名',
  `salt` char(8) COLLATE latin1_bin NOT NULL COMMENT '密码秘钥',
  `regtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(1=可用，0=不可用)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='用户信息表' AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
 ADD PRIMARY KEY (`log_id`), ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `mail_link`
--
ALTER TABLE `mail_link`
 ADD PRIMARY KEY (`link_id`), ADD UNIQUE KEY `link_key` (`link_key`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
 ADD PRIMARY KEY (`post_id`), ADD UNIQUE KEY `post_key` (`post_key`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `user_email` (`user_email`,`user_domain`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
MODIFY `user_id` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增字段';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
