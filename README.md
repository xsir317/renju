这是一个基于Yii2和Workerman（Gateway-Worker）框架的简单游戏项目。

游戏使用Yii2作为游戏后端，前端使用Js，和Gateway-Worker建立websocket连接。

本项目旨在为五子棋爱好者和对Web技术感兴趣的玩家提供一个五子棋对弈的代码示例。

# 目录
-----

1. [安装/配置](#安装/配置)
   * [安装](#安装)
   * [系统需求](#系统需求)
   * [Linux 安装](#Linux-安装)
   * [Windows 安装](#Windows-安装)
   * [常见问题](#常见问题)
1. [代码结构](#代码结构)
   * [总体架构](#总体架构)
   * [Web端](#Web端)
   * [JS和棋盘的实现](#JS和棋盘的实现)
   * [JS和Websoocket](#JS和Websoocket)
   * [数据库设计与数据结构](#数据库设计与数据结构)
   * [其他已经实现的内容](#其他已经实现的内容)

# 安装/配置
-----

[本项目](https://github.com/xsir317/renju)运行在PHP和Mysql下，另需要Redis作为缓存和队列。由于Windows下Workerman官方不建议承载较大压力，故建议线上环境只部署在Linux。

基于[Gateway-worker](http://www.workerman.net/gatewaydoc/)的websocket服务端有2个目录，Windows下请运行GatewayWorker-for-win\start_for_win.bat，Linux下请在GatewayWorker-master目录下运行 `php start.php start -d`

## 系统需求
  * PHP7.0+
  * Mysql 5.6+
  * Redis
  * PHP的[redis扩展](https://github.com/phpredis/phpredis)

## 安装

## Linux 安装

## Windows 安装

## 常见问题


# 代码结构
-----

## 总体架构

## Web端

## JS和棋盘的实现

## JS和Websoocket

## 数据库设计与数据结构

## 其他已经实现的内容

