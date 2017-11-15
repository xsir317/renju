<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/12/2017
 * Time: 15:55
 */

namespace common\services;


use common\models\Games;
use common\models\Player;

class GameService extends BaseService
{
    /**
     * @param Games $game
     * @return mixed
     */
    public static function renderGame($game)
    {
        $return  = $game->toArray();
        $return['bplayer'] = self::renderUser($game->black_id);
        $return['wplayer'] = self::renderUser($game->white_id);
        //turn
        $stones = strlen($game->game_record)/2 ;
        if($stones < 3)
        {
            $return['turn'] = 1;
        }
        elseif($stones == 4 && $game->a5_numbers == (strlen($game->a5_pos)/2))//打点摆完了
        {
            $return['turn'] = 0;
        }
        else
        {
            $return['turn'] = 1 - ($stones%2);
        }
        return $return;
    }

    public static function renderUser($uid)
    {
        $user = $uid ? Player::findOne($uid) : null;
        return $user ? [
            'id' => $user->id,
            'email' => $user->email,
            'nickname' => $user->nickname,
            'score' => $user->score,
            'intro' => $user->intro,
        ] : null;
    }

    public static function newToken()
    {
        $return = [];
        $return['token'] = \Yii::$app->security->generateRandomString();
        $return['secret'] = \Yii::$app->security->generateRandomString();
        \Yii::$app->redis->setEx($return['token'],600,$return['secret']);
        \Yii::$app->session['chat_token'] = $return['token'];
        return $return;
    }
}