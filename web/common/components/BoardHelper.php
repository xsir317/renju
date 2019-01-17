<?php
namespace common\components;


#棋盘 15*15
#看最后一手
#如果是pass 则看是否第二次pass，连续2次pass则和棋。 如不是则返回-1
#结果：
# 若有连5，连当前落子方获胜
# 否则 如果是白棋，则返回-1
#      如果是黑棋，则检查禁手
#         如果有长连，则对方胜利
#         如果有2个或更多活三 ，则对方胜利
#         如果有2个或者更多活四/ 冲四 则对方胜利
#      并没有产生禁手，则返回-1

# 禁手判断：
# 长连： 数。。。
# 判断四： 当前坐标先放个黑棋，然后向某个方向（一共8个方向）寻找空格，找到空格则判断 此处落子是否连5. 如果连5则算一个4.
# 8个方向： x+,y=   x-,y=  | x+,y+   x-,y- | x=,y+   x=,y-  |  x+,y-  x-,y+  ，这玩意回头得封装一下啊
# 判断活四： 当前坐标先放个黑棋，然后指定方向检查，2头都能连5  就是活四（或者同一条线上的双4，用$nLine算法没问题）
# 判断双四：  四个方向遍历， 如果是 活四判断时发现同一条线上的双4 返回true   否则 如果是4则 +1   只要大于2就是双4了
# 判断活三： 八个方向找空格， 落子是否形成活4而且不是禁手   如果活四且不是禁手，那么这就是个活三。
# 判断双活三   ： 每个方向检测，把活三数量加起来。

class BoardHelper
{
    const BOARDSIZE = 15;
    const EMPTY_STONE = '.';
    const BORDER      = '$';
    const BLACK_STONE = '*';
    const WHITE_STONE = '0';

    /**
     * @var array 方向可能有点反直觉， 前一位是行号，最上面是1 最下面是15
     * 后一位是列。
     */
    private static $directions = [
        [+1,0],[-1,0],   //下，上
        [0,+1],[0,-1],   //前，后
        [+1,+1],[-1,-1], //右下，左上
        [+1,-1],[-1,+1], //左下，右上
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
     * @param null $pos
     * @return array
     *
     * 8个方向，切割一下，两两返回，分别是 | -  \ / 四个方向
     */
    private function get_positions($pos = null)
    {
        $pos_shapes = [
            '|'  => 0,
            '-'  => 1,
            '\\' => 2,
            '/'  => 3
        ];
        $chunk =  array_chunk(self::$directions,2);
        if($pos && isset($pos_shapes[$pos]))
        {
            return $chunk[ $pos_shapes[$pos] ];
        }
        return $chunk;
    }

    /**
     * BoardHelper constructor.
     * @param string $init
     * 初始化棋盘，然后Load一下棋局
     */
    public function __construct($init = '')
    {
        $this->board = [//初始化，也不要做什么循环了，这样干净。。。也更直观
            ['$','$','$','$','$','$','$','$','$','$','$','$','$','$','$',],//border
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//1
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//2
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//3
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//4
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//5
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//6
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//7
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//8
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//9
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//10
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//11
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//12
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//13
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//14
            ['$','.','.','.','.','.','.','.','.','.','.','.','.','.','$',],//15
            ['$','$','$','$','$','$','$','$','$','$','$','$','$','$','$',],//border
        ];
        $arrStones = strlen($init) > 0 ? array_unique(str_split($init,2)) : [];
        //棋子序号
        $nowstone = self::BLACK_STONE;
        foreach ($arrStones as $onestone)
        {
            $this->board[hexdec($onestone{0})][hexdec($onestone{1})] = $nowstone;
            $nowstone = ($nowstone == self::WHITE_STONE) ? self::BLACK_STONE : self::WHITE_STONE;
        }
    }
    public function moveTo($to = [8,8])
    {
        if($to[0] >= 1 && $to[0] <= 15 && $to[1] >= 1 && $to[1] <= 15)
        {
            $this->current = $to;
        }
        return $this->_();
    }

    public function setStone($stone = '.',$coordinate = [])
    {
        if(empty($coordinate))
        {
            $coordinate = $this->current;
        }
        $this->board[$coordinate[0]][$coordinate[1]] = $stone;
    }

    //跑8个方向。。。
    public function moveDirection($direction)
    {
        $next = [
            $this->current[0] + $direction[0],
            $this->current[1] + $direction[1],
        ];
        $next_stone = $this->_($next);
        if($next_stone == self::BORDER)
        {
            return false;
        }
        $this->current = $next;
        return $next_stone;
    }

    public function _($coordinate = [])
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
     * 对指定坐标点，计算指定方向的连续同色棋子数
     * 只负责数，不负责放棋子。
     */
    public function count_stone($coordinate,$shape)
    {

    }

    public function isFive($coordinate,$color,$shape)
    {
        
    }

    public function IsOverline()
    {

    }

    public function IsFour()
    {

    }

    public function IsOpenFour()
    {

    }

    public function IsDoubleFour()
    {

    }

    public function IsOpenThree()
    {

    }

    public function IsDoubleThree()
    {

    }



    public function checkWin($pos)
    {

    }

    public function gomokuCheckWin($pos)
    {

    }
}