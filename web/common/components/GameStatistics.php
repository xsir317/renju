<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019/10/15
 * Time: 17:30
 */

namespace common\components;


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
        
        return $record->id;
    }

    public static function regularize($game)
    {
        return $game;
    }
}