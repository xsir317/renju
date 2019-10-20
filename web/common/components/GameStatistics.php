<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019/10/15
 * Time: 17:30
 */

namespace common\components;


use common\models\BoardRecordRel;
use common\models\BoardWinStatistics;
use common\models\GameRecords;

class GameStatistics
{
    /**
     * 数据入库，
     * @param $game
     * @param $result  int | 0 | 2 | 1 黑棋得分，
     * @param array $extra  额外数据，目前支持black_player white_player rule source origin_game
     * @return int | bool
     */
    public static function do_record($game,$result,$extra = [])
    {
        //先正则化棋谱
        $game = self::regularize($game);
        //然后查询一下是否已经记录了
        $record = GameRecords::find()
            ->where(['game' => $game])
            ->one();
        if($record)
        {
            return false;
        }
        $source = isset($extra['source']) ? $extra['source'] : '';
        $rel_id = isset($extra['rel_id']) ? $extra['rel_id'] : 0;
        if($source && $rel_id)
        {
            if(GameRecords::find()->where(['data_from' => $source,'rel_id' => $rel_id])->one())
            {
                //TODO 这里后续可能会负责做一些数据核对和修补
                return false;
            }
        }

        //TODO 这里要全部重写啊，要统一处理所有局面的insert和update
        $record = new GameRecords();
        $record->black_player = isset($extra['black_player']) ? $extra['black_player'] : '';
        $record->white_player = isset($extra['white_player']) ? $extra['white_player'] : '';
        $record->game = $game;
        $record->origin_game = isset($extra['origin_game']) ? $extra['origin_game'] : '';
        $record->rule = isset($extra['rule']) ? $extra['rule'] : '';
        $record->result = $result;
        $record->data_from = $source;
        $record->rel_id = $rel_id;
        $record->game_time = isset($extra['game_time']) ? $extra['game_time'] : '';
        $record->created_time = date('Y-m-d H:i:s');
        $record->save(0);

        //对每一步，生成棋盘， 查询棋盘，然后更新胜率以及下一手统计。
        $board_tool = new RenjuBoardTool_bit('');
        //第64手之后不统计分支胜负了，意义不大
        $stat_end = min(strlen($game),64*2);
        for($i = 0 ; $i <= $stat_end ; $i += 2 )
        {
            $move = substr($game,$i,2);
            if($i > 0)
            {
                //在棋盘上有棋子的时候，取棋盘。
                $board = $board_tool->get_binary();
                $stat_record = BoardWinStatistics::find()
                    ->where(['board' => $board])
                    ->one();

                //查询等价棋盘。
                //如果找到了等价棋盘，则当前局面上一手那个盘面， 走当前落点的统计数据要merge等价棋盘的。
                //例如我们先录入的是丘月
                //然后录入了一盘斜月，走到4 盘面找到等价了
                //此时不仅当前的获胜要向先前的等价记录累加，写 see_as ， 此点前面一手（也就是斜月局面）的下一手坐标的next_move记录也得按照等价的盘面统计修改
                //棋盘方向要遵从等价记录
                //是不是要把等价记录拿来回溯？ 比如丘月原先统计了10胜10负，在斜月录入了一盘通丘月的对局之后，斜月、丘月的前面几手的局面统计是不是都得回溯
                //一个对局的数据更新之后，需要从叶节点开始，对路径上所有局面的统计进行更新
                if(!$stat_record)
                {
                    $stat_record = new BoardWinStatistics();
                    $stat_record->board = $board;
                    $stat_record->white_wins = 0;
                    $stat_record->black_wins = 0;
                    $stat_record->draws = 0;
                    $stat_record->next_move = '{}';
                }

                $decode_next_move = json_decode($stat_record->next_move,1);
                if($move && !isset($decode_next_move[$move]))
                {
                    $decode_next_move[$move] = [0,0,0];
                }
                switch ($result)
                {
                    case 2: //黑胜
                        $stat_record->black_wins ++;
                        $move ? $decode_next_move[$move][0] ++ : null;
                        break;
                    case 1: //和
                        $stat_record->draws ++ ;
                        $move ? $decode_next_move[$move][1] ++ : null;
                        break;
                    case 0:
                        $stat_record->white_wins ++;
                        $move ? $decode_next_move[$move][2] ++ : null;
                        break;
                }
                $stat_record->next_move = json_encode($decode_next_move);
                $stat_record->save(0);
                if($i >= 12) // 开局的局面-对局关系不要记录了 太多而且没用。
                {
                    $rel = new BoardRecordRel();
                    $rel->board_id = $stat_record->id;
                    $rel->record_id = $record->id;
                    $rel->save(0);
                }
            }
            if($move)
            {
                $board_tool->doMove($move);
            }
        }
        return $record->id;
    }

    public static function regularize($game)
    {
        //如果不是正常开局，无视掉。
        if(strlen($game) < 6 || substr($game,0,2) != '88' || ( !in_array($game{2},[7,8,9]) || !in_array($game{3},[7,8,9]) ) || (!in_array($game{4},[6,7,8,9,'a']) || !in_array($game{5},[6,7,8,9,'a'])))
        {
            return $game;
        }
        //$game = self::rotate_clock_90($game);
        $stone2 = substr($game,2,2);
        switch ($stone2)
        {
            case '78':
                break;
            case '98':
                $game = self::flip_by_x($game);
                break;
            case '87' :
                $game = self::rotate_clock_90($game);
                break;
            case '89' :
                $game = self::rotate_reverse_90($game);
                break;

            case '79':
                break;
            case '99':
                $game = self::flip_by_x($game);
                break;
            case '97' :
                $game = self::flip_by_reverse_diagonal($game);
                break;
            case '77' :
                $game = self::flip_by_y($game);
                break;
        }
        $stone2 = substr($game,2,2);
        //正规化检查到8手为止
        $check_end = min(8,strlen($game)/2) * 2;
        switch ($stone2) {
            case '78':
                //直指，如果从第三手开始，有棋子不是落在 y=8 则 y<8 就 flip_by_y 结束
                for($i = 4; $i < $check_end; $i += 2)
                {
                    if($game{$i+1} != '8')
                    {
                        if(intval(hexdec($game{$i+1})) < 8)
                        {
                            $game = self::flip_by_y($game);
                        }
                        break;
                    }
                }
                break;
            case '79':
                //斜指，如果从第三手开始，如果从第三手开始，有棋子不是落在 x+y=16 则 x+y<16 就 flip_by_diagonal 结束
                for($i = 4; $i < $check_end; $i += 2)
                {
                    $tmp_coord_sum = hexdec($game{$i}) + hexdec($game{$i+1});
                    if($tmp_coord_sum != 16)
                    {
                        if($tmp_coord_sum < 16)
                        {
                            $game = self::flip_by_diagonal($game);
                        }
                        break;
                    }
                }
                break;
        }
        //需要的是展示坐标的正规化，需要转成展示坐标。
        $game = self::rotate_clock_90($game);
        return $game;
    }

    //沿着x轴旋转， 行号（前一位）变成16-n，后一位不变。
    private static function flip_by_x($game)
    {
        $return = '';
        foreach (str_split($game,2) as $stone)
        {
            $return .= dechex(16 - hexdec($stone{0})) . $stone{1};
        }
        return $return;
    }

    private static function flip_by_y($game)
    {
        $return = '';
        foreach (str_split($game,2) as $stone)
        {
            $return .= $stone{0} . dechex(16 - hexdec($stone{1}));
        }
        return $return;
    }

    private static function flip_by_diagonal($game)
    {
        $return = '';
        foreach (str_split($game,2) as $stone)
        {
            $return .= dechex(16 - hexdec($stone{1})) . dechex(16 - hexdec($stone{0}));
        }
        return $return;
    }

    private static function flip_by_reverse_diagonal($game)
    {
        $return = '';
        foreach (str_split($game,2) as $stone)
        {
            $return .= $stone{1}.$stone{0};
        }
        return $return;
    }

    //实际实现的坐标，顺时针90度转换，就是展示用坐标
    private static function rotate_clock_90($game)
    {
        $return = '';
        foreach (str_split($game,2) as $stone)
        {
            $return .= $stone{1} . dechex(16 - hexdec($stone{0})) ;
        }
        return $return;
    }

    //展示用坐标，逆时针90度转换，就是实现坐标。
    private static function rotate_reverse_90($game)
    {
        $return = '';
        foreach (str_split($game,2) as $stone)
        {
            $return .= dechex(16 - hexdec($stone{1})) . $stone{0};
        }
        return $return;
    }
}