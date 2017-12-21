<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2016/10/2
 * Time: 15:01
 */

namespace GatewayWorker\Lib;


class Security
{
    private static $_data = null;
    private static $_uid = 0;

    public static function verifyRequest($data)
    {
        self::$_data = $data;
        return self::verifyTimestamp() && self::verifyToken();
    }

    private static function verifyTimestamp()
    {
        if(isset(self::$_data['_timestamp']))
        {
            return abs(intval(self::$_data['_timestamp']) - time()) <= 60;
        }
        return false;
    }

    private static function verifyToken()
    {
        if(isset(self::$_data['_token']) && isset(self::$_data['_checksum']))
        {
            $redis = RedisConnection::_get_instance();
            $encoded_secret = $redis->get(self::$_data['_token']);
            if($encoded_secret)
            {
                $redis->setTimeout(self::$_data['_token'],600);
            }
            $tmp_decode = @json_decode($encoded_secret,1);
            if(!isset($tmp_decode['secret']))
            {
                return false;
            }
            $secret = $tmp_decode['secret'];
            self::$_uid = $tmp_decode['uid'];
            $checksum_data = self::$_data;
            unset($checksum_data['_checksum']);
            $checksum_data['_secret'] = $secret;

            ksort($checksum_data);
            $stringfy = '';
            foreach ($checksum_data as $k=>$v)
            {
                if($stringfy)
                {
                    $stringfy .= '&';
                }
                $stringfy .= "{$k}={$v}";
            }
            $server_md5 = md5($stringfy);
            echo "md5 $stringfy  ".self::$_data['_checksum']." {$server_md5}\n";
            if(strtoupper(self::$_data['_checksum']) !== strtoupper($server_md5))
            {
                file_put_contents(
                    "./checksum.log.".date('ymd'),
                    date('H:i:s')." data : ".var_export(self::$_data)."\n stringfy: {$stringfy} \n server md5: {$server_md5} \n",
                    FILE_APPEND);
                return false;
            }
            else
            {
                return true;
            }
        }
        return false;
    }

    public static function getUid()
    {
        return self::$_uid;
    }
}