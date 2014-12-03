-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: wecms
-- ------------------------------------------------------
-- Server version	5.5.40-0ubuntu1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `img_id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '图片id',
  `img_url` varchar(45) COLLATE latin1_bin NOT NULL DEFAULT '' COMMENT '图片url',
  `img_note` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图片描述',
  PRIMARY KEY (`img_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `note` (
  `auto_id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `note_id` varchar(6) COLLATE latin1_bin NOT NULL COMMENT '日志id',
  `user_id` int(6) unsigned NOT NULL COMMENT '发布用户',
  `title` varchar(64) CHARACTER SET utf8 NOT NULL COMMENT '日志标题',
  `content` text CHARACTER SET utf8 NOT NULL COMMENT '日志内容',
  `img_related` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '关联图片',
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `page_view` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0=草稿，1=发布，2=推荐）',
  PRIMARY KEY (`auto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='日志信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `note`
--

LOCK TABLES `note` WRITE;
/*!40000 ALTER TABLE `note` DISABLE KEYS */;
/*!40000 ALTER TABLE `note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `auto_id` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `post_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '文章类型1=古诗文，2=诗歌，3=收藏美文，4=技术笔记',
  `post_id` char(6) COLLATE latin1_bin NOT NULL COMMENT '文章id',
  `post_title` varchar(64) CHARACTER SET utf8 DEFAULT NULL COMMENT '文章标题',
  `content` text CHARACTER SET utf8 COMMENT '文章内容',
  `img_related` int(8) unsigned NOT NULL COMMENT '关联图片',
  `page_view` int(6) unsigned NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `inputtime` int(10) unsigned NOT NULL COMMENT '发布时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态（0=草稿，1=发布）',
  PRIMARY KEY (`auto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='文章信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `uid` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `user_email` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '登录邮箱，英文字符和数字组成',
  `user_passwd` varchar(32) COLLATE latin1_bin NOT NULL COMMENT '登录密码（默认密码123123）',
  `nick_name` varchar(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户昵称',
  `sign` varchar(128) CHARACTER SET utf8 DEFAULT NULL COMMENT '一句话签名',
  `reg_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_code` varchar(32) COLLATE latin1_bin DEFAULT NULL COMMENT '邀请注册码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=待注册，1=正式注册',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=latin1 COLLATE=latin1_bin COMMENT='用户信息表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-03 22:20:34
