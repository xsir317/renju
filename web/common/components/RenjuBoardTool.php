<?php
namespace common\components;


class RenjuBoardTool
{
    const EMPTY_STONE = '.'; //空
    const BORDER      = '$'; //边界
    const BLACK_STONE = 'b'; //黑棋
    const WHITE_STONE = 'w'; //白棋
    const WHITE_FIVE = 1;
    const BLACK_FIVE = 2;
    const BLACK_FORBIDDEN = 4;

    /**
     * @var array 方向可能有点反直觉， 前一位是行号，最上面是1 最下面是15
     * 后一位是列。
     */
    private static $directions = [
        '|' => [[+1,0],[-1,0]],   //下，上
        '-' => [[0,+1],[0,-1]],   //前，后
        '\\' => [[+1,+1],[-1,-1]], //右下，左上
        '/' => [[+1,-1],[-1,+1]], //左下，右上
    ];

    /**
     * @var array 二维数组，保存棋盘。
     *
     */
    private $board = [];
    /**
     * @var array 当前坐标
     */
    private $current = [1,1];


    /**
     * RenjuBoardTool constructor.
     * @param string $init
     * 初始化棋盘，然后Load一下棋局
     */
    public function __construct($init = '')
    {
        $this->board = [//初始化，也不要做什么循环了，这样干净。。。也更直观
            ['$','$','$','$','$','$','$','$','$','$','$','$','$','$','$','$','$',],//border
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//1
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//2
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//3
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//4
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//5
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//6
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//7
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//8
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//9
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//10
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//11
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//12
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//13
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//14
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//15
            ['$','$','$','$','$','$','$','$','$','$','$','$','$','$','$','$','$',],//border
        ];
        $arrStones = strlen($init) > 0 ? array_unique(str_split($init,2)) : [];
        //棋子序号
        $stone = self::BLACK_STONE;
        foreach ($arrStones as $onestone)
        {
            $coordinate = self::pos2coordinate($onestone);
            $this->board[$coordinate[0]][$coordinate[1]] = $stone;
            $stone = ($stone == self::WHITE_STONE) ? self::BLACK_STONE : self::WHITE_STONE;
        }
    }

    public function _debug_board()
    {
        $return = "\n";
        foreach ($this->board as $row)
        {
            $return .= implode('',$row);
            $return .= "\n";
        }
        return $return;
    }

    private static function pos2coordinate($position)
    {
        return [
            hexdec($position{0}),
            hexdec($position{1}),
        ];
    }

    private function moveTo($to = [8,8])
    {
        if($to[0] >= 1 && $to[0] <= 15 && $to[1] >= 1 && $to[1] <= 15)
        {
            $this->current = $to;
        }
        return $this->_();
    }

    private function setStone($stone = '.',$coordinate = [])
    {
        if(empty($coordinate))
        {
            $coordinate = $this->current;
        }
        $this->board[$coordinate[0]][$coordinate[1]] = $stone;
    }

    /**
     * @param $direction
     * @return bool
     * 从$this->current 开始，按照指定的向量（方向）走一格
     * 如果走出了棋盘则返回false
     * 否则就设置current，并且返回当前棋子。
     * 这个方法把direction抽象出来之后，代码得到了极大简化。
     */
    private function moveDirection($direction)
    {
        $next = [
            $this->current[0] + $direction[0],
            $this->current[1] + $direction[1],
        ];
        $next_stone = $this->_($next);
        if($next_stone == self::BORDER)
        {
            return false;//出棋盘了，返回false，也不保存current了。
        }
        $this->current = $next;
        return $next_stone;
    }

    /**
     * @param array $coordinate
     * @return string  棋子
     * 返回指定坐标的棋子。。。默认current
     */
    private function _($coordinate = [])
    {
        if(empty($coordinate))
        {
            $coordinate = $this->current;
        }
        return $this->board[$coordinate[0]][$coordinate[1]];
    }

    /**
     * @param $coordinate
     * @param $shape  string   | - \ / 形状（方向）
     * @return int 连续棋子数量
     * 对指定坐标点，计算指定方向的连续同色棋子数
     * 只负责数，不负责放棋子。
     */
    private function count_stone($coordinate,$shape)
    {
        $color = $this->_($coordinate);
        if($color == self::BLACK_STONE || $color == self::WHITE_STONE)
        {
            $count = 1; // 起始位置1个，然后向两边走，边走边数。 走到头了立刻回原点，往另一头走。
            foreach (self::$directions[$shape] as $direction)
            {
                $this->moveTo($coordinate);
                while($color == $this->moveDirection($direction))
                {
                    $count ++;
                }
            }
            return $count;
        }
        return 0;
    }

    /**
     * @param $coordinate
     * @param $color
     * @param string $shape
     * @param string $rule
     * @return bool
     * 判断指定坐标落子【在指定方向上】是否形成了连五  连5具有最高优先级。
     */
    private function isFive($coordinate,$color,$shape = '',$rule = 'renju')
    {
        if($this->_($coordinate) != self::EMPTY_STONE)
        {
            return false;
        }
        $this->setStone($color,$coordinate);
        $result = false;
        if($shape)
        {
            $count = $this->count_stone($coordinate,$shape);
            $result = $this->count_as_five($count,$color,$rule);
        }
        else
        {
            foreach (self::$directions as $s => $i)
            {
                $count = $this->count_stone($coordinate,$s);
                if($result = $this->count_as_five($count,$color,$rule))
                {
                    break;
                }
            }
        }
        $this->setStone(self::EMPTY_STONE,$coordinate);
        return $result;
    }

    /**
     * @param $coordinate
     * @param string $shape
     * @return int
     * 判断指定坐标落子【在指定方向上】是否形成了四； 返回可能是0 1 2
     * 【注意： 此方法仅仅用于禁手相关的判断，白棋完全没有判断四的需求，所以这里写死了黑棋。】
     */
    private function isFour($coordinate, $shape = '|')
    {
        if($this->_($coordinate) != self::EMPTY_STONE)
        {
            return false;
        }
        $result = 0;
        //放棋子
        $this->setStone(self::BLACK_STONE,$coordinate);
        //沿着每个方向去找空格，顺便数黑棋
        $count_black = 1;//刚刚手动放了一个的
        foreach (self::$directions[$shape] as $direction)
        {
            $this->moveTo($coordinate);
            while(self::BLACK_STONE == $this->moveDirection($direction))
            {
                $count_black ++;
            }
            if($this->_() == self::EMPTY_STONE)
            {
                //对此空格判断是否能连5
                if($this->isFive($this->current,self::BLACK_STONE,$shape))
                {
                    $result ++;
                }
            }
        }
        //如果两边都能连5，则可能有一个特殊情况
        if($count_black == 4 && $result == 2)
        {
            $result = 1;
        }
        //恢复空格
        $this->setStone(self::EMPTY_STONE,$coordinate);
        return $result;
    }

    /**
     * @param $coordinate
     * @param string $shape
     * @return bool
     * 判断指定坐标落子【在指定方向上】是否形成了活四；
     * 对于假禁手问题的针对性判断：  如果此落点形成了禁手，则直接返回false；
     * 所以判断 IsFour 的时候，会返回真； 而IsOpenFour 则返回false  因为落点是禁手点，不认为其具有攻击性。
     * 此点为禁手，则此点相关的四 是四，但不算活四。 与之相关的三 一定不是活三
     * 【注意： 此方法仅仅用于禁手相关的判断，白棋的胜负与此无关，所以这里写死了黑棋】
     */
    private function isOpenFour($coordinate,$shape = '|')
    {
        if($this->_($coordinate) != self::EMPTY_STONE)
        {
            return false;
        }
        $count_active = 0;
        //放棋子
        $count_black = 1;//当前点肯定是黑棋
        $this->setStone(self::BLACK_STONE,$coordinate);
        foreach (self::$directions[$shape] as $direction)
        {
            $this->moveTo($coordinate);
            //沿着每个方向去找空格，顺便数黑棋
            while(self::BLACK_STONE == $this->moveDirection($direction))
            {
                $count_black ++;
            }
            if($this->_() == self::EMPTY_STONE)
            {
                //对此空格判断是否能连5
                if($this->isFive($this->current,self::BLACK_STONE,$shape))
                {
                    $count_active ++;
                }
            }
            else
            {
                break;
            }
        }
        //恢复空格
        $this->setStone(self::EMPTY_STONE,$coordinate);
        if($count_black == 4 && $count_active == 2)
        {
            if($this->isForbidden($coordinate))
            {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * @param $coordinate
     * @param string $shape
     * @return bool
     * 判断是否是活三。
     * 【注意： 此方法仅仅用于禁手相关的判断，白棋完全没有判断活三的需求，所以这里写死了黑棋。】
     */
    private function isOpenThree($coordinate,$shape = '|')
    {
        $return = false;
        $this->setStone(self::BLACK_STONE,$coordinate);
        //沿着每个方向去找空格
        foreach (self::$directions[$shape] as $direction)
        {
            $this->moveTo($coordinate);
            while(self::BLACK_STONE == $this->moveDirection($direction))
            {
                null;//一直挪，这个方向走到不是黑棋的地方
            }
            if($this->_() == self::EMPTY_STONE)
            {
                //对此空格判断是否能活四
                if($this->isOpenFour($this->current,$shape))
                {
                    $return = true;
                    break;//能活四的话另一边不用看了，不能活四再看另一头
                }
            }
            else//如果落子的地方有一头不是空格，那看也不用看了。。。
            {
                break;
            }
        }
        //恢复空格
        $this->setStone(self::EMPTY_STONE,$coordinate);
        return $return;
    }

    /**
     * @param $coordinate
     * @return bool
     * 【注意： 此方法仅仅用于禁手相关的判断，白棋完全没有判断活三的需求，所以这里写死了黑棋。】
     */
    private function isDoubleThree($coordinate)
    {
        $count = 0;
        foreach (self::$directions as $s => $i)
        {
            if($this->isOpenThree($coordinate,$s))
            {
                $count ++;
                if($count >= 2)
                {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $coordinate
     * @return bool
     * 判断一个点是否形成了双四禁手
     * 【注意： 此方法仅仅用于禁手相关的判断，白棋完全没有判断双四的需求，所以这里写死了黑棋。】
     */
    private function isDoubleFour($coordinate)
    {
        $count = 0;
        foreach (self::$directions as $s => $i) {
            $count += $this->isFour($coordinate,$s);
            if($count >= 2)
            {
                return true;
            }
        }
        return false;
    }


    /**
     * @param $coordinate
     * @return bool
     * 在指定点是否形成了长连
     */
    private function isOverline($coordinate)
    {
        $this->setStone(self::BLACK_STONE,$coordinate);
        $result = false;
        foreach (self::$directions as $s => $i)
        {
            if($this->count_stone($coordinate,$s) > 5)
            {
                $result = true;
                break;
            }
        }

        $this->setStone(self::EMPTY_STONE,$coordinate);
        return $result;
    }

    private function count_as_five($number,$color,$rule = 'renju')
    {
        if($color == self::BLACK_STONE)
        {
            return $number == 5;
        }
        //white
        return ($rule == 'renju') ? ($number >= 5) : ($number == 5);
    }

    public function isForbidden($coordinate)
    {
        if($this->_($coordinate) != self::EMPTY_STONE)
        {
            return false;
        }
        if($this->isFive($coordinate,self::BLACK_STONE))
        {
            return false;
        }
        return ($this->isOverline($coordinate) || $this->isDoubleFour($coordinate) || $this->isDoubleThree($coordinate));
    }

    /**
     * @param $position
     * @param $color string  black|white
     * @return int | bool
     * 按照连珠规则检查是否获胜
     */
    public function checkWin($position, $color)
    {
        $stone = ($color == 'white') ? self::WHITE_STONE : self::BLACK_STONE;
        $coordinate = self::pos2coordinate($position);
        if($color == 'white')
        {
            if($this->isFive($coordinate,$stone))
            {
                return self::WHITE_FIVE;
            }
        }
        else
        {
            if($this->isFive($coordinate,$stone))
            {
                return self::BLACK_FIVE;
            }
            if($this->isForbidden($coordinate))
            {
                return self::BLACK_FORBIDDEN;
            }
        }
        return false;
    }

    /**
     * @param $position
     * @param $color string  black|white
     * @return int | bool
     */
    public function gomokuCheckWin($position, $color)
    {
        $coordinate = self::pos2coordinate($position);
        $stone = ($color == 'white') ? self::WHITE_STONE : self::BLACK_STONE;
        if($this->isFive($coordinate,$stone,'','gomoku'))
        {
            return $color == 'black' ? self::BLACK_FIVE : self::WHITE_FIVE;
        }
        return false;
    }

    /**
     * 遍历，获得棋盘上的所有禁手点
     */
    public function findForbiddenPoints()
    {

    }

    /**
     * 遍历，获得棋盘上的所有连5点
     */
    public function findWinPoints()
    {

    }
}
