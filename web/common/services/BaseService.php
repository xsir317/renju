<?php
/**
 * Created by PhpStorm.
 * User: hujie
 * Date: 2015/4/27
 * Time: 19:06
 * Service们的基类，定义了统一的错误处理方式、错误信息和代码。
 */

namespace common\services;


class BaseService {
    protected static $_error_msg = null;
    protected static $_error_code = null;

    //protected static $error_messages = [
    //    -1 => '未定义的错误',
    //    0 => 'xxx',
    //    1 => 'xxx',//...
    //];

    /**
     * @param $msg
     * @return bool false
     *
     * usage:
     * if(some error)
     * {
     *      return self::_err(msg,code);// will set error code and return false
     * }
     *
     * if(! some operate)
     * {
     *      XXService::getLastErrorMsg();// will get the last error
     * }
     */

    public static function _err($msg='',$code = -1)
    {
        if($msg)
        {
            self::$_error_msg = $msg;
        }
        else
        {
            self::$_error_msg = '操作失败';
        }
        self::$_error_code = $code;
        return false;
    }

    public static function getLastErrorMsg()
    {
        if(self::$_error_msg !== null)
        {
            return self::$_error_msg;
        }
        return '';
    }


    public static function getLastErrorCode()
    {
        if(self::$_error_code !== null)
        {
            return self::$_error_code;
        }
        return 0;
    }
}