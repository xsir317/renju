<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/12/9
 * Time: 18:14
 */

namespace console\controllers;


use yii\console\Controller;

class ToolsController extends Controller
{
    public function actionCombine_js()
    {
        $files = ['page','board','swfobject','web_socket','md5.min','websocket'];
        $folder = dirname(\Yii::$app->getBasePath()).'/frontend/web/js/';
        file_put_contents($folder.'all.js',"/*".date('Y-m-d H:i:s')."*/\n");
        foreach ($files as $_f)
        {
            $file_name = $folder.$_f.'.js';
            $tmp = file_get_contents($file_name);
            file_put_contents($folder.'all.js',"/* {$_f}.js */\n\n {$tmp}\n",FILE_APPEND);
        }
    }
}