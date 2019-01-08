
-- ----------------------------
-- Table structure for dcmd_company
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_company`;
CREATE TABLE `dcmd_company` (
  `comp_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comp_name` varchar(64) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`comp_id`),
  UNIQUE KEY `comp_name` (`comp_name`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_department
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_department`;
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_group
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_group`;
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_user
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_user`;
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_user_group
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_user_group`;
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_group_cmd
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_group_cmd`;
CREATE TABLE `dcmd_group_cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `opr_cmd_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`gid`,`opr_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_app_upload_script
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_upload_script`;
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
  INDEX `name` (`app_id`,`svr_id`,`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_svr_script
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_svr_script`;
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_app_pkg_upload
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_pkg_upload`;
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
  INDEX `id` (`app_id`,`svr_id`,`svr_pool_id`),
  INDEX `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_upload_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_upload_history`;
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
  INDEX `id` (`app_id`,`svr_id`,`svr_pool_id`),
  INDEX `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_reject_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_reject_history`;
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
  `errmsg`  varchar(255) NOT NULL,
  `upload_time` datetime NOT NULL,
  `accept_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `id` (`app_id`,`svr_id`,`svr_pool_id`),
  INDEX `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_app_upload_error
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_upload_error`;
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
  INDEX `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_upload_sync_error
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_upload_sync_error`;
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
  INDEX `name` (`app_name`,`svr_name`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_pkg_version
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_pkg_version`;
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_conf_version
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_conf_version`;
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_obj_change_event
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_obj_change_event`;
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
  INDEX `name` (`obj_type`,`change_type`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_group_repeat_cmd
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_group_repeat_cmd`;
CREATE TABLE `dcmd_group_repeat_cmd` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gid` int(10) unsigned NOT NULL,
  `repeat_cmd_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guid` (`gid`,`repeat_cmd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_sys_info
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_sys_info`;
CREATE TABLE `dcmd_sys_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`type`,`name`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_dc_info
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_dc_info`;
CREATE TABLE `dcmd_dc_info` (
  `dc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(64) NOT NULL,
  `area` varchar(64) NOT NULL,
  `dc` varchar(64) NOT NULL,
  PRIMARY KEY (`dc_id`),
  UNIQUE KEY `name` (`country`,`area`,`dc`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;




-- ----------------------------
-- Table structure for dcmd_node_group
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_node_group`;
CREATE TABLE `dcmd_node_group` (
  `ngroup_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ngroup_name` varchar(128) NOT NULL,
  `ngroup_alias` varchar(128) NOT NULL,
  `gid`  int(10) NOT NULL,
  `comment` varchar(512) NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ngroup_id`),
  UNIQUE KEY `ngroup_name` (`ngroup_name`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_node
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_node`;
CREATE TABLE `dcmd_node` (
  `nid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(16) NOT NULL,
  `ngroup_id` int(10) NOT NULL,
  `host` varchar(128) NOT NULL,
  `sid` varchar(128) NOT NULL,
  `did` varchar(128) NOT NULL,
  `bend_ip` varchar(16) NOT NULL,
  `public_ip` varchar(16) NOT NULL,
  `comment` varchar(512) NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`nid`),
  UNIQUE KEY `ip` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_center
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_center`;
CREATE TABLE `dcmd_center` (
  `host` varchar(32) NOT NULL,
  `master` int(11) NOT NULL,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`host`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_app
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app`;
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
  `comment` varchar(512) NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`app_id`),
  UNIQUE KEY `app_name` (`app_name`),
  UNIQUE KEY `app_alias` (`app_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_node_group
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_node_group`;
CREATE TABLE `dcmd_app_node_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int NOT NULL,
  `ngroup_id` int NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ngroup_id` (`app_id`,`ngroup_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_res_type
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_type`;
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_column
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_column`;
CREATE TABLE `dcmd_res_column` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_type` varchar(32) NOT NULL,
  `res_table` varchar(64) NOT NULL,
  `colum_name` varchar(64) NOT NULL,
  `display_name` varchar(64) NOT NULL,
  `display_order` int(10) unsigned NOT NULL,
  `display_list` boolean NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_type_column` (`res_type`,`colum_name`),
  UNIQUE KEY `res_type_display` (`res_type`,`display_name`),
  UNIQUE KEY `res_table_column` (`res_table`,`colum_name`),
  UNIQUE KEY `res_table_display` (`res_table`,`display_name`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_res
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_app_res`;
CREATE TABLE `dcmd_app_res` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int NOT NULL,
  `svr_id` int NOT NULL,
  `svr_pool_id` int NOT NULL,
  `res_type` varchar(32) NOT NULL,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(256) NOT NULL,
  `is_own` int(1) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res` (`app_id`,`svr_id`,`svr_pool_id`,`res_type`,`res_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_dns
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_dns`;
CREATE TABLE `dcmd_res_dns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `name`    varchar(255) NOT NULL,
  `I2`   varchar(64) NOT NULL,
  `I3`   varchar(64) NOT NULL,
  `I4`   varchar(64) NOT NULL,
  `I5`   varchar(64) NOT NULL,
  `tag`   varchar(64) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`),
  UNIQUE KEY  `res_name` (`res_name`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_res_slb
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_slb`;
CREATE TABLE `dcmd_res_slb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `name`    varchar(255) NOT NULL,
  `I2`   varchar(64) NOT NULL,
  `I3`   varchar(64) NOT NULL,
  `I4`   varchar(64) NOT NULL,
  `I5`   varchar(64) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_lvs
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_lvs`;
CREATE TABLE `dcmd_res_lvs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `vip`    varchar(64) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_cbase
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_cbase`;
CREATE TABLE `dcmd_res_cbase` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `cluster`    varchar(64) NOT NULL,
  `bucket`    varchar(128) NOT NULL,
  `quota`     int(10) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_redis
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_redis`;
CREATE TABLE `dcmd_res_redis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `cluster`    varchar(64) NOT NULL,
  `instance`    varchar(128) NOT NULL,
  `quota`     int(10) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_mq
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_mq`;
CREATE TABLE `dcmd_res_mq` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `cluster`    varchar(64) NOT NULL,
  `queue`    varchar(128) NOT NULL,
  `quota`     int(10) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_gluster
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_gluster`;
CREATE TABLE `dcmd_res_gluster` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `cluster`    varchar(64) NOT NULL,
  `volume`    varchar(128) NOT NULL,
  `quota`     int(10) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_mysql
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_mysql`;
CREATE TABLE `dcmd_res_mysql` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `server`    varchar(128) NOT NULL,
  `port`     varchar(64) NOT NULL,
  `db`       varchar(128) NOT NULL,
  `comp_id` int(10) unsigned NOT NULL,
  `contact` varchar(32) NOT NULL,
  `state` int(1) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `ctime` datetime NOT NULL,
  `stime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `res_id` (`res_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_mongo
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_mongo`;
CREATE TABLE `dcmd_res_mongo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `cluster`    varchar(64) NOT NULL,
  `instance`   varchar(128) NOT NULL,
  `port`       int(5) NOT NULL,
  `instance_num` int(5) NOT NULL,
  `backup_cluster`    varchar(64) NOT NULL,
  `backup_port`       int(5) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_oracle
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_oracle`;
CREATE TABLE `dcmd_res_oracle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `cluster`    varchar(64) NOT NULL,
  `cluster_ip`    varchar(64) NOT NULL,
  `schema_name`    varchar(64) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_res_mcluster
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_res_mcluster`;
CREATE TABLE `dcmd_res_mcluster` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `res_id` varchar(32) NOT NULL,
  `res_name` varchar(255) NOT NULL,
  `res_order` int NOT NULL,
  `is_public` int(0) NOT NULL,
  `cluster`    varchar(64) NOT NULL,
  `port`     int(5) NOT NULL,
  `db`       varchar(128) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service`;
CREATE TABLE `dcmd_service` (
  `svr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_name` varchar(128) NOT NULL,
  `svr_alias` varchar(128) NOT NULL,
  `service_tree` varchar(256) NOT NULL,
  `res_id` varchar(32) NOT NULL,
  `svr_path`  varchar(128) NOT NULL,
  `run_user`   varchar(16) NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `script_md5`  varchar(64) NOT NULL default "",
  `node_multi_pool` int(10) unsigned NOT NULL,
  `owner` int(10) NOT NULL,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`svr_id`),
  UNIQUE KEY `svr_name` (`app_id`, `svr_name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_pool_group
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_pool_group`;
CREATE TABLE `dcmd_service_pool_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `pool_group`  varchar(64) NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_id` (`svr_id`,`pool_group`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_script
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_script`;
CREATE TABLE `dcmd_service_script` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `pool_group`  varchar(64) NOT NULL,
  `script_md5`  varchar(64) NOT NULL,
  `script` text ,
  `approve_time` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  `approve_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_id` (`svr_id`, `pool_group`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_script_apply
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_script_apply`;
CREATE TABLE `dcmd_service_script_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `pool_group`  varchar(64) NOT NULL,
  `is_apply` int(1) NOT NULL,
  `script_md5`  varchar(64) NOT NULL,
  `script` text ,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_id` (`svr_id`, `pool_group`, `is_apply`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_pool
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_pool`;
CREATE TABLE `dcmd_service_pool` (
  `svr_pool_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool` varchar(128) NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `app_id` int(10) NOT NULL,
  `pool_group`  varchar(64) NOT NULL,
  `tag` varchar(128) NOT NULL default "",
  `tag_md5` varchar(64)  NOT NULL default "",
  `tag_task_id` int(11)  NOT NULL default 0,
  `repo` varchar(512) NOT NULL,
  `env_ver` varchar(64)  NOT NULL default "",
  `env_md5` varchar(64)  NOT NULL default "",
  `env_passwd` varchar(128) NOT NULL default "",
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`svr_pool_id`),
  UNIQUE KEY `svr_pool` (`svr_id`, `svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_pool_node
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_pool_node`;
CREATE TABLE `dcmd_service_pool_node` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `svr_id` int(10) unsigned NOT NULL,
  `nid` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `tag` varchar(128) NOT NULL default "",
  `tag_md5` varchar(64) NOT NULL default "",
  `env_ver` varchar(64) NOT NULL default "",
  `env_md5` varchar(64) NOT NULL default "",
  `tag_task_id` int(11) NOT NULL default 0,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `svr_pool_id` (`svr_pool_id`,`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;


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
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;


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
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_ci
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_ci`;
CREATE TABLE `dcmd_service_ci` (
  `ci_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `ci_type` varchar(32) NOT NULL,
  `ci_url` varchar(512) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`ci_id`),
  index  `svr_id` (`svr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_ci_job
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_service_ci_job`;
CREATE TABLE `dcmd_service_ci_job` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ci_id` int(10) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `ci_type` varchar(32) NOT NULL,
  `ci_job` varchar(32) NOT NULL,
  `ci_url` varchar(512) NOT NULL,
  `source_branch` varchar(128) NULL,
  `source_sha1` varchar(128) NULL,
  `source_xml` varchar(64) NULL,
  `pkg_version` varchar(128) NOT NULL,
  `pkg_md5` varchar(32) NOT NULL,
  `source_commit_id` varchar(128) NOT NULL,
  `state` int(1) NOT NULL comment '0:undo;1:doing;2:success;3:failed',
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  index  `svr_id` (`svr_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for dcmd_task_cmd
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_cmd`;
CREATE TABLE `dcmd_task_cmd` (
  `task_cmd_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_cmd` varchar(64) NOT NULL,
  `is_deploy` int(1) NOT NULL,
  `script_md5` varchar(32) NOT NULL,
  `timeout` int(11) NOT NULL,
  `comment` varchar(512) DEFAULT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  `ui_name` varchar(64) NOT NULL,
  PRIMARY KEY (`task_cmd_id`),
  UNIQUE KEY `task_cmd` (`task_cmd`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_cmd_arg
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_cmd_arg`;
CREATE TABLE `dcmd_task_cmd_arg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_cmd_id` int(10) unsigned NOT NULL,
  `task_cmd`  varchar(64) NOT NULL,
  `arg_name` varchar(32) NOT NULL,
  `optional` int(10) NOT NULL,
  `arg_type` int(10) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_cmd` (`task_cmd_id`,`arg_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_template
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_template`;
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_template_service_pool
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_template_service_pool`;
CREATE TABLE `dcmd_task_template_service_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_tmpt_id` int(10) unsigned NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_tmpt_id` (`task_tmpt_id`,`svr_pool_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task`;
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
  `tag_md5` varchar(64) NOT NULL default "",
  `tag_passwd` varchar(128) NOT NULL default "",
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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;



-- ----------------------------
-- Table structure for dcmd_task_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_history`;
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
  `tag_md5` varchar(64) NOT NULL default "",
  `tag_passwd` varchar(128) NOT NULL default "",
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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_task_service_pool
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_service_pool`;
CREATE TABLE `dcmd_task_service_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `pool_group`  varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `env_md5` varchar(64) NOT NULL default "",
  `env_passwd` varchar(128) NOT NULL default "",
  `repo` varchar(512) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `undo_node` int(10) unsigned NOT NULL,
  `doing_node` int(10) unsigned NOT NULL,
  `finish_node` int(10) unsigned NOT NULL,
  `fail_node` int(10) unsigned NOT NULL,
  `ignored_fail_node` int(10) unsigned NOT NULL,
  `ignored_doing_node` int(10) unsigned NOT NULL,
  `state` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_task_id_svr_pool_id` (`task_id`,`svr_pool_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_service_pool_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_service_pool_history`;
CREATE TABLE `dcmd_task_service_pool_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `task_cmd` varchar(64) NOT NULL,
  `pool_group`  varchar(64) NOT NULL,
  `svr_pool` varchar(64) NOT NULL,
  `svr_pool_id` int(10) unsigned NOT NULL,
  `env_ver` varchar(64) NOT NULL,
  `env_md5` varchar(64) NOT NULL default "",
  `env_passwd` varchar(128) NOT NULL default "",
  `repo` varchar(512) NOT NULL,
  `run_user` varchar(32) NOT NULL,
  `undo_node` int(10) unsigned NOT NULL,
  `doing_node` int(10) unsigned NOT NULL,
  `finish_node` int(10) unsigned NOT NULL,
  `fail_node` int(10) unsigned NOT NULL,
  `ignored_fail_node` int(10) unsigned NOT NULL,
  `ignored_doing_node` int(10) unsigned NOT NULL,
  `state` int(10) unsigned NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_id` (`task_id`,`svr_pool`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_node
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_node`;
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
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_node_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_task_node_history`;
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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_command
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_command`;
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
) ENGINE=InnoDB AUTO_INCREMENT=307 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_command_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_command_history`;
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
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd_arg
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_arg`;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_opr_cmd_exec
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_exec`;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd_repeat_exec
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_repeat_exec`;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd_exec_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_exec_history`;
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
) ENGINE=InnoDB AUTO_INCREMENT=424 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_cmd_repeat_exec_history
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_cmd_repeat_exec_history`;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_opr_log
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_opr_log`;
CREATE TABLE `dcmd_opr_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_table` varchar(64) NOT NULL,
  `opr_type` int(11) NOT NULL,
  `sql_statement` text NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_opr_log_table` (`log_table`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_cron
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_cron`;
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


-- ----------------------------
-- Table structure for dcmd_cron_node
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_cron_node`;
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
  KEY `cron_ip` (`cron_id`, `ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_cron_event
-- ----------------------------
DROP TABLE IF EXISTS `dcmd_cron_event`;
CREATE TABLE `dcmd_cron_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cron_id` int(10) NOT NULL,
  `version` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `app_id` int(10) NOT NULL,
  `svr_id` int(10) NOT NULL,
  `level` int(10) NOT NULL,
  `message` varchar(1024)  NOT NULL,
  `ctime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cron_ip` (`ip`, `cron_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_monitor
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_monitor;
CREATE TABLE dcmd_service_monitor (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) NOT NULL,
  svr_id int(10) NOT NULL,
  monitor_type int(10)  NOT NULL,
  version int(10) NOT NULL,
  check_script_name varchar(64) NOT NULL,
  check_script_md5  varchar(32) NOT NULL,
  check_script_arg  text,
  start_script_name varchar(64) ,
  start_script_md5  varchar(32) ,
  start_script_arg  text,
  stop_script_name  varchar(64) ,
  stop_script_md5   varchar(32) ,
  stop_script_arg  text,
  `comment` varchar(512) NOT NULL,
  `utime` datetime NOT NULL,
  `ctime` datetime NOT NULL,
  `opr_uid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_service_monitor` (app_id, svr_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_monitor_event
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_monitor_stat;
CREATE TABLE dcmd_service_monitor_stat (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) NOT NULL,
  svr_id int(10) NOT NULL,
  ip varchar(16) NOT NULL,
  level int(10) NOT NULL,
  message varchar(1024)  NOT NULL,
  ctime datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_node_group_attr_def
-- ----------------------------
DROP TABLE IF EXISTS dcmd_node_group_attr_def;
CREATE TABLE dcmd_node_group_attr_def (
  attr_id   int(10) unsigned NOT NULL AUTO_INCREMENT,
  attr_name varchar(32) NOT NULL,
  optional  int(11) NOT NULL,
  attr_type int(10) NOT NULL,
  def_value varchar(256),
  comment   varchar(512) NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`attr_id`),
  unique index `dcmd_node_group_attr_def` (attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_node_group_attr
-- ----------------------------
DROP TABLE IF EXISTS dcmd_node_group_attr;
CREATE TABLE dcmd_node_group_attr (
  id   int(10) unsigned NOT NULL AUTO_INCREMENT,
  ngroup_id int(10) unsigned NOT NULL,
  attr_name varchar(32) NOT NULL,
  attr_value varchar(256) NOT NULL,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_node_group_attr` (ngroup_id, attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_app_arch_diagram
-- ----------------------------
DROP TABLE IF EXISTS dcmd_app_arch_diagram;
CREATE TABLE dcmd_app_arch_diagram (
  id     int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) unsigned NOT NULL,
  arch_name varchar(200) NOT NULL,
  diagram   longblob,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_app_arch_diagram` (app_id, arch_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_arch_diagram
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_arch_diagram;
CREATE TABLE dcmd_service_arch_diagram (
  id     int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) unsigned NOT NULL,
  svr_id int(10) unsigned NOT NULL,
  arch_name varchar(200) NOT NULL,
  diagram   longblob,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_service_arch_diagram` (svr_id, arch_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_service_pool_attr_def
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_pool_attr_def;
CREATE TABLE dcmd_service_pool_attr_def (
  attr_id   int(10) unsigned NOT NULL AUTO_INCREMENT,
  attr_name varchar(32) NOT NULL,
  optional  int(11) NOT NULL,
  attr_type int(10) NOT NULL,
  def_value varchar(256),
  comment   varchar(512) NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`attr_id`),
  unique index `dcmd_service_pool_attr_def` (attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_service_pool_attr
-- ----------------------------
DROP TABLE IF EXISTS dcmd_service_pool_attr;
CREATE TABLE dcmd_service_pool_attr (
  id   int(10) unsigned NOT NULL AUTO_INCREMENT,
  app_id int(10) unsigned NOT NULL,
  svr_id int(10) unsigned NOT NULL,
  svr_pool_id int(10) unsigned NOT NULL,
  attr_name varchar(32) NOT NULL,
  attr_value varchar(256) NOT NULL,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_service_pool_attr` (svr_pool_id, attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for dcmd_task_service_pool_attr
-- ----------------------------
DROP TABLE IF EXISTS dcmd_task_service_pool_attr;
CREATE TABLE dcmd_task_service_pool_attr (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id int(10) unsigned NOT NULL,
  app_id int(10) unsigned NOT NULL,
  svr_id int(10) unsigned NOT NULL,
  svr_pool_id int(10) unsigned NOT NULL,
  attr_name varchar(32) NOT NULL,
  attr_value varchar(256) NOT NULL,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_task_service_pool_attr` (task_id, svr_pool_id, attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for dcmd_task_service_pool_attr_history
-- ----------------------------
DROP TABLE IF EXISTS dcmd_task_service_pool_attr_history;
CREATE TABLE dcmd_task_service_pool_attr_history (
  id bigint(20) NOT NULL AUTO_INCREMENT,
  task_id int(10) unsigned NOT NULL,
  app_id int(10) unsigned NOT NULL,
  svr_id int(10) unsigned NOT NULL,
  svr_pool_id int(10) unsigned NOT NULL,
  attr_name varchar(32) NOT NULL,
  attr_value varchar(256) NOT NULL,
  comment   varchar(512) NOT NULL,
  utime  datetime NOT NULL,
  ctime datetime NOT NULL,
  opr_uid  int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  unique index `dcmd_task_service_pool_attr_history` (task_id, svr_pool_id, attr_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Records of dcmd_user
-- ----------------------------
INSERT INTO `dcmd_user` (`uid`, `comp_id`, `username`, `passwd`, `sa`, `admin`, `depart_id`, `tel`, `email`, `state`, `comment`, `utime`, `ctime`, `opr_uid`) VALUES ('1', 0, 'admin', '6910a551ce91b6b4b9258925bc72aaeb', 1, 1, 0, '', '', 1, '', '2014-12-19 03:13:54', '2014-12-19 03:13:54', 1);

-- ----------------------------
-- Records of dcmd_opr_cmd
-- ----------------------------
INSERT INTO `dcmd_opr_cmd` (`opr_cmd_id`, `opr_cmd`, `ui_name`, `run_user`, `script_md5`, `timeout`, `comment`, `utime`, `ctime`, `opr_uid`) VALUES ('1', 'host_user', 'get_host_user', 'root', 'f4b5024642b198e66b8d27acec0bd09b', 20, '閼惧嘲褰囨稉缁樻簚閻€劍鍩�', '2014-02-20 15:55:05', '2014-02-20 15:55:05', 1);
INSERT INTO `dcmd_opr_cmd` (`opr_cmd_id`, `opr_cmd`, `ui_name`, `run_user`, `script_md5`, `timeout`, `comment`, `utime`, `ctime`, `opr_uid`) VALUES ('2', 'os_info', 'os_info', 'root', '78cd5d54bca0b4ee2c4c17a7c08f6759', 10, '閼惧嘲褰囨稉缁樻簚娣団剝浼�', '2014-02-20 15:55:05', '2014-02-20 15:55:05', 1);
INSERT INTO `dcmd_opr_cmd` (`opr_cmd_id`, `opr_cmd`, `ui_name`, `run_user`, `script_md5`, `timeout`, `comment`, `utime`, `ctime`, `opr_uid`) VALUES ('3', 'proc_info', 'proc_info', 'root', '9a11445918e661dbb950ed0718f96ab2', 10, '閼惧嘲褰囨潻娑氣柤娣団剝浼�', '2014-02-20 15:55:05', '2014-02-20 15:55:05', 1);
INSERT INTO `dcmd_opr_cmd` (`opr_cmd_id`, `opr_cmd`, `ui_name`, `run_user`, `script_md5`, `timeout`, `comment`, `utime`, `ctime`, `opr_uid`) VALUES ('4', 'ps', 'ps', 'root', '522c8c25b28740b2aa2928672f4cd985', 10, 'ps', '2014-02-20 15:55:05', '2014-02-20 15:55:05', 1);
INSERT INTO `dcmd_opr_cmd` (`opr_cmd_id`, `opr_cmd`, `ui_name`, `run_user`, `script_md5`, `timeout`, `comment`, `utime`, `ctime`, `opr_uid`) VALUES ('5', 'test_opr_env', 'test_opr_env', 'root', 'fe39937172b36ca82bcc9a824f1e259e', 10, '濞村鐦悳顖氼暔閸欐﹢鍣�', '2014-02-20 15:55:05', '2014-02-20 15:55:05', 1);



-- -----------------------------
-- Records of dcmd_task_cmd
-- -----------------------------
INSERT INTO `dcmd_task_cmd` (`task_cmd_id`, `task_cmd`, `script_md5`, `timeout`, `comment`, `utime`, `ctime`, `opr_uid`, `ui_name`) VALUES (1, 'test_process', 'eee6d3932d4e082d3850b9ec30e68c91', 1800, '閻€劋绨ù瀣槸dcmd鏉╂稑瀹抽弰鍓с仛', '2014-12-27 10:56:00', '2014-12-27 10:56:00', 1, '鏉╂稑瀹冲ù瀣槸娴犺濮熼懘姘拱');
INSERT INTO `dcmd_task_cmd` (`task_cmd_id`, `task_cmd`, `script_md5`, `timeout`, `comment`, `utime`, `ctime`, `opr_uid`, `ui_name`) VALUES (2, 'test_task_env', '1c737d825d3f88174d637bf1ac8dffde', 100, '濞村鐦痶ask閻滎垰顣ㄩ崣姗�鍣�', '2014-12-27 10:56:00', '2014-12-27 10:56:00', 1, '鏉╂稑瀹砊ask閻滎垰顣ㄩ崣姗�鍣�');
INSERT INTO `dcmd_task_cmd` (`task_cmd_id`, `task_cmd`, `script_md5`, `timeout`, `comment`, `utime`, `ctime`, `opr_uid`, `ui_name`) VALUES (3, 'install_by_svn', '4f2d5b646166939bcbd5a6da6f042b42', 1000, '', '2014-12-27 10:56:00', '2014-12-27 10:56:00', 1, 'install_by_svn');

