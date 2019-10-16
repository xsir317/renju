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
     * @param $game
     * @param $result  int | 0 | 2 | 1 黑棋得分，
     * @param array $extra
     * @return int | bool
     */
    public static function do_record($game,$result,$extra = [])
    {
        //丢弃非天元开局？
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

        $record = new GameRecords();
        $record->black_player = isset($extra['black_player']) ? $extra['black_player'] : '';
        $record->white_player = isset($extra['white_player']) ? $extra['white_player'] : '';
        $record->game = $game;
        $record->rule = isset($extra['white_player']) ? $extra['white_player'] : '';
        $record->result = $result;
        $record->game_time = isset($extra['white_player']) ? $extra['white_player'] : '';
        $record->created_time = date('Y-m-d H:i:s');
        $record->save(0);

        //对每一步，生成棋盘， 查询棋盘，然后更新胜率以及下一手统计。
        $board_tool = new RenjuBoardTool_bit('');
        $game_len = strlen($game);
        for($i = 0 ; $i <= $game_len ; $i += 2 )
        {
            $move = substr($game,$i,2);
            if($i > 0)
            {
                //在棋盘上有棋子的时候，取棋盘。
                $board = $board_tool->get_binary();
                $stat_record = BoardWinStatistics::find()
                    ->where(['board' => $board])
                    ->one();
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
                if(!isset($decode_next_move[$move]))
                {
                    $decode_next_move[$move] = [0,0,0];
                }
                switch ($result)
                {
                    case 2: //黑胜
                        $stat_record->black_wins ++;
                        $decode_next_move[$move][0] ++;
                        break;
                    case 1: //和
                        $stat_record->draws ++ ;
                        $decode_next_move[$move][1] ++;
                        break;
                    case 0:
                        $stat_record->white_wins ++;
                        $decode_next_move[$move][2] ++;
                        break;
                }
                $stat_record->next_move = json_encode($decode_next_move);
                $stat_record->save(0);
                if($i >= 6) // 开局的局面-对局关系不要记录了 太多而且没用。
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
        //需要的是展示坐标的正规化，所以拿到展示坐标先 rotate_clock_90 转成实现（假定我们拿到的棋谱相对靠谱），操作之后再转回来。
        $game = self::rotate_clock_90($game);
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
        $check_end = min(8,strlen($game)/2);
        switch ($stone2) {
            case '78':
                //直指，如果从第三手开始，有棋子不是落在 y=8 则 y<8 就 flip_by_y 结束

                break;
            case '79':
                //斜指，如果从第三手开始，如果从第三手开始，有棋子不是落在 x+y=16 则 x+y<16 就 flip_by_diagonal 结束

                break;
        }


        $game = self::rotate_reverse_90($game);
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
            $return .= $stone{1} . dechex(16 - $stone{0}) ;
        }
        return $return;
    }

    //展示用坐标，逆时针90度转换，就是实现坐标。
    private static function rotate_reverse_90($game)
    {
        $return = '';
        foreach (str_split($game,2) as $stone)
        {
            $return .= dechex(16 - $stone{1}) . $stone{0};
        }
        return $return;
    }
}