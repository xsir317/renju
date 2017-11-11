<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2017
 * Time: 17:51
 */

namespace frontend\modules\games\controllers;


use frontend\components\Controller;

class GamesController extends Controller
{
    public function actionGame()
    {
        $game_id = intval($this->get('id'));
        echo $game_id;
    }
}