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
}