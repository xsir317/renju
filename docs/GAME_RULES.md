# 五子棋胜负判断逻辑

## 1. 概述

本文档详细描述五子棋/连珠游戏的胜负判断算法思路。系统实现了两种核心判断逻辑：

1. **普通五子棋 (Gomoku)** - 只要连成五子即获胜
2. **棋类规则 (Renju)** - 黑棋有禁手限制，白棋无限制

---

## 2. 核心数据结构

### 2.1 棋盘表示

系统使用 **位运算** 来高效表示 15x15 的棋盘：

```php
// 每行使用一个 int32 存储
// 低 16 位：黑棋位置 (bit 0-15)
// 高 16 位：白棋位置 (bit 16-31)

class RenjuBoardTool_bit
{
    const BLACK_STONE = 'b';
    const WHITE_STONE = 'w';
    const EMPTY_STONE = '.';
}
```

### 2.2 坐标系统

- 使用十六进制字符串表示坐标，如 `"8878"` 表示 (8,8) 和 (7,8)
- 行号范围：1-15（十六进制 1-f）
- 列号范围：1-15（十六进制 1-f）

### 2.3 四个方向

系统定义了四个基本方向用于判断连子：

```
| (竖向)     : [+1,0], [-1,0]    下、上
- (横向)    : [0,+1], [0,-1]    前、后
\ (主对角线): [+1,+1], [-1,-1]   右下、左上
/ (副对角线): [+1,-1], [-1,+1]   左下、右上
```

---

## 3. 基础算法

### 3.1 连子计数算法

**核心方法**：`count_stone(coordinate, shape)`

```
算法思路：
1. 从指定坐标出发，先将当前棋子计入总数（count = 1）
2. 沿指定方向的正向遍历，统计同色连续棋子
3. 返回起点，沿指定方向的反向遍历，统计同色连续棋子
4. 返回总数
```

```php
private function count_stone($coordinate, $shape)
{
    $color = $this->_($coordinate);  // 获取当前坐标棋子颜色
    if ($color != EMPTY) {
        $count = 1;
        // 正向遍历
        foreach ($directions[$shape] as $direction) {
            moveTo(coordinate);
            while (color == moveDirection(direction)) {
                $count++;
            }
        }
        return $count;
    }
    return 0;
}
```

### 3.2 连五判断

**核心方法**：`isFive(coordinate, color, shape, rule)`

```
算法思路：
1. 在指定坐标临时放置棋子
2. 统计该方向上的连续同色棋子数
3. 根据规则判断是否构成"五"：
   - 普通五子棋：连子数 == 5
   - 棋类规则-黑棋：连子数 == 5（超过5不算）
   - 棋类规则-白棋：连子数 >= 5（五连以上都算胜）
4. 移除临时放置的棋子
5. 返回判断结果
```

```php
private function count_as_five($number, $color, $rule = 'renju')
{
    if ($color == BLACK_STONE) {
        return $number == 5;  // 黑棋必须正好5个
    }
    // 白棋在 Renju 规则下超过5也算胜
    return ($rule == 'renju') ? ($number >= 5) : ($number == 5);
}
```

---

## 4. 禁手判断算法 (仅黑棋)

### 4.1 禁手类型

在棋类规则(Renju)中，黑棋有以下禁手：

| 类型 | 说明 | 示例 |
|------|------|------|
| **长连** | 超过五连 (六连及以上) | ●●●●●● |
| **双四** | 同时形成两个活四 | ●●●● ○ ●●●● |
| **双三** | 同时形成两个活三 | ●●● ○ ●●● |

### 4.2 算法实现

#### 4.2.1 长连判断

**核心方法**：`isOverline(coordinate)`

```
算法思路：
1. 在指定坐标放置黑棋
2. 遍历四个方向，统计每个方向的连子数
3. 如果任何方向连子数 > 5，则为长连禁手
4. 移除临时棋子
```

```php
private function isOverline($coordinate)
{
    $this->setStone(BLACK_STONE, $coordinate);
    foreach (四个方向 as $shape) {
        if ($this->count_stone($coordinate, $shape) > 5) {
            return true;  // 长连禁手
        }
    }
    $this->setStone(EMPTY_STONE, $coordinate);
    return false;
}
```

#### 4.2.2 双四判断

**核心方法**：`isFour(coordinate, shape)` + `isDoubleFour(coordinate)`

**活四定义**：四子连成，且两端都是空位

```
算法思路（isFour）：
1. 在指定坐标放置黑棋
2. 沿指定方向两端遍历：
   - 统计连续黑棋数
   - 遇到空位时，检查该空位是否能形成连五
3. 如果两端都能形成连五，则为活四（count_active == 2）
4. 如果只有一端能形成连五，则为冲四（count_active == 1）
5. 返回活四数量

算法思路（isDoubleFour）：
1. 遍历四个方向
2. 对每个方向调用 isFour()
3. 统计形成活四的方向数
4. 如果 >= 2，则为双四禁手
```

```php
private function isFour($coordinate, $shape)
{
    // 1. 放置黑棋
    $this->setStone(BLACK_STONE, $coordinate);
    
    // 2. 遍历两端
    foreach (两端 as $direction) {
        // 统计连续黑棋数
        while (下一颗是黑棋) {
            $count_black++;
        }
        
        // 3. 遇到空位时检查是否能连五
        if (空位 && isFive(空位, BLACK_STONE)) {
            $result++;  // 活四计数 +1
        }
    }
    
    // 4. 特殊情况：四子 + 双活四 = 冲四
    if ($count_black == 4 && $result == 2) {
        $result = 1;  // 降为冲四
    }
    
    $this->setStone(EMPTY_STONE, $coordinate);
    return $result;
}

private function isDoubleFour($coordinate)
{
    $count = 0;
    foreach (四个方向 as $shape) {
        $count += isFour($coordinate, $shape);
        if ($count >= 2) return true;
    }
    return false;
}
```

#### 4.2.3 双三判断

**核心方法**：`isOpenThree(coordinate, shape)` + `isDoubleThree(coordinate)`

**活三定义**：三子连成，且一端或两端是空位，且该空位能形成活四

```
算法思路（isOpenThree）：
1. 在指定坐标放置黑棋
2. 沿指定方向两端遍历：
   - 跳过连续黑棋
   - 遇到空位时，检查该空位是否能形成活四
3. 如果能形成活四，则为活三
4. 移除临时棋子

算法思路（isDoubleThree）：
1. 遍历四个方向
2. 对每个方向调用 isOpenThree()
3. 统计形成活三的方向数
4. 如果 >= 2，则为双三禁手
```

```php
private function isOpenThree($coordinate, $shape)
{
    $this->setStone(BLACK_STONE, $coordinate);
    foreach (两端 as $direction) {
        // 跳过连续黑棋
        while (下一颗是黑棋) { continue; }
        
        // 空位检查是否能活四
        if (空位 && isOpenFour(空位, $shape)) {
            return true;
        }
    }
    $this->setStone(EMPTY_STONE, $coordinate);
    return false;
}

private function isDoubleThree($coordinate)
{
    $count = 0;
    foreach (四个方向 as $shape) {
        if (isOpenThree($coordinate, $shape)) {
            $count++;
            if ($count >= 2) return true;
        }
    }
    return false;
}
```

### 4.3 禁手判断入口

**核心方法**：`isForbidden(coordinate)`

```
算法逻辑（优先级从高到低）：
1. 如果该点已经形成连五 → 不是禁手（连五胜于禁手）
2. 检查是否长连 (isOverline)
3. 检查是否双四 (isDoubleFour)
4. 检查是否双三 (isDoubleThree)
5. 以上三种任一满足 → 禁手

【重要】连五具有最高优先级：即使形成禁手，如果同时形成连五，则判为连五胜
```

```php
public function isForbidden($coordinate)
{
    // 已落子位置不可能是禁手点
    if ($this->_(coordinate) != EMPTY) {
        return false;
    }
    
    // 连五胜于禁手
    if ($this->isFive(coordinate, BLACK_STONE)) {
        return false;
    }
    
    // 检查三种禁手
    return (
        isOverline(coordinate) || 
        isDoubleFour(coordinate) || 
        isDoubleThree(coordinate)
    );
}
```

---

## 5. 胜负判断入口

### 5.1 主判断方法

**核心方法**：`checkWin(position, color)`

```
输入：
- position: 十六进制坐标字符串，如 "88"
- color: 'black' 或 'white'

判断逻辑：
1. 白棋落子：
   - 检查是否连五 → 白胜 (WHITE_FIVE = 1)

2. 黑棋落子：
   a) 检查是否连五
      - 是 → 黑胜 (BLACK_FIVE = 2)
   b) 检查是否禁手
      - 是 → 黑禁手，判白胜 (BLACK_FORBIDDEN = 4)
   
3. 无胜负 → 返回 false
```

```php
public function checkWin($position, $color)
{
    $coordinate = pos2coordinate($position);
    $stone = ($color == 'white') ? WHITE_STONE : BLACK_STONE;
    
    if ($color == 'white') {
        if ($this->isFive($coordinate, $stone)) {
            return self::WHITE_FIVE;  // 白胜
        }
    } else {
        if ($this->isFive($coordinate, $stone)) {
            return self::BLACK_FIVE;  // 黑胜
        }
        if ($this->isForbidden($coordinate)) {
            return self::BLACK_FORBIDDEN;  // 黑禁手，白胜
        }
    }
    return false;
}
```

### 5.2 普通五子棋判断

**方法**：`gomokuCheckWin(position, color)`

与棋类规则的区别：
- 黑棋无禁手
- 连子数 >= 5 即获胜（不限五连，六连也算胜）

---

## 6. 完整判断流程

### 6.1 对局结束判断流程

```
用户落子后：
    │
    ▼
┌─────────────────────────────┐
│ 1. 临时放置棋子              │
│ 2. 检查连五                  │
│    ├─ 是 → 判断胜负          │
│    └─ 否 → 继续              │
└─────────────────────────────┘
    │
    ▼
┌─────────────────────────────┐
│ 3. 检查禁手（仅黑棋）        │
│    ├─ 长连 → 禁手            │
│    ├─ 双四 → 禁手            │
│    ├─ 双三 → 禁手            │
│    └─ 无  → 正常继续         │
└─────────────────────────────┘
    │
    ▼
┌─────────────────────────────┐
│ 4. 移除临时棋子             │
│ 5. 返回判断结果              │
└─────────────────────────────┘
```

### 6.2 胜负结果码

```php
const WHITE_FIVE = 1;       // 白胜
const BLACK_FIVE = 2;       // 黑胜
const BLACK_FORBIDDEN = 4;  // 黑禁手（白胜）
```

---

## 7. 开局判断

### 7.1 标准开局

系统内置了 26 种标准开局（棋谚）：

```php
public static $openings = [
    '887868' => '寒星',
    '887869' => '溪月',
    '88786a' => '疏星',
    '887879' => '花月',
    '88787a' => '残月',
    '887889' => '雨月',
    '88788a' => '金星',
    // ... 共26种
];
```

### 7.2 开局校验算法

**方法**：`board_correct(boardstr)`

```
校验逻辑：
1. 棋盘字符串长度必须为偶数
2. 所有坐标必须唯一
3. 所有坐标必须在 1-15 范围内
```

### 7.3 打点对称性检测

**方法**：`a5_symmetry(board, a5_pos)`

检测五手打点是否存在对称情况（不允许对称打点）：

```
支持的对称类型：
1. 水平翻转对称
2. 垂直翻转对称
3. 中心对称（水平+垂直）
4. 对角线对称
5. 反对角线对称
```

---

## 8. 性能优化

### 8.1 位运算优化

系统使用位运算存储棋盘，优势：

- **空间**：每行仅需 1 个 int32（4 字节）
- **操作**：落子、查子只需位运算（AND/OR）
- **统计**：遍历连续棋子可使用位移操作

### 8.2 剪枝策略

在判断禁手时采用优先级剪枝：

```
1. 首先检查连五（最高优先级）
2. 连五成立 → 跳过禁手检查
3. 连五不成立 → 检查三种禁手
```

---

## 9. 重构建议

### 9.1 核心接口设计

```typescript
interface BoardGameRules {
    // 检查指定位置落子后的胜负
    checkWin(position: string, color: Color): WinResult;
    
    // 检查指定位置是否为禁手点
    isForbidden(position: string): boolean;
    
    // 获取当前棋盘状态
    getBoardState(): BoardState;
}
```

### 9.2 建议的技术选型

| 模块 | 建议技术 | 理由 |
|------|----------|------|
| 棋盘存储 | 位运算 / 二维数组 | 高效遍历和判断 |
| 规则引擎 | 策略模式 | 方便扩展新规则 |
| 前后端通信 | JSON WebSocket | 实时性要求高 |

### 9.3 需保留的业务逻辑

1. **四种禁手判断**：长连、双四、双三的完整算法
2. **连五判断**：支持不同规则的连五判定
3. **开局校验**：标准26种开局 + 对称打点检测
4. **坐标系统**：保持现有的十六进制坐标表示
