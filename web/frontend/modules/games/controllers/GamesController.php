<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2017
 * Time: 17:51
 */

namespace frontend\modules\games\controllers;


use common\components\Gateway;
use common\components\MsgHelper;
use common\models\Games;
use common\models\GameUndoLog;
use common\services\GameService;
use common\services\UserService;
use frontend\components\Controller;
use yii\web\HttpException;

class GamesController extends Controller
{
    /**
     * 展示对局网页。
     * @return string
     * @throws HttpException
     */
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


    /**
     * 提出悔棋申请
     * 悔棋申请提出时 记录当前局面，提出者id，时间，回到第几手。
     * 不能提出涉及前5手的悔棋。
     * render棋局时，如果是正在进行的棋局，则先update悔棋记录，检查状态0的悔棋申请，和当前盘面不一致的全部-1掉。 将最新的有效的悔棋申请附在数据结构里。
     * 同意：验证盘面与申请时一致，然后恢复到指定手数，render，然后发广播通知。
     * 同意的话，可以获得10%时间的补偿。
     * 终局时清理所有未同意的悔棋申请。
     */
    public function actionUndo_create()
    {
        $game_id = intval($this->post('game_id'));
        //悔棋到第几手。 最终会保留前$to_step - 1手
        $to_step = intval($this->post('to_step'));
        $comment = trim($this->post('comment'));

        if(!$this->_user())
        {
            return $this->renderJSON([],'您尚未登录',-1);
        }
        $game_info = GameService::renderGame($game_id);
        if(!$game_info)
        {
            return $this->renderJSON([],'棋局不存在',-1);
        }
        if($game_info['black_id'] != $this->_user()->id && $game_info['white_id'] != $this->_user()->id)
        {
            return $this->renderJSON([],'这不是您的对局',-1);
        }

        if($game_info['status'] != GameService::PLAYING)
        {
            return $this->renderJSON([],'棋局不是对局状态，不能进行操作。',-1);
        }
        if($to_step <= 5)
        {
            return $this->renderJSON([],'最多只允许悔棋到第六手',-1);
        }
        if(strlen($game_info['game_record']) / 2 <= $to_step)
        {
            return $this->renderJSON([],'悔棋步数超出了当前棋局的范围',-1);
        }

        GameUndoLog::updateAll(['status' => -1],['game_id' => $game_id,'status' => 0,]);

        $undo = new GameUndoLog();
        $undo->game_id = $game_id;
        $undo->uid = $this->_user()->id;
        $undo->current_board = $game_info['game_record'];
        $undo->to_number = $to_step;
        $undo->comment = $comment;
        $undo->status = 0;
        $undo->created_time = date('Y-m-d H:i:s');
        $undo->save(0);

        Gateway::sendToUid(($game_info['black_id'] == $this->_user()->id ? $game_info['white_id'] : $game_info['black_id']),MsgHelper::build('undo',[
            'undo_id' => $undo->id,
        ]));
        return $this->renderJSON([],'悔棋申请成功');

    }

    public function actionUndo_accept()
    {
        $undo_id = intval($this->post('undo_id'));
/*
        if(!$this->_user())
        {
            return $this->renderJSON([],'您尚未登录',-1);
        }
        $game_info = GameService::renderGame($game_id);
        if(!$game_info)
        {
            return $this->renderJSON([],'棋局不存在',-1);
        }
        if($game_info['black_id'] != $this->_user()->id && $game_info['white_id'] != $this->_user()->id)
        {
            return $this->renderJSON([],'这不是您的对局',-1);
        }

        if($game_info['status'] != GameService::PLAYING)
        {
            return $this->renderJSON([],'棋局不是对局状态，不能进行操作。',-1);
        }
*/

    }

    public function actionTimeout()
    {
        $game_id = intval($this->post('game_id'));
        if(!$game_id)
        {
            return $this->renderJSON([],'指定游戏不存在');
        }
        $cache_key = sprintf("timeout_lock_game%d",$game_id);
        $my_rand = rand(10000,99999);
        $lock = \Yii::$app->redis->setNx($cache_key,$my_rand);
        //采用setNx存一个数字进去，如果存成功了，而且
        if($lock && \Yii::$app->redis->get($cache_key) == $my_rand)
        {
            GameService::renderGame($game_id);
            \Yii::$app->redis->setTimeout($cache_key,10);
            return $this->renderJSON([],'done');
        }
        return $this->renderJSON([],'thanks');
    }

    public function actionInfo()
    {
        $game_id = intval($this->get('id'));
        if(!$game_id)
        {
            return $this->renderJSON([],'指定游戏不存在');
        }
        return $this->renderJSON(['game' => GameService::renderGame($game_id)]);
    }
    /**
     * 一个演示板，用于教学、沟通；
     * 就是一个不判断胜负的没有时间限制的演示功能；新建者可落子，可授权给他人落子。
     */
    public function actionPlay_board()
    {

    }

    public function actionHistory()
    {
        $per_page = 12;
        $player_id = intval($this->get('player_id'));
        $player = UserService::renderUser($player_id);
        if(!$player)
        {
            return $this->redirect('/');
        }
        if(\Yii::$app->request->isAjax)
        {
            $page = intval($this->get('page',1));
            $games = Games::find()
                ->select(['id','black_id','white_id','game_record','status','rule','comment'])
                ->where("black_id={$player_id} or white_id={$player_id}")
                ->asArray()
                ->limit($per_page + 1)
                ->offset($per_page * ($page - 1))
                ->orderBy('id desc')
                ->all();
            $has_next = count($games) > $per_page ;
            if($has_next)
            {
                unset($games[$per_page]);
            }
            UserService::render($games,'black_id','black');
            UserService::render($games,'white_id','white');
            return $this->renderJSON([
                'games' => $games,
                'has_next' => $has_next
            ]);
        }
        else
        {
            return $this->render("history",['player' => $player]);
        }
    }
}