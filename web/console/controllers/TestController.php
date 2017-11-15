<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/12/2017
 * Time: 23:11
 */

namespace console\controllers;


use common\components\Gateway;
use yii\console\Controller;

class TestController extends Controller
{
    public function actionMsg()
    {
        Gateway::sendToClient('7f0000010b5400000002','11111');
    }
}