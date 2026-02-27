# 核心算法详细说明

## 1. 禁手判断算法

### 1.1 基本概念

#### 棋型定义
- **活三**: 两端都是空位的三连
- **跳活三**: 中间隔一个空位的活三
- **活四**: 两端都是空位的四连，必成活五
- **冲四**: 再添一子即可连五获胜的形状， 例如：`0XXXX` `XX0XX` `X0XXX`
- **连五**: 连续五子，获胜

### 1.2 双三禁手判断

```typescript
/**
 * 判断某点是否形成双三禁手
 * @param board 棋盘状态
 * @param row 行索引
 * @param col 列索引
 * @returns 是否是双三禁手
 */
function isDoubleThree(
  board: Board,
  row: number,
  col: number
): boolean {
  // 模拟放置黑子
  const tempBoard = copyBoard(board);
  tempBoard[row][col] = 'black';

  let openThreeCount = 0;
  const directions = [[0, 1], [1, 0], [1, 1], [1, -1]];

  for (const [dr, dc] of directions) {
    if (hasOpenThree(tempBoard, row, col, dr, dc)) {
      openThreeCount++;
      if (openThreeCount >= 2) {
        return true;
      }
    }
  }

  return false;
}

/**
 * 检查指定方向上是否有活三
 */
function hasOpenThree(
  board: Board,
  row: number,
  col: number,
  dr: number,
  dc: number
): boolean {
  // 获取该方向上的棋型
  const pattern = getLinePattern(board, row, col, dr, dc, 5);

  // 活三模式(0表示空位，X表示黑子)
  const openThreePatterns = [
    '0XXX0',    // 三连中间
    '0XX0X',    // 跳活三1
    'X0XX0',    // 跳活三2
    '0X0XX',    // 跳活三3
    'XX0X0',    // 跳活三4
  ];

  return openThreePatterns.some(p => pattern.includes(p));
}

/**
 * 获取指定方向上的棋型字符串
 */
function getLinePattern(
  board: Board,
  row: number,
  col: number,
  dr: number,
  dc: number,
  length: number
): string {
  let pattern = '';
  const color = board[row][col];

  for (let i = -length; i <= length; i++) {
    const r = row + dr * i;
    const c = col + dc * i;

    if (i === 0) {
      pattern += 'X'; // 当前位置
    } else if (r < 0 || r >= 15 || c < 0 || c >= 15) {
      pattern += 'B'; // 边界
    } else if (board[r][c] === 'empty') {
      pattern += '0'; // 空位
    } else if (board[r][c] === color) {
      pattern += 'X'; // 同色
    } else {
      pattern += 'O'; // 异色
    }
  }

  return pattern;
}
```

### 1.3 双四禁手判断

```typescript
/**
 * 判断某点是否形成双四禁手
 * @param board 棋盘状态
 * @param row 行索引
 * @param col 列索引
 * @returns 是否是双四禁手
 */
function isDoubleFour(
  board: Board,
  row: number,
  col: number
): boolean {
  const tempBoard = copyBoard(board);
  tempBoard[row][col] = 'black';

  let fourCount = 0;
  const directions = [[0, 1], [1, 0], [1, 1], [1, -1]];

  for (const [dr, dc] of directions) {
    const fourType = hasFour(tempBoard, row, col, dr, dc);
    if (fourType === 'open') {
      fourCount += 1;
    } else if (fourType === 'double') {
      // 双四在同一条线上，算2个
      fourCount += 2;
    }

    if (fourCount >= 2) {
      return true;
    }
  }

  return false;
}

/**
 * 检查指定方向上是否有四
 * @returns 'none' | 'closed' | 'open' | 'double'
 */
function hasFour(
  board: Board,
  row: number,
  col: number,
  dr: number,
  dc: number
): string {
  const pattern = getLinePattern(board, row, col, dr, dc, 6);

  // 四的模式(包括冲四和活四)
  // 活四: 两端开放，必成活五
  const openFourPatterns = [
    '0XXXX0',
  ];

  // 冲四: 一端被堵
  const closedFourPatterns = [
    'BXXXX0',
    'OXXXX0',
    '0XXXXB',
    '0XXXXO',
  ];

  // 双四(同一直线上的两个四)
  const doubleFourPatterns = [
    '0XXX0X0',
    '0X0XXX0',
  ];

  if (doubleFourPatterns.some(p => pattern.includes(p))) {
    return 'double';
  }

  if (openFourPatterns.some(p => pattern.includes(p))) {
    return 'open';
  }

  if (closedFourPatterns.some(p => pattern.includes(p))) {
    return 'closed';
  }

  return 'none';
}
```

### 1.4 长连禁手判断

```typescript
/**
 * 判断某点是否形成长连禁手(超过五子连线)
 * @param board 棋盘状态
 * @param row 行索引
 * @param col 列索引
 * @returns 是否是长连禁手
 */
function isOverline(
  board: Board,
  row: number,
  col: number
): boolean {
  const tempBoard = copyBoard(board);
  tempBoard[row][col] = 'black';

  const directions = [[0, 1], [1, 0], [1, 1], [1, -1]];

  for (const [dr, dc] of directions) {
    const count = countInDirection(tempBoard, row, col, dr, dc, 'black');
    if (count > 5) {
      return true;
    }
  }

  return false;
}

/**
 * 在指定方向上统计连续同色棋子数
 */
function countInDirection(
  board: Board,
  row: number,
  col: number,
  dr: number,
  dc: number,
  color: string
): number {
  let count = 1;

  // 正向
  for (let i = 1; i < 15; i++) {
    const r = row + dr * i;
    const c = col + dc * i;
    if (r >= 0 && r < 15 && c >= 0 && c < 15 && board[r][c] === color) {
      count++;
    } else {
      break;
    }
  }

  // 反向
  for (let i = 1; i < 15; i++) {
    const r = row - dr * i;
    const c = col - dc * i;
    if (r >= 0 && r < 15 && c >= 0 && c < 15 && board[r][c] === color) {
      count++;
    } else {
      break;
    }
  }

  return count;
}
```

## 2. 悔棋逻辑

### 2.1 悔棋申请流程

```typescript
interface UndoRequest {
  id: string;
  gameId: string;
  requesterId: string; // 申请悔棋的玩家
  targetStep: number;  // 悔棋到第几手
  currentBoard: string; // 申请时的盘面记录
  reason?: string;
  status: 'pending' | 'accepted' | 'rejected';
  createdAt: number;
  respondedAt?: number;
}

/**
 * 申请悔棋
 */
async function requestUndo(
  gameId: string,
  userId: string,
  targetStep: number,
  reason?: string
): Promise<UndoRequest> {
  const game = await getGame(gameId);

  // 验证
  if (game.status !== 'playing') {
    throw new Error('GAME_NOT_IN_PROGRESS');
  }

  if (!game.players.includes(userId)) {
    throw new Error('NOT_GAME_PLAYER');
  }

  if (!game.settings.allowUndo) {
    throw new Error('UNDO_NOT_ALLOWED');
  }

  const currentStep = game.moves.length;

  // 目标手数必须在合理范围
  if (targetStep <= 5 || targetStep >= currentStep) {
    throw new Error('INVALID_UNDO_TARGET');
  }

  // 确保悔棋后轮到申请方落子
  const isBlack = game.blackId === userId;
  if (isBlack && targetStep % 2 === 0) {
    // 黑方悔棋，目标手数应为奇数(轮到黑下)
    throw new Error('INVALID_UNDO_TARGET_FOR_BLACK');
  }
  if (!isBlack && targetStep % 2 === 1) {
    // 白方悔棋，目标手数应为偶数(轮到白下)
    throw new Error('INVALID_UNDO_TARGET_FOR_WHITE');
  }

  // 检查是否有未处理的悔棋申请
  const existingRequest = await getPendingUndoRequest(gameId);
  if (existingRequest) {
    // 如果盘面已变化，自动作废旧申请
    if (existingRequest.currentBoard !== game.record) {
      await cancelUndoRequest(existingRequest.id, 'BOARD_CHANGED');
    } else {
      throw new Error('PENDING_UNDO_REQUEST_EXISTS');
    }
  }

  // 创建悔棋申请
  const request: UndoRequest = {
    id: generateId(),
    gameId,
    requesterId: userId,
    targetStep,
    currentBoard: game.record,
    reason,
    status: 'pending',
    createdAt: Date.now()
  };

  await saveUndoRequest(request);

  // 通知对手
  const opponentId = game.players.find(id => id !== userId);
  notifyUser(opponentId, {
    type: 'game:undoRequested',
    payload: {
      gameId,
      requestId: request.id,
      requesterId: userId,
      targetStep,
      reason
    }
  });

  return request;
}

/**
 * 回应悔棋申请
 */
async function respondUndo(
  requestId: string,
  userId: string,
  accept: boolean
): Promise<void> {
  const request = await getUndoRequest(requestId);

  if (!request || request.status !== 'pending') {
    throw new Error('REQUEST_NOT_FOUND_OR_PROCESSED');
  }

  const game = await getGame(request.gameId);

  // 验证回应者身份
  const opponentId = game.players.find(id => id !== request.requesterId);
  if (userId !== opponentId) {
    throw new Error('NOT_AUTHORIZED');
  }

  // 验证盘面是否变化
  if (game.record !== request.currentBoard) {
    await cancelUndoRequest(requestId, 'BOARD_CHANGED');
    throw new Error('BOARD_CHANGED');
  }

  request.status = accept ? 'accepted' : 'rejected';
  request.respondedAt = Date.now();
  await saveUndoRequest(request);

  if (accept) {
    // 执行悔棋
    await executeUndo(game, request);
  }

  // 通知双方
  notifyUsers([request.requesterId, opponentId], {
    type: 'game:undoReplied',
    payload: {
      gameId: game.id,
      requestId: request.id,
      accept
    }
  });
}

/**
 * 执行悔棋操作
 */
async function executeUndo(game: Game, request: UndoRequest): Promise<void> {
  // 恢复到指定手数
  const newMoves = game.moves.slice(0, request.targetStep - 1);

  // 重新构建棋盘
  const newBoard = createEmptyBoard();
  for (let i = 0; i < newMoves.length; i++) {
    const move = newMoves[i];
    const { row, col } = parsePosition(move.position);
    newBoard[row][col] = {
      color: move.color,
      step: i + 1
    };
  }

  // 悔棋补偿: 给同意悔棋方增加10%时间
  const isRequesterBlack = game.blackId === request.requesterId;
  const timeBonus = game.settings.totalTime * 0.1;

  const updates: Partial<Game> = {
    moves: newMoves,
    board: newBoard,
    currentStep: request.targetStep,
    currentColor: request.targetStep % 2 === 1 ? 'black' : 'white',
    ...(isRequesterBlack
      ? { whiteTimeRemaining: game.whiteTimeRemaining + timeBonus }
      : { blackTimeRemaining: game.blackTimeRemaining + timeBonus }
    ),
    undoHistory: [...(game.undoHistory || []), {
      fromStep: game.moves.length,
      toStep: request.targetStep,
      requesterId: request.requesterId,
      timestamp: Date.now()
    }]
  };

  await updateGame(game.id, updates);
}
```

## 3. 时间控制算法

```typescript
interface TimeControl {
  // 总时间(秒)
  mainTime: number;
  // 每步加秒
  byoyomi: number;
  // 加时次数
  byoyomiPeriods?: number;
}

/**
 * 计算当前剩余时间
 */
function calculateRemainingTime(
  game: Game,
  color: 'black' | 'white'
): number {
  const isBlack = color === 'black';
  const baseTime = isBlack
    ? game.timestamps.blackRemaining
    : game.timestamps.whiteRemaining;

  // 如果是当前行棋方，减去已用时间
  if (game.currentColor === color) {
    const elapsed = (Date.now() - game.timestamps.lastMove) / 1000;
    return Math.max(0, baseTime - elapsed);
  }

  return baseTime;
}

/**
 * 处理超时
 */
async function handleTimeout(
  game: Game,
  color: 'black' | 'white'
): Promise<void> {
  const winner = color === 'black' ? 'white' : 'black';

  await endGame(game.id, {
    winner,
    reason: 'TIMEOUT',
    loser: color
  });

  // 通知双方
  notifyPlayers(game, {
    type: 'game:ended',
    payload: {
      winner,
      reason: 'TIMEOUT',
      message: `${color === 'black' ? '黑方' : '白方'}超时，${winner === 'black' ? '黑方' : '白方'}获胜`
    }
  });
}
```

## 4. 棋谱存储与复盘

```typescript
/**
 * 棋谱格式 (兼容现有)
 * 每个棋子用2位16进制表示行列(1-F)
 * 如: 88表示天元(第8行第8列)
 * 完整棋谱: "887868..."
 */

interface GameRecord {
  // 棋谱字符串
  record: string;
  // 每步的详细信息
  moves: MoveDetail[];
  // 关键局面标记
  keyPositions: KeyPosition[];
}

interface MoveDetail {
  step: number;
  position: string;
  color: 'black' | 'white';
  timestamp: number;
  thinkingTime: number; // 思考时间(秒)
  comment?: string;
}

/**
 * 解析棋谱字符串为详细记录
 */
function parseRecord(record: string): MoveDetail[] {
  const moves: MoveDetail[] = [];

  for (let i = 0; i < record.length; i += 2) {
    const pos = record.substr(i, 2);
    moves.push({
      step: Math.floor(i / 2) + 1,
      position: pos,
      color: (i / 2) % 2 === 0 ? 'black' : 'white',
      timestamp: 0,
      thinkingTime: 0
    });
  }

  return moves;
}

/**
 * 生成棋谱字符串
 */
function generateRecord(moves: MoveDetail[]): string {
  return moves.map(m => m.position).join('');
}

/**
 * 导出为其他格式
 */
function exportToFormat(record: GameRecord, format: 'sgf' | 'pdn' | 'json'): string {
  switch (format) {
    case 'sgf':
      return exportToSGF(record);
    case 'json':
      return JSON.stringify(record, null, 2);
    default:
      throw new Error('UNSUPPORTED_FORMAT');
  }
}

/**
 * 导出为SGF格式
 */
function exportToSGF(record: GameRecord): string {
  const lines: string[] = [];
  lines.push('(;FF[4]GM[41]'); // FF=文件格式, GM=游戏类型(41=五子棋)

  // 对局信息
  lines.push(`SZ[15]`); // 棋盘大小

  // 每步记录
  for (const move of record.moves) {
    const coord = positionToSGFCoord(move.position);
    const prefix = move.color === 'black' ? 'B' : 'W';
    lines.push(`;${prefix}[${coord}]`);
  }

  lines.push(')');
  return lines.join('\n');
}

/**
 * 将位置字符串转换为SGF坐标
 * 88 -> oo (天元)
 */
function positionToSGFCoord(pos: string): string {
  const row = parseInt(pos[0], 16);
  const col = parseInt(pos[1], 16);

  // SGF使用字母, a=1, b=2, ...
  // 跳过 'i' (SGF传统)
  const colChar = String.fromCharCode('a'.charCodeAt(0) + col - 1 + (col >= 9 ? 1 : 0));
  const rowChar = String.fromCharCode('a'.charCodeAt(0) + row - 1 + (row >= 9 ? 1 : 0));

  return colChar + rowChar;
}
```

## 5. 开局定式识别

```typescript
/**
 * 26种开局定式名称
 */
const OPENING_NAMES: Record<string, string> = {
  '887868': '寒星', '887869': '溪月', '88786a': '疏星',
  '887879': '花月', '88787a': '残月', '887889': '雨月',
  '88788a': '金星', '887898': '松月', '887899': '丘月',
  '88789a': '新月', '8878a8': '瑞星', '8878a9': '山月',
  '8878aa': '游星', '88796a': '长星', '88797a': '峡月',
  '88798a': '恒星', '88799a': '水月', '8879aa': '流星',
  '887989': '云月', '887999': '浦月', '8879a9': '岚月',
  '887998': '银月', '8879a8': '明星', '887997': '斜月',
  '8879a7': '名月', '8879a6': '彗星'
};

/**
 * 识别开局定式
 * @param record 棋谱记录(至少前3手)
 * @returns 开局名称，未识别返回null
 */
function identifyOpening(record: string): string | null {
  if (record.length < 6) return null;

  // 获取前3手(6个字符)
  const first3Moves = record.substring(0, 6).toLowerCase();

  // 标准化处理: 将局面变换到标准形式
  const normalized = normalizeOpening(first3Moves);

  return OPENING_NAMES[normalized] || null;
}

/**
 * 标准化开局定式
 * 通过旋转、翻转、平移将局面变换到标准形式
 */
function normalizeOpening(moves: string): string {
  // 解析3手棋子的坐标
  const stones: Array<{row: number, col: number, color: number}> = [];
  for (let i = 0; i < 6; i += 2) {
    const row = parseInt(moves[i], 16) - 1;
    const col = parseInt(moves[i + 1], 16) - 1;
    stones.push({ row, col, color: i / 2 });
  }

  // 平移: 使第一手位于天元(7,7)
  const deltaRow = 7 - stones[0].row;
  const deltaCol = 7 - stones[0].col;
  stones.forEach(s => {
    s.row += deltaRow;
    s.col += deltaCol;
  });

  // 如果第一手不在天元，无法标准化
  if (stones[0].row !== 7 || stones[0].col !== 7) {
    return moves; // 返回原始字符串
  }

  // 尝试8种对称变换，找到字典序最小的表示
  const transformations = [
    // 旋转0°, 90°, 180°, 270°
    (r: number, c: number) => [r, c],
    (r: number, c: number) => [14 - c, r],
    (r: number, c: number) => [14 - r, 14 - c],
    (r: number, c: number) => [c, 14 - r],
    // 水平翻转后旋转
    (r: number, c: number) => [r, 14 - c],
    (r: number, c: number) => [c, r],
    (r: number, c: number) => [14 - r, c],
    (r: number, c: number) => [14 - c, 14 - r],
  ];

  let bestRepresentation = moves;

  for (const transform of transformations) {
    const transformed = stones.map(s => {
      const [nr, nc] = transform(s.row, s.col);
      return { row: nr, col: nc, color: s.color };
    });

    // 检查合法性(所有坐标在0-14范围内)
    if (transformed.every(s => s.row >= 0 && s.row < 15 && s.col >= 0 && s.col < 15)) {
      // 生成字符串表示
      let repr = '';
      for (const s of transformed) {
        repr += String.fromCharCode('a'.charCodeAt(0) + s.row);
        repr += String.fromCharCode('a'.charCodeAt(0) + s.col);
      }

      // 选择字典序最小的
      if (repr < bestRepresentation) {
        bestRepresentation = repr;
      }
    }
  }

  return bestRepresentation;
}
```

---

*本文档详细描述了五子棋项目的核心算法实现，包括禁手判断、悔棋逻辑、棋谱存储和开局定式识别等。*
