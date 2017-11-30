这是一个基于Yii2和Workerman（Gateway-Worker）框架的简单游戏项目。

游戏使用Yii2作为游戏后端，前端使用Js，和Gateway-Worker建立websocket连接。

本项目旨在为五子棋爱好者和对Web技术感兴趣的玩家提供一个五子棋对弈的代码示例。

# 目录
-----

1. [安装/配置](#安装/配置)
   * [目录结构](#目录结构)
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

基于[Gateway-worker](http://www.workerman.net/gatewaydoc/)的websocket服务端有2个目录，Windows下请运行GatewayWorker-for-win\start_for_win.bat，Linux下请在GatewayWorker-master目录下进行手动启动。

## 系统需求
  * PHP7.0+
  * Mysql 5.6+
  * Redis
  * PHP的[redis扩展](https://github.com/phpredis/phpredis)

本系统服务端分为2部分，负责运行Web后端的Yii2框架项目，以及负责Websocket连接的GatewayWorker部分，两部分通过Workerman官方提供的Gateway.php 沟通。

基本数据存储在Mysql中，Redis用于缓存以及队列。

## 目录结构
GatewayWorker-for-win
    这个目录和 GatewayWorker-master 分别是Windows和Linux下使用的Gateway-worker框架代码。 这2个目录和web目录是独立的三部分代码，并不会互相调用。
GatewayWorker-master 
    如上所述，这是Linux下的Websocket服务端。这2个目录下，你需要关注的一般就是Applications/GameWS/Events.php。
web
    网站。网站根目录在web/frontend/web/。 命名有点尴尬，凑合用吧。


## 安装

## Linux 安装
   * 将代码check下来
   * 安装好系统需求里说的各种软件，当然PHP的Mysql PDO之类的也是不能少的。缺啥装啥。
   * 在./web 目录下执行 composer update， 有关composer问题请去[Composer中文网](http://www.phpcomposer.com/)获得更多帮助。
   * 在Mysql执行create_database.sql，然后修改web/enviorments/prod或者dev/config/main-local.php 的配置。
   * 在./web 目录下执行 php init，进行项目初始化。
   * 准备好域名，解析或者写host，然后修改nginx/Apache的配置，把网站根目录配置到web/frontend/web/，reload。
   * 去 GatewayWorker-master/Applications/GameWS 目录下，依次运行

    php start_register.php start -d
    php start_gateway.php start -d
    php start_businessworker.php start -d
   * 去 web/console/runtime 目录下，建立目录 console/runtime/logs/queue/。 然后去 web/console/bin 目录下执行 `nohup /bin/bash/ QueueManager.sh start &`。 如有需求可以将输出重定向到日志文件，方便查阅。


## Windows 安装

Windows安装与Linux类似，需要注意的就是
   1. Windows不能用作线上环境，仅用于开发调试；
   1. QueueManager.sh 这一步只能自己写代码解决，或者手动在web/ 目录下不停地执行  `php yii queue/notices/game/start` 来处理了。
   1. GatewayWorker-master用不上，请去GatewayWorker-for-win目录下，双击 start_for_win.bat 来启动Websocket。

## 常见问题
   1. GatewayWorker是一个分布式框架，在业务扩大之后可以分布式部署，增加服务器来承受更高负载。请参照其[官网](http://doc2.workerman.net/326145)。 
   1. 域名以及备案问题等，部署在国内云服务上经常会遇到很多非技术性问题；当然放国外可能会有更加非技术性的问题（墙）。


# 代码结构
-----

## 总体架构
整个系统的基础是一个简单的基于PHP和Mysql的Web网站，框架采用Yii2，落子、聊天以及各种操作都是Post，使用JS进行相当传统的Ajax操作。 每当服务器端有变动时，会通过 common/components/Gateway.php 将相应数据下发给需要通知到的客户端（websocket连接）。

这里注意一个细节就是，目前的队列用于发送房间用户名单，因为用户进出房间时，我会发一个当前用户列表给所有此房间内的用户，而为了降低复杂性，Websocket部分（Workerman部分）的PHP代码并不会去读数据库，于是就必须要将“下发某房间的用户列表”这件事加入队列，用队列去处理实际的render用户，下发列表的操作。

每个棋局作为一个房间，有单独的聊天频道，单独的用户列表，用户进出房间时都会触发刷新。

棋局内容有更新时，会下发棋局列表给大厅，下发当前棋局信息给房间内用户。 棋盘和网页上的具体信息和操作限制由页面js负责。

## Web端

## JS和棋盘的实现

## JS和Websoocket

## 数据库设计与数据结构

## 其他已经实现的内容

