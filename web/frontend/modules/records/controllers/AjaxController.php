<?php
/**
 * Created by PhpStorm.
 * User: HuJie
 * Date: 2019/10/16
 * Time: 0:35
 */

namespace frontend\modules\records\controllers;


use common\components\RenjuBoardTool_bit;
use common\models\BoardRecordRel;
use common\models\BoardWinStatistics;
use frontend\components\Controller;

class AjaxController extends Controller
{
    public function actionQuery()
    {
        $game = $this->get('game');
        if(!$game)
        {
            return $this->renderJSON([],'空棋盘',-1);
        }
        $board = new RenjuBoardTool_bit($game);
        $board_record = BoardWinStatistics::find()
            ->where(['board' => $board->get_binary()])
            ->one();
        if($board_record)
        {
            $related_games = BoardRecordRel::getDb()
                ->createCommand("Select record_id,g.black_player,g.white_player from board_record_rel r left join game_records g on r.record_id=g.id where r.board_id={$board_record->id} order by r.record_id desc limit 10")
                ->queryAll();
            return $this->renderJSON([
                'white_wins' => $board_record->white_wins,
                'black_wins' => $board_record->black_wins,
                'draws' => $board_record->draws,
                'next_move' => json_decode($board_record->next_move,1),
                'related_games' => $related_games
            ]);
        }
        else
        {
            return $this->renderJSON([],'无相关记录',-1);
        }
    }
}