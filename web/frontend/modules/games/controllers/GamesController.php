<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2017
 * Time: 17:51
 */

namespace frontend\modules\games\controllers;


use common\models\Games;
use common\services\GameService;
use frontend\components\Controller;

class GamesController extends Controller
{
    public function actionGame()
    {
        $game_id = intval($this->get('id'));
        $game = Games::findOne($game_id);
        return $this->render('game',[
            'game' => GameService::renderGame($game),
            'ws_token' => GameService::newToken(),
            'userinfo' => $this->_user() ? GameService::renderUser($this->_user()->id ) : 0
        ]);
    }

    //TODO 研究一下怎么引入transaction，棋局的计时、计算输赢需要事务处理，冲突了就跪了
    public function actionPlay()
    {

    }

    public function actionSwap()
    {

    }

    public function actionOffer_draw()
    {

    }

    public function actionResgin()
    {

    }
}