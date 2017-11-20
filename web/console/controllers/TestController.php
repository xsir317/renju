<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/12/2017
 * Time: 23:11
 */

namespace console\controllers;


use common\components\Gateway;
use common\components\MsgHelper;
use common\models\Games;
use common\services\GameService;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionMsg()
    {
        Gateway::sendToGroup(1,MsgHelper::build('game_info',[
            'game' => GameService::renderGame(1)
        ]));
    }
}