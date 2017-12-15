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
   * [前端](#前端)
   * [JS和棋盘的实现](#JS和棋盘的实现)
   * [JS和Websocket](#JS和Websocket)
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
**GatewayWorker-for-win**  
    这个目录和 GatewayWorker-master 分别是Windows和Linux下使用的Gateway-worker框架代码。 这2个目录和web目录是独立的三部分代码，并不会互相调用。
      
**GatewayWorker-master**   
    如上所述，这是Linux下的Websocket服务端。这2个目录下，你需要关注的一般就是Applications/GameWS/Events.php。
      
**web**  
    这个目录是Yii2搭建的网站。网站根目录在web/frontend/web/。 命名有点尴尬，凑合用吧。


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

这里注意一个细节就是，目前的队列用于发送房间用户名单，因为用户进出房间时，我会发一个当前用户列表给所有此房间内的用户，而为了降低复杂性，Websocket部分（Workerman部分）的PHP代码并不会去读数据库，于是就必须要将“下发某房间的用户列表”这件事加入队列，用队列去处理实际的render用户，下发列表的操作。所以才会有一个Queue脚本去反复执行，处理队列。

每个棋局作为一个房间，有单独的聊天频道，单独的用户列表，用户进出房间时都会触发刷新。

棋局内容有更新时，会下发棋局列表给大厅，下发当前棋局信息给房间内用户。 棋盘和网页上的具体信息和操作限制由页面js负责。

## 网页和HTTP接口
网站主体是网页，用Ajax和websocket与服务端交互。

网页端向服务器发起的请求，有发起对局邀请、响应对局邀请、落子、悔棋申请、悔棋同意/拒绝、和棋申请、认输等。

以对局邀请为例，网页上用户点击他人昵称，会弹出邀请对话框，设置好规则之后发出邀请；

服务器记录邀请数据之后，通过websocket向被邀请者下发一个邀请数据，在对方网页上会弹出相同的对话框请求对方同意。

对方同意了，则开始对局；如果对方有异议，比如觉得时间不合适，规则不合适，都可以修改，修改之后将作为对局邀请反向发回给最初的发起者确认。反复此过程直到一方放弃或者双方达成一致。

达成一致之后，服务器会生成一个games表的记录，并通知双方游戏开始，双方页面都会跳到对局页面。



## 前端
网页主要有board.js负责棋盘、棋局展示和操作逻辑， page.js负责页面其他部分（聊天区域文字展示、用户列表、各种交互等等），websocket.js负责处理websocket连接，处理发过来的数据（只管数据接收，具体在页面上如何展示是丢给page去做的）。

需要注意的是各个js的职能范围，负责棋盘的部分，不要越界去处理连接相关的或者页面交互相关的业务； 负责Websocket连接和数据交互的js，也不要去处理网页展示、交互的业务，否则一旦有类似的业务，就会导致重复代码。

分清楚各部分代码的职能范围，可以提高代码的可维护性，降低冗余。

## JS和棋盘的实现
整个棋盘主要由board和对局信息2部分组成，其中board是棋盘，棋盘的展现形式由当前棋局状态、当前用户身份决定。

对局信息包括黑白双方棋手身份、对局时限、规则、时间等信息构成，对局者申请悔棋、和棋也属于对局信息，这些信息都由board.js负责展示。

棋盘由背景图片和每个交叉点的div组成，每个交叉点在被点击之后会进入place_stone() 方法，具体处理放置棋子的操作和响应。

棋局进程由当前路径currgame（棋盘实际每个棋子的展示坐标）、当前终局路径endgame（当前盘面最远走到过的记录）和原始对局信息boardObj.gameData三部分共同维护。

    当前路径就是当前盘面的N个棋子的坐标拼接，长度等于2N，它负责表示当前棋局的状态。
    终局路径表示了当前盘面能前进到哪里。 比如你落了3个棋子88898a，然后退回到第一手88，那么当前路径是88，终局路径是88898a。终局路径用于棋局的"前进"功能。
    boardObj.gameData记录了页面初始化时获得的盘面数据，此数据用于页面信息的展示，在"恢复"操作时，也会用到。


boardObj.gameData用于保存棋局信息，在页面初始化时，从页面输出的json对象 gameObj获得；在websocket下发数据时，由game_info通知的内容获得。

boardObj.load() 方法，用于load一个对局数据。如上所说，gameData有页面初始化时的页面json对象，和websocket下发数据 这2个来源，但是它们的数据结构是完全一致的，具体可参照PHP部分代码的renderGame。 

load() 方法在被调用之后，将boardObj.gameData赋值为传入的游戏数据，对比当前盘面来决定是否播放落子声音，然后调用show_origin 来根据load进来的数据重新渲染整个棋盘：先调用渲染对局信息，然后将盘面退到空棋盘状态，修改当前终局，然后前进至终局状态。

主要棋盘逻辑其实是在place_stone方法里，这个方法负责向棋盘上放置棋子，此操作会同时影响currgame，可能影响endgame和游戏模式（目前有game和analyze两个模式），对局者在落子时，此方法还会调用Ajax请求，向服务器发送落子通知。

棋盘操作时，轮到哪位玩家落子、棋盘当前状态等信息，主要由服务端的`GameService::renderGame` 负责计算。因为涉及到专业规则，这部分逻辑相当复杂，所以前端只按照后端给出的计算结果来处理。

在倒计时结束时，调用了 notice_timeout 来提示服务器进行时间结算。

另，模式切换、播放声音和展示时间这几个方法有闭包结构。将局部用到的数据封在闭包内，可以防止意外地访问到不该访问的数据，尽量减小一个变量的作用域，也能增加代码的可维护性。

## JS和Websocket
Websocket在连接之后，需要发送Login请求给服务器，表明自己的身份，并且和当前用户（UID）绑定。  
鉴权失败的请求会被服务器端强制断开。

客户端收到消息之后，根据消息的类型自动调用相应的处理方法，进行处理。 大部分消息都是解析出数据之后，转交page去处理显示。

Websocket基本只承担了服务器端发生事件之后，主动通知客户端的工作；客户端要提交数据给服务器时，并不通过websocket，而是走POST接口，向网站的接口提交数据。

这样做的好处就在于Websocket端没有业务逻辑，不管数据库，代码相对也比较稳定，不太需要修改。

## 数据库设计与数据结构
    games 游戏表，每盘棋一个记录，其中比赛相关的tid字段目前无用。
    game_invites 游戏邀请记录，当然这个是个生存周期很短的数据，一般更适合放在redis里。
    game_undo_log 悔棋日志，算是一个比较特色的功能。 在悔棋的时候，我们会从棋盘上移除部分棋子。为了防止这部分的数据遗失，
    我们把悔棋发生时的盘面记录了下来，并且作为棋局记录的一部分，展示给观看者。这个日志的相关记录会在对局页面展示。
    player 用户表，没啥说的。
    score_log 等级分升降记录，这个以后也可能放在用户的对局列表页面展示出来。

## 其他已经实现的内容
  * 有禁手五子棋的胜负判断；
  * 无禁手五子棋的胜负判断；
  * RIF、山口、索索夫8 三个常见的连珠规则；
  * 悔棋和悔棋记录的保存、展示；
  * 聊天区域发送和展示棋盘分析；
  * 实时聊天以及聊天的表情。

