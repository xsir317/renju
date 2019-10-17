<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2019/10/17
 * Time: 13:23
 */

namespace frontend\modules\records\controllers;


use common\models\GameRecords;
use frontend\components\Controller;
use yii\web\HttpException;

class ShowController extends Controller
{
    public function actionIndex()
    {
        $this->layout = '//record';

    }

    public function actionGame()
    {
        $this->layout = '//record';
        $id = intval($this->get('id'));
        $record = GameRecords::findOne($id);
        if(!$record)
        {
            throw new HttpException(404);
        }
        return $this->render('game',[
            'record' => $record
        ]);
    }
}