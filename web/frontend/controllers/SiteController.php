<?php
namespace frontend\controllers;

use common\components\RenjuBoardTool_bit;
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
                'game_list' => GameService::getRecentGameList($this->_user())
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
        return $this->render('players');
    }

    public function actionPlayers()
    {
        $per_page = 100;
        $page = intval($this->get('page',1));
        $cache_key = "top_players_p{$page}";
        $result = \Yii::$app->cache->get($cache_key);
        if(empty($result))
        {
            $players = Player::find()
                ->select(['id','nickname','games','score','intro',])
                ->where('games>0')
                ->limit($per_page + 1)
                ->offset($per_page * ($page - 1))
                ->orderBy('score desc')
                ->asArray()
                ->all();
            $has_next = count($players) > $per_page ;
            if($has_next)
            {
                array_pop($players);
            }
            $result = [
                'players' => $players,
                'has_next' => $has_next
            ];
            \Yii::$app->cache->set($cache_key,$result,60);
        }
        return $this->renderJSON($result);
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
}
