<?php
/**
 * TODO 这个文件在网站那边也有一个，需要有一个自动的机制保证同步
 * Created by PhpStorm.
 * User: user
 * Date: 2016/10/11
 * Time: 15:44
 */

namespace GatewayWorker\Lib;



class CustomGateway extends Gateway
{
    const UID_CLIENT_SET = 'uid_client';
    const CLIENT_UID_HASH = 'client_uid';


    public static function isUidOnline($uid)
    {
        return intval(RedisConnection::_get_instance()->sSize(self::UID_CLIENT_SET.'::'.$uid)) > 0;
    }

    public static function getClientIdByUid($uid)
    {
        $uid = intval($uid);
        if(!$uid)
        {
            return [];
        }
        return RedisConnection::_get_instance()->sMembers(self::UID_CLIENT_SET.'::'.$uid);
    }
    
    public static function getUidByClient_id($client_id)
    {
        return intval(RedisConnection::_get_instance()->hGet(self::CLIENT_UID_HASH,$client_id));
    }

    public static function bindUid($client_id, $uid)
    {
        RedisConnection::_get_instance()->sAdd(self::UID_CLIENT_SET.'::'.$uid,$client_id);
        RedisConnection::_get_instance()->hSet(self::CLIENT_UID_HASH,$client_id,$uid);
        return true;
    }

    public static function unbindUid($client_id, $uid)
    {
        RedisConnection::_get_instance()->sRemove(self::UID_CLIENT_SET.'::'.$uid,$client_id);
        RedisConnection::_get_instance()->hDel(self::CLIENT_UID_HASH,$client_id);
        return null;
    }

    public static function sendToUid($uid, $message)
    {
        $client_ids = self::getClientIdByUid($uid);
        if(!empty($client_ids))
        {
            foreach ($client_ids as $client_id)
            {
                self::sendToClient($client_id,$message);
            }
            return true;
        }
        return false;
    }

}