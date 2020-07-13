<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/21/2017
 * Time: 7:57 PM
 */

namespace frontend\modules\games\controllers;


use common\components\BoardTool;
use common\components\Gateway;
use common\components\MsgHelper;
use common\models\Games;
use common\services\CommonService;
use common\services\GameService;
use common\services\UserService;
use frontend\components\Controller;

class ChatController extends Controller
{
    public function actionSay()
    {
        //TODO 按照IP限制发言频率
        //TODO 按照关键词屏蔽内容
        $game_id = $this->post('game_id');
        $content = strip_tags(trim($this->post('content')));
        $board_str = trim($this->post('board'));//用于复盘讨论的board。
        if(!$board_str || !BoardTool::board_correct($board_str) || !intval($game_id))
        {
            $board_str = '';
        }

        if(!$this->_user())
        {
            return $this->renderJSON([],\Yii::t('app','Please Login'),-1);
        }
        //限制发言长度，检查发言重复等
        if(mb_strlen($content,'UTF-8') > 80)
        {
            return $this->renderJSON([],\Yii::t('app',"Content too long!"),-1);
        }
        //发言长度和重复检查 end
        if(!$game_id)
        {
            return $this->renderJSON([],'没有指定房间号',-1);
        }
        if(intval($game_id))
        {
            $game = Games::findOne($game_id);
            //检查是否允许聊天
            if(($game && $game->status == GameService::PLAYING && !$game->allow_ob_talk) && ($this->_user()->id != $game->black_id && $this->_user()->id != $game->white_id))
            {
                return $this->renderJSON([],'本房间对局期间不可发言',-1);
            }
        }


        //发言记录
        $content_hash = md5($content);
        $speak_record = \Yii::$app->redis->hGet('last_speak',$this->_user()->id);
        if($speak_record)
        {
            $speak_record = json_decode($speak_record,1);
            if($content_hash == $speak_record['content_hash'])
            {
                return $this->renderJSON([],\Yii::t('app',"Please don't repeat the same content."),-1);
            }
            if(abs(time() - $speak_record['time']) < 5)
            {
                return $this->renderJSON([],\Yii::t('app',"Speaking interval is too short,Please send again later"),-1);
            }
        }
        \Yii::$app->redis->hSet('last_speak',$this->_user()->id,json_encode([
            'content_hash' => $content_hash,
            'time' => time(),
        ]));

        $from_user = UserService::renderUser($this->_user()->id);

        $new_message = [
            'from_user' => $from_user,
            'content' => $content,
            'board' => $board_str
        ];
        //日志
        CommonService::file_log(\Yii::$app->getRuntimePath().'/chat.'.date('ymd').'.log',sprintf("IP:%s , UID:%s, Room:%s, content:%s",CommonService::getIP(),$this->_user()->id,$game_id,$content));
        $send_msg = MsgHelper::build('say',$new_message);
        //TODO 放到队列里？考虑下是否要异步处理。
        try{
            //保存消息到redis中
            MsgHelper::persist($game_id,$send_msg);
            Gateway::sendToGroup($game_id ,$send_msg);
        }catch (\Exception $e)
        {
            return $this->renderJSON([],$e->getMessage(),-1);
        }
        return $this->renderJSON([
            'message' => @json_decode($send_msg,1),
        ],'发送成功');
    }
}