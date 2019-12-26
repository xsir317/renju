<?php
namespace frontend\controllers;

use common\components\RenjuBoardTool;
use common\components\RenjuBoardTool_bit;
use common\models\Liyi;
use common\models\Player;
use common\services\GameService;
use common\services\UserService;
use frontend\components\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;
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

    public function actionBoard()
    {
        $board = $this->post('board');
        $target = $this->post('target');
        $color = strlen($board) % 4 == 0 ? 'black':'white';
        //$board = new RenjuBoardTool($board);
        $board = new RenjuBoardTool_bit($board);
        return $this->renderJSON([
            'result' => $board->checkWin($target,$color),
        ]);
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
            if($this->_user())
            {
                $this->_user()->language = \Yii::$app->session['language'];
                $this->_user()->save(0);
            }
        }
        return $this->renderJSON();
    }

    public function actionLanguages()
    {
        header("Content-type: application/javascript");
        $language = trim($this->get('language'));
        $language = $language ? : 'zh-CN';
        if(isset(\Yii::$app->params['languages'][$language]))
        {
            $return = require \Yii::getAlias("@common/languages/{$language}/app.php");
            return 'let lang_map = '.json_encode($return).';';
        }

        return '';
    }

    public function actionLiyi()
    {
        $id = intval($this->get('id'));
        $article = Liyi::findOne($id);
        return $this->render('liyi',[
            'article' => $article,
            'articles' => Liyi::find()->all()
        ]);
    }
}
