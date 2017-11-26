<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2017
 * Time: 17:43
 */

namespace frontend\modules\games\controllers;


use common\components\CustomGateway;
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
            $invite->black_id = intval($this->post('use_black')) ? $this->_user()->id : $to_user_id;
            $invite->message = trim($this->post('comment'));
            $invite->totaltime = $minutes * 60;
            $invite->rule = isset(\Yii::$app->params['rules'][$rule]) ? $rule : 'RIF';
            $invite->free_opening = intval($this->post('free_open'));
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
        CustomGateway::sendToUid($invite_obj->to,MsgHelper::build('invite',[
            'invite' => $invite
        ]));
    }
}