# WebSocket通信协议设计

## 1. 设计原则

### 1.1 核心原则

- **状态分离**: 连接管理与业务状态分离
- **事件驱动**: 采用发布-订阅模式
- **幂等性**: 关键操作可重试，不重复生效
- **可扩展性**: 支持多种客户端类型

### 1.2 消息格式

所有消息采用JSON格式，统一结构如下：

```json
{
  "type": "事件类型",
  "timestamp": 1234567890,
  "requestId": "uuid-optional",
  "payload": {}
}
```

字段说明：
- `type`: 事件类型，采用 `domain:action` 格式
- `timestamp`: 消息发送时间戳（毫秒）
- `requestId`: 可选，客户端请求标识，服务端响应时原样返回
- `payload`: 事件负载数据

## 2. 客户端事件 (Client → Server)

### 2.1 连接管理

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `auth` | 连接认证 | `{token: string, clientInfo: object}` |
| `ping` | 心跳 | `{}` |
| `disconnect` | 主动断开 | `{reason: string}` |

**认证示例：**
```json
{
  "type": "auth",
  "timestamp": 1709016000000,
  "payload": {
    "token": "Bearer eyJhbGciOiJIUzI1NiIs...",
    "clientInfo": {
      "version": "1.0.0",
      "platform": "web"
    }
  }
}
```

### 2.2 房间管理

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `room:join` | 加入房间 | `{roomId: string, role: "player|observer"}` |
| `room:leave` | 离开房间 | `{roomId: string}` |

### 2.3 游戏操作

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `game:move` | 落子 | `{position: string}` |
| `game:swap` | 交换黑白 | `{}` |
| `game:setA5` | 设置打点数 | `{count: number}` |
| `game:resign` | 认输 | `{}` |
| `game:offerDraw` | 提和 | `{}` |
| `game:replyDraw` | 回应和棋 | `{accept: boolean}` |
| `game:undo:request` | 申请悔棋 | `{toStep: number, reason?: string}` |
| `game:undo:reply` | 回应悔棋 | `{accept: boolean}` |

**落子示例：**
```json
{
  "type": "game:move",
  "timestamp": 1709016001234,
  "requestId": "req-123456",
  "payload": {
    "position": "88"
  }
}
```

### 2.4 聊天

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `chat:send` | 发送消息 | `{content: string, type: "text|emoji|board", board?: string}` |

## 3. 服务端事件 (Server → Client)

### 3.1 连接管理

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `auth:success` | 认证成功 | `{userId: string, profile: object}` |
| `auth:failed` | 认证失败 | `{reason: string}` |
| `pong` | 心跳响应 | `{timestamp: number}` |
| `error` | 通用错误 | `{code: string, message: string}` |

### 3.2 房间事件

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `room:joined` | 成功加入房间 | `{roomId: string, role: string, gameState: object}` |
| `room:playerJoined` | 有玩家加入 | `{userId: string, profile: object, role: string}` |
| `room:playerLeft` | 有玩家离开 | `{userId: string, reason: string}` |

### 3.3 游戏状态

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `game:started` | 游戏开始 | `{gameId: string, players: object, rule: string, settings: object}` |
| `game:ended` | 游戏结束 | `{gameId: string, result: object, reason: string}` |
| `game:state` | 完整游戏状态 | `{gameId: string, state: object}` |
| `game:moved` | 棋子落下 | `{gameId: string, position: string, color: string, step: number, playerId: string}` |
| `game:turn` | 轮到谁下 | `{gameId: string, playerId: string, color: string, timeoutAt: number}` |
| `game:swapped` | 交换黑白 | `{gameId: string, newBlack: string, newWhite: string}` |

### 3.4 玩家操作响应

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `game:resigned` | 认输 | `{gameId: string, by: string, color: string}` |
| `game:drawOffered` | 提和 | `{gameId: string, by: string}` |
| `game:undoRequested` | 悔棋申请 | `{gameId: string, by: string, toStep: number}` |
| `game:undoReply` | 悔棋回应 | `{gameId: string, accept: boolean}` |
| `game:undoDone` | 悔棋完成 | `{gameId: string, newRecord: string, toStep: number}` |

### 3.5 聊天

| 事件类型 | 说明 | payload |
|---------|------|---------|
| `chat:message` | 新消息 | `{roomId: string, message: object}` |
| `chat:system` | 系统消息 | `{roomId: string, content: string, type: string}` |

## 4. 与旧协议的对比

| 特性 | 旧协议 | 新协议 |
|-----|--------|--------|
| 事件命名 | 不统一，如 `actionLogin`、`buildSay` | 统一 `domain:action` 格式 |
| 请求响应 | 无明确对应关系 | 支持 `requestId` 追踪 |
| 频道订阅 | 无 | 支持 `subscribe` 订阅 |
| 状态同步 | 不明确 | 明确 `game:state` 完整状态 |
| 错误处理 | 不统一 | 统一 `error` 事件 |

## 5. 使用示例

### 5.1 完整对局流程

```javascript
// 1. 连接并认证
ws.send(JSON.stringify({
  type: 'auth',
  payload: { token: 'Bearer xxx' }
}));

// 2. 收到认证成功
// { type: 'auth:success', payload: { userId: '123', profile: {...} } }

// 3. 加入游戏房间
ws.send(JSON.stringify({
  type: 'room:join',
  payload: { roomId: 'game-456', role: 'player' }
}));

// 4. 收到游戏开始
// { type: 'game:started', payload: { gameId: 'game-456', rule: 'RIF', ... } }

// 5. 落子
ws.send(JSON.stringify({
  type: 'game:move',
  requestId: 'move-001',
  payload: { position: '88' }
}));

// 6. 收到落子确认和对手落子
// { type: 'game:moved', payload: { position: '88', color: 'black', step: 1 } }
// { type: 'game:moved', payload: { position: '87', color: 'white', step: 2 } }

// 7. 游戏结束
// { type: 'game:ended', payload: { winner: 'black', reason: 'FIVE_IN_A_ROW' } }
```

---

*本文档描述了优化后的WebSocket通信协议设计。*
