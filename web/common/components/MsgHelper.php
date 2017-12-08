<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/10/10
 * Time: 18:25
 */

namespace common\components;


class MsgHelper
{
    public static function build($type,$params=[])
    {
        if($type && is_callable('self::build'.ucfirst($type)))
        {
            $result = call_user_func('self::build'.ucfirst($type),$params);
            $result['msg_id'] = self::msg_id();
        }
        else
        {
            throw new MsgException('不支持的消息类型');
        }
        return json_encode($result);
    }
    public static function persist($room_id,$data)
    {
        //TODO 塞个别的队列啥的。。。 用于分析，这里放redis是用于用户显示历史消息
        //插入一个逻辑，如果长度已经超过了100，清一下。。。
        if(\Yii::$app->redis->zSize(self::getRoomMsgKey($room_id)) > 100)
        {
            //只保留最近10个
            \Yii::$app->redis->zRemRangeByRank(self::getRoomMsgKey($room_id),0,-11);
        }
        return \Yii::$app->redis->zAdd(
            self::getRoomMsgKey($room_id),
            self::getRoomMsgId($room_id),
            $data
        );
    }

    public static function getRecentMsgs($room_id)
    {
        $return =  \Yii::$app->redis->zRange(
            self::getRoomMsgKey($room_id),
            -5,
            -1
        );
        foreach ($return as &$item)
        {
            $item = @json_decode($item,1);
        }
        return $return;
    }

    private static function buildShutdown($params)
    {
        $content = '内部错误，请刷新网页';
        if(!empty($params['content']) && is_string($params['content']))
        {
            $content = $params['content'];
        }
        return [
            'type' => 'shutdown',
            'content' => $content
        ];
    }

    private static function buildClient_list($params)
    {
        if(!isset($params['client_list']) || !is_array($params['client_list']))
        {
            throw new MsgException('参数不完整，需要client_list');
        }
        return [
            'type' => 'client_list',
            'client_list' => $params['client_list']
        ];
    }
    private static function buildGame_start($params)
    {
        if(!isset($params['game_id']))
        {
            throw new MsgException('参数不完整，需要game_id');
        }
        return [
            'type' => 'game_start',
            'game_id' => $params['game_id']
        ];
    }


    private static function buildEnter($params)
    {
        if(!isset($params['client_id']) || !is_string($params['client_id']))
        {
            throw new MsgException('参数不完整，需要client_id');
        }
        return [
            'type' => 'enter',
            'client_id' => $params['client_id'],
            'history_msg' => empty($params['history_msg']) ? [] : $params['history_msg']
        ];
    }

    private static function buildLogin($params)
    {
        if(empty($params['user']))
        {
            throw new MsgException('参数不完整，需要user');
        }
        return [
            'type' => 'login',
            'user' => $params['user'],
            'time' => date('Y-m-d H:i:s'),
        ];
    }

    private static function buildNotice($params)
    {
        if(empty($params['content']))
        {
            throw new MsgException('缺少提示内容');
        }

        return [
            'type' => 'notice',
            'content' => $params['content']
        ];
    }
    private static function buildGame_over($params)
    {
        if(empty($params['content']))
        {
            throw new MsgException('缺少提示内容');
        }

        return [
            'type' => 'game_over',
            'content' => $params['content']
        ];
    }

    private static function buildInvite($params)
    {
        if(empty($params['invite']))
        {
            throw new MsgException('缺少invite');
        }

        return [
            'type' => 'invite',
            'invite' => $params['invite']
        ];
    }

    private static function buildSay($params)
    {
        if(empty($params['from_user']))
        {
            throw new MsgException('缺少参数from_user');
        }
        if(empty($params['content']))
        {
            throw new MsgException('缺少参数content');
        }
        $return = [
            'type' => 'say',
            'from_user' => $params['from_user'],
            'content' => $params['content'],
            'board' => isset($params['board']) ? $params['board'] : '',
            'time' => date('Y-m-d H:i:s'),
        ];
        return $return;
    }

    private static function buildLogout($params)
    {
        if(empty($params['client']))
        {
            throw new MsgException('参数不完整，缺少client');
        }
        if(!isset($params['client_list']))
        {
            throw new MsgException('参数不完整，缺少client_list');
        }

        return [
            'type' => 'logout',
            'client' => $params['client'],
            'client_list' => $params['client_list'],
            'time' => date('Y-m-d H:i:s')
        ];
    }

    private static function buildGame_info($params)
    {
        if(!isset($params['game']))
        {
            throw new MsgException('参数不完整，缺少game游戏信息');
        }
        return [
            'type' => 'game_info',
            'game' => $params['game']
        ];
    }

    private static function buildGames($params)
    {
        if(!isset($params['games']))
        {
            throw new MsgException('参数不完整，缺少games游戏信息');
        }
        return [
            'type' => 'games',
            'games' => $params['games']
        ];
    }

    private static function getRoomMsgKey($room_id)
    {
        return sprintf("room_msg::%s",$room_id);
    }

    private static function getRoomMsgId($room_id)
    {
        return \Yii::$app->redis->hIncrBy("room_incr_msg",$room_id,1);
    }

    private static function msg_id()
    {
        return \Yii::$app->redis->incr("global_msg_id");
    }
}

class MsgException extends \Exception
{

}