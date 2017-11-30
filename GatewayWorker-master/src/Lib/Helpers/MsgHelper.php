<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/10/10
 * Time: 18:25
 */

namespace GatewayWorker\Lib\Helpers;


//TODO 把这些private方法公开出去调用，这样IDE可以检查参数。
use GatewayWorker\Lib\RedisConnection;

class MsgHelper
{
    public static function build($type,$params=[],$encode=true)
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
        return $encode ? json_encode($result) : $result;
    }

    public static function persist($room_id,$data)
    {
        //TODO 塞个别的队列啥的。。。 用于分析，这里放redis是用于用户显示历史消息
        return RedisConnection::_get_instance()->zAdd(
            self::getRoomMsgKey($room_id),
            self::getRoomMsgId($room_id),
            $data
        );
    }

    public static function getRecentMsgs($room_id)
    {
        $return =  RedisConnection::_get_instance()->zRange(
            self::getRoomMsgKey($room_id),
            -10,
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
            'client_list' => $params['client_list'],
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

    private static function buildRoom_announce($params)
    {
        if(empty($params['user']))
        {
            throw new MsgException('缺少参数user');
        }
        if(empty($params['content']))
        {
            throw new MsgException('缺少参数content');
        }

        return [
            'type' => 'room_announce',
            'user' => $params['user'],
            'content' => $params['content'],
            'time' => date('Y-m-d H:i:s'),
        ];
    }

    private static function buildLogout($params)
    {
        if(empty($params['user']))
        {
            throw new MsgException('参数不完整，缺少user');
        }

        return [
            'type' => 'logout',
            'user' => $params['user'],
            'time' => date('Y-m-d H:i:s')
        ];
    }

    private static function getRoomMsgKey($room_id)
    {
        return sprintf("room_msg::%s",$room_id);
    }

    private static function getRoomMsgId($room_id)
    {
        return RedisConnection::_get_instance()->hIncrBy("room_incr_msg",$room_id,1);
    }


    private static function msg_id()
    {
        return RedisConnection::_get_instance()->incr("global_msg_id");
    }
}

class MsgException extends \Exception
{

}