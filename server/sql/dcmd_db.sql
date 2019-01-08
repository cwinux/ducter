-- MySQL dump 10.13  Distrib 5.6.27-76.0, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: dcmd
-- ------------------------------------------------------
-- Server version	5.6.27-76.0-log

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
-- Table structure for table `dcmd_app`
--

DROP TABLE IF EXISTS `dcmd_app`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app` (
  `app_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(128) NOT NULL,
  `app_alias` varchar(128) NOT NULL,
  `app_type` varchar(16) NOT NULL,
  `service_tree` varchar(256) NOT NULL,
  `is_self` int(2) unsigned NOT NULL,
  `sa_gid` int(10) unsigned NOT NULL,
  `svr_gid` int(10) unsigned NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `depart_id` int(10) unsigned NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`app_id`),
  UNIQUE KEY `app_name` (`app_name`),
  UNIQUE KEY `app_alias` (`app_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app`
--

LOCK TABLES `dcmd_app` WRITE;
/*!40000 ALTER TABLE `dcmd_app` DISABLE KEYS */;
INSERT INTO `dcmd_app` VALUES (52,'search','搜索产品','app','/root',0,53,52,52,52,'search','2018-01-12 16:14:35','2018-01-12 16:14:35',18);
/*!40000 ALTER TABLE `dcmd_app` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_arch_diagram`
--

DROP TABLE IF EXISTS `dcmd_app_arch_diagram`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_arch_diagram` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `arch_name` varchar(200) NOT NULL,
  `diagram` longblob,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_app_arch_diagram` (`app_id`,`arch_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_arch_diagram`
--

LOCK TABLES `dcmd_app_arch_diagram` WRITE;
/*!40000 ALTER TABLE `dcmd_app_arch_diagram` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_arch_diagram` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_conf_version`
--

DROP TABLE IF EXISTS `dcmd_app_conf_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_conf_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `username` varchar(128) NOT NULL,
  `version` varchar(128) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`app_id`,`svr_id`,`svr_pool_id`,`version`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_conf_version`
--

LOCK TABLES `dcmd_app_conf_version` WRITE;
/*!40000 ALTER TABLE `dcmd_app_conf_version` DISABLE KEYS */;
INSERT INTO `dcmd_app_conf_version` VALUES (38,52,26,14,'admin','1.0.1','efd6d47f4c4ab88a26ecdfa5c6eec3d0','doiSarRC5J7qQXbWA4IMsDZKtfxBGp1n8Zdny1tRHfgIDJEbLelCavkSVXWNOu5MTB4Dn9jwuq5mQkhZdbzMUXFs8fJ032LYbUxC4WTRBVGDOd1en95L7qSQoFN2ca0E','2018-01-12 17:29:42'),(39,52,26,14,'admin','conf1.0.1','18e77884c4ca2596225e8f1a51f8094e','l0fmSq8oh3ieF56kUXHxzOZuQGYptnK2FIRZgBcD0TGsq4HNQjiyJk1PudWUeL7zmGzRr9t1F3L5NeVbKcoJgW0hjiOaTvAH6Kyt9YjB2I7u1zfaVWNkC8wPrvJOEAix','2018-01-12 17:30:02');
/*!40000 ALTER TABLE `dcmd_app_conf_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_node_group`
--

DROP TABLE IF EXISTS `dcmd_app_node_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_node_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `ngroup_id` int(11) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ngroup_id` (`app_id`,`ngroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_node_group`
--

LOCK TABLES `dcmd_app_node_group` WRITE;
/*!40000 ALTER TABLE `dcmd_app_node_group` DISABLE KEYS */;
INSERT INTO `dcmd_app_node_group` VALUES (52,52,22,'2018-01-12 16:18:06',18);
/*!40000 ALTER TABLE `dcmd_app_node_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_pkg_upload`
--

DROP TABLE IF EXISTS `dcmd_app_pkg_upload`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_pkg_upload` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_pool` varchar(128) NOT NULL,
  `upload_type` varchar(16) NOT NULL,
  `upload_username` varchar(128) NOT NULL,
  `accept_username` varchar(128) NOT NULL,
  `version` varchar(128) NOT NULL,
  `src_path` varchar(256) NOT NULL,
  `pkg_file` varchar(256) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  `upload_host` varchar(16) NOT NULL,
  `is_reupload` int(1) NOT NULL,
  `is_accept` int(1) NOT NULL,
  `upload_time` datetime NOT NULL,
  `accept_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`app_id`,`svr_id`,`svr_pool_id`),
  KEY `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_pkg_upload`
--

LOCK TABLES `dcmd_app_pkg_upload` WRITE;
/*!40000 ALTER TABLE `dcmd_app_pkg_upload` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_pkg_upload` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_pkg_version`
--

DROP TABLE IF EXISTS `dcmd_app_pkg_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_pkg_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `username` varchar(128) NOT NULL,
  `version` varchar(128) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`app_id`,`svr_id`,`version`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_pkg_version`
--

LOCK TABLES `dcmd_app_pkg_version` WRITE;
/*!40000 ALTER TABLE `dcmd_app_pkg_version` DISABLE KEYS */;
INSERT INTO `dcmd_app_pkg_version` VALUES (38,52,26,'admin','1.0.1','ae29221c31e2609926c235939cb61540','wKfslP1yN6ieXmHokWCdOVR7rZpDtIcFZnMHqVAw6eCdpliD0bvhWL4I8xQuk2YU46fH3UbAM9ygEq2Y85csDdXzJrOkiuVBGMKYWFkuLJZef0s4XR1D5mC78h2PjlzQ','2018-01-12 17:28:46'),(39,52,26,'sre_test','v2.0','fe019073b0fcb2921a96c59bad784bce','KhvQtl4WuMkjY67rJHPei8ygaEVoDsBUqZWfxOjRIKwe8aVb6MrpTNyUAlDPoBmu1TIhvZOnASmCX9zQcFfB6a5N2DdV87GMoklFDzgcYu8HyBhP4XUvQprxEKM5qaTG','2018-01-12 17:53:57');
/*!40000 ALTER TABLE `dcmd_app_pkg_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_reject_history`
--

DROP TABLE IF EXISTS `dcmd_app_reject_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_reject_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_pool` varchar(128) NOT NULL,
  `upload_type` varchar(16) NOT NULL,
  `upload_username` varchar(128) NOT NULL,
  `accept_username` varchar(128) NOT NULL,
  `version` varchar(128) NOT NULL,
  `src_path` varchar(256) NOT NULL,
  `pkg_file` varchar(256) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  `upload_host` varchar(16) NOT NULL,
  `is_reupload` int(1) NOT NULL,
  `is_accept` int(1) NOT NULL,
  `errmsg` varchar(255) NOT NULL,
  `upload_time` datetime NOT NULL,
  `accept_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`app_id`,`svr_id`,`svr_pool_id`),
  KEY `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_reject_history`
--

LOCK TABLES `dcmd_app_reject_history` WRITE;
/*!40000 ALTER TABLE `dcmd_app_reject_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_reject_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_res`
--

DROP TABLE IF EXISTS `dcmd_app_res`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_res` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `svr_id` int(11) NOT NULL,
  `svr_pool_id` int(11) NOT NULL,
  `res_type` varchar(32) NOT NULL,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(256) NOT NULL,
  `is_own` int(1) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res` (`app_id`,`svr_id`,`svr_pool_id`,`res_type`,`res_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_res`
--

LOCK TABLES `dcmd_app_res` WRITE;
/*!40000 ALTER TABLE `dcmd_app_res` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_res` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_svr_script`
--

DROP TABLE IF EXISTS `dcmd_app_svr_script`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_svr_script` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `upload_username` varchar(128) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`app_id`,`svr_id`,`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_svr_script`
--

LOCK TABLES `dcmd_app_svr_script` WRITE;
/*!40000 ALTER TABLE `dcmd_app_svr_script` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_svr_script` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_upload_error`
--

DROP TABLE IF EXISTS `dcmd_app_upload_error`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_upload_error` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(128) NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_pool` varchar(128) NOT NULL,
  `upload_type` varchar(16) NOT NULL,
  `upload_username` varchar(128) NOT NULL,
  `version` varchar(128) NOT NULL,
  `src_path` varchar(256) NOT NULL,
  `pkg_file` varchar(256) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  `upload_host` varchar(16) NOT NULL,
  `errmsg` varchar(256) NOT NULL,
  `is_reupload` int(1) NOT NULL,
  `upload_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_upload_error`
--

LOCK TABLES `dcmd_app_upload_error` WRITE;
/*!40000 ALTER TABLE `dcmd_app_upload_error` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_upload_error` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_upload_history`
--

DROP TABLE IF EXISTS `dcmd_app_upload_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_upload_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_pool` varchar(128) NOT NULL,
  `upload_type` varchar(16) NOT NULL,
  `upload_username` varchar(128) NOT NULL,
  `accept_username` varchar(128) NOT NULL,
  `version` varchar(128) NOT NULL,
  `src_path` varchar(256) NOT NULL,
  `pkg_file` varchar(256) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `passwd` varchar(128) NOT NULL,
  `upload_host` varchar(16) NOT NULL,
  `is_reupload` int(1) NOT NULL,
  `is_accept` int(1) NOT NULL,
  `upload_time` datetime NOT NULL,
  `accept_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`app_id`,`svr_id`,`svr_pool_id`),
  KEY `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_upload_history`
--

LOCK TABLES `dcmd_app_upload_history` WRITE;
/*!40000 ALTER TABLE `dcmd_app_upload_history` DISABLE KEYS */;
INSERT INTO `dcmd_app_upload_history` VALUES (38,52,'search',26,'search_ui',0,'','pkg','sre_test','admin','1.0.1','/home/apollo/apollo','pkg_search_search_ui_1.0.1_ae29221c31e26099.tar.gz','ae29221c31e2609926c235939cb61540','wKfslP1yN6ieXmHokWCdOVR7rZpDtIcFZnMHqVAw6eCdpliD0bvhWL4I8xQuk2YU46fH3UbAM9ygEq2Y85csDdXzJrOkiuVBGMKYWFkuLJZef0s4XR1D5mC78h2PjlzQ','10.163.9.231',0,1,'2018-01-12 17:27:25','2018-01-12 17:28:45'),(39,52,'search',26,'search_ui',14,'rel_aliyun_01','conf','sre_test','admin','1.0.1','/home/apollo/apollo/log','conf_search_search_ui_rel_aliyun_01_1.0.1_efd6d47f4c4ab88a.tar.gz','efd6d47f4c4ab88a26ecdfa5c6eec3d0','doiSarRC5J7qQXbWA4IMsDZKtfxBGp1n8Zdny1tRHfgIDJEbLelCavkSVXWNOu5MTB4Dn9jwuq5mQkhZdbzMUXFs8fJ032LYbUxC4WTRBVGDOd1en95L7qSQoFN2ca0E','10.163.9.231',0,1,'2018-01-12 17:29:29','2018-01-12 17:29:41'),(40,52,'search',26,'search_ui',14,'rel_aliyun_01','conf','sre_test','admin','conf1.0.1','/home/apollo/apollo/log','conf_search_search_ui_rel_aliyun_01_conf1.0.1_18e77884c4ca2596.tar.gz','18e77884c4ca2596225e8f1a51f8094e','l0fmSq8oh3ieF56kUXHxzOZuQGYptnK2FIRZgBcD0TGsq4HNQjiyJk1PudWUeL7zmGzRr9t1F3L5NeVbKcoJgW0hjiOaTvAH6Kyt9YjB2I7u1zfaVWNkC8wPrvJOEAix','10.163.9.231',0,1,'2018-01-12 17:29:51','2018-01-12 17:30:01'),(41,52,'search',26,'search_ui',0,'','pkg','sre_test','sre_test','v2.0','/home/apollo/apollo','pkg_search_search_ui_v2.0_fe019073b0fcb292.tar.gz','fe019073b0fcb2921a96c59bad784bce','KhvQtl4WuMkjY67rJHPei8ygaEVoDsBUqZWfxOjRIKwe8aVb6MrpTNyUAlDPoBmu1TIhvZOnASmCX9zQcFfB6a5N2DdV87GMoklFDzgcYu8HyBhP4XUvQprxEKM5qaTG','10.163.9.231',0,1,'2018-01-12 17:53:42','2018-01-12 17:53:56');
/*!40000 ALTER TABLE `dcmd_app_upload_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_upload_script`
--

DROP TABLE IF EXISTS `dcmd_app_upload_script`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_upload_script` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `upload_username` varchar(128) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `content` text NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`app_id`,`svr_id`,`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_upload_script`
--

LOCK TABLES `dcmd_app_upload_script` WRITE;
/*!40000 ALTER TABLE `dcmd_app_upload_script` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_upload_script` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_app_upload_sync_error`
--

DROP TABLE IF EXISTS `dcmd_app_upload_sync_error`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_app_upload_sync_error` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_name` varchar(128) NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_pool` varchar(128) NOT NULL,
  `upload_type` varchar(16) NOT NULL,
  `version` varchar(128) NOT NULL,
  `pkg_file` varchar(256) NOT NULL,
  `md5` varchar(64) NOT NULL,
  `upload_host` varchar(16) NOT NULL,
  `sync_host` varchar(16) NOT NULL,
  `errmsg` varchar(256) NOT NULL,
  `sync_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_app_upload_sync_error`
--

LOCK TABLES `dcmd_app_upload_sync_error` WRITE;
/*!40000 ALTER TABLE `dcmd_app_upload_sync_error` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_app_upload_sync_error` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_audit`
--

DROP TABLE IF EXISTS `dcmd_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `nid` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `action` varchar(64) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_audit`
--

LOCK TABLES `dcmd_audit` WRITE;
/*!40000 ALTER TABLE `dcmd_audit` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_audit_log`
--

DROP TABLE IF EXISTS `dcmd_audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_audit_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `nid` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `action` varchar(64) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  `audit_time` datetime NOT NULL,
  `audit_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_audit_log`
--

LOCK TABLES `dcmd_audit_log` WRITE;
/*!40000 ALTER TABLE `dcmd_audit_log` DISABLE KEYS */;
INSERT INTO `dcmd_audit_log` VALUES (1000,14,26,56,52,'112.126.77.12','add','2018-01-12 16:18:28',18,'2018-01-12 16:18:28',18);
/*!40000 ALTER TABLE `dcmd_audit_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_center`
--

DROP TABLE IF EXISTS `dcmd_center`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_center` (
  `host` varchar(32) NOT NULL,
  `master` int(11) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_center`
--

LOCK TABLES `dcmd_center` WRITE;
/*!40000 ALTER TABLE `dcmd_center` DISABLE KEYS */;
INSERT INTO `dcmd_center` VALUES ('112.126.77.12:6667',1,'2019-01-08 14:01:27');
/*!40000 ALTER TABLE `dcmd_center` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_command`
--

DROP TABLE IF EXISTS `dcmd_command`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_command` (
  `cmd_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `subtask_id` bigint(20) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cmd_type` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cmd_id`),
  KEY `idx_command_svr` (`svr_name`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_command`
--

LOCK TABLES `dcmd_command` WRITE;
/*!40000 ALTER TABLE `dcmd_command` DISABLE KEYS */;
INSERT INTO `dcmd_command` VALUES (22,63,0,'',0,'search_ui','',1,1,'','2018-10-26 16:41:45','2018-10-26 16:41:45',1),(23,63,52,'rel_aliyun_01',14,'search_ui','112.126.77.12',9,2,'Failure to download package, cmd:rsync  -vzrtopg --progress 10.163.9.231::search/search_ui/pkg/pkg_search_search_ui_v2.0_fe019073b0fcb292.tar.gz /var/app/search/running/search_ui/rel_aliyun_01/.install/tmp/pkg_search_search_ui_v2.0_fe019073b0fcb292.tar.gz','2018-10-26 16:41:46','2018-10-26 16:41:46',0),(28,63,0,'',0,'search_ui','',14,1,'','2018-10-31 14:22:48','2018-10-31 14:22:48',1),(29,63,0,'',0,'search_ui','',15,1,'','2018-10-31 14:22:52','2018-10-31 14:22:52',1),(31,64,0,'',0,'search_ui','',1,1,'','2018-10-31 14:24:18','2018-10-31 14:24:18',1),(32,64,53,'rel_aliyun_01',14,'search_ui','112.126.77.12',9,1,'','2018-10-31 14:24:19','2018-10-31 14:24:19',0),(33,64,53,'rel_aliyun_01',14,'search_ui','112.126.77.12',9,1,'','2018-10-31 18:02:33','2018-10-31 18:02:33',1);
/*!40000 ALTER TABLE `dcmd_command` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_command_history`
--

DROP TABLE IF EXISTS `dcmd_command_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_command_history` (
  `cmd_id` bigint(20) NOT NULL,
  `task_id` int(10) unsigned NOT NULL,
  `subtask_id` bigint(20) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cmd_type` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  KEY `cmd_id` (`cmd_id`),
  KEY `task_id` (`task_id`),
  KEY `task_cmd_id` (`task_id`,`cmd_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_command_history`
--

LOCK TABLES `dcmd_command_history` WRITE;
/*!40000 ALTER TABLE `dcmd_command_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_command_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_company`
--

DROP TABLE IF EXISTS `dcmd_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_company` (
  `comp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comp_name` varchar(64) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`comp_id`),
  UNIQUE KEY `comp_name` (`comp_name`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_company`
--

LOCK TABLES `dcmd_company` WRITE;
/*!40000 ALTER TABLE `dcmd_company` DISABLE KEYS */;
INSERT INTO `dcmd_company` VALUES (52,'ducter','ducter','2018-01-12 15:15:50','2018-01-12 15:15:50',0);
/*!40000 ALTER TABLE `dcmd_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_cron`
--

DROP TABLE IF EXISTS `dcmd_cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_cron` (
  `cron_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_name` varchar(64) NOT NULL,
  `script_name` varchar(64) NOT NULL,
  `script_md5` varchar(32) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `min` varchar(32) NOT NULL,
  `hour` varchar(32) NOT NULL,
  `day` varchar(32) NOT NULL,
  `month` varchar(32) NOT NULL,
  `week` varchar(32) NOT NULL,
  `arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`cron_id`),
  KEY `cron_name` (`cron_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_cron`
--

LOCK TABLES `dcmd_cron` WRITE;
/*!40000 ALTER TABLE `dcmd_cron` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_cron` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_cron_event`
--

DROP TABLE IF EXISTS `dcmd_cron_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_cron_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `level` int(10) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cron_ip` (`ip`,`cron_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_cron_event`
--

LOCK TABLES `dcmd_cron_event` WRITE;
/*!40000 ALTER TABLE `dcmd_cron_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_cron_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_cron_node`
--

DROP TABLE IF EXISTS `dcmd_cron_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_cron_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `cron_state` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cron_ip` (`cron_id`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_cron_node`
--

LOCK TABLES `dcmd_cron_node` WRITE;
/*!40000 ALTER TABLE `dcmd_cron_node` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_cron_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_dc_info`
--

DROP TABLE IF EXISTS `dcmd_dc_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_dc_info` (
  `dc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(64) NOT NULL,
  `area` varchar(64) NOT NULL,
  `dc` varchar(64) NOT NULL,
  PRIMARY KEY (`dc_id`),
  UNIQUE KEY `name` (`country`,`area`,`dc`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_dc_info`
--

LOCK TABLES `dcmd_dc_info` WRITE;
/*!40000 ALTER TABLE `dcmd_dc_info` DISABLE KEYS */;
INSERT INTO `dcmd_dc_info` VALUES (38,'CN','SC','MYIDC'),(39,'中国','北京','北京酒仙桥'),(40,'中国','北京','顺义机房');
/*!40000 ALTER TABLE `dcmd_dc_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_department`
--

DROP TABLE IF EXISTS `dcmd_department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_department` (
  `depart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `depart_name` varchar(64) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`depart_id`),
  UNIQUE KEY `depart_name` (`depart_name`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_department`
--

LOCK TABLES `dcmd_department` WRITE;
/*!40000 ALTER TABLE `dcmd_department` DISABLE KEYS */;
INSERT INTO `dcmd_department` VALUES (52,'运维',52,'','2018-01-12 15:16:52','2018-01-12 15:16:52',1),(53,'研发一部',52,'','2018-01-12 15:17:06','2018-01-12 15:17:06',1);
/*!40000 ALTER TABLE `dcmd_department` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_group`
--

DROP TABLE IF EXISTS `dcmd_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_group` (
  `gid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comp_id` int(10) unsigned NOT NULL,
  `gname` varchar(64) NOT NULL,
  `gtype` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`gid`),
  UNIQUE KEY `gname` (`gname`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_group`
--

LOCK TABLES `dcmd_group` WRITE;
/*!40000 ALTER TABLE `dcmd_group` DISABLE KEYS */;
INSERT INTO `dcmd_group` VALUES (52,52,'业务组--搜索产品组',2,'搜索产品组','2018-01-12 15:17:54','2018-01-12 15:17:54',1),(53,52,'系统组--系统运维一组',1,'系统组--系统运维一组','2018-01-12 15:18:23','2018-01-12 15:18:23',1);
/*!40000 ALTER TABLE `dcmd_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_group_cmd`
--

DROP TABLE IF EXISTS `dcmd_group_cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_group_cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`gid`,`opr_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_group_cmd`
--

LOCK TABLES `dcmd_group_cmd` WRITE;
/*!40000 ALTER TABLE `dcmd_group_cmd` DISABLE KEYS */;
INSERT INTO `dcmd_group_cmd` VALUES (38,52,1,'2018-01-12 16:33:15','2018-01-12 16:33:15',1),(39,52,2,'2018-01-12 16:33:15','2018-01-12 16:33:15',1),(40,52,3,'2018-01-12 16:33:15','2018-01-12 16:33:15',1),(41,52,4,'2018-01-12 16:33:15','2018-01-12 16:33:15',1),(42,52,5,'2018-01-12 16:33:15','2018-01-12 16:33:15',1);
/*!40000 ALTER TABLE `dcmd_group_cmd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_group_repeat_cmd`
--

DROP TABLE IF EXISTS `dcmd_group_repeat_cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_group_repeat_cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `repeat_cmd_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`gid`,`repeat_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_group_repeat_cmd`
--

LOCK TABLES `dcmd_group_repeat_cmd` WRITE;
/*!40000 ALTER TABLE `dcmd_group_repeat_cmd` DISABLE KEYS */;
INSERT INTO `dcmd_group_repeat_cmd` VALUES (38,52,5,'2018-01-12 16:33:20','2018-01-12 16:33:20',1),(39,52,4,'2018-01-12 16:33:20','2018-01-12 16:33:20',1);
/*!40000 ALTER TABLE `dcmd_group_repeat_cmd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_node`
--

DROP TABLE IF EXISTS `dcmd_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_node` (
  `nid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `ngroup_id` int(10) NOT NULL,
  `host` varchar(128) NOT NULL,
  `sid` varchar(128) NOT NULL,
  `did` varchar(128) NOT NULL,
  `bend_ip` varchar(16) NOT NULL,
  `public_ip` varchar(16) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`nid`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_node`
--

LOCK TABLES `dcmd_node` WRITE;
/*!40000 ALTER TABLE `dcmd_node` DISABLE KEYS */;
INSERT INTO `dcmd_node` VALUES (56,'112.126.77.12',22,'','112.126.77.12 ','112.126.77.12 ','0.0.0.0','0.0.0.0',NULL,'2018-01-12 15:21:34','2018-01-12 15:21:34',18),(57,'192.168.0.1',24,'','','','','','','2018-05-31 08:54:02','2018-05-31 08:53:42',1);
/*!40000 ALTER TABLE `dcmd_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_node_group`
--

DROP TABLE IF EXISTS `dcmd_node_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_node_group` (
  `ngroup_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngroup_name` varchar(128) NOT NULL,
  `ngroup_alias` varchar(128) NOT NULL,
  `gid` int(10) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ngroup_id`),
  UNIQUE KEY `ngroup_name` (`ngroup_name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_node_group`
--

LOCK TABLES `dcmd_node_group` WRITE;
/*!40000 ALTER TABLE `dcmd_node_group` DISABLE KEYS */;
INSERT INTO `dcmd_node_group` VALUES (22,'ducter_搜索','ducter_搜索',53,'ducter_搜索','2018-01-12 15:20:34','2018-01-12 15:20:34',18),(23,'chizi','my',53,'','2018-01-12 18:12:21','2018-01-12 18:12:21',18),(24,'hzq001','hzq001',53,'','2018-05-31 08:53:10','2018-05-31 08:53:10',1),(25,'mysql集群','mysql集群',53,'','2018-08-03 10:44:58','2018-08-03 10:44:58',1);
/*!40000 ALTER TABLE `dcmd_node_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_node_group_attr`
--

DROP TABLE IF EXISTS `dcmd_node_group_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_node_group_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngroup_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(32) NOT NULL,
  `attr_value` varchar(256) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_node_group_attr` (`ngroup_id`,`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_node_group_attr`
--

LOCK TABLES `dcmd_node_group_attr` WRITE;
/*!40000 ALTER TABLE `dcmd_node_group_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_node_group_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_node_group_attr_def`
--

DROP TABLE IF EXISTS `dcmd_node_group_attr_def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_node_group_attr_def` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(32) NOT NULL,
  `optional` int(11) NOT NULL,
  `attr_type` int(10) NOT NULL,
  `def_value` varchar(256) DEFAULT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attr_id`),
  UNIQUE KEY `dcmd_node_group_attr_def` (`attr_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_node_group_attr_def`
--

LOCK TABLES `dcmd_node_group_attr_def` WRITE;
/*!40000 ALTER TABLE `dcmd_node_group_attr_def` DISABLE KEYS */;
INSERT INTO `dcmd_node_group_attr_def` VALUES (1,'cpu',1,1,'1','','2018-08-03 16:46:37',1);
/*!40000 ALTER TABLE `dcmd_node_group_attr_def` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_obj_change_event`
--

DROP TABLE IF EXISTS `dcmd_obj_change_event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_obj_change_event` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `obj_type` varchar(32) NOT NULL,
  `change_type` int(1) NOT NULL,
  `nid` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `sid` varchar(128) NOT NULL,
  `app_id` int(11) NOT NULL,
  `svr_id` int(11) NOT NULL,
  `svr_pool_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `data` varchar(4096) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`obj_type`,`change_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_obj_change_event`
--

LOCK TABLES `dcmd_obj_change_event` WRITE;
/*!40000 ALTER TABLE `dcmd_obj_change_event` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_obj_change_event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd` (
  `opr_cmd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opr_cmd` varchar(64) NOT NULL,
  `ui_name` varchar(255) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `script_md5` varchar(32) NOT NULL DEFAULT '',
  `timeout` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`opr_cmd_id`),
  UNIQUE KEY `opr_cmd` (`opr_cmd`),
  UNIQUE KEY `ui_name` (`ui_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd`
--

LOCK TABLES `dcmd_opr_cmd` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd` DISABLE KEYS */;
INSERT INTO `dcmd_opr_cmd` VALUES (1,'host_user','get_host_user','root','f4b5024642b198e66b8d27acec0bd09b',20,'閼惧嘲褰囨稉缁樻簚閻€劍鍩�','2018-01-12 18:44:15','2014-02-20 15:55:05',18),(2,'os_info','os_info','root','78cd5d54bca0b4ee2c4c17a7c08f6759',10,'閼惧嘲褰囨稉缁樻簚娣団剝浼�','2014-02-20 15:55:05','2014-02-20 15:55:05',1),(3,'proc_info','proc_info','root','9a11445918e661dbb950ed0718f96ab2',10,'閼惧嘲褰囨潻娑氣柤娣団剝浼�','2014-02-20 15:55:05','2014-02-20 15:55:05',1),(4,'ps','ps','root','522c8c25b28740b2aa2928672f4cd985',10,'ps','2014-02-20 15:55:05','2014-02-20 15:55:05',1),(5,'test_opr_env','test_opr_env','root','fe39937172b36ca82bcc9a824f1e259e',10,'濞村鐦悳顖氼暔閸欐﹢鍣�','2014-02-20 15:55:05','2014-02-20 15:55:05',1),(6,'df','df','root','',10,'','2018-05-23 11:36:18','2018-05-23 11:36:18',1);
/*!40000 ALTER TABLE `dcmd_opr_cmd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_arg`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_arg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_arg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `arg_name` varchar(32) NOT NULL,
  `optional` int(11) NOT NULL,
  `arg_type` int(10) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `opr_cmd_id` (`opr_cmd_id`,`arg_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_arg`
--

LOCK TABLES `dcmd_opr_cmd_arg` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_arg` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_opr_cmd_arg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_exec`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_exec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_exec` (
  `exec_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`exec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_exec`
--

LOCK TABLES `dcmd_opr_cmd_exec` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_exec` DISABLE KEYS */;
INSERT INTO `dcmd_opr_cmd_exec` VALUES (6,4,'ps','root',10,'10.0.6.200','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:36:33','2018-01-12 18:36:33',18),(8,4,'ps','root',10,'127.0.0.1','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:54:40','2018-01-16 13:54:40',18),(11,6,'df','root',10,'112.126.77.12','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-23 11:39:17','2018-05-23 11:39:17',1);
/*!40000 ALTER TABLE `dcmd_opr_cmd_exec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_exec_history`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_exec_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_exec_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `exec_id` bigint(20) NOT NULL,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_exec_id` (`exec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=430 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_exec_history`
--

LOCK TABLES `dcmd_opr_cmd_exec_history` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_exec_history` DISABLE KEYS */;
INSERT INTO `dcmd_opr_cmd_exec_history` VALUES (424,4,1,'host_user','root',20,'112.126.77.12','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:12:03','2018-01-12 18:12:03',18),(425,5,1,'host_user','root',20,'127.0.0.1','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:14:44','2018-01-12 18:14:44',18),(426,7,1,'host_user','root',20,'127.0.0.1','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:54:21','2018-01-16 13:54:21',18),(427,9,1,'host_user','root',20,'112.126.77.12','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-21 11:21:48','2018-05-21 11:21:48',1),(428,10,2,'os_info','root',10,'112.126.77.12','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-21 11:22:13','2018-05-21 11:22:13',1),(429,12,1,'host_user','root',20,'112.126.77.12','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-23 11:39:50','2018-05-23 11:39:50',1);
/*!40000 ALTER TABLE `dcmd_opr_cmd_exec_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_repeat_exec`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_repeat_exec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_repeat_exec` (
  `repeat_cmd_id` int(10) NOT NULL AUTO_INCREMENT,
  `repeat_cmd_name` varchar(64) NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `repeat` int(10) NOT NULL,
  `cache_time` int(10) NOT NULL,
  `ip_mutable` int(10) NOT NULL,
  `arg_mutable` int(10) NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`repeat_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_repeat_exec`
--

LOCK TABLES `dcmd_opr_cmd_repeat_exec` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_repeat_exec` DISABLE KEYS */;
INSERT INTO `dcmd_opr_cmd_repeat_exec` VALUES (4,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 16:11:25','2018-01-12 16:11:25',18),(5,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 16:12:09','2018-01-12 16:12:09',18);
/*!40000 ALTER TABLE `dcmd_opr_cmd_repeat_exec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_cmd_repeat_exec_history`
--

DROP TABLE IF EXISTS `dcmd_opr_cmd_repeat_exec_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_cmd_repeat_exec_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `repeat_cmd_name` varchar(64) NOT NULL,
  `opr_cmd` varchar(64) NOT NULL,
  `run_user` varchar(64) NOT NULL,
  `timeout` int(11) NOT NULL,
  `ip` text NOT NULL,
  `repeat` int(10) NOT NULL,
  `cache_time` int(10) NOT NULL,
  `ip_mutable` int(10) NOT NULL,
  `arg_mutable` int(10) NOT NULL,
  `arg` text,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_cmd_repeat_exec_history`
--

LOCK TABLES `dcmd_opr_cmd_repeat_exec_history` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_cmd_repeat_exec_history` DISABLE KEYS */;
INSERT INTO `dcmd_opr_cmd_repeat_exec_history` VALUES (4,'os_info','os_info','root',1,'1.1.1.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 16:11:40','2018-01-12 16:11:25',18),(5,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 16:12:15','2018-01-12 16:12:09',18),(6,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 16:12:21','2018-01-12 16:12:09',18),(7,'os_info','os_info','root',1,'1.1.1.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:10:21','2018-01-12 16:11:25',18),(8,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:10:28','2018-01-12 16:12:09',18),(9,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:10:44','2018-01-12 16:12:09',18),(10,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:45:10','2018-01-12 16:12:09',18),(11,'os_info','os_info','root',1,'1.1.1.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:46:31','2018-01-12 16:11:25',18),(12,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-12 18:46:40','2018-01-12 16:12:09',18),(13,'os_info','os_info','root',1,'1.1.1.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:35:37','2018-01-12 16:11:25',18),(14,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:35:40','2018-01-12 16:12:09',18),(15,'os_info','os_info','root',1,'1.1.1.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:38:31','2018-01-12 16:11:25',18),(16,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:38:42','2018-01-12 16:12:09',18),(17,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:53:20','2018-01-12 16:12:09',18),(18,'os_info','os_info','root',1,'1.1.1.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:53:36','2018-01-12 16:11:25',18),(19,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 13:53:56','2018-01-12 16:11:25',18),(20,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-01-16 14:15:08','2018-01-12 16:12:09',18),(21,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-03-12 10:58:21','2018-01-12 16:11:25',18),(22,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-03-12 10:58:30','2018-01-12 16:12:09',18),(23,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-03-12 10:59:12','2018-01-12 16:11:25',18),(24,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-03-12 10:59:26','2018-01-12 16:12:09',18),(25,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-21 11:23:21','2018-01-12 16:12:09',18),(26,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-23 11:34:13','2018-01-12 16:11:25',18),(27,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-23 11:34:13','2018-01-12 16:12:09',18),(28,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-29 18:34:59','2018-01-12 16:11:25',18),(29,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-31 08:53:52','2018-01-12 16:11:25',18),(30,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-05-31 08:54:08','2018-01-12 16:12:09',18),(31,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-06-08 11:43:22','2018-01-12 16:11:25',18),(32,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-06-08 11:43:22','2018-01-12 16:12:09',18),(33,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-06-08 11:43:24','2018-01-12 16:11:25',18),(34,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-06-20 10:04:50','2018-01-12 16:11:25',18),(35,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-06-20 10:05:04','2018-01-12 16:12:09',18),(36,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-06-20 10:05:06','2018-01-12 16:11:25',18),(37,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-07-24 14:11:04','2018-01-12 16:11:25',18),(38,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-07-24 14:11:05','2018-01-12 16:12:09',18),(39,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-08-01 16:03:12','2018-01-12 16:11:25',18),(40,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-08-01 16:03:12','2018-01-12 16:12:09',18),(41,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-12-08 10:14:10','2018-01-12 16:11:25',18),(42,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-12-08 10:14:11','2018-01-12 16:12:09',18),(43,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-12-21 11:30:15','2018-01-12 16:11:25',18),(44,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-12-21 11:30:16','2018-01-12 16:12:09',18),(45,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-12-21 11:30:18','2018-01-12 16:11:25',18),(46,'get_host_user','host_user','root',1,'0.0.0.0',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-12-21 11:30:39','2018-01-12 16:12:09',18),(47,'os_info','os_info','root',1,'127.0.0.1',1,10,1,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','2018-12-21 11:30:40','2018-01-12 16:11:25',18);
/*!40000 ALTER TABLE `dcmd_opr_cmd_repeat_exec_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_opr_log`
--

DROP TABLE IF EXISTS `dcmd_opr_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_opr_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_table` varchar(64) NOT NULL,
  `opr_type` int(11) NOT NULL,
  `sql_statement` text NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_opr_log_table` (`log_table`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_opr_log`
--

LOCK TABLES `dcmd_opr_log` WRITE;
/*!40000 ALTER TABLE `dcmd_opr_log` DISABLE KEYS */;
INSERT INTO `dcmd_opr_log` VALUES (1,'dcmd_department',1,'insert department:运维','2018-01-12 15:16:52',1),(2,'dcmd_department',1,'insert department:研发一部','2018-01-12 15:17:07',1),(3,'dcmd_group',1,'insert group:业务组--搜索产品组','2018-01-12 15:17:54',1),(4,'dcmd_group',1,'insert group:系统组--系统运维一组','2018-01-12 15:18:23',1),(5,'dcmd_user_group',1,'add user:1 group:53','2018-01-12 15:18:33',1),(6,'dcmd_user',1,'add user:sre_test','2018-01-12 15:19:15',1),(7,'dcmd_user_group',1,'add user:18 group:53','2018-01-12 15:19:51',1),(8,'dcmd_node',1,'insert node:112.126.77.12','2018-01-12 15:21:34',18),(9,'dcmd_opr_cmd_repeat_exec',1,'insert repeat exec cmd:os_info','2018-01-12 16:11:25',18),(10,'dcmd_opr_cmd_repeat_exec',1,'insert repeat exec cmd:get_host_user','2018-01-12 16:12:09',18),(11,'dcmd_app',1,'insert app:search','2018-01-12 16:14:35',18),(12,'dcmd_service',1,'insert service:search_ui','2018-01-12 16:15:56',18),(13,'dcmd_service',1,'insert service pool group:aliyun','2018-01-12 16:16:49',18),(14,'dcmd_service_pool',1,'insert service pool:rel_aliyun_01','2018-01-12 16:17:49',18),(15,'dcmd_app',1,'insert node group:52','2018-01-12 16:18:06',18),(16,'dcmd_service_pool_node',1,'add ip:112.126.77.12','2018-01-12 16:18:23',18),(17,'dcmd_service_pool_node',1,'add ip:112.126.77.12','2018-01-12 16:18:28',18),(18,'dcmd_app',1,'insert task template:search_发布','2018-01-12 16:19:50',18),(19,'dcmd_service_pool_node',1,'pass script:5226','2018-01-12 16:31:21',1),(20,'dcmd_user',1,'add user:svr_test','2018-01-12 16:32:54',1),(21,'dcmd_user_group',1,'add user:19 group:52','2018-01-12 16:33:10',1),(22,'dcmd_service_pool',2,'update service pool:rel_aliyun_01','2018-01-12 17:30:16',1),(23,'dcmd_task',1,'insert task:install_by_rsync-20180112173027','2018-01-12 17:30:35',1),(24,'dcmd_task',1,'insert task:install_by_rsync-20180112173114','2018-01-12 17:31:19',1),(25,'dcmd_service_pool_node',1,'pass script:5226','2018-01-12 17:42:00',1),(26,'dcmd_service_pool_node',1,'pass script:5226','2018-01-12 17:42:31',1),(27,'dcmd_task',1,'insert task:install_by_rsync-20180112174255','2018-01-12 17:43:01',1),(28,'dcmd_task',1,'insert task:install_by_rsync-20180112175406','2018-01-12 17:54:11',18),(29,'dcmd_opr_cmd',2,'modify opr_cmd:host_user','2018-01-12 18:44:15',18),(30,'dcmd_opr_cmd_repeat_exec',2,'update repeat exec cmd:os_info','2018-01-16 13:53:50',18),(31,'dcmd_app',1,'insert task template:sdd','2018-05-17 14:28:47',1),(32,'dcmd_task',1,'insert task:test_process-20180517142856','2018-05-17 14:29:00',1),(33,'dcmd_opr_cmd',1,'insert opr_cmd:df','2018-05-23 11:36:18',1),(34,'dcmd_node',1,'insert ip:192.168.0.1','2018-05-31 08:53:42',1),(35,'dcmd_node',2,'update node:192.168.0.1','2018-05-31 08:54:02',1),(36,'dcmd_task',1,'insert task:install_by_rsync-20180926204142','2018-09-26 20:41:45',1),(37,'dcmd_task',1,'insert task:install_by_rsync-20181026164102','2018-10-26 16:41:33',1),(38,'dcmd_task',1,'insert task:install_by_rsync-20181031133344','2018-10-31 13:33:57',1),(39,'dcmd_task_history',3,'delete history task:install_by_rsync-20180926204142','2018-10-31 14:22:18',1),(40,'dcmd_task_history',3,'delete history task:install_by_rsync-20180112173027','2018-10-31 14:22:20',1),(41,'dcmd_task_history',3,'delete history task:test_process-20180517142856','2018-10-31 14:23:00',1),(42,'dcmd_task_history',3,'delete history task:install_by_rsync-20180112174255','2018-10-31 14:23:00',1),(43,'dcmd_task_history',3,'delete history task:install_by_rsync-20180112173114','2018-10-31 14:23:00',1),(44,'dcmd_task_history',3,'delete history task:install_by_rsync-20180112175406','2018-10-31 14:23:29',1);
/*!40000 ALTER TABLE `dcmd_opr_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_cbase`
--

DROP TABLE IF EXISTS `dcmd_res_cbase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_cbase` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `cluster` varchar(64) NOT NULL,
  `bucket` varchar(128) NOT NULL,
  `quota` int(10) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `bucket` (`cluster`,`bucket`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_cbase`
--

LOCK TABLES `dcmd_res_cbase` WRITE;
/*!40000 ALTER TABLE `dcmd_res_cbase` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_cbase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_column`
--

DROP TABLE IF EXISTS `dcmd_res_column`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_column` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_type` varchar(32) NOT NULL,
  `res_table` varchar(64) NOT NULL,
  `colum_name` varchar(64) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `display_order` int(10) unsigned NOT NULL,
  `display_list` tinyint(1) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_type_column` (`res_type`,`colum_name`),
  UNIQUE KEY `res_type_display` (`res_type`,`display_name`),
  UNIQUE KEY `res_table_column` (`res_table`,`colum_name`),
  UNIQUE KEY `res_table_display` (`res_table`,`display_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_column`
--

LOCK TABLES `dcmd_res_column` WRITE;
/*!40000 ALTER TABLE `dcmd_res_column` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_column` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_dns`
--

DROP TABLE IF EXISTS `dcmd_res_dns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_dns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `I2` varchar(64) NOT NULL,
  `I3` varchar(64) NOT NULL,
  `I4` varchar(64) NOT NULL,
  `I5` varchar(64) NOT NULL,
  `tag` varchar(64) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_dns`
--

LOCK TABLES `dcmd_res_dns` WRITE;
/*!40000 ALTER TABLE `dcmd_res_dns` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_dns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_gluster`
--

DROP TABLE IF EXISTS `dcmd_res_gluster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_gluster` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `cluster` varchar(64) NOT NULL,
  `volume` varchar(128) NOT NULL,
  `quota` int(10) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `volume` (`cluster`,`volume`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_gluster`
--

LOCK TABLES `dcmd_res_gluster` WRITE;
/*!40000 ALTER TABLE `dcmd_res_gluster` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_gluster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_lvs`
--

DROP TABLE IF EXISTS `dcmd_res_lvs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_lvs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `vip` varchar(64) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `vip` (`vip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_lvs`
--

LOCK TABLES `dcmd_res_lvs` WRITE;
/*!40000 ALTER TABLE `dcmd_res_lvs` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_lvs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_mcluster`
--

DROP TABLE IF EXISTS `dcmd_res_mcluster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_mcluster` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `cluster` varchar(64) NOT NULL,
  `port` int(5) NOT NULL,
  `db` varchar(128) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `cluster` (`cluster`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_mcluster`
--

LOCK TABLES `dcmd_res_mcluster` WRITE;
/*!40000 ALTER TABLE `dcmd_res_mcluster` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_mcluster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_mongo`
--

DROP TABLE IF EXISTS `dcmd_res_mongo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_mongo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `cluster` varchar(64) NOT NULL,
  `instance` varchar(128) NOT NULL,
  `port` int(5) NOT NULL,
  `instance_num` int(5) NOT NULL,
  `backup_cluster` varchar(64) NOT NULL,
  `backup_port` int(5) NOT NULL,
  `backup_instance_num` int(5) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `instance` (`cluster`,`instance`,`port`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_mongo`
--

LOCK TABLES `dcmd_res_mongo` WRITE;
/*!40000 ALTER TABLE `dcmd_res_mongo` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_mongo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_mq`
--

DROP TABLE IF EXISTS `dcmd_res_mq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_mq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `cluster` varchar(64) NOT NULL,
  `queue` varchar(128) NOT NULL,
  `quota` int(10) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `queue` (`cluster`,`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_mq`
--

LOCK TABLES `dcmd_res_mq` WRITE;
/*!40000 ALTER TABLE `dcmd_res_mq` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_mq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_mysql`
--

DROP TABLE IF EXISTS `dcmd_res_mysql`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_mysql` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `server` varchar(128) NOT NULL,
  `port` varchar(64) NOT NULL,
  `db` varchar(128) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_mysql`
--

LOCK TABLES `dcmd_res_mysql` WRITE;
/*!40000 ALTER TABLE `dcmd_res_mysql` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_mysql` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_oracle`
--

DROP TABLE IF EXISTS `dcmd_res_oracle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_oracle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `cluster` varchar(64) NOT NULL,
  `cluster_ip` varchar(64) NOT NULL,
  `schema_name` varchar(64) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `cluster_ip` (`cluster`,`cluster_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_oracle`
--

LOCK TABLES `dcmd_res_oracle` WRITE;
/*!40000 ALTER TABLE `dcmd_res_oracle` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_oracle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_redis`
--

DROP TABLE IF EXISTS `dcmd_res_redis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_redis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `cluster` varchar(64) NOT NULL,
  `instance` varchar(128) NOT NULL,
  `quota` int(10) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `instance` (`cluster`,`instance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_redis`
--

LOCK TABLES `dcmd_res_redis` WRITE;
/*!40000 ALTER TABLE `dcmd_res_redis` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_redis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_slb`
--

DROP TABLE IF EXISTS `dcmd_res_slb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_slb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int(11) NOT NULL,
  `is_public` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `I2` varchar(64) NOT NULL,
  `I3` varchar(64) NOT NULL,
  `I4` varchar(64) NOT NULL,
  `I5` varchar(64) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY `res_name` (`res_name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_slb`
--

LOCK TABLES `dcmd_res_slb` WRITE;
/*!40000 ALTER TABLE `dcmd_res_slb` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_slb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_res_type`
--

DROP TABLE IF EXISTS `dcmd_res_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_res_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_type` varchar(32) NOT NULL,
  `res_table` varchar(64) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_type` (`res_type`),
  UNIQUE KEY `res_table` (`res_table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_res_type`
--

LOCK TABLES `dcmd_res_type` WRITE;
/*!40000 ALTER TABLE `dcmd_res_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_res_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service`
--

DROP TABLE IF EXISTS `dcmd_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service` (
  `svr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_name` varchar(128) NOT NULL,
  `svr_alias` varchar(128) NOT NULL,
  `service_tree` varchar(256) NOT NULL,
  `res_id` varchar(32) NOT NULL,
  `svr_path` varchar(128) NOT NULL,
  `run_user` varchar(16) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `script_md5` varchar(64) NOT NULL DEFAULT '',
  `node_multi_pool` int(10) unsigned NOT NULL,
  `owner` int(10) NOT NULL,
  `svr_mem` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_cpu` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_net` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_io` int(11) unsigned NOT NULL DEFAULT '0',
  `image_name` varchar(256) NOT NULL DEFAULT '',
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`svr_id`),
  UNIQUE KEY `svr_name` (`app_id`,`svr_name`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service`
--

LOCK TABLES `dcmd_service` WRITE;
/*!40000 ALTER TABLE `dcmd_service` DISABLE KEYS */;
INSERT INTO `dcmd_service` VALUES (26,'search_ui','搜索UI','/','0','/var/app/search','search',52,'',1,18,0,0,0,0,'','search','2018-01-12 16:15:56','2018-01-12 16:15:56',18);
/*!40000 ALTER TABLE `dcmd_service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_arch_diagram`
--

DROP TABLE IF EXISTS `dcmd_service_arch_diagram`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_arch_diagram` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `arch_name` varchar(200) NOT NULL,
  `diagram` longblob,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_service_arch_diagram` (`svr_id`,`arch_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_arch_diagram`
--

LOCK TABLES `dcmd_service_arch_diagram` WRITE;
/*!40000 ALTER TABLE `dcmd_service_arch_diagram` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_arch_diagram` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_ci`
--

DROP TABLE IF EXISTS `dcmd_service_ci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_ci` (
  `ci_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `ci_type` varchar(32) NOT NULL,
  `ci_url` varchar(512) NOT NULL,
  `ci_user` varchar(64) NOT NULL,
  `ci_passwd` varchar(64) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ci_id`),
  KEY `svr_id` (`svr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_ci`
--

LOCK TABLES `dcmd_service_ci` WRITE;
/*!40000 ALTER TABLE `dcmd_service_ci` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_ci` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_ci_job`
--

DROP TABLE IF EXISTS `dcmd_service_ci_job`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_ci_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ci_id` int(10) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `ci_type` varchar(32) NOT NULL,
  `ci_job` varchar(32) NOT NULL,
  `ci_url` varchar(512) NOT NULL,
  `source_branch` varchar(128) DEFAULT NULL,
  `source_sha1` varchar(128) DEFAULT NULL,
  `source_xml` varchar(64) DEFAULT NULL,
  `pkg_version` varchar(128) NOT NULL,
  `pkg_md5` varchar(32) NOT NULL,
  `source_commit_id` varchar(128) NOT NULL,
  `state` varchar(32) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `svr_id` (`svr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_ci_job`
--

LOCK TABLES `dcmd_service_ci_job` WRITE;
/*!40000 ALTER TABLE `dcmd_service_ci_job` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_ci_job` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_monitor`
--

DROP TABLE IF EXISTS `dcmd_service_monitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_monitor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `monitor_type` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `check_script_name` varchar(64) NOT NULL,
  `check_script_md5` varchar(32) NOT NULL,
  `check_script_arg` text,
  `start_script_name` varchar(64) DEFAULT NULL,
  `start_script_md5` varchar(32) DEFAULT NULL,
  `start_script_arg` text,
  `stop_script_name` varchar(64) DEFAULT NULL,
  `stop_script_md5` varchar(32) DEFAULT NULL,
  `stop_script_arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_service_monitor` (`app_id`,`svr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_monitor`
--

LOCK TABLES `dcmd_service_monitor` WRITE;
/*!40000 ALTER TABLE `dcmd_service_monitor` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_monitor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_monitor_stat`
--

DROP TABLE IF EXISTS `dcmd_service_monitor_stat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_monitor_stat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `level` int(10) NOT NULL,
  `message` varchar(1024) NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_monitor_stat`
--

LOCK TABLES `dcmd_service_monitor_stat` WRITE;
/*!40000 ALTER TABLE `dcmd_service_monitor_stat` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_monitor_stat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool`
--

DROP TABLE IF EXISTS `dcmd_service_pool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool` (
  `svr_pool_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `app_id` int(10) NOT NULL,
  `pool_group` varchar(64) NOT NULL,
  `tag` varchar(128) NOT NULL DEFAULT '',
  `tag_md5` varchar(64) NOT NULL DEFAULT '',
  `tag_task_id` int(11) NOT NULL DEFAULT '0',
  `repo` varchar(512) NOT NULL,
  `env_ver` varchar(64) NOT NULL DEFAULT '',
  `env_md5` varchar(64) NOT NULL DEFAULT '',
  `env_passwd` varchar(128) NOT NULL DEFAULT '',
  `svr_mem` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_cpu` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_net` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_io` int(11) unsigned NOT NULL DEFAULT '0',
  `image_name` varchar(256) NOT NULL DEFAULT '',
  `image_url` varchar(256) NOT NULL DEFAULT '',
  `image_user` varchar(64) NOT NULL DEFAULT '',
  `image_passwd` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`svr_pool_id`),
  UNIQUE KEY `svr_pool` (`svr_id`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool`
--

LOCK TABLES `dcmd_service_pool` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool` DISABLE KEYS */;
INSERT INTO `dcmd_service_pool` VALUES (14,'rel_aliyun_01',26,52,'aliyun','v2.0','fe019073b0fcb2921a96c59bad784bce',64,'10.163.9.231','conf1.0.1','18e77884c4ca2596225e8f1a51f8094e','l0fmSq8oh3ieF56kUXHxzOZuQGYptnK2FIRZgBcD0TGsq4HNQjiyJk1PudWUeL7zmGzRr9t1F3L5NeVbKcoJgW0hjiOaTvAH6Kyt9YjB2I7u1zfaVWNkC8wPrvJOEAix',0,0,0,0,'','','','','10.163.9.231','2018-01-12 17:30:16','2018-01-12 16:17:49',1);
/*!40000 ALTER TABLE `dcmd_service_pool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_attr`
--

DROP TABLE IF EXISTS `dcmd_service_pool_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(32) NOT NULL,
  `attr_value` varchar(256) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_service_pool_attr` (`svr_pool_id`,`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_attr`
--

LOCK TABLES `dcmd_service_pool_attr` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_pool_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_attr_def`
--

DROP TABLE IF EXISTS `dcmd_service_pool_attr_def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_attr_def` (
  `attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(32) NOT NULL,
  `optional` int(11) NOT NULL,
  `attr_type` int(10) NOT NULL,
  `def_value` varchar(256) DEFAULT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`attr_id`),
  UNIQUE KEY `dcmd_service_pool_attr_def` (`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_attr_def`
--

LOCK TABLES `dcmd_service_pool_attr_def` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_attr_def` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_pool_attr_def` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_group`
--

DROP TABLE IF EXISTS `dcmd_service_pool_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `pool_group` varchar(64) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_id` (`svr_id`,`pool_group`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_group`
--

LOCK TABLES `dcmd_service_pool_group` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_group` DISABLE KEYS */;
INSERT INTO `dcmd_service_pool_group` VALUES (26,52,26,'aliyun','2018-01-12 16:16:49',18);
/*!40000 ALTER TABLE `dcmd_service_pool_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_node`
--

DROP TABLE IF EXISTS `dcmd_service_pool_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `nid` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `tag` varchar(128) NOT NULL DEFAULT '',
  `tag_md5` varchar(64) NOT NULL DEFAULT '',
  `env_ver` varchar(64) NOT NULL DEFAULT '',
  `env_md5` varchar(64) NOT NULL DEFAULT '',
  `tag_task_id` int(11) NOT NULL DEFAULT '0',
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_pool_id` (`svr_pool_id`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_node`
--

LOCK TABLES `dcmd_service_pool_node` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_node` DISABLE KEYS */;
INSERT INTO `dcmd_service_pool_node` VALUES (12,14,26,56,52,'112.126.77.12','v2.0','fe019073b0fcb2921a96c59bad784bce','conf1.0.1','18e77884c4ca2596225e8f1a51f8094e',64,'2018-01-12 16:18:28','2018-01-12 16:18:28',18);
/*!40000 ALTER TABLE `dcmd_service_pool_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_node_port`
--

DROP TABLE IF EXISTS `dcmd_service_pool_node_port`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_node_port` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `port_name` varchar(32) NOT NULL,
  `port` int(6) NOT NULL,
  `svr_id` int(11) unsigned NOT NULL,
  `svr_pool_id` int(11) unsigned NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_port` (`ip`,`port`),
  KEY `svr_pool_id` (`svr_pool_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_node_port`
--

LOCK TABLES `dcmd_service_pool_node_port` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_node_port` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_pool_node_port` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_pool_port`
--

DROP TABLE IF EXISTS `dcmd_service_pool_port`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_pool_port` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool_id` int(11) unsigned NOT NULL,
  `svr_id` int(11) unsigned NOT NULL,
  `port_name` varchar(32) NOT NULL,
  `port` int(6) NOT NULL,
  `mapped_port` int(6) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_pool_port_name` (`svr_pool_id`,`port_name`),
  UNIQUE KEY `svr_pool_port` (`svr_pool_id`,`port`),
  KEY `app_svr_pool_port_name` (`svr_id`,`svr_pool_id`,`port_name`),
  KEY `app_svr_pool_port` (`svr_id`,`svr_pool_id`,`port`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_pool_port`
--

LOCK TABLES `dcmd_service_pool_port` WRITE;
/*!40000 ALTER TABLE `dcmd_service_pool_port` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_pool_port` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_port`
--

DROP TABLE IF EXISTS `dcmd_service_port`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_port` (
  `svr_port_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `svr_id` int(11) unsigned NOT NULL,
  `port_name` varchar(32) NOT NULL,
  `protocol` varchar(16) NOT NULL,
  `def_port` int(6) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`svr_port_id`),
  UNIQUE KEY `port_name` (`svr_id`,`port_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_port`
--

LOCK TABLES `dcmd_service_port` WRITE;
/*!40000 ALTER TABLE `dcmd_service_port` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_service_port` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_script`
--

DROP TABLE IF EXISTS `dcmd_service_script`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_script` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `pool_group` varchar(64) NOT NULL,
  `script_md5` varchar(64) NOT NULL,
  `script` text,
  `approve_time` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  `approve_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_id` (`svr_id`,`pool_group`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_script`
--

LOCK TABLES `dcmd_service_script` WRITE;
/*!40000 ALTER TABLE `dcmd_service_script` DISABLE KEYS */;
INSERT INTO `dcmd_service_script` VALUES (28,26,52,'aliyun','ba965f6fe6ec0fbc261ff15331a4d33b','#!/bin/bash\n\nprepare(){\necho  \"Begin prepare.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"start enviroment:\"\nset|grep DCMD\necho  \"End prepare.\"\nexit 0\n}\n\nstart(){\necho  \"Begin start.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"start enviroment:\"\nset|grep DCMD\necho  \"End start.\"\nexit 0\n}\n\nstop(){\necho  \"Begin stop.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"stop enviroment:\"\nset|grep DCMD\necho  \"End stop.\"\nexit 0\n}\n\ncheck(){\necho  \"Begin check.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"check enviroment:\"\nset|grep DCMD\necho  \"End check.\"\nexit 0\n}\n\ninstall(){\necho  \"Begin install.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"install type: $3\"\nif [ $3 == \"all\" ];then\n  echo  \"new_pkg_path: $4\"\n  echo  \"new_env_path: $5\"\nelif [ $3 == \"pkg\" ];then\n  echo  \"new_pkg_path: $4\"\nelif [ $3 == \"env\" ];then\n  echo  \"new_env_path: $4\"\nelse\n  echo \"invalid install type:$3\"\nfi\necho  \"check enviroment:\"\nset|grep DCMD\necho  \"End install.\"\nexit 0\n}\n\nif [ $1 == \"prepare\" ]; then\n  prepare $*\nelif [ $1 == \"start\" ]; then\n  start $*\nelif [ $1 == \"stop\" ]; then\n  stop  $*\nelif [ $1 == \"check\" ]; then\n  check $*\nelif [ $1 == \"install\" ]; then\n  install $*\nelse\n  echo \"invalid action\"\n  exit 1\nfi\n','2018-01-12 17:42:31','2018-01-12 17:42:15',1,1);
/*!40000 ALTER TABLE `dcmd_service_script` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_service_script_apply`
--

DROP TABLE IF EXISTS `dcmd_service_script_apply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_service_script_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `pool_group` varchar(64) NOT NULL,
  `is_apply` int(1) NOT NULL,
  `script_md5` varchar(64) NOT NULL,
  `script` text,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_id` (`svr_id`,`pool_group`,`is_apply`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_service_script_apply`
--

LOCK TABLES `dcmd_service_script_apply` WRITE;
/*!40000 ALTER TABLE `dcmd_service_script_apply` DISABLE KEYS */;
INSERT INTO `dcmd_service_script_apply` VALUES (27,26,52,'',0,'ba965f6fe6ec0fbc261ff15331a4d33b','#!/bin/bash\n\nprepare(){\necho  \"Begin prepare.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"start enviroment:\"\nset|grep DCMD\necho  \"End prepare.\"\nexit 0\n}\n\nstart(){\necho  \"Begin start.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"start enviroment:\"\nset|grep DCMD\necho  \"End start.\"\nexit 0\n}\n\nstop(){\necho  \"Begin stop.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"stop enviroment:\"\nset|grep DCMD\necho  \"End stop.\"\nexit 0\n}\n\ncheck(){\necho  \"Begin check.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"check enviroment:\"\nset|grep DCMD\necho  \"End check.\"\nexit 0\n}\n\ninstall(){\necho  \"Begin install.........\"\necho  \"User:\" `/bin/whoami`\necho  \"Action: $1\"\necho  \"service home: $2\"\necho  \"install type: $3\"\nif [ $3 == \"all\" ];then\n  echo  \"new_pkg_path: $4\"\n  echo  \"new_env_path: $5\"\nelif [ $3 == \"pkg\" ];then\n  echo  \"new_pkg_path: $4\"\nelif [ $3 == \"env\" ];then\n  echo  \"new_env_path: $4\"\nelse\n  echo \"invalid install type:$3\"\nfi\necho  \"check enviroment:\"\nset|grep DCMD\necho  \"End install.\"\nexit 0\n}\n\nif [ $1 == \"prepare\" ]; then\n  prepare $*\nelif [ $1 == \"start\" ]; then\n  start $*\nelif [ $1 == \"stop\" ]; then\n  stop  $*\nelif [ $1 == \"check\" ]; then\n  check $*\nelif [ $1 == \"install\" ]; then\n  install $*\nelse\n  echo \"invalid action\"\n  exit 1\nfi\n','2018-01-12 17:40:31',1);
/*!40000 ALTER TABLE `dcmd_service_script_apply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_sys_info`
--

DROP TABLE IF EXISTS `dcmd_sys_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_sys_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`type`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_sys_info`
--

LOCK TABLES `dcmd_sys_info` WRITE;
/*!40000 ALTER TABLE `dcmd_sys_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_sys_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task`
--

DROP TABLE IF EXISTS `dcmd_task`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_name` varchar(128) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `depend_task_id` int(10) unsigned NOT NULL,
  `depend_task_name` varchar(128) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_path` varchar(256) NOT NULL,
  `tag` varchar(128) NOT NULL,
  `tag_md5` varchar(64) NOT NULL DEFAULT '',
  `tag_passwd` varchar(128) NOT NULL DEFAULT '',
  `update_env` int(10) NOT NULL,
  `update_tag` int(10) NOT NULL,
  `node_multi_pool` int(10) unsigned NOT NULL,
  `is_deploy` int(1) NOT NULL,
  `state` int(11) NOT NULL,
  `freeze` int(11) NOT NULL,
  `valid` int(11) NOT NULL,
  `pause` int(11) NOT NULL,
  `err_msg` varchar(512) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `process` int(10) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `idx_task_name` (`svr_id`,`task_name`),
  KEY `idx_dcmd_svr_task_name` (`svr_name`,`task_name`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task`
--

LOCK TABLES `dcmd_task` WRITE;
/*!40000 ALTER TABLE `dcmd_task` DISABLE KEYS */;
INSERT INTO `dcmd_task` VALUES (63,'install_by_rsync-20181026164102','install_by_rsync',0,'NULL',52,'search',26,'search_ui','/var/app/search','v2.0','fe019073b0fcb2921a96c59bad784bce','KhvQtl4WuMkjY67rJHPei8ygaEVoDsBUqZWfxOjRIKwe8aVb6MrpTNyUAlDPoBmu1TIhvZOnASmCX9zQcFfB6a5N2DdV87GMoklFDzgcYu8HyBhP4XUvQprxEKM5qaTG',0,1,1,1,2,1,1,0,' ',10,1,1,0,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','','2018-10-26 16:41:33','2018-10-26 16:41:33',1),(64,'install_by_rsync-20181031133344','install_by_rsync',0,'NULL',52,'search',26,'search_ui','/var/app/search','v2.0','fe019073b0fcb2921a96c59bad784bce','KhvQtl4WuMkjY67rJHPei8ygaEVoDsBUqZWfxOjRIKwe8aVb6MrpTNyUAlDPoBmu1TIhvZOnASmCX9zQcFfB6a5N2DdV87GMoklFDzgcYu8HyBhP4XUvQprxEKM5qaTG',0,0,1,1,3,0,1,0,' ',10,1,1,0,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','','2018-10-31 13:33:57','2018-10-31 13:33:57',1);
/*!40000 ALTER TABLE `dcmd_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_cmd`
--

DROP TABLE IF EXISTS `dcmd_task_cmd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_cmd` (
  `task_cmd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_cmd` varchar(64) NOT NULL,
  `is_deploy` int(1) NOT NULL DEFAULT '1',
  `script_md5` varchar(32) NOT NULL,
  `timeout` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  `ui_name` varchar(64) NOT NULL,
  PRIMARY KEY (`task_cmd_id`),
  UNIQUE KEY `task_cmd` (`task_cmd`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_cmd`
--

LOCK TABLES `dcmd_task_cmd` WRITE;
/*!40000 ALTER TABLE `dcmd_task_cmd` DISABLE KEYS */;
INSERT INTO `dcmd_task_cmd` VALUES (1,'test_process',1,'eee6d3932d4e082d3850b9ec30e68c91',1800,'test_process','2014-12-27 10:56:00','2014-12-27 10:56:00',1,'test_process'),(2,'test_task_env',1,'1c737d825d3f88174d637bf1ac8dffde',100,'test_task_env','2014-12-27 10:56:00','2014-12-27 10:56:00',1,'test_task_env'),(4,'install_by_rsync',1,'b8294968e487eaa4a421fabda7efb1b6',60,'','2018-01-12 16:12:55','2018-01-12 16:12:55',18,'install_by_rsync');
/*!40000 ALTER TABLE `dcmd_task_cmd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_cmd_arg`
--

DROP TABLE IF EXISTS `dcmd_task_cmd_arg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_cmd_arg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_cmd_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `arg_name` varchar(32) NOT NULL,
  `optional` int(10) NOT NULL,
  `arg_type` int(10) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_cmd` (`task_cmd_id`,`arg_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_cmd_arg`
--

LOCK TABLES `dcmd_task_cmd_arg` WRITE;
/*!40000 ALTER TABLE `dcmd_task_cmd_arg` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_cmd_arg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_history`
--

DROP TABLE IF EXISTS `dcmd_task_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_history` (
  `task_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_name` varchar(128) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `depend_task_id` int(10) unsigned NOT NULL,
  `depend_task_name` varchar(128) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `app_name` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `svr_path` varchar(256) NOT NULL,
  `tag` varchar(128) NOT NULL,
  `tag_md5` varchar(64) NOT NULL DEFAULT '',
  `tag_passwd` varchar(128) NOT NULL DEFAULT '',
  `update_env` int(10) NOT NULL,
  `update_tag` int(10) NOT NULL,
  `node_multi_pool` int(10) unsigned NOT NULL,
  `is_deploy` int(1) NOT NULL,
  `state` int(11) NOT NULL,
  `freeze` int(11) NOT NULL,
  `valid` int(11) NOT NULL,
  `pause` int(11) NOT NULL,
  `err_msg` varchar(512) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `process` int(10) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `idx_task_finish_name` (`svr_id`,`task_name`),
  KEY `idx_task_svr_task_finish_name` (`svr_name`,`task_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_history`
--

LOCK TABLES `dcmd_task_history` WRITE;
/*!40000 ALTER TABLE `dcmd_task_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_node`
--

DROP TABLE IF EXISTS `dcmd_task_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_node` (
  `subtask_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `state` int(11) NOT NULL,
  `ignored` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `process` varchar(32) DEFAULT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`subtask_id`),
  UNIQUE KEY `task_id` (`task_id`,`ip`,`svr_pool`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_node`
--

LOCK TABLES `dcmd_task_node` WRITE;
/*!40000 ALTER TABLE `dcmd_task_node` DISABLE KEYS */;
INSERT INTO `dcmd_task_node` VALUES (52,63,'install_by_rsync','rel_aliyun_01','search_ui','112.126.77.12',3,0,'2018-10-26 16:41:46','2018-10-26 16:42:49','','Failure to download package, cmd:rsync  -vzrtopg --progress 10.163.9.231::search/search_ui/pkg/pkg_search_search_ui_v2.0_fe019073b0fcb292.tar.gz /var/app/search/running/search_ui/rel_aliyun_01/.install/tmp/pkg_search_search_ui_v2.0_fe019073b0fcb292.tar.gz','2018-10-26 16:42:49','2018-10-26 16:41:38',1),(53,64,'install_by_rsync','rel_aliyun_01','search_ui','112.126.77.12',2,0,'2018-10-31 18:02:33','2018-10-31 18:02:34','','','2018-10-31 18:02:34','2018-10-31 13:34:05',1);
/*!40000 ALTER TABLE `dcmd_task_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_node_history`
--

DROP TABLE IF EXISTS `dcmd_task_node_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_node_history` (
  `subtask_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_name` varchar(64) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `state` int(11) NOT NULL,
  `ignored` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `finish_time` datetime NOT NULL,
  `process` varchar(32) DEFAULT NULL,
  `err_msg` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`subtask_id`),
  UNIQUE KEY `task_id` (`task_id`,`ip`,`svr_pool`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_node_history`
--

LOCK TABLES `dcmd_task_node_history` WRITE;
/*!40000 ALTER TABLE `dcmd_task_node_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_node_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_service_pool`
--

DROP TABLE IF EXISTS `dcmd_task_service_pool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_service_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `pool_group` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `env_md5` varchar(64) NOT NULL DEFAULT '',
  `env_passwd` varchar(128) NOT NULL DEFAULT '',
  `repo` varchar(512) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `undo_node` int(10) unsigned NOT NULL,
  `doing_node` int(10) unsigned NOT NULL,
  `finish_node` int(10) unsigned NOT NULL,
  `fail_node` int(10) unsigned NOT NULL,
  `ignored_fail_node` int(10) unsigned NOT NULL,
  `ignored_doing_node` int(10) unsigned NOT NULL,
  `svr_mem` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_cpu` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_net` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_io` int(11) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(256) NOT NULL DEFAULT '',
  `image_user` varchar(64) NOT NULL DEFAULT '',
  `image_passwd` varchar(64) NOT NULL DEFAULT '',
  `image_name` varchar(256) NOT NULL DEFAULT '',
  `ports` text,
  `state` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_task_id_svr_pool_id` (`task_id`,`svr_pool_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_service_pool`
--

LOCK TABLES `dcmd_task_service_pool` WRITE;
/*!40000 ALTER TABLE `dcmd_task_service_pool` DISABLE KEYS */;
INSERT INTO `dcmd_task_service_pool` VALUES (104,63,'install_by_rsync','aliyun','rel_aliyun_01',14,'conf1.0.1','18e77884c4ca2596225e8f1a51f8094e','l0fmSq8oh3ieF56kUXHxzOZuQGYptnK2FIRZgBcD0TGsq4HNQjiyJk1PudWUeL7zmGzRr9t1F3L5NeVbKcoJgW0hjiOaTvAH6Kyt9YjB2I7u1zfaVWNkC8wPrvJOEAix','10.163.9.231','search',0,0,0,1,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><ports></ports>',2,'2018-10-26 16:41:33','2018-10-26 16:41:33',1),(105,64,'install_by_rsync','aliyun','rel_aliyun_01',14,'conf1.0.1','18e77884c4ca2596225e8f1a51f8094e','l0fmSq8oh3ieF56kUXHxzOZuQGYptnK2FIRZgBcD0TGsq4HNQjiyJk1PudWUeL7zmGzRr9t1F3L5NeVbKcoJgW0hjiOaTvAH6Kyt9YjB2I7u1zfaVWNkC8wPrvJOEAix','10.163.9.231','search',0,0,1,0,0,0,0,0,0,0,'','','','','<?xml version=\"1.0\" encoding=\"UTF-8\" ?><ports></ports>',3,'2018-10-31 13:33:57','2018-10-31 13:33:57',1);
/*!40000 ALTER TABLE `dcmd_task_service_pool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_service_pool_attr`
--

DROP TABLE IF EXISTS `dcmd_task_service_pool_attr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_service_pool_attr` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(32) NOT NULL,
  `attr_value` varchar(256) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_task_service_pool_attr` (`task_id`,`svr_pool_id`,`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_service_pool_attr`
--

LOCK TABLES `dcmd_task_service_pool_attr` WRITE;
/*!40000 ALTER TABLE `dcmd_task_service_pool_attr` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_service_pool_attr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_service_pool_attr_history`
--

DROP TABLE IF EXISTS `dcmd_task_service_pool_attr_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_service_pool_attr_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `attr_name` varchar(32) NOT NULL,
  `attr_value` varchar(256) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dcmd_task_service_pool_attr_history` (`task_id`,`svr_pool_id`,`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_service_pool_attr_history`
--

LOCK TABLES `dcmd_task_service_pool_attr_history` WRITE;
/*!40000 ALTER TABLE `dcmd_task_service_pool_attr_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_service_pool_attr_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_service_pool_history`
--

DROP TABLE IF EXISTS `dcmd_task_service_pool_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_service_pool_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `pool_group` varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `env_md5` varchar(64) NOT NULL DEFAULT '',
  `env_passwd` varchar(128) NOT NULL DEFAULT '',
  `repo` varchar(512) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `undo_node` int(10) unsigned NOT NULL,
  `doing_node` int(10) unsigned NOT NULL,
  `finish_node` int(10) unsigned NOT NULL,
  `fail_node` int(10) unsigned NOT NULL,
  `ignored_fail_node` int(10) unsigned NOT NULL,
  `ignored_doing_node` int(10) unsigned NOT NULL,
  `svr_mem` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_cpu` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_net` int(11) unsigned NOT NULL DEFAULT '0',
  `svr_io` int(11) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(256) NOT NULL DEFAULT '',
  `image_user` varchar(64) NOT NULL DEFAULT '',
  `image_passwd` varchar(64) NOT NULL DEFAULT '',
  `image_name` varchar(256) NOT NULL DEFAULT '',
  `ports` text,
  `state` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_id` (`task_id`,`svr_pool`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_service_pool_history`
--

LOCK TABLES `dcmd_task_service_pool_history` WRITE;
/*!40000 ALTER TABLE `dcmd_task_service_pool_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `dcmd_task_service_pool_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_template`
--

DROP TABLE IF EXISTS `dcmd_task_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_template` (
  `task_tmpt_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_tmpt_name` varchar(128) NOT NULL,
  `task_cmd_id` int(10) NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `svr_name` varchar(128) NOT NULL,
  `app_id` int(10) NOT NULL,
  `update_env` int(10) NOT NULL,
  `concurrent_rate` int(10) NOT NULL,
  `timeout` int(10) NOT NULL,
  `process` int(10) NOT NULL,
  `auto` int(11) NOT NULL,
  `task_arg` text,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`task_tmpt_id`),
  UNIQUE KEY `task_tmpt_name` (`svr_id`,`task_tmpt_name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_template`
--

LOCK TABLES `dcmd_task_template` WRITE;
/*!40000 ALTER TABLE `dcmd_task_template` DISABLE KEYS */;
INSERT INTO `dcmd_task_template` VALUES (24,'search_发布',4,'install_by_rsync',26,'search_ui',52,0,10,1,0,1,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','111','2018-01-12 16:19:50','2018-01-12 16:19:50',18),(25,'sdd',1,'test_process',26,'search_ui',52,0,11,11,0,0,'<?xml version=\"1.0\" encoding=\"UTF-8\" ?><env>\n</env>','111','2018-05-17 14:28:47','2018-05-17 14:28:47',1);
/*!40000 ALTER TABLE `dcmd_task_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_task_template_service_pool`
--

DROP TABLE IF EXISTS `dcmd_task_template_service_pool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_task_template_service_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_tmpt_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_tmpt_id` (`task_tmpt_id`,`svr_pool_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_task_template_service_pool`
--

LOCK TABLES `dcmd_task_template_service_pool` WRITE;
/*!40000 ALTER TABLE `dcmd_task_template_service_pool` DISABLE KEYS */;
INSERT INTO `dcmd_task_template_service_pool` VALUES (1,24,14,'2018-01-12 17:31:10','2018-01-12 17:31:10',1),(2,25,14,'2018-05-17 14:28:54','2018-05-17 14:28:54',1);
/*!40000 ALTER TABLE `dcmd_task_template_service_pool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_user`
--

DROP TABLE IF EXISTS `dcmd_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comp_id` int(10) unsigned NOT NULL,
  `username` varchar(128) NOT NULL,
  `passwd` varchar(64) NOT NULL,
  `sa` int(10) NOT NULL,
  `admin` int(10) NOT NULL,
  `depart_id` int(10) NOT NULL,
  `tel` varchar(128) NOT NULL,
  `email` varchar(64) NOT NULL,
  `state` int(11) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_user`
--

LOCK TABLES `dcmd_user` WRITE;
/*!40000 ALTER TABLE `dcmd_user` DISABLE KEYS */;
INSERT INTO `dcmd_user` VALUES (1,0,'admin','6b44b98eaf01a11570c9d132a0aaace3',1,1,0,'','',1,'','2014-12-19 03:13:54','2014-12-19 03:13:54',1),(18,52,'sre_test','91d15ef79b0f9cbbdd8b29c539307f4d',0,1,52,'110','sre@ducter.net',0,'sre@ducter.net','2018-01-12 15:19:15','2018-01-12 15:19:15',1),(19,52,'svr_test','817b040f1bbb5b66cbc312be5e649866',0,0,53,'110','svr_test@ducter.net',0,'svr_test@ducter.net','2018-01-12 16:32:54','2018-01-12 16:32:54',1);
/*!40000 ALTER TABLE `dcmd_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dcmd_user_group`
--

DROP TABLE IF EXISTS `dcmd_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dcmd_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `is_leader` int(1) unsigned NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`,`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dcmd_user_group`
--

LOCK TABLES `dcmd_user_group` WRITE;
/*!40000 ALTER TABLE `dcmd_user_group` DISABLE KEYS */;
INSERT INTO `dcmd_user_group` VALUES (38,1,53,1,'comment','2018-01-12 15:18:33','2018-01-12 15:18:33',1),(39,18,53,0,'comment','2018-01-12 15:19:51','2018-01-12 15:19:51',1),(40,19,52,0,'comment','2018-01-12 16:33:10','2018-01-12 16:33:10',1);
/*!40000 ALTER TABLE `dcmd_user_group` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-08 14:01:28
