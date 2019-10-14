-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-01-31 14:57:17
-- 服务器版本： 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `renju`
--
CREATE DATABASE IF NOT EXISTS `renju` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `renju`;

-- --------------------------------------------------------

--
-- 表的结构 `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `black_id` int(11) NOT NULL,
  `white_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `offer_draw` int(11) NOT NULL DEFAULT '0' COMMENT '如果有玩家提和，记录其id',
  `rule` enum('Yamaguchi','RIF','Soosyrv8','Renju','Gomoku') NOT NULL DEFAULT 'Yamaguchi',
  `free_opening` tinyint(4) NOT NULL DEFAULT '0',
  `allow_undo` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可申请悔棋',
  `game_record` varchar(450) NOT NULL DEFAULT '',
  `black_time` int(11) NOT NULL COMMENT '剩余秒数',
  `white_time` int(11) NOT NULL,
  `totaltime` int(11) NOT NULL COMMENT '秒数',
  `swap` tinyint(4) NOT NULL,
  `soosyrv_swap` tinyint(4) NOT NULL DEFAULT '0' COMMENT '索索夫规则的第四手交换',
  `a5_pos` varchar(40) NOT NULL DEFAULT '',
  `a5_numbers` tinyint(4) UNSIGNED NOT NULL,
  `updtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `movetime` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00' COMMENT '最后落子时间',
  `comment` varchar(64) NOT NULL,
  `tid` smallint(6) NOT NULL DEFAULT '0' COMMENT '比赛id',
  `create_time` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `game_invites`
--

CREATE TABLE `game_invites` (
  `id` int(11) NOT NULL,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `black_id` int(11) NOT NULL,
  `message` varchar(64) NOT NULL,
  `totaltime` int(11) NOT NULL,
  `rule` enum('Yamaguchi','RIF','Soosyrv8','Renju','Gomoku') NOT NULL DEFAULT 'Yamaguchi',
  `free_opening` int(11) NOT NULL,
  `allow_undo` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否可申请悔棋',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0等待响应 1已同意 -1 已失效',
  `game_id` int(11) NOT NULL DEFAULT '0',
  `updtime` timestamp NOT NULL DEFAULT '2016-12-31 16:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `game_undo_log`
--

CREATE TABLE `game_undo_log` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '提出悔棋的玩家id',
  `current_board` varchar(512) NOT NULL DEFAULT '' COMMENT '悔棋的时候的盘面',
  `to_number` int(11) NOT NULL COMMENT '悔棋到第几手',
  `comment` varchar(32) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `player`
--

CREATE TABLE `player` (
  `id` int(11) NOT NULL,
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
  `language` varchar(8) NOT NULL DEFAULT 'zh-CN',
  `intro` varchar(128) NOT NULL DEFAULT 'hi,it''s me'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `score_log`
--

CREATE TABLE `score_log` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL COMMENT '玩家id',
  `op_id` int(11) NOT NULL COMMENT '对手id',
  `before_score` decimal(15,3) NOT NULL COMMENT '赛前分',
  `op_score` decimal(15,3) NOT NULL COMMENT '对手分',
  `k_val` tinyint(4) NOT NULL COMMENT 'K值',
  `delta_score` decimal(15,3) NOT NULL COMMENT '分差',
  `after_score` decimal(15,3) NOT NULL COMMENT '赛后分',
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `board_win_statistics` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `board` binary(60) NOT NULL COMMENT '二进制的棋盘',
 `white_wins` int(11) NOT NULL DEFAULT '0',
 `black_wins` int(11) NOT NULL DEFAULT '0',
 `draws` int(11) NOT NULL DEFAULT '0',
 `next_move` varchar(2048) NOT NULL DEFAULT '{}' COMMENT '{ "aa" :[1,2,3]} 这样的下一手走法胜率记录',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `black_id` (`black_id`),
  ADD KEY `white_id` (`white_id`),
  ADD KEY `updtime` (`updtime`),
  ADD KEY `game_record` (`game_record`(128)),
  ADD KEY `tid` (`tid`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `game_invites`
--
ALTER TABLE `game_invites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from` (`from`),
  ADD KEY `to` (`to`);

--
-- Indexes for table `game_undo_log`
--
ALTER TABLE `game_undo_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`,`status`) USING BTREE;

--
-- Indexes for table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`(8)),
  ADD KEY `nickname` (`nickname`(8)),
  ADD KEY `score` (`score`);

--
-- Indexes for table `score_log`
--
ALTER TABLE `score_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `player_id` (`player_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `game_invites`
--
ALTER TABLE `game_invites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `game_undo_log`
--
ALTER TABLE `game_undo_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `player`
--
ALTER TABLE `player`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `score_log`
--
ALTER TABLE `score_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;