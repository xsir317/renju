<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/12/2017
 * Time: 23:11
 */

namespace console\controllers;


use common\components\BoardTool;
use common\components\ForbiddenPointFinder;
use common\components\GameStatistics;
use common\components\Gateway;
use common\components\MsgHelper;
use common\components\RenjuBoardTool_bit;
use common\models\BoardWinStatistics;
use common\models\Games;
use common\services\GameService;
use common\services\UserService;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionMsg()
    {
        Gateway::sendToGroup(1,MsgHelper::build('game_info',[
            'game' => GameService::renderGame(1)
        ]));

        $client_list = Gateway::getClientSessionsByGroup(1);
        UserService::render($client_list,'uid');
        Gateway::sendToGroup(1,MsgHelper::build('client_list',[
            'client_list' => $client_list
        ]));
    }

    public function actionGames()
    {
        GameService::sendGamesList();
    }

    public function actionA5()
    {
        $board = new ForbiddenPointFinder('');
        var_dump($board->AddStone(7,7,0));
        //var_dump(BoardTool::a5_symmetry('88798a99','9a7a'));
    }

    public function actionPack()
    {
        echo GameStatistics::regularize('8889aa');
    }
}