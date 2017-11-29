<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/21/2017
 * Time: 7:57 PM
 */

namespace frontend\modules\games\controllers;


use common\components\Gateway;
use common\components\MsgHelper;
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

        if(!$this->_user())
        {
            return $this->renderJSON([],'请先登录',-1);
        }
        //限制发言长度，检查发言重复等
        if(mb_strlen($content,'UTF-8') > 80)
        {
            return $this->renderJSON([],'内容长度超过限制',-1);
        }
        //发言长度和重复检查 end
        if(!$game_id)
        {
            return $this->renderJSON([],'没有指定房间号',-1);
        }

        //发言记录
        $content_hash = md5($content);
        $speak_record = \Yii::$app->redis->hGet('last_speak',$this->_user()->id);
        if($speak_record)
        {
            $speak_record = json_decode($speak_record,1);
            if($content_hash == $speak_record['content_hash'])
            {
                return $this->renderJSON([],'请不要发布相同的内容',-1);
            }
            if(abs(time() - $speak_record['time']) < 5)
            {
                return $this->renderJSON([],'发言间隔太短，请稍后再发',-1);
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
        ];
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