<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/10/11
 * Time: 15:44
 */

namespace common\components;


class CustomGateway extends Gateway
{
    const UID_CLIENT_SET = 'uid_client';
    const CLIENT_UID_HASH = 'client_uid';
    public static function isUidOnline($uid)
    {
        return intval(\Yii::$app->redis->sSize(self::UID_CLIENT_SET.'::'.$uid)) > 0;
    }

    public static function getClientIdByUid($uid)
    {
        $uid = intval($uid);
        if(!$uid)
        {
            return [];
        }
        return \Yii::$app->redis->sMembers(self::UID_CLIENT_SET.'::'.$uid);
    }

    public static function getUidByClient_id($client_id)
    {
        return intval(\Yii::$app->redis->hGet(self::CLIENT_UID_HASH,$client_id));
    }

    public static function bindUid($client_id, $uid)
    {
        \Yii::$app->redis->sAdd(self::UID_CLIENT_SET.'::'.$uid,$client_id);
        \Yii::$app->redis->hSet(self::CLIENT_UID_HASH,$client_id,$uid);
        return true;
    }

    public static function unbindUid($client_id, $uid)
    {
        \Yii::$app->redis->sRemove(self::UID_CLIENT_SET.'::'.$uid,$client_id);
        \Yii::$app->redis->hDel(self::CLIENT_UID_HASH,$client_id);
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

    public static function disconnect($client_id)
    {
        $uid = self::getUidByClient_id($client_id);
        if($uid)
        {
            self::unbindUid($client_id,$uid);
        }
    }
}