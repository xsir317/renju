<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2017
 * Time: 17:43
 */

namespace frontend\modules\games\controllers;


use common\components\Gateway;
use common\components\MsgHelper;
use common\models\GameInvites;
use common\models\Games;
use common\models\Player;
use common\services\GameService;
use common\services\UserService;
use frontend\components\Controller;

class InviteController extends Controller
{
    public function actionCreate()
    {
        if(!$this->_user())
        {
            return $this->renderJSON([],'请先登录',-1);
        }
        $id = intval($this->post('id'));
        $to_user_id = intval($this->post('to_user'));
        $minutes = 60 * intval($this->post('hours')) + intval($this->post('minutes'));
        $rule = trim($this->post('rule'));
        $rule = isset(\Yii::$app->params['rules'][$rule]) ? $rule : 'RIF';
        $use_black = intval($this->post('use_black'));
        $comment = trim($this->post('comment'));
        $free_open = intval($this->post('free_open'));
        if($minutes < 3)
        {
            return $this->renderJSON([],'时间请至少设置为3分钟',-1);
        }
        if($minutes >= 180)
        {
            return $this->renderJSON([],'系统暂不接受3小时及以上的对局',-1);
        }
        if($id)
        {
            $exist_invite = GameInvites::findOne($id);
            if(!$exist_invite || $exist_invite->to != $this->_user()->id || $exist_invite->status != 0)
            {
                return $this->renderJSON([],'此邀请不存在',-1);
            }
            $invite_from = $exist_invite->from;
            //核对，一切都符合则进入游戏； 不符合则进入协商，修改invite发给对面。
            $match = true;
            //发出15秒后没有应答，则进入二次协商。
            //以下逻辑其实可以写在一起的，但是写在一起 这个if 太长了。。。
            if(time() - strtotime($exist_invite->updtime) > 15)
            {
                $match = false;
            }
            elseif ($exist_invite->black_id != ($use_black ? $this->_user()->id : $invite_from))//执黑？
            {
                $match = false;
            }
            elseif ($exist_invite->rule != $rule || $exist_invite->totaltime != $minutes * 60 || $exist_invite->free_opening != $free_open)//rule
            {
                $match = false;
            }
            elseif ($exist_invite->message != $comment)
            {
                $match = false;
            }

            if($match)
            {
                //新建棋局， 进入游戏 TODO 封装一下
                $game = new Games();
                $game->black_id = $use_black ? $this->_user()->id : $invite_from;
                $game->white_id = $use_black ? $invite_from : $this->_user()->id;
                $game->status = GameService::PLAYING;
                $game->offer_draw = 0;
                $game->rule = $rule;
                $game->free_opening = $free_open;
                $game->game_record = '';
                $game->black_time = $minutes * 60;
                $game->white_time = $minutes * 60;
                $game->totaltime = $minutes * 60;
                $game->swap = 0;
                $game->soosyrv_swap = 0;
                $game->a5_pos = '';
                $game->a5_numbers = $rule == 'RIF' ? 2:0;
                $game->updtime = date('Y-m-d H:i:s');
                $game->movetime = date('Y-m-d H:i:s');
                $game->comment = $comment;
                $game->tid = 0;
                $game->create_time = date('Y-m-d H:i:s');
                $game->save(0);
                Gateway::sendToUid($this->_user()->id,MsgHelper::build('game_start',[
                    'game_id' => $game->id,
                ]));
                Gateway::sendToUid($invite_from,MsgHelper::build('game_start',[
                    'game_id' => $game->id,
                ]));
                return $this->renderJSON([],'接受邀请，对局即将开始');
            }
            else
            {
                $exist_invite->from = $this->_user()->id; //对调双方。。。
                $exist_invite->to = $invite_from;
                $exist_invite->black_id = $use_black ? $this->_user()->id : $invite_from;
                $exist_invite->message = $comment;
                $exist_invite->totaltime = $minutes * 60;
                $exist_invite->rule = $rule;
                $exist_invite->free_opening = $free_open;
                $exist_invite->status = 0;
                $exist_invite->game_id = 0;
                $exist_invite->updtime = date('Y-m-d H:i:s');
                $exist_invite->save(0);
                $this->send_invite($exist_invite);
                return $this->renderJSON([],'邀请发送成功');
            }

        }
        elseif ($to_user_id)//create
        {
            if($to_user_id == $this->_user()->id)
            {
                return $this->renderJSON([],'目前还不能邀请自己',-1);
            }
            $to_user = Player::findOne($to_user_id);
            if(!$to_user)
            {
                return $this->renderJSON([],'您选择的玩家不存在',-1);
            }
            //正在下棋的不让邀请 1表示正在对局
            if(Games::find()->where("status=1 and (black_id={$to_user_id} or white_id={$to_user_id})")->one())
            {
                return $this->renderJSON([],'此玩家正在对局中，不能邀请',-1);
            }
            //create a db record
            $invite = new GameInvites();
            $invite->from = $this->_user()->id;
            $invite->to = $to_user_id;
            $invite->black_id = $use_black ? $this->_user()->id : $to_user_id;
            $invite->message = $comment;
            $invite->totaltime = $minutes * 60;
            $invite->rule = isset(\Yii::$app->params['rules'][$rule]) ? $rule : 'RIF';
            $invite->free_opening = $free_open;
            $invite->status = 0;
            $invite->game_id = 0;
            $invite->updtime = date('Y-m-d H:i:s');
            $invite->save(0);
            $this->send_invite($invite);
            return $this->renderJSON([],'邀请发送成功');
        }
        else
        {
            return $this->renderJSON([],'参数错误，请刷新重试',-1);
        }
        return $this->renderJSON();
    }

    /**
     * @param GameInvites $invite_obj
     */
    private function send_invite($invite_obj)
    {
        $invite = $invite_obj->toArray();
        $invite['from_user'] = UserService::renderUser($invite_obj->from);
        Gateway::sendToUid($invite_obj->to,MsgHelper::build('invite',[
            'invite' => $invite
        ]));
    }
}