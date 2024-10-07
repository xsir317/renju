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
use common\models\GameUndoLog;

class GameService extends BaseService
{

    public static $status_define = [
        0 => 'Not started',
        1 => 'Playing',
        2 => 'Black win',
        4 => 'White win',
        8 => 'Draw'
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
        $can_swap = false;
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
                elseif ($stones == 3 && $game->a5_numbers > 0)
                {
                    //$turn = 0;
                    $can_swap = $game->swap ? 0:1;
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
            case 'TaraGuchi':
                $tara_turns = static::taraguchi_turn(strlen($game->game_record)/2 , $game->swap , $game->a5_pos , $game->a5_numbers);
                $turn = $tara_turns[0];
                $can_swap = $tara_turns[1];
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
        $return['undo'] = null;
        $return['undo_log'] = self::render_undo_log($game_id);
        $return['can_swap'] = $can_swap;

        //附加信息：悔棋
        if($game->status == self::PLAYING)
        {
            $return['undo'] = self::render_undo($game_id,$game->game_record);
        }
        //附加信息：悔棋 end
        return $return;
    }

    /**
     * @param $stones
     * @param $swap
     * @return [ 0 | 1 , bool can swap ]
     */
    public static function taraguchi_turn($stones , $swap , $a5_pos , $a5_number){
        $turn = 1 - ($stones%2);//0 白方 1黑方
        $can_swap = false;//是否能点交换
        if($stones == 0){
            return [1,false];
        }
        //swap 这里用于记录 每一手是否交换 以及是否已经交换了 ，防止换来换去。
        //用低位 1 << $stones表示 是否已经换过了
        if($stones < 4){
            $can_swap = !boolval($swap & (1 << $stones));
        }elseif($stones == 4){
            if($a5_number == 10 && $a5_pos == ''){
                $can_swap = true;
            }
            //选择2： 不交换，摆打点。
        }elseif($stones == 5){
            //判断是不是选择1
            if($a5_number == 1){
                $can_swap = !boolval($swap & (1 << $stones));
            }else{
                $can_swap = false;
            }
        }
        //第五手
        if($stones == 4 && $a5_number == (strlen($a5_pos)/2))//打点摆完了，等白棋选。
        {
            $turn = 0;
        }

        return [$turn , $can_swap];
    }

    private static function render_undo($game_id,$board)
    {
        GameUndoLog::getDb()->createCommand("Update game_undo_log set status=-1 where game_id={$game_id} and current_board<>'{$board}' and status=0")->execute();
        $last_undo = GameUndoLog::find()
            ->where(['game_id' => $game_id,'status' => 0])
            ->orderBy('id desc')
            ->limit(1)
            ->one();
        if($last_undo)
        {
            return [
                'id' => $last_undo->id,
                'uid' => $last_undo->uid,
                'game_id' => $last_undo->game_id,
                'to_step' => $last_undo->to_number,
                'comment' => $last_undo->comment,
            ];
        }
        return null;
    }

    private static function render_undo_log($game_id)
    {
        $logs = \Yii::$app->cache->get('undo_log_cache::'.$game_id);
        if(!$logs)
        {
            $logs = GameUndoLog::find()
                ->select(['id','uid','current_board','to_number',])
                ->where(['status' => 1,'game_id' => $game_id])
                ->orderBy('id desc')
                ->limit(20)
                ->asArray()
                ->all();
            UserService::render($logs,'uid');
            \Yii::$app->cache->set('undo_log_cache::'.$game_id,$logs,60);
        }
        return $logs;
    }

    public static function newToken($user_id)
    {
        $return = [];
        $return['token'] = \Yii::$app->security->generateRandomString();
        $return['secret'] = \Yii::$app->security->generateRandomString();
        \Yii::$app->redis->setEx($return['token'],600,json_encode([
            'secret' => $return['secret'],
            'uid' => $user_id
        ]));
        \Yii::$app->session['chat_token'] = $return['token'];
        return $return;
    }

    public static function sendGamesList()
    {
        //加一个时间锁
        $time = time();
        $redis = \Yii::$app->redis;
        $last_send = intval($redis->get('glb:game_list:ts'));
        if($last_send != $time)
        {
            $redis->set('glb:game_list:ts',$time,10);
            Gateway::sendToGroup('HALL',MsgHelper::build('games',['games' => self::getRecentGameList()]));
        }
    }

    private static function refresh_time($game_id,$turn)
    {
        $game = Games::findOne($game_id);
        //刷时间。
        if($game->status == GameService::PLAYING)
        {
            $lastupd = strtotime($game->updtime);
            $delta = time() - $lastupd;
            if($delta <= 0)
            {
                return false;
            }
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
            ->select(['id','black_id','white_id','rule','game_record','is_private','status'])
            ->where(['status' => self::PLAYING])
            ->orWhere(['>=','movetime',date('Y-m-d H:i:s',time() - 600)])
            //->orderBy('status ASC , movetime DESC') 2020-11-08修改  改为按id排序， 尽量让这个列表稳定
            ->orderBy('status ASC , id DESC')
            ->asArray()
            ->all();
        if(!empty($games_list)){
            UserService::render($games_list,'black_id','black');
            UserService::render($games_list,'white_id','white');
        }
        return $games_list;
    }
}