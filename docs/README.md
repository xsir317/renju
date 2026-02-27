# 五子棋项目重构文档

## 文档概述

本文档集合为五子棋在线对弈平台的重构提供了完整的需求分析和设计指导。

## 文档清单

| 编号 | 文档 | 说明 | 重点内容 |
|-----|-----|------|---------|
| 01 | [01-requirements.md](./01-requirements.md) | **功能需求文档** | 数据库结构、完整功能需求（邀请/协商/悔棋/求和/私密对局/聊天等）、ELO等级分、WebSocket协议 |
| 02 | [02-elo-rating.md](./02-elo-rating.md) | **ELO等级分系统** | 计算公式、K值规则、变动记录、排名机制 |
| 03 | [03-rule-descriptions.md](./03-rule-descriptions.md) | **五子棋规则说明** | 6种规则详细玩法：Gomoku、Renju、RIF、Yamaguchi、Soosyrv8、TaraGuchi |
| 04 | [04-websocket-protocol.md](./04-websocket-protocol.md) | **WebSocket协议** | 优化版通信协议设计、事件命名规范、消息格式、完整事件列表 |
| 05 | [05-rule-engine.md](./05-rule-engine.md) | **规则引擎设计** | RenjuRule接口定义、BaseRenjuRule抽象类、规则注册机制 |
| 06 | [06-technical-design.md](./06-technical-design.md) | **技术设计** | 系统架构、API设计、数据库优化、缓存策略、部署架构 |
| 07 | [07-algorithm-details.md](./07-algorithm-details.md) | **核心算法** | 禁手判断算法、悔棋逻辑、棋谱存储、开局定式识别、时间控制 |

## 关键设计决策

### 1. 数据库兼容

**必须完全兼容现有的数据库结构**，字段说明已在 [01-requirements.md](./01-requirements.md) 的"数据库结构"章节详细列出。

主要表:
- `player` - 用户表
- `games` - 对局表
- `game_invites` - 对局邀请表
- `game_undo_log` - 悔棋记录表
- `score_log` - 等级分变动记录表

### 2. WebSocket协议优化

**与旧项目不兼容**，重新设计了更清晰的事件命名和消息结构:

- 事件命名: `domain:action` 格式 (如 `game:move`, `chat:message`)
- 支持请求-响应模式 (带 `requestId`)
- 明确的状态同步机制

详情见 [04-websocket-protocol.md](./04-websocket-protocol.md)。

### 3. 规则引擎设计

**核心抽象**:

```typescript
interface RenjuRule {
  readonly id: string;
  readonly hasForbiddenMoves: boolean;
  readonly supportsSwap: boolean;
  readonly requiresA5Count: boolean;

  validateMove(state: GameState, move: Move): ValidationResult;
  applyMove(state: GameState, move: Move): GameState;
  checkGameEnd(state: GameState): GameEndResult | null;
  // ...
}
```

每种规则独立实现，互不干扰，易于添加新规则。

详情见 [05-rule-engine.md](./05-rule-engine.md) 和 [03-rule-descriptions.md](./03-rule-descriptions.md)。

## 重构实施建议

### 阶段一: 基础架构 (Week 1-2)

1. 搭建新的项目框架
2. 配置数据库连接(兼容现有结构)
3. 实现基础用户认证
4. 搭建WebSocket服务

### 阶段二: 规则引擎 (Week 3-4)

1. 实现规则引擎基类
2. 实现Gomoku规则(最简单)
3. 实现RIF规则(验证引擎能力)
4. 实现Yamaguchi规则
5. 添加规则单元测试

### 阶段三: 游戏核心 (Week 5-6)

1. 实现对局创建、落子逻辑
2. 实现悔棋、提和功能
3. 实现时间控制
4. 实现胜负判定

### 阶段四: WebSocket通信 (Week 7)

1. 实现新WebSocket协议
2. 实现房间管理
3. 实现实时消息推送

### 阶段五: 前端开发 (Week 8-9)

1. 实现棋盘组件
2. 实现对局界面
3. 实现大厅、房间列表
4. 实现用户系统界面

### 阶段六: 测试与优化 (Week 10)

1. 集成测试
2. 性能测试
3. 安全测试
4. 部署上线

## 技术栈建议

### 后端
- **语言**: Node.js (TypeScript) 或 Go
- **框架**: NestJS / Express 或 Gin
- **WebSocket**: ws 库
- **数据库**: MySQL 8.0 (兼容现有结构)
- **缓存**: Redis
- **消息队列**: Redis Pub/Sub 或 RabbitMQ

### 前端
- **框架**: Vue 3 + TypeScript 或 React
- **状态管理**: Pinia 或 Redux Toolkit
- **UI库**: Element Plus 或 Ant Design
- **WebSocket**: 原生 WebSocket API

### 部署
- **容器**: Docker
- **编排**: Docker Compose / Kubernetes
- **CI/CD**: GitHub Actions / GitLab CI
- **监控**: Prometheus + Grafana

## 注意事项

1. **数据库兼容**: 必须完全兼容现有数据库字段，不能修改字段定义
2. **等级分计算**: 必须严格按照ELO公式实现，确保与现有数据兼容
3. **棋谱格式**: 保持现有的坐标编码方式(16进制两位表示)
4. **开局定式**: 26种标准开局名称需要保持一致

## 联系与支持

如有问题或需要进一步澄清，请联系项目相关人员。

---

*本文档最后更新: 2026-02-27*
