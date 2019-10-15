<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019/10/15
 * Time: 17:30
 */

namespace common\components;


use common\models\BoardWinStatistics;
use common\models\GameRecords;

class GameStatistics
{
    /**
     * @param $game
     * @param $result  int | 0 | 2 | 1 黑棋得分，
     * @param array $extra
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
        for($i = 0 ; $i < $game_len ; $i += 2 )
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
            }
            $board_tool->doMove($move);
        }
        return $record->id;
    }

    public static function regularize($game)
    {
        return $game;
    }
}