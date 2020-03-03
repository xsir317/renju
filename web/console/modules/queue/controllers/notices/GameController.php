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
    protected $queueName = 'client_list';
    protected  function handle($data)
    {
        //推送信息
        if(empty($data['game_id']))
        {
            return false;
        }
        $client_list = Gateway::getClientSessionsByGroup($data['game_id']);
        $uniq_client_list = [];
        $uids = [];
        foreach ($client_list as $item)
        {
            if(!isset($uids[$item['uid']]))
            {
                $uids[$item['uid']] = 1;
                $uniq_client_list[] = $item;
            }
        }
        UserService::render($uniq_client_list,'uid');
        Gateway::sendToGroup($data['game_id'],MsgHelper::build('client_list',[
            'client_list' => $uniq_client_list
        ]));
        return true;
    }
}