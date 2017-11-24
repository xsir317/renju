<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/12/2017
 * Time: 15:55
 */

namespace common\services;


use common\components\BoardTool;
use common\components\CustomGateway;
use common\components\MsgHelper;
use common\models\Games;
use common\models\Player;

class GameService extends BaseService
{

    public static $status_define = [
        0 => '未开始',
        1 => '进行中',
        2 => '黑胜',
        4 => '白胜',
        8 => '和棋'
    ];
    const NOT_STARTED = 0;
    const PLAYING = 1;
    const BLACK_WIN = 2;
    const WHITE_WIN = 4;
    const GAME_DRAW = 8;
    /**
     * @param int $game_id
     * @return mixed
     */
    public static function renderGame($game_id)
    {
        $game = Games::findOne($game_id);
        if(!$game)
        {
            return null;
        }
        //turn
        $turn = 0;
        $stones = strlen($game->game_record)/2 ;
        if($stones < 3)
        {
            $turn = 1;
        }
        elseif($stones == 4 && $game->a5_numbers == (strlen($game->a5_pos)/2))//打点摆完了
        {
            $turn = 0;
        }
        else
        {
            $turn = 1 - ($stones%2);
        }

        //刷新时间的时候，如果涉及到超时胜负，会发个消息出去。发消息的时候会render。但是不会再次走进refresh的逻辑。
        self::refresh_time($game_id,$turn);
        $game->refresh();

        $whom_to_play = 0;
        if($game->status == GameService::PLAYING)
        {
            $whom_to_play = $turn ? $game->black_id : $game->white_id;
        }
        //do over
        $return  = $game->toArray();
        $return['bplayer'] = UserService::renderUser($game->black_id);
        $return['wplayer'] = UserService::renderUser($game->white_id);
        $return['turn'] = $turn;
        $return['whom_to_play'] = $whom_to_play;
        return $return;
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

    public static function sendGamesList()
    {
        $games_list = Games::find()
            ->select(['id','black_id','white_id','rule','game_record'])
            ->where(['status' => self::PLAYING])
            ->orderBy('movetime desc')
            ->asArray()
            ->all();
        UserService::render($games_list,'black_id','black');
        UserService::render($games_list,'white_id','white');
        CustomGateway::sendToHall(MsgHelper::build('games',['games' => $games_list]));
    }

    private static function refresh_time($game_id,$turn)
    {
        $game = Games::findOne($game_id);
        //刷时间。
        if($game->status == GameService::PLAYING)
        {
            $lastupd = strtotime($game->updtime);
            $delta = time() - $lastupd;
            if($turn)
            {
                $game->black_time -= $delta;
                $game->black_time = ($game->black_time < 0 ? 0 : $game->black_time);
                if($game->black_time == 0)
                {
                    $game->movetime = date('Y-m-d H:i:s');
                    $game->save(0);
                    BoardTool::do_over($game_id,0);
                    $game->refresh();
                }
            }
            else
            {
                $game->white_time -= $delta;
                $game->white_time = ($game->white_time < 0 ? 0 : $game->white_time);
                if($game->white_time == 0)
                {
                    $game->movetime = date('Y-m-d H:i:s');
                    $game->save(0);
                    BoardTool::do_over($game_id,1);
                    $game->refresh();
                }
            }
            $game->updtime = date('Y-m-d H:i:s');
            $game->save(0);
        }
    }
}