<?php
/**
 * Created by PhpStorm.
 * User: hujie
 * Date: 2015/5/14
 * Time: 14:47
 */

namespace console\modules\queue\controllers\notices;


use common\components\Gateway;
use common\components\MsgHelper;
use common\services\UserService;
use console\modules\queue\Queue;

class GameController extends Queue {
    protected $queueName = 'game';
    protected  function handle($data)
    {
        //推送信息
        if(empty($data['game_id']))
        {
            return false;
        }
        $client_list = Gateway::getClientSessionsByGroup($data['game_id']);
        UserService::render($client_list,'uid');
        Gateway::sendToGroup($data['game_id'],MsgHelper::build('client_list',[
            'client_list' => $client_list
        ]));
        return true;
    }
}