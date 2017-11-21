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
use common\services\UserService;
use frontend\components\Controller;
use yii\web\HttpException;

class GamesController extends Controller
{
    public function actionGame()
    {
        $game_id = intval($this->get('id'));
        $game = Games::findOne($game_id);
        //TODO 显示正确的错误页。
        if(!$game)
        {
            throw new HttpException(404);
        }
        return $this->render('game',[
            'game' => GameService::renderGame($game_id),
            'ws_token' => GameService::newToken(),
            'userinfo' => $this->_user() ? UserService::renderUser($this->_user()->id ) : null
        ]);
    }

    //TODO 研究一下怎么引入transaction，棋局的计时、计算输赢需要事务处理，冲突了就跪了
    public function actionPlay()
    {
        if(!$this->_user())
        {
            return $this->renderJSON([],'您尚未登录',-1);
        }
        $game_id = intval($this->post("game_id"));
        //TODO 这里如果发生超时之类的，需要通知一下websocket
        $game_info = GameService::renderGame($game_id);
        if(!$game_info)
        {

        }
        if($game_info['whom_to_play'] != $this->_user()->id)
        {

        }
        //到这里，可以落子了。
        //是不是在下打点？
        $game_object = Games::findOne($game_id);
        //提和id设置为0
        $game_object->offer_draw = 0;
        if(strlen($game_object->game_record)/2 == 4)
        {
            //第五手，有2种情况，都很特殊
            $a5_on_board = strlen($game_object->a5_pos)/2;
            if($a5_on_board < $game_object->a5_numbers)
            {
                //落子进a5_pos
            }
            else
            {
                //这是在选黑5，只能是在a5_pos范围内选点。
            }
        }
        else
        {
            //在棋盘上落子。调用胜负判断。
        }
        //轮到自己下了。
        //如果不在摆打点，校验位置合法，可落子，则刷新时间并落子
        //如果在摆打点，校验位置合法，落子计入5apos
        
    }

    public function actionSwap()
    {
        //仅当当前应落第四手，且未交换时，可交换。 if user id == white and not swap and strlen(game_record)==6
        //结算时间
        //swap : 交换黑白ID记录，交换用时。

    }

    public function actionOffer_draw()
    {
        //是对局双方，且棋局进行中
        //如果原提和id为0 则写入自己id
        //如果原提和id为对方，和了。
        //如果原提和id为自己，不做操作。
    }

    public function actionResgin()
    {

    }
}