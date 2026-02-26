# 五子棋规则详解

## 1. 概述

本文档详细描述系统支持的六种五子棋规则的完整实现逻辑。由于专业规则逻辑复杂，代码分散在多个文件和多个方法中，本文档将系统性地梳理各规则的实现细节。

### 1.1 支持的规则列表

| 规则 | 说明 | 特点 |
|------|------|------|
| **Yamaguchi** | 山口规则 | 三手可交换，五手打点(1-12) |
| **RIF** | RIF规则 | 三手可交换，固定打2点 |
| **Soosyrv8** | 索索夫规则 | 三/四手可交换，四手声明打点数 |
| **TaraGuchi** | 纯行规则 | 多阶段交换，打点选择复杂 |
| **Gomoku** | 普通五子棋 | 无禁手，连五即胜 |
| **Renju** | 棋类规则 | 有禁手，黑棋限制多 |

---

## 2. 核心概念

### 2.1 棋盘表示

- **棋盘大小**：15×15
- **坐标系统**：十六进制 (1-f, 1-f)，中心点为 (8,8)
- **棋谱存储**：字符串连接，如 `"887868"` 表示连续三手棋

### 2.2 关键字段

| 字段 | 说明 |
|------|------|
| `game_record` | 完整棋谱字符串 |
| `a5_pos` | 打点位置坐标串 |
| `a5_numbers` | 打点数量 |
| `swap` | 交换标记位 |
| `soosyrv_swap` | Soosyrv规则交换标记 |
| `free_opening` | 0=标准开局，1=自由开局 |

### 2.3 turn 计算

turn 表示当前该哪一方落子：
- `turn = 1`：黑方落子
- `turn = 0`：白方落子

---

## 3. 规则详解

### 3.1 Gomoku（普通五子棋）

#### 3.1.1 规则特点
- 最简单的五子棋规则
- 无任何禁手限制
- 黑白棋连成五子即获胜
- 超过五连也算获胜

#### 3.1.2 实现逻辑
```php
// 在 checkWin 中区分规则
if ($game_object->rule == 'Gomoku') {
    $result = $checkwin->gomokuCheckWin($coordinate, $color);
} else {
    $result = $checkwin->checkWin($coordinate, $color);
}
```

#### 3.1.3 落子位置限制
- 自由开局：无限制
- 标准开局：需在指定区域

---

### 3.2 Renju（棋类规则）

#### 3.2.1 规则特点
- 黑棋有禁手限制
- 白棋无限制
- 仅白棋获胜时超过五连也算胜

#### 3.2.2 禁手类型（详见 GAME_RULES.md）
- **长连**：六连及以上
- **双四**：形成两个活四
- **双三**：形成两个活三

#### 3.2.3 实现逻辑
```php
// RenjuBoardTool_bit::checkWin
if ($color == 'white') {
    if ($this->isFive($coordinate, $stone)) {
        return self::WHITE_FIVE;
    }
} else {
    if ($this->isFive($coordinate, $stone)) {
        return self::BLACK_FIVE;
    }
    if ($this->isForbidden($coordinate)) {
        return self::BLACK_FORBIDDEN;
    }
}
```

---

### 3.3 RIF 规则

#### 3.3.1 规则特点
- **三手可交换**：前三手任意一手后可交换黑白
- **固定打点**：第3手后必须打2个点
- **黑棋有禁手**

#### 3.3.2 手数流程

```
第1手 → 黑棋(中心) → turn=1
第2手 → 白棋 → turn=0  
第3手 → 黑棋 → turn=1 (可选交换)
       ↓
   [可交换时机]
       ↓
第4手 → 白棋(选打点)
第5手 → 黑棋(选第5点)
       ↓
后续 → 正常对局
```

#### 3.3.3 代码实现

**Turn 计算** (`GameService::renderGame`):
```php
case 'RIF':
case 'Yamaguchi':
    if ($stones < 3) {
        $turn = 1;  // 前三手黑先
    } elseif ($stones == 3 && $game->a5_numbers == 0) {
        // Yamaguchi 专用：第3手后需设定打点数
        $waiting_for_a5_number = 1;
    } elseif ($stones == 3 && $game->a5_numbers > 0) {
        // 第3手已设定打点数，可交换
        $can_swap = $game->swap ? 0 : 1;
    } elseif ($stones == 4 && $a5_numbers == 已摆打点数) {
        $turn = 0;  // 打点摆完，等白棋选
    } else {
        $turn = 1 - ($stones % 2);  // 正常轮换
    }
    break;
```

**交换逻辑** (`PlayController::actionSwap`):
```php
case 'RIF':
case 'Yamaguchi':
    if ($stones == 3 && $game_object->a5_numbers > 0 && $game_object->swap == 0) {
        $allow_swap = true;  // 第3手后可交换
    }
    break;
```

**创建对局时的默认值**:
```php
$game->a5_numbers = $rule == 'RIF' ? 2 : 0;
```

---

### 3.4 Yamaguchi（山口规则）

#### 3.4.1 规则特点
- 继承 RIF 规则
- **三手后可设定打点数**：1-12个
- 后续可交换

#### 3.4.2 与 RIF 的区别

| 特性 | RIF | Yamaguchi |
|------|-----|-----------|
| 打点数量 | 固定2个 | 1-12个可选 |
| 交换时机 | 第3手后 | 设定打点数后 |
| a5_numbers 初始化 | 2 | 0 (需手动设定) |

#### 3.4.3 打点数设定流程

```
第1手(黑) → 第2手(白) → 第3手(黑)
                         ↓
              [系统提示：设定打点数]
                         ↓
         输入打点数量(如：5) → a5_numbers = 5
                         ↓
              [可选择交换]
                         ↓
第4手(白) → 第5手(黑) → 后续...
```

#### 3.4.4 代码实现

**等待设定打点数** (`PlayController::actionA5_number`):
```php
switch ($game_object->rule) {
    case 'Yamaguchi':
        if ($stones == 3) {
            $game_object->a5_numbers = min($number, 12);  // 最多12个
        }
        break;
    case 'Soosyrv8':
        if ($stones == 4) {
            $game_object->a5_numbers = min($number, 8);  // 最多8个
        }
        break;
}
```

---

### 3.5 Soosyrv8（索索夫规则）

#### 3.5.1 规则特点
- **三手可交换**
- **第四手声明打点数量**（非必须）
- 可在第4手后交换
- 打点数量：1-8个

#### 3.5.2 手数流程

```
第1手 → 黑棋
第2手 → 白棋
第3手 → 黑棋 → [可交换]
           ↓
第4手 → 白棋 → [声明打点数量] → [可交换]
           ↓
第5手+ → 摆打点 + 选第5点
```

#### 3.5.3 代码实现

**Turn 计算**:
```php
case 'Soosyrv8':
    if ($stones < 3) {
        $turn = 1;
    } elseif ($stones == 4) {
        if ($game->a5_numbers == 0) {
            // 第4手需声明打点数
            $waiting_for_a5_number = 1;
        } elseif ($game->a5_numbers == (strlen($game->a5_pos)/2)) {
            // 打点已摆完
            $turn = 0;
        } else {
            // 正在摆打点
            $turn = 1;
        }
    } else {
        $turn = 1 - ($stones % 2);
    }
    break;
```

**交换逻辑**:
```php
case 'Soosyrv8':
    if ($stones == 3 && $game_object->swap == 0) {
        $allow_swap = true;  // 第3手后
    } elseif ($stones == 4 && $game_object->a5_numbers > 0 
              && $game_object->a5_pos == '' && $game_object->soosyrv_swap == 0) {
        $allow_swap = true;  // 第4手声明打点数后可再交换
    }
    break;
```

**交换后处理**:
```php
if ($stones == 3) {
    $game_object->swap = 1;
} else {
    $game_object->soosyrv_swap = 1;  // 标记为第4手后的交换
}
```

---

### 3.6 TaraGuchi（纯行规则）

#### 3.6.1 规则特点

最复杂的规则，包含多阶段交换：

1. **第1-3手**：可任意交换
2. **第4手**：声明打点数量
3. **第5手**：
   - 选择1：交换 → 打1个点
   - 选择2：不交换 → 打10个点
4. 后续可继续交换

#### 3.6.2 手数流程

```
第1手(黑) → [可交换]
第2手(白) → [可交换]
第3手(黑) → [可交换]
            ↓
第4手(白) → 声明打点数量
            ↓
┌─────────────────────────────────────────┐
│ 第5手(黑)                              │
│ ┌────────────────┬───────────────────┐  │
│ │ 选择1:交换     │ 选择2:不交换       │  │
│ │ → a5_numbers=1│ → a5_numbers=10  │  │
│ │ (TaraOption1) │ (正常摆打点)      │  │
│ └────────────────┴───────────────────┘  │
└─────────────────────────────────────────┘
            ↓
后续 → 可继续交换
```

#### 3.6.3 交换状态存储

使用位运算存储每手是否交换：

```php
// swap 字段使用位标记
// bit 0 (1<<0): 第1手交换标记
// bit 1 (1<<1): 第2手交换标记
// bit 2 (1<<2): 第3手交换标记
// bit 3 (1<<3): 第4手交换标记
// bit 4 (1<<4): 第5手交换标记

$game_object->swap = ($game_object->swap | (1 << $stones));
```

#### 3.6.4 Turn 计算 (`taraguchi_turn` 方法)

```php
public static function taraguchi_turn($stones, $swap, $a5_pos, $a5_number)
{
    $turn = 1 - ($stones % 2);
    $can_swap = false;

    if ($stones == 0) {
        return [1, false];
    }

    // 检查当前手是否已交换
    if ($stones < 4) {
        // 前3手：检查对应bit是否未设置
        $can_swap = !boolval($swap & (1 << $stones));
    } elseif ($stones == 4) {
        // 第4手：必须是声明10个点且未摆打点
        if ($a5_number == 10 && $a5_pos == '') {
            $can_swap = !boolval($swap & (1 << $stones));
        }
    } elseif ($stones == 5) {
        // 第5手：必须是选择了交换(声明1个点)
        if ($a5_number == 1) {
            $can_swap = !boolval($swap & (1 << $stones));
        }
    }

    // 第4手摆完打点后，等白棋选
    if ($stones == 4 && $a5_number == (strlen($a5_pos)/2)) {
        $turn = 0;
    }

    return [$turn, $can_swap];
}
```

#### 3.6.5 交换逻辑

```php
case 'TaraGuchi':
    $tara_turns = GameService::taraguchi_turn(
        strlen($game_object->game_record)/2,
        $game_object->swap,
        $game_object->a5_pos,
        $game_object->a5_numbers
    );
    $allow_swap = $tara_turns[1];
    break;

// 交换执行
if ($game_object->rule == 'TaraGuchi') {
    $game_object->swap = ($game_object->swap | (1 << $stones));
    if ($stones == 4) {
        $game_object->a5_numbers = 1;  // 交换后只打1个点
    }
}
```

#### 3.6.6 选择1实现（TaraOption1）

```php
public function actionTaraOption1()
{
    // 条件：第4手、声明了>1个打点、还未摆打点
    if ($game_object->rule == 'TaraGuchi'
        && $stones == 4
        && $game_object->a5_numbers > 1
        && $game_object->a5_pos == '') 
    {
        $game_object->a5_numbers = 1;  // 改为1个点（交换）
    }
}
```

---

## 4. 开局限制

### 4.1 标准开局 vs 自由开局

| 模式 | free_opening | 说明 |
|------|--------------|------|
| 标准 | 0 | 必须在前3手在指定区域落子 |
| 自由 | 1 | 任意位置落子 |

### 4.2 标准开局坐标限制

**代码实现** (`PlayController::actionPlay`):
```php
if ($game_info['free_opening'] == 0) {
    // 第1手：必须中心(88)
    if ($stones == 0 && $coordinate != '88') {
        return error;
    }
    
    // 第2手：必须在3×3区域(77-99)
    if ($stones == 1 && (!in_array($x, [7,8,9]) || !in_array($y, [7,8,9]))) {
        return error;
    }
    
    // 第3手：必须在4×4区域(66-9a)
    if ($stones == 2 && (!in_array($x, [6,7,8,9,'a']) || !in_array($y, [6,7,8,9,'a']))) {
        return error;
    }
    
    // TaraGuchi 第4手：必须在5×5区域
    if ($stones == 3 && $rule == 'TaraGuchi' 
        && (!in_array($x, [5,6,7,8,9,'a','b']) || !in_array($y, [5,6,7,8,9,'a','b']))) {
        return error;
    }
    
    // TaraGuchi 第5手(交换后)：必须在9×9区域
    if ($stones == 4 && $rule == 'TaraGuchi' && $a5_numbers == 1
        && (!in_array($x, [4,5,6,7,8,9,'a','b','c']) || !in_array($y, [4,5,6,7,8,9,'a','b','c']))) {
        return error;
    }
}
```

### 4.3 可视化

```
标准开局落子区域：

第1手(88):           •
                 •  •  •
              •  •  •  •  •
           •  •  • 88 •  •  •  •
              •  •  •  •  •
                 •  •  •
                    •
                    
第2手:  3×3 区域
第3手: 4×4 区域  
第4手: 5×5 区域 (TaraGuchi)
```

---

## 5. 打点(A5)机制

### 5.1 打点流程

```
┌─────────────────────────────────────────┐
│ 1. 设定打点数量                          │
│    (根据规则设定上限)                    │
└──────────────────┬──────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│ 2. 摆放打点                              │
│    - 轮流摆放指定数量的点                 │
│    - 不能对称                             │
│    - 不能与已有棋子重叠                   │
└──────────────────┬──────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│ 3. 选择第5点                             │
│    - 只能选已摆放的打点                   │
│    - 选完后正式进入对局                   │
└─────────────────────────────────────────┘
```

### 5.2 规则对比

| 规则 | 设定时机 | 可选数量 | 备注 |
|------|----------|----------|------|
| RIF | 第3手后 | 固定2 | 不可改变 |
| Yamaguchi | 第3手后 | 1-12 | 可自定义 |
| Soosyrv8 | 第4手后 | 1-8 | 可自定义 |
| TaraGuchi | 第4手后 | 1或10 | 10=选打点，1=交换 |

### 5.3 对称检测

**方法**：`BoardTool::a5_symmetry(board, a5_pos)`

检测打点是否存在以下对称：
- 水平翻转
- 垂直翻转
- 中心对称
- 对角线对称
- 反对角线对称

```php
// 如果存在对称，返回 true（不允许）
if (BoardTool::a5_symmetry($board, $a5_pos)) {
    return error("打点不能对称");
}
```

### 5.4 打点代码逻辑

```php
// 第5手处理
if ($stones == 4 && 
    in_array($rule, ['Yamaguchi', 'RIF', 'Soosyrv8']) ||
    ($rule == 'TaraGuchi' && $a5_numbers > 1)) 
{
    $a5_on_board = strlen($a5_pos) / 2;
    
    if ($a5_on_board < $a5_numbers) {
        // 正在摆放打点
        $new_a5_pos = $a5_pos . $coordinate;
        
        // 检查对称
        if (board_correct($game_record . $new_a5_pos) && 
            !a5_symmetry($game_record, $new_a5_pos)) {
            $a5_pos = $new_a5_pos;
        }
    } else {
        // 选择第5点（只能从已摆放的打点中选择）
        if (in_array($coordinate, $a5_pos)) {
            $game_record .= $coordinate;  // 正式落子
        }
    }
}
```

---

## 6. 交换机制

### 6.1 交换的本质

交换后：
- 黑白方互换（ID交换）
- 剩余时间互换
- `swap` 字段标记已交换

```php
// 执行交换
$game_object->black_id = $game_info['white_id'];
$game_object->white_id = $game_info['black_id'];
$game_object->black_time = $game_info['white_time'];
$game_object->white_time = $game_info['black_time'];
```

### 6.2 各规则交换时机

| 规则 | 交换时机 | 备注 |
|------|----------|------|
| RIF | 第3手后 | 一次 |
| Yamaguchi | 设定打点数后 | 一次 |
| Soosyrv8 | 第3手后 + 第4手后 | 两次机会 |
| TaraGuchi | 第1-5手 | 多次 |

### 6.3 交换状态管理

```php
// RIF/Yamaguchi
$game_object->swap = 1;

// Soosyrv8 第4手后
$game_object->soosyrv_swap = 1;

// TaraGuchi (位运算标记)
$game_object->swap = $game_object->swap | (1 << $current_stone);
```

---

## 7. 数据表关键字段

### 7.1 games 表规则相关字段

| 字段 | 说明 | 规则差异 |
|------|------|----------|
| rule | 规则类型 | Yamaguchi/RIF/Soosyrv8/TaraGuchi/Gomoku/Renju |
| free_opening | 0=标准/1=自由 | 所有规则 |
| a5_numbers | 打点数量 | RIF=2, Yamaguchi=1-12, Soosyrv8=1-8, TaraGuchi=1/10 |
| a5_pos | 打点坐标串 | 所有规则 |
| swap | 交换标记 | RIF/Yamaguchi/TaraGuchi |
| soosyrv_swap | Soosyrv交换 | 仅 Soosyrv8 |

---

## 8. 代码架构分析

### 8.1 分散的逻辑

当前实现中，规则逻辑分散在：

| 文件 | 职责 |
|------|------|
| `GameService::renderGame` | 计算 turn、can_swap、waiting_for_a5_number |
| `PlayController::actionPlay` | 落子、开局校验、打点处理 |
| `PlayController::actionSwap` | 交换执行 |
| `PlayController::actionA5_number` | 设定打点数 |
| `PlayController::actionTaraOption1` | TaraGuchi 选择1 |
| `BoardTool::do_over` | 终局处理、积分计算 |

### 8.2 重构建议

建议将规则逻辑重构为**策略模式**：

```
GameRule (接口)
    ├── GomokuRule
    ├── RenjuRule
    ├── RIFRule
    ├── YamaguchiRule
    ├── Soosyrv8Rule
    └── TaraGuchiRule

每个规则类实现：
    - getTurn(stones, gameState): 当前该谁落子
    - canSwap(stones, gameState): 当前是否能交换
    - validateMove(stones, coordinate): 落子校验
    - handleA5Move(stones, coordinate): 打点处理
    - isWaitingForA5Number(stones): 是否等待设定打点数
    - checkWin(move): 胜负判断
```

---

## 9. 代码位置索引

| 功能 | 文件位置 |
|------|----------|
| Turn 计算 | `GameService::renderGame()` |
| TaraGuchi turn | `GameService::taraguchi_turn()` |
| 落子逻辑 | `PlayController::actionPlay()` |
| 交换逻辑 | `PlayController::actionSwap()` |
| 打点数设定 | `PlayController::actionA5_number()` |
| TaraGuchi选项1 | `PlayController::actionTaraOption1()` |
| 开局校验 | `PlayController::actionPlay()` |
| 打点对称检测 | `BoardTool::a5_symmetry()` |
| 棋盘校验 | `BoardTool::board_correct()` |
| 胜负判断 | `RenjuBoardTool_bit::checkWin()` |
| 积分结算 | `BoardTool::do_over()` |
