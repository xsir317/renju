<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/11/13
 * Time: 13:34
 */

namespace GatewayWorker\Lib;


class QueueService
{
    private static $queues = [//队列 简称 => 队列key
        'game' => 'queue_game_info',
    ];

    public static function len($list_name)
    {
        if($key = self::get_list_key($list_name))
        {
            return RedisConnection::_get_instance()->lLen($key);
        }
        return false;
    }

    //注意，存取的方法并不对等，insert时额外加了一层入队时间
    public static function insert($name,$data)
    {
        if($key = self::get_list_key($name))
        {
            $value = [
                "data"    =>    $data,
                "created_time"    =>    time()
            ];
            return RedisConnection::_get_instance()->lPush($key,serialize($value));
        }
        return false;
    }

    public static function pop($name)
    {
        if($key = self::get_list_key($name))
        {
            $data = RedisConnection::_get_instance()->rPop($key);
            return @unserialize($data);
        }
        return false;
    }


    private static function get_list_key($name)
    {
        if(isset(self::$queues[$name]))
        {
            return self::$queues[$name];
        }
        return false;
    }
}