<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2017
 * Time: 17:43
 */

namespace frontend\modules\games\controllers;


use common\models\Games;
use common\models\Player;
use common\services\GameService;
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
        }
        else
        {
            return $this->renderJSON([],'参数错误，请刷新重试',-1);
        }
        return $this->renderJSON();
    }
}