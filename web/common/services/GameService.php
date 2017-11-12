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
        /*
         * id
            black_id
            white_id
            status
            offer_draw
            rule
            free_opening
            game_record
            black_time
            white_time
            totaltime
            swap
            a5_pos
            a5_numbers
            updtime
            movetime
            comment
            tid
            create_time*/
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

    private static function renderUser($uid)
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
}