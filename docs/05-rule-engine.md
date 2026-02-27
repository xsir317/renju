# 规则引擎设计

## 1. 概述

规则引擎是五子棋系统的核心组件，负责处理不同规则的验证、状态管理和胜负判定。每种规则独立实现，通过统一的接口与系统交互。

## 2. 核心接口设计

### 2.1 RenjuRule 接口

```typescript
interface RenjuRule {
  /** 规则唯一标识符 */
  readonly id: string;

  /** 规则显示名称（i18n key） */
  readonly name: string;

  /** 是否有禁手（仅对黑棋） */
  readonly hasForbiddenMoves: boolean;

  /** 是否支持交换黑白 */
  readonly supportsSwap: boolean;

  /** 是否需要设置五手打点数 */
  readonly requiresA5Count: boolean;

  /**
   * 初始化游戏状态
   * @param settings 游戏设置
   * @returns 初始游戏状态
   */
  initializeGame(settings: GameSettings): GameState;

  /**
   * 验证落子是否合法
   * @param state 当前游戏状态
   * @param move 落子操作
   * @returns 验证结果
   */
  validateMove(state: GameState, move: Move): MoveValidationResult;

  /**
   * 执行落子，返回新状态
   * @param state 当前游戏状态
   * @param move 落子操作
   * @returns 新游戏状态
   */
  applyMove(state: GameState, move: Move): GameState;

  /**
   * 检查游戏是否结束
   * @param state 当前游戏状态
   * @returns 游戏结束结果，未结束返回null
   */
  checkGameEnd(state: GameState): GameEndResult | null;

  /**
   * 获取当前回合信息
   * @param state 当前游戏状态
   * @returns 回合信息
   */
  getTurnInfo(state: GameState): TurnInfo;

  /**
   * 处理交换黑白请求
   * @param state 当前游戏状态
   * @returns 交换后的新状态
   */
  handleSwap(state: GameState): GameState;

  /**
   * 处理设置五手打点数
   * @param state 当前游戏状态
   * @param count 打点数
   * @returns 设置后的新状态
   */
  handleSetA5Count(state: GameState, count: number): GameState;

  /**
   * 获取开局限制（第N手的坐标限制）
   * @param moveNumber 手数（从1开始）
   * @returns 位置限制，无限制返回null
   */
  getOpeningRestrictions(moveNumber: number): PositionRestriction | null;
}
```

### 2.2 核心类型定义

```typescript
/** 游戏设置 */
interface GameSettings {
  ruleId: string;
  totalTime: number;      // 总时间（秒）
  stepAddSec: number;     // 每步加秒
  allowUndo: boolean;     // 是否允许悔棋
  isPrivate: boolean;     // 是否私密对局
  blackPlayerId: string;
  whitePlayerId: string;
}

/** 游戏状态 */
interface GameState {
  board: BoardCell[][];           // 15x15棋盘
  moves: Move[];                  // 落子历史
  currentColor: 'black' | 'white';
  step: number;                   // 当前手数
  status: 'playing' | 'ended';
  result?: GameEndResult;

  // 规则相关状态
  swapAvailable: boolean;         // 是否可以交换
  a5Count: number;                // 五手打点数
  a5Positions: string[];           // 打点位置

  // 时间相关
  timestamps: {
    start: number;
    lastMove: number;
    blackRemaining: number;  // 秒
    whiteRemaining: number;  // 秒
  };
}

/** 棋盘单元格 */
interface BoardCell {
  type: 'empty' | 'black' | 'white';
  step?: number;        // 手数标记
  timestamp?: number;   // 落子时间
}

/** 落子操作 */
interface Move {
  position: string;     // 如 "88"
  color: 'black' | 'white';
  timestamp: number;
  thinkingTime?: number; // 思考时间（秒）
}

/** 落子验证结果 */
interface MoveValidationResult {
  valid: boolean;
  reason?: string;      // 错误代码，如 "NOT_YOUR_TURN"
  details?: any;        // 额外信息
}

/** 游戏结束结果 */
interface GameEndResult {
  winner: 'black' | 'white' | 'draw';
  reason: string;       // 如 "FIVE_IN_A_ROW", "FORBIDDEN_MOVE"
  winningLine?: string[]; // 获胜连线坐标
  details?: any;
}

/** 回合信息 */
interface TurnInfo {
  color: 'black' | 'white';
  playerId: string;
  step: number;
  timeoutAt: number;    // 超时时间点
}

/** 位置限制 */
interface PositionRestriction {
  type: 'exact' | 'area';
  position?: string;    // type=exact时使用
  size?: number;        // type=area时，限制区域大小
  center?: string;      // type=area时，中心点
}
```

## 3. 抽象基类实现

```typescript
abstract class BaseRenjuRule implements RenjuRule {
  abstract readonly id: string;
  abstract readonly name: string;
  abstract readonly hasForbiddenMoves: boolean;
  abstract readonly supportsSwap: boolean;
  abstract readonly requiresA5Count: boolean;

  protected readonly boardSize = 15;

  /**
   * 创建空棋盘
   */
  protected createEmptyBoard(): BoardCell[][] {
    return Array(this.boardSize).fill(null).map(() =>
      Array(this.boardSize).fill({ type: 'empty' })
    );
  }

  /**
   * 初始化游戏
   */
  initializeGame(settings: GameSettings): GameState {
    return {
      board: this.createEmptyBoard(),
      moves: [],
      currentColor: 'black',
      step: 0,
      status: 'playing',
      swapAvailable: false,
      a5Count: 0,
      a5Positions: [],
      timestamps: {
        start: Date.now(),
        lastMove: Date.now(),
        blackRemaining: settings.totalTime,
        whiteRemaining: settings.totalTime
      }
    };
  }

  /**
   * 验证位置有效性
   */
  protected isValidPosition(row: number, col: number): boolean {
    return row >= 0 && row < this.boardSize && col >= 0 && col < this.boardSize;
  }

  /**
   * 解析位置字符串（如 "88"）为行列索引
   */
  protected parsePosition(pos: string): { row: number; col: number } {
    const row = parseInt(pos[0], 16) - 1;
    const col = parseInt(pos[1], 16) - 1;
    return { row, col };
  }

  /**
   * 将行列索引格式化为位置字符串
   */
  protected formatPosition(row: number, col: number): string {
    return `${(row + 1).toString(16)}${(col + 1).toString(16)}`;
  }

  /**
   * 基础落子验证
   */
  validateMove(state: GameState, move: Move): MoveValidationResult {
    const { row, col } = this.parsePosition(move.position);

    // 验证位置在棋盘内
    if (!this.isValidPosition(row, col)) {
      return { valid: false, reason: 'POSITION_OUT_OF_BOARD' };
    }

    // 验证位置为空
    if (state.board[row][col].type !== 'empty') {
      return { valid: false, reason: 'POSITION_OCCUPIED' };
    }

    // 验证轮次
    if (move.color !== state.currentColor) {
      return { valid: false, reason: 'NOT_YOUR_TURN' };
    }

    return { valid: true };
  }

  /**
   * 执行落子
   */
  applyMove(state: GameState, move: Move): GameState {
    const newState = deepClone(state);
    const { row, col } = this.parsePosition(move.position);

    // 放置棋子
    newState.board[row][col] = {
      type: move.color,
      step: newState.step + 1,
      timestamp: Date.now()
    };

    // 更新状态
    newState.moves.push(move);
    newState.step++;
    newState.currentColor = newState.currentColor === 'black' ? 'white' : 'black';
    newState.timestamps.lastMove = Date.now();

    // 计算用时
    const elapsed = (Date.now() - state.timestamps.lastMove) / 1000;
    if (move.color === 'black') {
      newState.timestamps.blackRemaining -= elapsed;
    } else {
      newState.timestamps.whiteRemaining -= elapsed;
    }

    return newState;
  }

  /**
   * 获取回合信息
   */
  getTurnInfo(state: GameState): TurnInfo {
    return {
      color: state.currentColor,
      playerId: state.currentColor === 'black' ? 'blackPlayer' : 'whitePlayer',
      step: state.step + 1,
      timeoutAt: state.timestamps.lastMove +
        (state.currentColor === 'black' ?
          state.timestamps.blackRemaining :
          state.timestamps.whiteRemaining) * 1000
    };
  }

  // 抽象方法：子类必须实现
  abstract checkGameEnd(state: GameState): GameEndResult | null;
  abstract handleSwap(state: GameState): GameState;
  abstract handleSetA5Count(state: GameState, count: number): GameState;
  abstract getOpeningRestrictions(moveNumber: number): PositionRestriction | null;
}
```

## 4. 规则注册与管理

```typescript
/**
 * 规则引擎管理类
 */
class RuleEngine {
  private rules: Map<string, RenjuRule> = new Map();

  /**
   * 注册规则
   */
  registerRule(rule: RenjuRule): void {
    this.rules.set(rule.id, rule);
  }

  /**
   * 获取规则实例
   */
  getRule(id: string): RenjuRule | undefined {
    return this.rules.get(id);
  }

  /**
   * 获取所有可用规则
   */
  getAllRules(): RenjuRule[] {
    return Array.from(this.rules.values());
  }

  /**
   * 初始化默认规则
   */
  initializeDefaultRules(): void {
    this.registerRule(new GomokuRule());
    this.registerRule(new RenjuRule());
    this.registerRule(new RIFRule());
    this.registerRule(new YamaguchiRule());
    this.registerRule(new Soosyrv8Rule());
    this.registerRule(new TaraGuchiRule());
  }
}
```

## 5. 使用示例

```typescript
// 创建规则引擎
const ruleEngine = new RuleEngine();
ruleEngine.initializeDefaultRules();

// 获取规则
const rifRule = ruleEngine.getRule('RIF');

// 创建游戏
const gameState = rifRule.initializeGame({
  ruleId: 'RIF',
  totalTime: 600,
  stepAddSec: 10,
  allowUndo: true,
  isPrivate: false,
  blackPlayerId: 'user1',
  whitePlayerId: 'user2'
});

// 验证并执行落子
const move: Move = { position: '88', color: 'black', timestamp: Date.now() };
const validation = rifRule.validateMove(gameState, move);

if (validation.valid) {
  const newState = rifRule.applyMove(gameState, move);
  const endResult = rifRule.checkGameEnd(newState);
  // ...
}
```

---

*本文档描述了规则引擎的核心接口和抽象基类设计。*
