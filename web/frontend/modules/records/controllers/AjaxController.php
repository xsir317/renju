<?php
/**
 * Created by PhpStorm.
 * User: HuJie
 * Date: 2019/10/16
 * Time: 0:35
 */

namespace frontend\modules\records\controllers;


use common\components\RenjuBoardTool_bit;
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
            return $this->renderJSON([
                'white_wins' => $board_record->white_wins,
                'black_wins' => $board_record->black_wins,
                'draws' => $board_record->draws,
                'next_move' => json_decode($board_record->next_move,1),
            ]);
        }
        else
        {
            return $this->renderJSON([],'无相关记录',-1);
        }
    }
}