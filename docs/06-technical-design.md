# 五子棋项目技术设计文档

## 1. 系统架构

### 1.1 整体架构

```
┌─────────────────────────────────────────────────────────────────┐
│                         客户端层                                │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐   │
│  │   Web端     │  │  移动端APP  │  │    小程序/其他      │   │
│  │  (Vue/React)│  │  (Flutter)  │  │                     │   │
│  └──────┬──────┘  └──────┬──────┘  └──────────┬──────────┘   │
└─────────┼────────────────┼────────────────────┼──────────────┘
          │                │                    │
          └────────────────┴────────────────────┘
                             │
                    HTTP/WebSocket
                             │
┌─────────────────────────────┼──────────────────────────────────┐
│                         API网关层                              │
│  ┌─────────────────────────┼──────────────────────────────┐  │
│  │              Kong/Nginx (负载均衡、限流)                  │  │
│  └─────────────────────────┼──────────────────────────────┘  │
└─────────────────────────────┼──────────────────────────────────┘
                              │
┌─────────────────────────────┼──────────────────────────────────┐
│                        微服务层                                  │
│  ┌──────────────┐ ┌─────────┴──────────┐ ┌─────────────────┐  │
│  │   用户服务   │ │     游戏服务       │ │   匹配服务      │  │
│  │  (User Svc)  │ │   (Game Svc)       │ │ (Match Svc)     │  │
│  └──────────────┘ └────────────────────┘ └─────────────────┘  │
│  ┌──────────────┐ ┌────────────────────┐ ┌─────────────────┐  │
│  │   通知服务   │ │   等级分服务       │ │   规则引擎      │  │
│  │(Notify Svc)  │ │   (ELO Svc)        │ │ (Rule Engine)   │  │
│  └──────────────┘ └────────────────────┘ └─────────────────┘  │
└───────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────┼──────────────────────────────────┐
│                        数据层                                   │
│  ┌─────────────────────────┐┌──────────────────────────────┐  │
│  │   MySQL (主从复制)      ││      Redis (缓存/消息)       │  │
│  │  - 用户数据             ││   - 会话缓存                  │  │
│  │  - 棋局数据             ││   - 热点数据                  │  │
│  │  - 历史记录             ││   - 消息队列                  │  │
│  └─────────────────────────┘└──────────────────────────────┘  │
└───────────────────────────────────────────────────────────────┘
```

### 1.2 技术栈选择建议

| 层级 | 当前项目 | 重构建议 |
|------|---------|---------|
| 前端 | jQuery + Yii | Vue 3 + TypeScript 或 React |
| 后端 | PHP Yii | Node.js/NestJS 或 Go/Gin |
| WebSocket | GatewayWorker | 原生ws库 + Redis Pub/Sub |
| 数据库 | MySQL | MySQL + Redis |
| 缓存 | 无 | Redis |
| 部署 | 单机 | Docker + K8s(可选) |

## 2. 重新设计的WebSocket通信协议

### 2.1 设计原则

1. **状态分离**: 连接管理与游戏状态分离
2. **事件驱动**: 采用发布-订阅模式
3. **幂等性**: 关键操作可重试
4. **可扩展性**: 支持多种客户端

### 2.2 协议格式

统一采用JSON格式:

```json
{
  "type": "事件类型",
  "timestamp": 1234567890,
  "requestId": "uuid",
  "payload": { }
}
```

### 2.3 客户端事件 (Client → Server)

#### 2.3.1 连接管理

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `auth` | 连接认证 | `{token: string}` |
| `ping` | 心跳 | `{}` |
| `disconnect` | 主动断开 | `{reason: string}` |

#### 2.3.2 房间管理

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `room:join` | 加入房间 | `{roomId: string, type: "player|observer"}` |
| `room:leave` | 离开房间 | `{roomId: string}` |
| `room:list` | 获取房间列表 | `{page: number, filter: object}` |

#### 2.3.3 游戏操作

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `game:move` | 落子 | `{position: string, timestamp: number}` |
| `game:swap` | 交换黑白 | `{}` |
| `game:setA5` | 设置打点数 | `{count: number}` |
| `game:resign` | 认输 | `{}` |
| `game:offerDraw` | 提和 | `{}` |
| `game:replyDraw` | 回应和棋 | `{accept: boolean}` |
| `game:undo:request` | 申请悔棋 | `{toStep: number, reason?: string}` |
| `game:undo:reply` | 回应悔棋 | `{accept: boolean}` |

#### 2.3.4 聊天

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `chat:send` | 发送消息 | `{content: string, type: "text|emoji", to?: string}` |

### 2.4 服务端事件 (Server → Client)

#### 2.4.1 连接管理

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `auth:success` | 认证成功 | `{userId: string, profile: object}` |
| `auth:failed` | 认证失败 | `{reason: string}` |
| `pong` | 心跳响应 | `{timestamp: number}` |
| `error` | 通用错误 | `{code: string, message: string}` |

#### 2.4.2 房间事件

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `room:joined` | 成功加入房间 | `{roomId: string, role: string, gameState: object}` |
| `room:left` | 已离开房间 | `{roomId: string}` |
| `room:playerJoined` | 有玩家加入 | `{userId: string, profile: object, role: string}` |
| `room:playerLeft` | 有玩家离开 | `{userId: string, reason: string}` |
| `room:list` | 房间列表 | `{rooms: array, total: number}` |
| `room:updated` | 房间信息更新 | `{roomId: string, changes: object}` |

#### 2.4.3 游戏状态

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `game:started` | 游戏开始 | `{gameId: string, players: object, rule: string, settings: object}` |
| `game:ended` | 游戏结束 | `{gameId: string, result: object, reason: string}` |
| `game:state` | 完整游戏状态 | `{gameId: string, state: object}` |
| `game:moved` | 棋子落下 | `{gameId: string, position: string, color: string, step: number, playerId: string}` |
| `game:turn` | 轮到谁下 | `{gameId: string, playerId: string, color: string, timeoutAt: number}` |
| `game:swapped` | 交换黑白 | `{gameId: string, newBlack: string, newWhite: string}` |
| `game:a5Set` | 打点数设置 | `{gameId: string, count: number, by: string}` |
| `game:a5Placed` | 打点落下 | `{gameId: string, positions: array}` |
| `game:forbidden` | 禁手点 | `{gameId: string, positions: array}` |
| `game:winningLine` | 五连展示 | `{gameId: string, positions: array, color: string}` |

#### 2.4.4 玩家操作响应

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `game:resigned` | 认输 | `{gameId: string, by: string, color: string}` |
| `game:drawOffered` | 提和 | `{gameId: string, by: string}` |
| `game:drawReply` | 和棋回应 | `{gameId: string, accept: boolean, by: string}` |
| `game:undoRequested` | 悔棋申请 | `{gameId: string, by: string, toStep: number, reason?: string}` |
| `game:undoReply` | 悔棋回应 | `{gameId: string, accept: boolean, by: string}` |
| `game:undoDone` | 悔棋完成 | `{gameId: string, newRecord: string, toStep: number}` |

#### 2.4.5 时间控制

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `game:timeUpdate` | 时间更新 | `{gameId: string, blackTime: number, whiteTime: number, turn: string}` |
| `game:timeout` | 超时 | `{gameId: string, color: string, loser: string}` |

#### 2.4.6 聊天

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `chat:message` | 新消息 | `{roomId: string, message: object}` |
| `chat:system` | 系统消息 | `{roomId: string, content: string, type: string}` |

### 2.5 优化说明

相比旧项目的改进:

1. **结构化事件命名**: 使用`domain:action`格式，清晰表达意图
2. **状态与操作分离**: 客户端操作请求 vs 服务端状态广播分离
3. **完整的状态同步**: 支持游戏状态的完整恢复
4. **错误处理**: 统一的错误响应格式
5. **扩展性**: 易于添加新的事件类型
