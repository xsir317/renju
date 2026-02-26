# Renju 五子棋系统

## 1. 系统概述

### 1.1 项目背景
这是一个基于 Web 的五子棋/连珠在线对弈系统，支持多种规则对战，包括：
- 山口规则 (Yamaguchi)
- RIF 规则
- Soosyrv8 规则
- 纯行规则 (TaraGuchi)
- Gomoku 规则
- 棋类规则 (Renju)

### 1.2 技术架构
- **前端**：HTML/JavaScript (使用 WebSocket 进行实时通信)
- **后端**：PHP (Yii2 框架)
- **实时通信**：GatewayWorker (WebSocket)
- **缓存/队列**：Redis
- **数据库**：MySQL

### 1.3 系统角色
| 角色 | 说明 |
|------|------|
| 玩家 (Player) | 注册用户，可进行对局、观战、聊天 |
| 对局玩家 | 正在对局的黑方或白方 |
| 旁观者 | 观看对局的用户 |
| VIP 用户 | 付费用户，可创建更长时限的对局 |

---

## 2. 数据库结构 (需完全兼容)

### 2.1 表：player (玩家表)

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | int | 主键，自增 |
| email | varchar(64) | 邮箱（登录账号） |
| nickname | varchar(32) | 昵称 |
| password | char(32) | MD5 加密的密码 |
| login_times | int | 登录次数 |
| b_win | int | 执黑胜场 |
| b_lose | int | 执黑负场 |
| w_win | int | 执白胜场 |
| w_lose | int | 执白负场 |
| draw | int | 平局数 |
| games | int | 总对局数 |
| reg_time | timestamp | 注册时间 |
| reg_ip | varchar(15) | 注册 IP |
| last_login_time | timestamp | 最后登录时间 |
| last_login_ip | varchar(15) | 最后登录 IP |
| score | decimal(15,3) | 积分（默认 2100） |
| vip | tinyint | VIP 标识（0/1） |
| language | varchar(8) | 语言设置 (zh-CN/en-US/ja-JP) |
| intro | varchar(128) | 个人简介 |

### 2.2 表：games (对局表)

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | int | 主键，自增 |
| black_id | int | 黑方玩家 ID |
| white_id | int | 白方玩家 ID |
| status | tinyint | 对局状态 (0:未开始, 1:进行中, 2:黑胜, 4:白胜, 8:平局) |
| is_private | tinyint | 私密对局 (0:公开, 1:私密) |
| offer_draw | int | 提和玩家 ID (0:未提和) |
| rule | enum | 对局规则 |
| free_opening | tinyint | 自由开局 (0:标准, 1:自由) |
| allow_undo | tinyint | 允许悔棋 (0:否, 1:是) |
| allow_ob_talk | tinyint | 允许旁观说话 (0:否, 1:是) |
| game_record | varchar(450) | 棋谱（坐标字符串，如"8878..."） |
| black_time | int | 黑方剩余时间（秒） |
| white_time | int | 白方剩余时间（秒） |
| totaltime | int | 初始总时间（秒） |
| step_add_sec | int | 每步加秒数 |
| swap | tinyint | 交换标记 |
| soosyrv_swap | tinyint | Soosyrv 规则交换标记 |
| a5_pos | varchar(40) | 打点位置坐标 |
| a5_numbers | tinyint | 打点数量 |
| updtime | timestamp | 更新时间 |
| movetime | timestamp | 最后落子时间 |
| comment | varchar(64) | 对局备注 |
| tid | smallint | 比赛 ID (0:非比赛) |
| vip | tinyint | VIP 对局标记 |
| create_time | timestamp | 创建时间 |

### 2.3 表：game_invites (对战邀请表)

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | int | 主键，自增 |
| from | int | 邀请发起者 ID |
| to | int | 被邀请者 ID |
| black_id | int | 约定的执黑方 ID |
| message | varchar(64) | 邀请消息 |
| totaltime | int | 对局时间（秒） |
| step_add_sec | int | 每步加秒数 |
| rule | enum | 对局规则 |
| free_opening | int | 自由开局 |
| allow_undo | tinyint | 允许悔棋 |
| allow_ob_talk | tinyint | 允许旁观说话 |
| status | tinyint | 状态 (0:等待响应, 1:已同意, -1:已失效) |
| is_private | tinyint | 私密邀请 |
| game_id | int | 成立后关联的对局 ID |
| updtime | timestamp | 更新时间 |

### 2.4 表：game_undo_log (悔棋记录表)

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | int | 主键，自增 |
| game_id | int | 对局 ID |
| uid | int | 申请悔棋的玩家 ID |
| current_board | varchar(512) | 悔棋时的盘面 |
| to_number | int | 悔棋到第几手 |
| comment | varchar(32) | 申请说明 |
| status | tinyint | 状态 (0:待处理, 1:已同意, -1:已拒绝) |
| created_time | timestamp | 创建时间 |

### 2.5 表：score_log (积分日志表)

| 字段名 | 类型 | 说明 |
|--------|------|------|
| id | int | 主键，自增 |
| game_id | int | 对局 ID |
| player_id | int | 玩家 ID |
| op_id | int | 对手 ID |
| before_score | decimal(15,3) | 战前积分 |
| op_score | decimal(15,3) | 对手积分 |
| k_val | tinyint | K 值（ELO 评分系数） |
| delta_score | decimal(15,3) | 积分变化 |
| after_score | decimal(15,3) | 战后积分 |
| logtime | timestamp | 记录时间 |

---

## 3. 功能模块

### 3.1 用户系统

#### 3.1.1 注册
- 邮箱注册，密码 MD5 加密存储
- 昵称限制：最长 10 个字符
- 新用户初始积分：2100 分

#### 3.1.2 登录
- 邮箱 + 密码登录
- 登录成功后记录 IP 和时间

#### 3.1.3 个人资料
- 修改个人简介
- 语言切换（中文/英文/日文）

### 3.2 大厅系统

#### 3.2.1 功能
- 显示在线用户列表
- 显示进行中的对局列表
- 公共聊天

#### 3.2.2 WebSocket 连接
- 端口：8282
- Token 验证机制

### 3.3 对局系统

#### 3.3.1 创建对局
- 创建对战邀请
- 设置规则、时间、是否允许悔棋等
- VIP 用户可创建更长时限的对局（最高 2000 小时）

#### 3.3.2 对局规则
| 规则 | 说明 |
|------|------|
| Yamaguchi | 山口规则，三手可交换，五手打点 |
| RIF | RIF 规则，三手可交换，打点 2 个 |
| Soosyrv8 | 索索夫规则，四手打点 |
| TaraGuchi | 纯行规则，复杂交换 |
| Gomoku | 普通五子棋 |
| Renju | 棋类规则 |

#### 3.3.3 对局操作
- **落子**：在棋盘上放置棋子
- **交换**：在特定时机交换黑白方
- **打点**：设定第五手打点数量和位置
- **提和**：申请和棋
- **认输**：主动认输
- **悔棋**：申请悔棋（需对方同意）

#### 3.3.4 时限系统
- 读秒制：每方有有限时间
- 步时加秒：每步棋增加指定秒数
- 超时判负

#### 3.3.5 胜负判定
- 五连成棋（普通规则）
- 长连禁手（棋类规则）
- 超时判负
- 认输
- 双方提和

### 3.4 积分系统

#### 3.4.1 ELO 评分
- K 值：见 score_log 表
- 战前战后积分记录

### 3.5 聊天系统

#### 3.5.1 消息类型
- 大厅公共聊天
- 对局室内聊天
- 系统通知

---

## 4. WebSocket 通信协议

### 4.1 客户端 → 服务端

| 消息类型 | 说明 | 参数 |
|----------|------|------|
| login | 登录房间 | game_id, nickname, reconnect |
| say | 发送消息 | content, board(可选) |
| games | 获取对局列表 | - |
| invite | 创建/响应邀请 | (多种参数) |
| play | 落子 | game_id, coordinate |
| swap | 交换黑白 | game_id |
| offer_draw | 提和 | game_id |
| resign | 认输 | game_id |
| undo | 悔棋申请 | game_id, to_step |
| undo_reply | 悔棋响应 | undo_id, action |
| a5_number | 打点数量 | game_id, number |

### 4.2 服务端 → 客户端

| 消息类型 | 说明 | 参数 |
|----------|------|------|
| enter | 进入房间 | client_id, history_msg |
| login | 用户加入 | user |
| logout | 用户离开 | user |
| client_list | 在线用户列表 | client_list |
| game_info | 对局信息 | game |
| games | 对局列表 | games |
| say | 聊天消息 | content, from_user, board |
| invite | 邀请通知 | invite |
| game_start | 对局开始 | game_id |
| game_over | 对局结束 | content |
| notice | 系统通知 | content |
| room_announce | 房间公告 | user, content |
| shutdown | 关闭连接 | content |
| pong | 心跳响应 | - |

### 4.3 消息格式

```json
{
  "type": "消息类型",
  "msg_id": "消息ID",
  // 其他参数...
}
```

### 4.4 安全机制
- Token + Secret 验证
- Timestamp 防重放
- Checksum 校验

---

## 5. 重构指导

### 5.1 架构建议

#### 5.1.1 后端
- 保持使用 PHP 或迁移到 Node.js/Go
- 使用现代框架 (Laravel/Symfony 或 Express/Koa)
- 保持 WebSocket 实时通信

#### 5.1.2 前端
- 建议使用 Vue/React 重构
- 保留 WebSocket 通信层

### 5.2 数据库兼容性

**必须保持兼容的字段**：
- player 表：所有字段
- games 表：所有字段
- game_invites 表：所有字段
- game_undo_log 表：所有字段
- score_log 表：所有字段

### 5.3 业务逻辑保留

需要完整实现的业务逻辑：
1. 多种规则的胜负判定算法
2. ELO 积分系统
3. 时限和读秒系统
4. 悔棋逻辑
5. 交换/打点逻辑

### 5.4 关键业务规则

#### 5.4.1 山口规则 (Yamaguchi)
- 前 3 手可交换
- 第 3 手后需设定打点数量 (1-12)
- 棋盘 15x15
- 黑棋有禁手

#### 5.4.2 RIF 规则
- 前 3 手可交换
- 固定打点 2 个
- 黑棋有禁手

#### 5.4.3 纯行规则 (TaraGuchi)
- 复杂的多手交换机制
- 多阶段打点

---

## 6. 文件结构参考

### 6.1 现有结构
```
web/
├── common/           # 公共组件
│   ├── components/   # 棋盘逻辑、Gateway封装
│   ├── models/       # 数据模型
│   └── services/     # 业务服务
├── frontend/         # 前端应用
│   ├── controllers/  # HTTP控制器
│   ├── modules/      # 游戏模块
│   └── web/js/       # 前端JS
└── console/          # 控制台命令
GatewayWorker/        # WebSocket服务
```

### 6.2 建议重构后结构
```
src/
├── app/              # 应用核心
│   ├── Controllers/  # HTTP控制器
│   ├── Services/    # 业务逻辑
│   └── Models/       # 数据模型
├── ws/               # WebSocket服务
│   ├── Handlers/     # 消息处理
│   └── Game/         # 游戏逻辑
├── GameCore/         # 棋类核心算法
│   ├── Rules/        # 规则实现
│   └── Board/        # 棋盘处理
└── Database/         # 数据库相关
    └── Repositories/ # 数据仓储
```

---

## 7. 注意事项

1. **安全性**：重构时需保持密码加密、IP 限制等安全机制
2. **实时性**：对局操作需即时广播给所有旁观者
3. **事务处理**：棋局结算、积分更新需要事务保证
4. **并发处理**：超时判定、悔棋等需要处理并发冲突
