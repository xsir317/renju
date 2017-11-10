<?php
namespace frontend\controllers;

use frontend\components\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    //TODO  geetest
    public function actionLogin()
    {
        $req = \Yii::$app->request;
        if($req->isPost)
        {
            return $this->renderJSON(['redirect' => 'http://www.163.com/']);
        }
    }

    public function actionReg()
    {

    }
}
