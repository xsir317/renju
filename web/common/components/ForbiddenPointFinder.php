<?php
namespace common\components;

if(!defined('BOARDSIZE')) define('BOARDSIZE',15);
if(!defined('BLACKSTONE')) define('BLACKSTONE','X');
if(!defined('WHITESTONE')) define('WHITESTONE','O');
if(!defined('EMPTYSTONE')) define('EMPTYSTONE','.');
if(!defined('BLACKFIVE')) define('BLACKFIVE',0);
if(!defined('WHITEFIVE')) define('WHITEFIVE',1);
if(!defined('BLACKFORBIDDEN')) define('BLACKFORBIDDEN',2);

class ForbiddenPointFinder
{
    private $cBoard = array();
    private $recordstr = '';
    private $nForbiddenPoints = 0;
    private $ptForbidden = array();

    function  __construct($boardstring)
    {
        $this->clear();
        $this->recordstr = $boardstring;
		$arrStones = strlen($boardstring) > 0 ? str_split($boardstring,2) : [];
		//用来返回的二维棋盘数组
		//棋子序号
		$nowstone = BLACKSTONE;
		foreach ($arrStones as $onestone)
		{
			$this->cBoard[hexdec($onestone{0})][hexdec($onestone{1})] = $nowstone;
			if ($nowstone == BLACKSTONE)
			{
				$nowstone = WHITESTONE;
			}
			else
			{
				$nowstone = BLACKSTONE;
			}
		}
    }

    //////////////////////////////////////////////////////////////////////
    // Implementation
    //////////////////////////////////////////////////////////////////////
    //初始化一个17*17的棋盘，把四周用$填充，棋盘点用.填充
    //$$$$$$$$$$$$$$$$$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$...............$
    //$$$$$$$$$$$$$$$$$
    function clear()
    {
        $this->nForbiddenPoints = 0;
        for ($i=0; $i<BOARDSIZE+2; $i++)
        {
            $this->cBoard[0][$i] = '$';
            $this->cBoard[(BOARDSIZE+1)][$i] = '$';
            $this->cBoard[$i][0] = '$';
            $this->cBoard[$i][(BOARDSIZE+1)] = '$';
        }

        for ($i=1; $i<=BOARDSIZE; $i++)
        for ($j=1; $j<=BOARDSIZE; $j++)
        $this->cBoard[$i][$j] = EMPTYSTONE;
    }

	function outputboard()
	{
	    $returnstr = '';
	    $returnstr .= "<pre>\n";
        for ($i=0; $i<=BOARDSIZE+1; $i++)
        {
            for ($j=0; $j<=BOARDSIZE+1; $j++)
            {
                $returnstr .= ($i == 0 || $i == BOARDSIZE+1 ? dechex($j) : ($j == 0 || $j==BOARDSIZE+1 ? dechex($i) : $this->cBoard[$i][$j]));
            }
            $returnstr .= "\n";
        }
		$returnstr .= "</pre>";
		return $returnstr;
	}
    //落子
    function AddStone($x, $y, $cStone)
    {
        $nResult = -1;
        if ($cStone == BLACKSTONE)//如果是黑子
        {
            if ($this->IsFive($x, $y, 0))//如果黑棋成5了
            $nResult = BLACKFIVE;
            else if ($this->IsDoubleFour($x,$y) || $this->IsDoubleThree($x,$y) || $this->IsOverline($x,$y))//如果落子在已知是禁手的地方，则立即判为禁手。
            {
                $nResult = BLACKFORBIDDEN;//判断禁手。
            }
        }
        else if ($cStone == WHITESTONE)//如果白棋落子，那么判断是否为白胜
        {
            if ($this->IsFive($x, $y, 1))
            $nResult = WHITEFIVE;
        }

        //$this->cBoard[$x+1][$y+1] = $cStone;//在xy位置放置棋子（因为是多了一圈边界）
        return $nResult;
    }

    function SetStone($x, $y, $cStone)
    {
        $this->cBoard[$x+1][$y+1] = $cStone;//放置棋子。
    }

    function IsOverline($x, $y)//判断是否是长连。
    {
        if ($this->cBoard[$x+1][$y+1] != EMPTYSTONE)
        return FALSE;

        $this->SetStone($x, $y, BLACKSTONE);

        // detect black overline
        $bOverline = FALSE;

        // 1 - horizontal direction
        $nLine = 1;
        $i = $x;
        while ($i > 0)
        {
            if ($this->cBoard[$i--][$y+1] == BLACKSTONE)
            $nLine++;
            else
            break;
        }
        $i = $x+2;
        while ($i < (BOARDSIZE+1))
        {
            if ($this->cBoard[$i++][$y+1] == BLACKSTONE)
            $nLine++;
            else
            break;
        }
        if ($nLine == 5)
        {
            $this->SetStone($x, $y, EMPTYSTONE);
            return FALSE;
        }
        else
        $bOverline |= ($nLine >= 6);

        // 2 - vertical direction
        $nLine = 1;
        $i = $y;
        while ($i > 0)
        {
            if ($this->cBoard[$x+1][$i--] == BLACKSTONE)
            $nLine++;
            else
            break;
        }
        $i = $y+2;
        while ($i < (BOARDSIZE+1))
        {
            if ($this->cBoard[$x+1][$i++] == BLACKSTONE)
            $nLine++;
            else
            break;
        }
        if ($nLine == 5)
        {
            $this->SetStone($x, $y, EMPTYSTONE);
            return FALSE;
        }
        else
        $bOverline |= ($nLine >= 6);

        // 3 - diagonal direction (lower-left to upper-right: '/')
        $nLine = 1;
        $i = $x;
        $j = $y;
        while (($i > 0) && ($j > 0))
        {
            if ($this->cBoard[$i--][$j--] == BLACKSTONE)
            $nLine++;
            else
            break;
        }
        $i = $x+2;
        $j = $y+2;
        while (($i < (BOARDSIZE+1)) && ($j < (BOARDSIZE+1)))
        {
            if ($this->cBoard[$i++][$j++] == BLACKSTONE)
            $nLine++;
            else
            break;
        }
        if ($nLine == 5)
        {
            $this->SetStone($x, $y, EMPTYSTONE);
            return FALSE;
        }
        else
        $bOverline |= ($nLine >= 6);

        // 4 - diagonal direction (upper-left to lower-right: '\')
        $nLine = 1;
        $i = $x;
        $j = $y+2;
        while (($i > 0) && ($j < (BOARDSIZE+1)))
        {
            if ($this->cBoard[$i--][$j++] == BLACKSTONE)
            $nLine++;
            else
            break;
        }
        $i = $x+2;
        $j = $y;
        while (($i < (BOARDSIZE+1)) && ($j > 0))
        {
            if ($this->cBoard[$i++][$j--] == BLACKSTONE)
            $nLine++;
            else
            break;
        }
        if ($nLine == 5)
        {
            $this->SetStone($x, $y, EMPTYSTONE);
            return FALSE;
        }
        else
        $bOverline |= ($nLine >= 6);

        $this->SetStone($x, $y, EMPTYSTONE);
        return $bOverline;
    }

    function IsFive($x, $y, $nColor, $nDir=0)//判断是否连5，nDir参数：方向  1横的  2 竖的 3 斜线 4 反斜线
    {
        if ($nDir)
        {
            if ($this->cBoard[$x+1][$y+1] != EMPTYSTONE)
            return FALSE;

            $c = ($nColor == 0) ? BLACKSTONE:WHITESTONE;

            $this->SetStone($x, $y, $c);

            switch ($nDir)
            {
                case 1:		// horizontal direction
                $nLine = 1;
                $i = $x;
                while ($i > 0)
                {
                    if ($this->cBoard[$i--][$y+1] == $c)
                    $nLine++;
                    else
                    break;
                }
                $i = $x+2;
                while ($i < (BOARDSIZE+1))
                {
                    if ($this->cBoard[$i++][$y+1] == $c)
                    $nLine++;
                    else
                    break;
                }
                if ($nLine == 5)
                {
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return TRUE;
                }
                else
                {
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return FALSE;
                }
                break;
                case 2:		// vertial direction
                $nLine = 1;
                $i = $y;
                while ($i > 0)
                {
                    if ($this->cBoard[$x+1][$i--] == $c)
                    $nLine++;
                    else
                    break;
                }
                $i = $y+2;
                while ($i < (BOARDSIZE+1))
                {
                    if ($this->cBoard[$x+1][$i++] == $c)
                    $nLine++;
                    else
                    break;
                }
                if ($nLine == 5)
                {
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return TRUE;
                }
                else
                {
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return FALSE;
                }
                break;
                case 3:		// diagonal direction - '/'
                $nLine = 1;
                $i = $x;
                $j = $y;
                while (($i > 0) && ($j > 0))
                {
                    if ($this->cBoard[$i--][$j--] == $c)
                    $nLine++;
                    else
                    break;
                }
                $i = $x+2;
                $j = $y+2;
                while (($i < (BOARDSIZE+1)) && ($j < (BOARDSIZE+1)))
                {
                    if ($this->cBoard[$i++][$j++] == $c)
                    $nLine++;
                    else
                    break;
                }
                if ($nLine == 5)
                {
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return TRUE;
                }
                else
                {
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return FALSE;
                }
                break;
                case 4:		// diagonal direction - '\'
                $nLine = 1;
                $i = $x;
                $j = $y+2;
                while (($i > 0) && ($j < (BOARDSIZE+1)))
                {
                    if ($this->cBoard[$i--][$j++] == $c)
                    $nLine++;
                    else
                    break;
                }
                $i = $x+2;
                $j = $y;
                while (($i < (BOARDSIZE+1)) && ($j > 0))
                {
                    if ($this->cBoard[$i++][$j--] == $c)
                    $nLine++;
                    else
                    break;
                }
                if ($nLine == 5)
                {
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return TRUE;
                }
                else
                {
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return FALSE;
                }
                break;
                default:
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return FALSE;
                    break;
            }
        }
        else
        {
            foreach([1,2,3,4] as $direction)
            {
                if($this->IsFive($x,$y,$nColor,$direction))
                {
                    return true;
                }
            }
            return false;
        }
    }

    public function IsFour($x, $y, $nColor, $nDir)//判定是否是四
    {
        if ($this->cBoard[$x+1][$y+1] != EMPTYSTONE)
        {
            return FALSE;//如果该点有棋子，则返回false
        }

        if ($this->IsFive($x, $y, $nColor))	// five?如果连5，则返回false
        {
            return FALSE;
        }
        else if (($nColor == 0) && ($this->IsOverline($x, $y)))	// black overline?如果是黑棋长连，那么也返回false
        {
            return FALSE;
        }
        else//否则进行判断
        {
            $c = '';
            if ($nColor == 0)	// black
            $c = BLACKSTONE;
            else if ($nColor == 1)	// white
            $c = WHITESTONE;
            else
            return FALSE;
            //c是棋子颜色，0为黑棋，1为白棋
            $this->SetStone($x, $y, $c);

            switch ($nDir)
            {
                case 1:		// horizontal direction
                $i = $x;
                while ($i > 0)
                {
                    if ($this->cBoard[$i][$y+1] == $c)
                    {
                        $i--;
                        continue;
                    }
                    else if ($this->cBoard[$i][$y+1] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $y, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);//把刚才下的棋子拿掉
                            return $this->coordinate_to_str($i-1, $y);//如果横线左端能连5，则为四
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $i = $x+2;
                while ($i < (BOARDSIZE+1))
                {
                    if ($this->cBoard[$i][$y+1] == $c)
                    {
                        $i++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$y+1] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $y, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);//将刚刚下的棋子拿掉
                            return $this->coordinate_to_str($i-1, $y);//如果横线右端能连5，则为四
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return FALSE;
                break;
                case 2:		// vertial direction
                $i = $y;
                while ($i > 0)
                {
                    if ($this->cBoard[$x+1][$i] == $c)
                    {
                        $i--;
                        continue;
                    }
                    else if ($this->cBoard[$x+1][$i] == EMPTYSTONE)
                    {
                        if ($this->IsFive($x, $i-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return $this->coordinate_to_str($x, $i-1);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $i = $y+2;
                while ($i < (BOARDSIZE+1))
                {
                    if ($this->cBoard[$x+1][$i] == $c)
                    {
                        $i++;
                        continue;
                    }
                    else if ($this->cBoard[$x+1][$i] == EMPTYSTONE)
                    {
                        if ($this->IsFive($x, $i-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return $this->coordinate_to_str($x, $i-1);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return FALSE;
                break;
                case 3:		// diagonal direction - '/'
                $i = $x;
                $j = $y;
                while (($i > 0) && ($j > 0))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i--;
                        $j--;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $j-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return $this->coordinate_to_str($i-1, $j-1);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $i = $x+2;
                $j = $y+2;
                while (($i < (BOARDSIZE+1)) && ($j < (BOARDSIZE+1)))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i++;
                        $j++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $j-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return $this->coordinate_to_str($i-1, $j-1);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return FALSE;
                break;
                case 4:		// diagonal direction - '\'
                $i = $x;
                $j = $y+2;
                while (($i > 0) && ($j < (BOARDSIZE+1)))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i--;
                        $j++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $j-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return $this->coordinate_to_str($i-1, $j-1);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $i = $x+2;
                $j = $y;
                while (($i < (BOARDSIZE+1)) && ($j > 0))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i++;
                        $j--;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $j-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return $this->coordinate_to_str($i-1, $j-1);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return FALSE;
                break;
                default:
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return FALSE;
                    break;
            }
        }
    }

    function IsOpenFour($x, $y, $nColor, $nDir)//是否是活四
    {
        if ($this->cBoard[$x+1][$y+1] != EMPTYSTONE)
        return 0;

        if ($this->IsFive($x, $y, $nColor))	// five?
        return 0;
        else if (($nColor == 0) && ($this->IsOverline($x, $y)))	// black overline?
        return 0;
        else
        {
            $c = '';
            if ($nColor == 0)	// black
            $c = BLACKSTONE;
            else if ($nColor == 1)	// white
            $c = WHITESTONE;
            else
            return 0;

            $this->SetStone($x, $y, $c);

            switch ($nDir)
            {
                case 1:		// horizontal direction
                $nLine = 1;
                $i = $x;
                while ($i >= 0)
                {
                    if ($this->cBoard[$i][$y+1] == $c)
                    {
                        $i--;
                        $nLine++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$y+1] == EMPTYSTONE)
                    {
                        if (!$this->IsFive($i-1, $y, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return 0;//如果一端不能连5，则不是活四
                        }
                        else
                        break;
                    }
                    else
                    {
                        $this->SetStone($x, $y, EMPTYSTONE);
                        return 0;//如果一端被对方棋子挡住（不是自己的棋子也不是空），则不是活四
                    }
                }
                $i = $x+2;//如果一端能成5，看另一端
                while ($i < (BOARDSIZE+1))
                {
                    if ($this->cBoard[$i][$y+1] == $c)
                    {
                        $i++;
                        $nLine++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$y+1] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $y, $nColor, $nDir))//如果这边能成5
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return ($nLine==4 ? 1 : 2);//返回1表示是活四，返回2表示是一条线上的双四，类似于0-000-0的形状
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return 0;
                break;
                case 2:		// vertial direction
                $nLine = 1;
                $i = $y;
                while ($i >= 0)
                {
                    if ($this->cBoard[$x+1][$i] == $c)
                    {
                        $i--;
                        $nLine++;
                        continue;
                    }
                    else if ($this->cBoard[$x+1][$i] == EMPTYSTONE)
                    {
                        if (!$this->IsFive($x, $i-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return 0;
                        }
                        else
                        break;
                    }
                    else
                    {
                        $this->SetStone($x, $y, EMPTYSTONE);
                        return 0;
                    }
                }
                $i = $y+2;
                while ($i < (BOARDSIZE+1))
                {
                    if ($this->cBoard[$x+1][$i] == $c)
                    {
                        $i++;
                        $nLine++;
                        continue;
                    }
                    else if ($this->cBoard[$x+1][$i] == EMPTYSTONE)
                    {
                        if ($this->IsFive($x, $i-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return ($nLine==4 ? 1 : 2);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return 0;
                break;
                case 3:		// diagonal direction - '/'
                $nLine = 1;
                $i = $x;
                $j = $y;
                while (($i >= 0) && ($j >= 0))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i--;
                        $j--;
                        $nLine++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if (!$this->IsFive($i-1, $j-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return 0;
                        }
                        else
                        break;
                    }
                    else
                    {
                        $this->SetStone($x, $y, EMPTYSTONE);
                        return 0;
                    }
                }
                $i = $x+2;
                $j = $y+2;
                while (($i < (BOARDSIZE+1)) && ($j < (BOARDSIZE+1)))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i++;
                        $j++;
                        $nLine++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $j-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return ($nLine==4 ? 1 : 2);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return 0;
                break;
                case 4:		// diagonal direction - '\'
                $nLine = 1;
                $i = $x;
                $j = $y+2;
                while (($i >= 0) && ($j <= (BOARDSIZE+1)))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i--;
                        $j++;
                        $nLine++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if (!$this->IsFive($i-1, $j-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return 0;
                        }
                        else
                        break;
                    }
                    else
                    {
                        $this->SetStone($x, $y, EMPTYSTONE);
                        return 0;
                    }
                }
                $i = $x+2;
                $j = $y;
                while (($i < (BOARDSIZE+1)) && ($j > 0))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i++;
                        $j--;
                        $nLine++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if ($this->IsFive($i-1, $j-1, $nColor, $nDir))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return ($nLine==4 ? 1 : 2);
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return 0;
                break;
                default:
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return 0;
                    break;
            }
        }
    }

    function IsDoubleFour($x, $y)//是否是双4
    {
        if ($this->cBoard[$x+1][$y+1] != EMPTYSTONE)
            return FALSE;//如果这个坐标有棋子，直接返回false

        if ($this->IsFive($x, $y, 0))	// five?
            return FALSE;//如果能成5，则无视一切禁手，直接返回false

        $nFour = 0;//4的数目
        for ($i=1; $i<=4; $i++)
        {
            if ($this->IsOpenFour($x, $y, 0, $i) == 2)//如果是一条线上的双4
                $nFour += 2;//4的数量+2
            else if ($this->IsFour($x, $y, 0, $i))
                $nFour++;//否则+1
        }

        return ($nFour >= 2);//如果4的数量大于1返回true
    }

    function IsOpenThree($x, $y, $nColor, $nDir)//判断是否是活三
    {
        if ($this->IsFive($x, $y, $nColor))	// five?
        return FALSE;
        else if (($nColor == 0) && ($this->IsOverline($x, $y)))	// black overline?
        return FALSE;
        else
        {
            $c = '';
            if ($nColor == 0)	// black
            $c = BLACKSTONE;
            else if ($nColor == 1)	// white
            $c = WHITESTONE;
            else
            return FALSE;

            $this->SetStone($x, $y, $c);

            switch ($nDir)
            {
                case 1:		// horizontal direction
                $i = $x;
                while ($i > 0)
                {
                    if ($this->cBoard[$i][$y+1] == $c)
                    {
                        $i--;
                        continue;
                    }
                    else if ($this->cBoard[$i][$y+1] == EMPTYSTONE)
                    {
                        if (($this->IsOpenFour($i-1, $y, $nColor, $nDir) == 1) && (!$this->IsDoubleFour($i-1, $y)) && (!$this->IsDoubleThree($i-1, $y)))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                                return TRUE;
                            //如果这个方向能活四而且不是禁手，则为活三。长连问题已经通过IsOpenFour调用IsOverline判断过了
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $i = $x+2;
                while ($i < (BOARDSIZE+1))
                {
                    if ($this->cBoard[$i][$y+1] == $c)
                    {
                        $i++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$y+1] == EMPTYSTONE)
                    {
                        if (($this->IsOpenFour($i-1, $y, $nColor, $nDir) == 1) && (!$this->IsDoubleFour($i-1, $y)) && (!$this->IsDoubleThree($i-1, $y)))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return TRUE;
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return FALSE;
                break;
                case 2:		// vertial direction
                $i = $y;
                while ($i > 0)
                {
                    if ($this->cBoard[$x+1][$i] == $c)
                    {
                        $i--;
                        continue;
                    }
                    else if ($this->cBoard[$x+1][$i] == EMPTYSTONE)
                    {
                        if (($this->IsOpenFour($x, $i-1, $nColor, $nDir) == 1) && (!$this->IsDoubleFour($x, $i-1)) && (!$this->IsDoubleThree($x, $i-1)))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return TRUE;
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $i = $y+2;
                while ($i < (BOARDSIZE+1))
                {
                    if ($this->cBoard[$x+1][$i] == $c)
                    {
                        $i++;
                        continue;
                    }
                    else if ($this->cBoard[$x+1][$i] == EMPTYSTONE)
                    {
                        if (($this->IsOpenFour($x, $i-1, $nColor, $nDir) == 1) && (!$this->IsDoubleFour($x, $i-1)) && (!$this->IsDoubleThree($x, $i-1)))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return TRUE;
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return FALSE;
                break;
                case 3:		// diagonal direction - '/'
                $i = $x;
                $j = $y;
                while (($i > 0) && ($j > 0))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i--;
                        $j--;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if (($this->IsOpenFour($i-1, $j-1, $nColor, $nDir) == 1) && (!$this->IsDoubleFour($i-1, $j-1)) && (!$this->IsDoubleThree($i-1, $j-1)))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return TRUE;
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $i = $x+2;
                $j = $y+2;
                while (($i < (BOARDSIZE+1)) && ($j < (BOARDSIZE+1)))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i++;
                        $j++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if (($this->IsOpenFour($i-1, $j-1, $nColor, $nDir) == 1) && (!$this->IsDoubleFour($i-1, $j-1)) && (!$this->IsDoubleThree($i-1, $j-1)))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return TRUE;
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return FALSE;
                break;
                case 4:		// diagonal direction - '\'
                $i = $x;
                $j = $y+2;
                while (($i > 0) && ($j < (BOARDSIZE+1)))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i--;
                        $j++;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if (($this->IsOpenFour($i-1, $j-1, $nColor, $nDir) == 1) && (!$this->IsDoubleFour($i-1, $j-1)) && (!$this->IsDoubleThree($i-1, $j-1)))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return TRUE;
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $i = $x+2;
                $j = $y;
                while (($i < (BOARDSIZE+1)) && ($j > 0))
                {
                    if ($this->cBoard[$i][$j] == $c)
                    {
                        $i++;
                        $j--;
                        continue;
                    }
                    else if ($this->cBoard[$i][$j] == EMPTYSTONE)
                    {
                        if (($this->IsOpenFour($i-1, $j-1, $nColor, $nDir) == 1) && (!$this->IsDoubleFour($i-1, $j-1)) && (!$this->IsDoubleThree($i-1, $j-1)))
                        {
                            $this->SetStone($x, $y, EMPTYSTONE);
                            return TRUE;
                        }
                        else
                        break;
                    }
                    else
                    break;
                }
                $this->SetStone($x, $y, EMPTYSTONE);
                return FALSE;
                break;
                default:
                    $this->SetStone($x, $y, EMPTYSTONE);
                    return FALSE;
                    break;
            }
        }
    }

    function IsDoubleThree($x, $y)//是否是双三
    {
        if ($this->cBoard[$x+1][$y+1] != EMPTYSTONE)
        return FALSE;

        if ($this->IsFive($x, $y, 0))	// five?
        return FALSE;
        $nThree = 0;
        for ($i=1; $i<=4; $i++)
        {
            if ($this->IsOpenThree($x, $y, 0, $i))
            $nThree++;
        }

        if ($nThree >= 2)
        return TRUE;
        else
        return FALSE;
    }

    function FindForbiddenPoints()//对盘面上所有能落子的点，查看其是否是禁手点，并标注。
    {
        $nForbiddenPoints = 0;
        for ($i=0; $i<BOARDSIZE; $i++)
        {
            for ($j=0; $j<BOARDSIZE; $j++)
            {
                if ($this->cBoard[$i+1][$j+1] != EMPTYSTONE)
                continue;
                else
                {
                    if ($this->IsOverline($i, $j) || $this->IsDoubleFour($i, $j) || $this->IsDoubleThree($i, $j))
                    {
                        $this->ptForbidden['nForbiddenPoints']['x'] = $i;
                        $this->ptForbidden['nForbiddenPoints']['y'] = $j;
                        $this->nForbiddenPoints++;
                    }
                }
            }
        }
    }

    function CheckWin($pos)
    {
        $x_pos = hexdec($pos{0}) - 1;
        $y_pos = hexdec($pos{1}) - 1;
        if (strlen($this->recordstr)%4 == 0)
        {
            $color = BLACKSTONE;
        }
        else
        {
            $color = WHITESTONE;
        }
        return $this->AddStone($x_pos,$y_pos,$color);
    }

    function GomokuCheckWin($pos)
    {
        $x = hexdec($pos{0}) - 1;
        $y = hexdec($pos{1}) - 1;
        if (strlen($this->recordstr)%4 == 0)
        {
            //$cStone = BLACKSTONE;
            if ($this->IsFive($x, $y, 0))//如果是黑子
            {
                return BLACKFIVE;
            }
        }
        else
        {
            //$cStone = WHITESTONE;
            if ($this->IsFive($x, $y, 1))//如果白棋落子，那么判断是否为白胜
            {
                return  WHITEFIVE;
            }
        }
        return -1;
    }

    private function coordinate_to_str($x,$y)
    {
        return dechex($x+1).dechex($y+1);
    }
}