<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/12/3
 * Time: 17:33
 */

namespace frontend\controllers;


use frontend\components\Controller;
use common\models\Player;
use common\services\CommonService;
use common\services\QueueService;

class UserController extends Controller
{
    public function actionLogin()
    {
        $req = \Yii::$app->request;
        if($req->isPost)
        {
            if(!$this->check_abuse())
            {
                return $this->renderJSON([],\Yii::t('app','You operate too fast,please try again later'),-1);
            }
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
                return $this->renderJSON([],\Yii::t('app','Your password is incorrect'),-1);
            }
        }
        return $this->renderJSON([],'请求方式错误',-1);
    }

    public function actionReg()
    {
        $req = \Yii::$app->request;
        if($req->isPost)
        {
            if(!$this->check_abuse())
            {
                return $this->renderJSON([],\Yii::t('app','You operate too fast,please try again later'),-1);
            }
            $email = trim($this->post('email'));
            $password = trim($this->post('passwd'));
            $nickname = strip_tags(trim($this->post('nickname')));
            $user = Player::findOne(['email' => $email]);
            //验证密码
            if($user)
            {
                return $this->renderJSON([],\Yii::t('app','This email is already registered'),-1);
            }
            if(!$email || !$password || !$nickname)
            {
                return $this->renderJSON([],\Yii::t('app','Please complete the form'),-1);
            }
            if(mb_strlen($nickname) >= 10)
            {
                return $this->renderJSON([],\Yii::t('app','Nickname shall be no more than 10 letters.'),-1);
            }
            if(!preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,6}$/i',$email))
            {
                return $this->renderJSON([],\Yii::t('app','Email format incorrect'),-1);
            }

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
        return $this->renderJSON([],'请求方式错误',-1);
    }

    public function actionEdit()
    {
        if(!$this->_user())
        {
            return $this->renderJSON([],\Yii::t('app',"Please Login"),-1);
        }

        $intro = strip_tags(trim($this->post('intro')));
        $game_id = intval($this->post('game_id'));//TODO  目前game_id是 所在游戏房间id，没有的话就是大厅HALL 这个逻辑可能会动，或者以更明确的方式固定下来
        $game_id = $game_id ? : 'HALL';

        $this->_user()->intro = $intro;
        $this->_user()->save(0);

        QueueService::insert('client_list',['game_id' => $game_id]);
        return $this->renderJSON();
    }

    private function check_abuse()
    {
        $time = time();
        $ip = CommonService::getIP();
        $hash_key = sprintf("abuse_%u",ip2long($ip));
        $curr_min = date('mdHi',$time);
        \Yii::$app->redis->hIncrBy($hash_key,$curr_min,1);

        $last_ten_min = [];
        for($ts = $time - 600; $ts <= $time ; $ts += 60 )
        {
            $last_ten_min[] = date('mdHi',$ts);
        }
        $record_times = \Yii::$app->redis->hMGet($hash_key,$last_ten_min);
        //插入一个逻辑，如果长度已经超过了60，清一下。。。
        if(\Yii::$app->redis->hLen($hash_key) > 60)
        {
            \Yii::$app->redis->delete($hash_key);
            \Yii::$app->redis->hMSet($hash_key,$record_times);
        }
        //长度处理结束
        foreach ($last_ten_min as $_min)
        {
            $record_times[$_min] = isset($record_times[$_min]) ? intval($record_times[$_min]) : 0;
        }
        krsort($record_times);
        if($record_times[$curr_min] >= 5)
        {
            return false;
        }
        if(array_sum(array_slice($record_times,0,3)) >= 12)
        {
            return false;
        }
        if(array_sum($record_times) >= 30)
        {
            return false;
        }
        return true;
    }
}