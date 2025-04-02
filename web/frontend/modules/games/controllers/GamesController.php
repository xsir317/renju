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
        if($game->is_private)
        {
            if(!$this->_user() || ($this->_user()->id != $game->black_id && $this->_user()->id != $game->white_id))
            {
                return $this->render('forbidden');
            }
        }
        if($game->vip){
            if(!$this->_user() || (!$this->_user()->vip))
            {
                return $this->render('forbidden');
            }
        }
        return $this->render('game',[
            'game' => GameService::renderGame($game_id),
            'ws_token' => GameService::newToken($this->_user() ? $this->_user()->id : 0),
            'userinfo' => $this->_user() ? UserService::renderUser($this->_user()->id ) : null
        ]);
    }


    public function actionTimeout()
    {
        $game_id = intval($this->post('game_id'));
        if(!$game_id)
        {
            return $this->renderJSON([],\Yii::t('app',"Game doesn't exist"));
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
            return $this->renderJSON([],\Yii::t('app',"Game doesn't exist"));
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
                ->select(['id','black_id','white_id','game_record','status','is_private','rule','comment'])
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