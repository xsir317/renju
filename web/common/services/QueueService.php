<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/11/13
 * Time: 13:34
 */

namespace common\services;


class QueueService extends BaseService
{
    private static $queues = [//队列 简称 => 队列key
        'client_list' => 'queue_game_info',
    ];

    public static function len($list_name)
    {
        if($key = self::get_list_key($list_name))
        {
            return \Yii::$app->queue->lLen($key);
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
            return \Yii::$app->queue->lPush($key,serialize($value));
        }
        return false;
    }

    public static function pop($name)
    {
        if($key = self::get_list_key($name))
        {
            $data = \Yii::$app->queue->rPop($key);
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
        return self::_err($name.'队列未定义');
    }
}