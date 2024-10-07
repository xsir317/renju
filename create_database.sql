-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2024-06-10 19:22:30
-- 服务器版本： 10.4.24-MariaDB
-- PHP 版本： 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- 数据库： `renju`
--
CREATE DATABASE IF NOT EXISTS `renju` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `renju`;

-- --------------------------------------------------------

--
-- 表的结构 `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `black_id` int(11) NOT NULL,
  `white_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `is_private` tinyint(4) NOT NULL DEFAULT 0,
  `offer_draw` int(11) NOT NULL DEFAULT 0 COMMENT '如果有玩家提和，记录其id',
  `rule` enum('Yamaguchi','RIF','Soosyrv8','Renju','Gomoku','TaraGuchi') NOT NULL DEFAULT 'Yamaguchi',
  `free_opening` tinyint(4) NOT NULL DEFAULT 0,
  `allow_undo` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否可申请悔棋',
  `allow_ob_talk` tinyint(4) NOT NULL DEFAULT 0,
  `game_record` varchar(450) NOT NULL DEFAULT '',
  `black_time` int(11) NOT NULL COMMENT '剩余秒数',
  `white_time` int(11) NOT NULL,
  `totaltime` int(11) NOT NULL COMMENT '秒数',
  `step_add_sec` int(11) NOT NULL DEFAULT 0,
  `swap` tinyint(4) NOT NULL,
  `soosyrv_swap` tinyint(4) NOT NULL DEFAULT 0 COMMENT '索索夫规则的第四手交换',
  `a5_pos` varchar(40) NOT NULL DEFAULT '',
  `a5_numbers` tinyint(4) UNSIGNED NOT NULL,
  `updtime` timestamp NOT NULL DEFAULT current_timestamp(),
  `movetime` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00' COMMENT '最后落子时间',
  `comment` varchar(64) NOT NULL,
  `tid` smallint(6) NOT NULL DEFAULT 0 COMMENT '比赛id',
  `create_time` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00',
  PRIMARY KEY (`id`),
  KEY `black_id` (`black_id`),
  KEY `white_id` (`white_id`),
  KEY `updtime` (`updtime`),
  KEY `game_record` (`game_record`(128)),
  KEY `tid` (`tid`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `games`
--

INSERT INTO `games` (`id`, `black_id`, `white_id`, `status`, `is_private`, `offer_draw`, `rule`, `free_opening`, `allow_undo`, `allow_ob_talk`, `game_record`, `black_time`, `white_time`, `totaltime`, `step_add_sec`, `swap`, `soosyrv_swap`, `a5_pos`, `a5_numbers`, `updtime`, `movetime`, `comment`, `tid`, `create_time`) VALUES
(1, 1, 2, 1, 0, 0, 'TaraGuchi', 0, 0, 0, '88798a9a', 3192, 3048, 3600, 0, 16, 0, '', 0, '2024-06-10 11:09:30', '2024-06-10 11:02:51', '', 0, '2024-06-10 10:41:17');

-- --------------------------------------------------------

--
-- 表的结构 `game_invites`
--

CREATE TABLE IF NOT EXISTS `game_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `black_id` int(11) NOT NULL,
  `message` varchar(64) NOT NULL,
  `totaltime` int(11) NOT NULL,
  `step_add_sec` int(11) NOT NULL DEFAULT 0,
  `rule` enum('Yamaguchi','RIF','Soosyrv8','Renju','Gomoku','TaraGuchi') NOT NULL DEFAULT 'Yamaguchi',
  `free_opening` int(11) NOT NULL,
  `allow_undo` tinyint(4) NOT NULL DEFAULT 1 COMMENT '是否可申请悔棋',
  `allow_ob_talk` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0等待响应 1已同意 -1 已失效',
  `is_private` tinyint(4) NOT NULL DEFAULT 0,
  `game_id` int(11) NOT NULL DEFAULT 0,
  `updtime` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00',
  PRIMARY KEY (`id`),
  KEY `from` (`from`),
  KEY `to` (`to`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `game_invites`
--

INSERT INTO `game_invites` (`id`, `from`, `to`, `black_id`, `message`, `totaltime`, `step_add_sec`, `rule`, `free_opening`, `allow_undo`, `allow_ob_talk`, `status`, `is_private`, `game_id`, `updtime`) VALUES
(1, 1, 2, 2, '', 600, 0, 'TaraGuchi', 0, 0, 0, 0, 0, 1, '2024-06-10 10:41:13');

-- --------------------------------------------------------

--
-- 表的结构 `game_undo_log`
--

CREATE TABLE IF NOT EXISTS `game_undo_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '提出悔棋的玩家id',
  `current_board` varchar(512) NOT NULL DEFAULT '' COMMENT '悔棋的时候的盘面',
  `to_number` int(11) NOT NULL COMMENT '悔棋到第几手',
  `comment` varchar(32) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`,`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `nickname` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `login_times` int(11) NOT NULL,
  `b_win` int(11) NOT NULL DEFAULT 0,
  `b_lose` int(11) NOT NULL DEFAULT 0,
  `w_win` int(11) NOT NULL DEFAULT 0,
  `w_lose` int(11) NOT NULL DEFAULT 0,
  `draw` int(11) NOT NULL DEFAULT 0,
  `games` int(11) NOT NULL DEFAULT 0 COMMENT '总对局数',
  `reg_time` timestamp NOT NULL DEFAULT '2017-12-31 16:00:00',
  `reg_ip` varchar(15) NOT NULL DEFAULT '',
  `last_login_time` timestamp NOT NULL DEFAULT '2017-12-31 16:00:00',
  `last_login_ip` varchar(15) NOT NULL DEFAULT '',
  `score` decimal(15,3) NOT NULL,
  `language` varchar(8) NOT NULL DEFAULT 'zh-CN',
  `intro` varchar(128) NOT NULL DEFAULT 'hi,it''s me',
  PRIMARY KEY (`id`),
  KEY `email` (`email`(8)),
  KEY `nickname` (`nickname`(8)),
  KEY `score` (`score`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `player`
--

INSERT INTO `player` (`id`, `email`, `nickname`, `password`, `login_times`, `b_win`, `b_lose`, `w_win`, `w_lose`, `draw`, `games`, `reg_time`, `reg_ip`, `last_login_time`, `last_login_ip`, `score`, `language`, `intro`) VALUES
(1, 'xsir317@gmail.com', 'gmail', 'e20c8cb7b4ff787054dd18f6beef8343', 0, 0, 0, 2, 0, 0, 2, '2024-06-10 09:58:00', '127.0.0.1', '2024-06-10 09:58:00', '127.0.0.1', '2100.999', 'zh-CN', ''),
(2, '25403285@qq.com', 'qq', 'e20c8cb7b4ff787054dd18f6beef8343', 0, 0, 2, 0, 0, 0, 2, '2024-06-10 10:13:52', '127.0.0.1', '2024-06-10 10:37:35', '127.0.0.1', '2099.001', 'zh-CN', '');

-- --------------------------------------------------------

--
-- 表的结构 `score_log`
--

CREATE TABLE IF NOT EXISTS `score_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL COMMENT '玩家id',
  `op_id` int(11) NOT NULL COMMENT '对手id',
  `before_score` decimal(15,3) NOT NULL COMMENT '赛前分',
  `op_score` decimal(15,3) NOT NULL COMMENT '对手分',
  `k_val` tinyint(4) NOT NULL COMMENT 'K值',
  `delta_score` decimal(15,3) NOT NULL COMMENT '分差',
  `after_score` decimal(15,3) NOT NULL COMMENT '赛后分',
  `logtime` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

COMMIT;
