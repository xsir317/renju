<?php
namespace frontend\controllers;

use common\models\Player;
use common\services\CommonService;
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
                'ws_token' => GameService::newToken(),
                'userinfo' => UserService::renderUser($this->_user()->id )
            ]);
        }
        else
        {
            return $this->render('index');
        }
    }


    public function actionLogin()
    {
        $req = \Yii::$app->request;
        if($req->isPost)
        {
            $email = trim($this->post('email'));
            $password = trim($this->post('passwd'));
            $user = Player::findOne(['email' => $email]);
            //验证密码
            if($user && $user->password == Player::hash_pwd($password))
            {
                \Yii::$app->user->login($user,30*86400);
                return $this->renderJSON(['redirect' => '/']);
            }
            else
            {
                return $this->renderJSON([],'账号/密码错',-1);
            }
        }
        return $this->renderJSON([],'请求方式错误',-1);
    }

    public function actionReg()
    {
        $req = \Yii::$app->request;
        if($req->isPost)
        {
            $email = trim($this->post('email'));
            $password = trim($this->post('passwd'));
            $nickname = trim($this->post('nickname'));
            $user = Player::findOne(['email' => $email]);
            //验证密码
            if($user)
            {
                return $this->renderJSON([],'这个Email已经被占用',-1);
            }
            else
            {
                $new_user = new Player();
                $new_user->email = $email;
                $new_user->nickname = $nickname;
                $new_user->password = Player::hash_pwd($password);
                $new_user->login_times = 0;
                $new_user->b_win = 0;
                $new_user->b_lose = 0;
                $new_user->w_win = 0;
                $new_user->w_lose = 0;
                $new_user->draw = 0;
                $new_user->games = 0;
                $new_user->reg_time = date('Y-m-d H:i:s');
                $new_user->reg_ip = CommonService::getIP();
                $new_user->last_login_time = date('Y-m-d H:i:s');
                $new_user->last_login_ip = CommonService::getIP();
                $new_user->score = 2100;
                $new_user->intro = '';
                $new_user->save(0);
                \Yii::$app->user->login($new_user,30*86400);
                return $this->renderJSON(['redirect' => '/']);
            }
        }
        return $this->renderJSON([],'请求方式错误',-1);
    }

    public function actionLogout()
    {
        if(!\Yii::$app->user->isGuest)
        {
            \Yii::$app->user->logout();
        }
        return $this->redirect('/');
    }
}
