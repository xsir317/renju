<?php
namespace frontend\controllers;

use common\models\Player;
use common\services\GameService;
use common\services\UserService;
use frontend\components\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],/*
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],*/
        ];
    }




    public function actionIndex()
    {
        if($this->_user())
        {
            return $this->render('hall',[
                'ws_token' => GameService::newToken($this->_user()->id),
                'userinfo' => UserService::renderUser($this->_user()->id ),
                'game_list' => GameService::getRecentGameList()
            ]);
        }
        else
        {
            return $this->render('index');
        }
    }


    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionLogout()
    {
        if(!\Yii::$app->user->isGuest)
        {
            \Yii::$app->user->logout();
        }
        return $this->redirect('/');
    }

    public function actionTop100()
    {
        $users = \Yii::$app->cache->get('top_players0');
        if(!$users)
        {
            $users = Player::find()
                ->select(['id','nickname','games','score','intro',])
                ->where('games>0')
                ->orderBy('score desc')
                ->limit(100)
                ->asArray()
                ->all();
            \Yii::$app->cache->set('top_players0',$users,60);
        }
        return $this->render('players',['players' => $users]);
    }

    public function actionSwitch_language()
    {
        $language = trim($this->post('language'));
        if(isset(\Yii::$app->params['languages'][$language]))
        {
            \Yii::$app->session['language'] = $language;
        }
        return $this->renderJSON();
    }

    public function actionLanguages()
    {
        header("Content-type: application/javascript");
        $language = trim($this->get('language','zh-CN'));
        if(isset(\Yii::$app->params['languages'][$language]))
        {
            $return = include "@common/languages/{$language}/app.php";
            return 'let lang_map = '.json_encode($return).';';
        }

        return '';
    }
}
