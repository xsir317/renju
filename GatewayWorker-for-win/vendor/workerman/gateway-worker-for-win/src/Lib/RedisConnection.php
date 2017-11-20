<?php
namespace GatewayWorker\Lib;

/**
 *  host port 等参数都与Redis扩展一致。
 */
class RedisConnection
{
    const EVENT_AFTER_OPEN = 'afterOpen';

    public $host = '127.0.0.1';
    public $port = 6379;
    public $timeout =  0.5;
    public $database = 0;
    public $unixSocket;
    public $retry_interval;
    public $prefix = 'cache::';

    private $_redisconn_instance = null;
    private static $_instance = null;

    public function __sleep()
    {
        $this->close();
        return array_keys(get_object_vars($this));
    }

    public static function _get_instance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new self();
            self::$_instance->open();
        }
        return self::$_instance;
    }

    /**
     * Returns a value indicating whether the DB connection is established.
     * @return boolean whether the DB connection is established
     */
    public function getIsActive()
    {
        return $this->_redisconn_instance !== null;
    }

    /**
     * Establishes a DB connection.
     * It does nothing if a DB connection has already been established.
     * @throws \Exception if connection fails
     */
    public function open()
    {
        if ($this->_redisconn_instance !== null) {
            return;
        }
        $this->_redisconn_instance = new \Redis();
        $success = false;
        if($this->unixSocket)
        {
            $success = $this->_redisconn_instance->connect($this->unixSocket);
        }
        else
        {
            $success = $this->_redisconn_instance->connect($this->host,$this->port,$this->timeout,null,$this->retry_interval);
        }
        if($success)
        {
            $this->select($this->database);
            $this->initConnection();
        }
        else
        {
             throw new \Exception('Failed to open redis DB connection ', -1);
        }
    }

    /**
     * Closes the currently active DB connection.
     * It does nothing if the connection is already closed.
     */
    public function close()
    {
        if ($this->_redisconn_instance !== null) {
            $this->_redisconn_instance->close();
            $this->_redisconn_instance = null;
        }
    }

    protected function initConnection()
    {
        $this->setOption(\Redis::OPT_PREFIX, $this->prefix);
    }

    /**
     * Returns the name of the DB driver for the current [[dsn]].
     * @return string name of the DB driver
     */
    public function getDriverName()
    {
        return 'redis';
    }

    /**
     *
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params)
    {
        $this->open();
        //TODO 可能要禁止使用者调用connect之类的方法。
        if (is_callable([$this->_redisconn_instance,$name],true)) {
            return call_user_func_array([$this->_redisconn_instance,$name],$params);
        } else {
            return parent::__call($name, $params);
        }
    }

    //存取数组用的方法
    public function getData($key)
    {
        $data = $this->get($key);
        if($data)
        {
            return unserialize($data);
        }
        return null;
    }

    public function setData($key,$value,$expire=null)
    {
        $value = serialize($value);
        if($expire)
        {
            return $this->set($key,$value,$expire);
        }
        return $this->set($key,$value);
    }
}
