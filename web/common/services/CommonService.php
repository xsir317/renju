<?php

namespace common\services;



class CommonService extends BaseService
{
    public static function getIP()
    {
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        return isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"]:'';
    }

    public static function file_log($file_target,$data)
    {
        $content = is_array($data) ? var_export($data,1):$data;
        $content = date('Y-m-d H:i:s').'|'.$content."\n";
        file_put_contents($file_target,$content,FILE_APPEND);
    }

    public static function xmlToArray($xml){
        //将XML转为array
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    public static function getRules($rule_name='')
    {
        $return = [];
        foreach (\Yii::$app->params['rules'] as $k=>$name)
        {
            $return[$k] = \Yii::t('app',$k);
        }

        return $rule_name ? (isset($return[$rule_name]) ? $return[$rule_name] : $rule_name) : $return;
    }
}