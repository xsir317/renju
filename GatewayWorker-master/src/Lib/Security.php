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
    private $_data = null;

    public function __construct($data)
    {
        $this->_data = $data;
    }
    public function verifyRequest()
    {
        return $this->verifyTimestamp() && $this->verifyToken();
    }

    private function verifyTimestamp()
    {
        if(isset($this->_data['_timestamp']))
        {
            return abs(intval($this->_data['_timestamp']) - time()) <= 60;
        }
        return false;
    }

    private function verifyToken()
    {
        if(isset($this->_data['_token']) && isset($this->_data['_checksum']))
        {
            $redis = RedisConnection::_get_instance();
            $secret = $redis->get($this->_data['_token']);
            if($secret)
            {
                $redis->setTimeout($this->_data['_token'],600);
            }
            $checksum_data = $this->_data;
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
            echo "md5 $stringfy  {$this->_data['_checksum']} {$server_md5}\n";
            if(strtoupper($this->_data['_checksum']) !== strtoupper($server_md5))
            {
                file_put_contents(
                    "./checksum.log.".date('ymd'),
                    date('H:i:s')." data : ".var_export($this->_data)."\n stringfy: {$stringfy} \n server md5: {$server_md5},client md5 {$this->_data['_checksum']} \n",
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
}