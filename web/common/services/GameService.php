<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/12/2017
 * Time: 15:55
 */

namespace common\services;


use common\components\BoardTool;
use common\components\Gateway;
use common\components\MsgHelper;
use common\models\Games;

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
        //是否在等待打点数输入，默认0
        $waiting_for_a5_number = 0;
        //turn
        $turn = 0;
        //分不同规则来算turn，差别较大的规则不共用逻辑，否则会把手数和规则掺合起来，导致混乱
        //分开之后便于增加新规则。
        $stones = strlen($game->game_record)/2;
        switch ($game->rule)
        {
            case 'RIF':
            case 'Yamaguchi':
                if($stones < 3)
                {
                    $turn = 1;
                }
                elseif ($stones == 3 && $game->a5_numbers == 0 && $game->rule == 'Yamaguchi')
                {
                    $turn = 1;//这里是要写打点而不是要落子。
                    $waiting_for_a5_number = 1;
                }
                elseif($stones == 4 && $game->a5_numbers == (strlen($game->a5_pos)/2))//打点摆完了，等白棋选。
                {
                    $turn = 0;
                }
                else
                {
                    $turn = 1 - ($stones%2);
                }
                break;
            case 'Soosyrv8'://索索夫规则描述 三手可交换，第四手时声明打点数量，可交换。其余略。
                if($stones < 3)
                {
                    $turn = 1;
                }
                elseif($stones == 4)
                {
                    if($game->a5_numbers == 0)
                    {
                        $turn = 0;//这里是要写打点而不是要落子。
                        $waiting_for_a5_number = 1;
                    }
                    elseif ($game->a5_numbers == (strlen($game->a5_pos)/2))
                    {
                        $turn = 0;
                    }
                    else
                    {
                        $turn = 1;//下打点
                    }
                }
                else
                {
                    $turn = 1 - ($stones%2);
                }
                break;
            default:
                $turn = 1 - ($stones%2);
                break;
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
        $return['waiting_for_a5_number'] = $waiting_for_a5_number;
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
        Gateway::sendToGroup('HALL',MsgHelper::build('games',['games' => self::getRecentGameList()]));
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
                    Gateway::sendToGroup($game_id,MsgHelper::build('game_over',[
                        'content' => "黑方超时，对局结束。"
                    ]));
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
                    Gateway::sendToGroup($game_id,MsgHelper::build('game_over',[
                        'content' => "白方超时，对局结束。"
                    ]));
                    $game->refresh();
                }
            }
            $game->updtime = date('Y-m-d H:i:s');
            $game->save(0);
        }
    }

    public static function getRecentGameList()
    {
        $games_list = Games::find()
            ->select(['id','black_id','white_id','rule','game_record','status'])
            ->where(['status' => self::PLAYING])
            ->orWhere(['>=','movetime',date('Y-m-d H:i:s',time() - 600)])
            ->orderBy('status ASC , movetime DESC')
            ->asArray()
            ->all();
        UserService::render($games_list,'black_id','black');
        UserService::render($games_list,'white_id','white');
        return $games_list;
    }
}