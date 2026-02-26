-- MySQL dump 10.13  Distrib 5.5.60, for Linux (x86_64)
--
-- Host: localhost    Database: renju
-- ------------------------------------------------------
-- Server version	5.5.60

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
-- Table structure for table `game_invites`
--

DROP TABLE IF EXISTS `game_invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `black_id` int(11) NOT NULL,
  `message` varchar(64) NOT NULL,
  `totaltime` int(11) NOT NULL,
  `step_add_sec` int(11) NOT NULL DEFAULT '0' COMMENT '每一手加秒',
  `rule` enum('Yamaguchi','RIF','Soosyrv8','Renju','Gomoku','TaraGuchi') NOT NULL DEFAULT 'Yamaguchi',
  `free_opening` int(11) NOT NULL,
  `allow_undo` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可申请悔棋',
  `allow_ob_talk` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否允许旁观说话',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0等待响应 1已同意 -1 已失效',
  `is_private` tinyint(4) NOT NULL DEFAULT '0',
  `game_id` int(11) NOT NULL DEFAULT '0',
  `updtime` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00',
  PRIMARY KEY (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`)
) ENGINE=InnoDB AUTO_INCREMENT=52950 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `game_undo_log`
--

DROP TABLE IF EXISTS `game_undo_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_undo_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '提出悔棋的玩家id',
  `current_board` varchar(512) NOT NULL DEFAULT '' COMMENT '悔棋的时候的盘面',
  `to_number` int(11) NOT NULL COMMENT '悔棋到第几手',
  `comment` varchar(32) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`,`status`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14147 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `black_id` int(11) NOT NULL,
  `white_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `is_private` tinyint(4) NOT NULL DEFAULT '0',
  `offer_draw` int(11) NOT NULL DEFAULT '0' COMMENT '如果有玩家提和，记录其id',
  `rule` enum('Yamaguchi','RIF','Soosyrv8','Renju','Gomoku','TaraGuchi') NOT NULL DEFAULT 'Yamaguchi',
  `free_opening` tinyint(4) NOT NULL DEFAULT '0',
  `allow_undo` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可申请悔棋',
  `allow_ob_talk` tinyint(4) NOT NULL DEFAULT '1',
  `game_record` varchar(450) NOT NULL DEFAULT '',
  `black_time` int(11) NOT NULL COMMENT '剩余秒数',
  `white_time` int(11) NOT NULL,
  `totaltime` int(11) NOT NULL COMMENT '秒数',
  `step_add_sec` int(11) NOT NULL DEFAULT '0',
  `swap` tinyint(4) NOT NULL,
  `soosyrv_swap` tinyint(4) NOT NULL DEFAULT '0' COMMENT '索索夫规则的第四手交换',
  `a5_pos` varchar(40) NOT NULL DEFAULT '',
  `a5_numbers` tinyint(4) unsigned NOT NULL,
  `updtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `movetime` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00' COMMENT '最后落子时间',
  `comment` varchar(64) NOT NULL,
  `tid` smallint(6) NOT NULL DEFAULT '0' COMMENT '比赛id',
  `vip` tinyint(4) NOT NULL DEFAULT '0',
  `create_time` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00',
  PRIMARY KEY (`id`),
  KEY `black_id` (`black_id`),
  KEY `white_id` (`white_id`),
  KEY `updtime` (`updtime`),
  KEY `game_record` (`game_record`(128)),
  KEY `tid` (`tid`),
  KEY `status` (`status`),
  KEY `movetime` (`movetime`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=24852 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `nickname` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `login_times` int(11) NOT NULL,
  `b_win` int(11) NOT NULL DEFAULT '0',
  `b_lose` int(11) NOT NULL DEFAULT '0',
  `w_win` int(11) NOT NULL DEFAULT '0',
  `w_lose` int(11) NOT NULL DEFAULT '0',
  `draw` int(11) NOT NULL DEFAULT '0',
  `games` int(11) NOT NULL DEFAULT '0' COMMENT '总对局数',
  `reg_time` timestamp NOT NULL DEFAULT '2017-12-31 16:00:00',
  `reg_ip` varchar(15) NOT NULL DEFAULT '',
  `last_login_time` timestamp NOT NULL DEFAULT '2017-12-31 16:00:00',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '',
  `score` decimal(15,3) NOT NULL,
  `vip` tinyint(4) NOT NULL DEFAULT '0',
  `language` varchar(8) NOT NULL DEFAULT 'zh-CN',
  `intro` varchar(128) NOT NULL DEFAULT 'hi,it''s me',
  PRIMARY KEY (`id`),
  KEY `email` (`email`(8)),
  KEY `nickname` (`nickname`(8)),
  KEY `score` (`score`)
) ENGINE=InnoDB AUTO_INCREMENT=1915 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `score_log`
--

DROP TABLE IF EXISTS `score_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `score_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL COMMENT '玩家id',
  `op_id` int(11) NOT NULL COMMENT '对手id',
  `before_score` decimal(15,3) NOT NULL COMMENT '赛前分',
  `op_score` decimal(15,3) NOT NULL COMMENT '对手分',
  `k_val` tinyint(4) NOT NULL COMMENT 'K值',
  `delta_score` decimal(15,3) NOT NULL COMMENT '分差',
  `after_score` decimal(15,3) NOT NULL COMMENT '赛后分',
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49725 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-26 20:18:32
