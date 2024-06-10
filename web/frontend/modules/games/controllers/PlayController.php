<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/12/11
 * Time: 18:59
 */

namespace frontend\modules\games\controllers;


use common\components\BoardTool;
use common\components\ForbiddenPointFinder;
use common\components\Gateway;
use common\components\MsgHelper;
use common\components\RenjuBoardTool_bit;
use common\models\Games;
use common\services\GameService;
use frontend\components\Controller;


/**
 * Class PlayController
 * @package frontend\modules\games\controllers
 * 落子、提和、认输等用户操作的controller。原代码一个controller太大了，拆分一下。
 */
class PlayController extends Controller
{

    /**
     * 输入五手打点数量
     */
    public function actionA5_number()
    {
        $number = abs(intval($this->post('number')));
        $game_id = intval($this->post('game_id'));
        if(!$number)
        {
            return $this->renderJSON([],\Yii::t('app','Please input the correct number'),-1);
        }

        if(!$this->_user())
        {
            return $this->renderJSON([],\Yii::t('app','Please Login'),-1);
        }
        $game_info = GameService::renderGame($game_id);
        if(!$game_info)
        {
            return $this->renderJSON([],\Yii::t('app',"Game doesn't exist"),-1);
        }
        if($game_info['whom_to_play'] != $this->_user()->id)
        {
            return $this->renderJSON([],\Yii::t('app','Not your turn to play'),-1);
        }
        if($game_info['a5_numbers'] > 0)
        {
            return $this->renderJSON([],\Yii::t('app','A5 number already set'),-1);
        }

        $stones = strlen($game_info['game_record'])/2;
        $game_object = Games::findOne($game_id);

        switch ($game_object->rule)
        {
            case 'Yamaguchi':
                if($stones == 3)
                {
                    $game_object->a5_numbers = min($number,12);
                }
                break;
            case 'Soosyrv8':
                if($stones == 4)
                {
                    $game_object->a5_numbers = min($number,8);
                }
                break;
        }
        if($game_object->a5_numbers > 0)
        {
            $game_object->offer_draw = 0;
            $game_object->movetime = date('Y-m-d H:i:s');
            $game_object->save(0);
            if($game_object->step_add_sec)
            {
                $this->add_step_time($game_id,$this->_user()->id);
            }
            Gateway::sendToGroup($game_id,MsgHelper::build('game_info',[
                'game' => GameService::renderGame($game_id)
            ]));
            Gateway::sendToGroup($game_id,MsgHelper::build('notice',[
                'content' => \Yii::t('app','A5 number has been set to ') . $game_object->a5_numbers . ' .'
            ]));
            return $this->renderJSON([]);
        }
        else
        {
            return $this->renderJSON([],'指定打点数量失败',-1);
        }
    }

    //TODO 研究一下怎么引入transaction，棋局的计时、计算输赢需要事务处理，冲突了就跪了
    /**
     * 落子
     */
    public function actionPlay()
    {
        if(!$this->_user())
        {
            return $this->renderJSON([],\Yii::t('app','Please Login'),-1);
        }
        $game_id = intval($this->post("game_id"));
        $coordinate = trim($this->post('coordinate'));
        $game_info = GameService::renderGame($game_id);
        if(!$game_info)
        {
            return $this->renderJSON([],\Yii::t('app',"Game doesn't exist"),-1);
        }
        if($game_info['whom_to_play'] != $this->_user()->id)
        {
            return $this->renderJSON([],\Yii::t('app','Not your turn to play'),-1);
        }
        $stones = strlen($game_info['game_record'])/2;
        //特殊情况判断：如果是轮到输入打点的，提示玩家输入打点数目，而不是落子。在这时走进此逻辑的都不予执行，做个提示。
        if($game_info['a5_numbers'] == 0 && (($game_info['rule'] == 'Yamaguchi' && $stones == 3) || ($game_info['rule'] == 'Soosyrv8' && $stones == 4)))
        {
            return $this->renderJSON([],\Yii::t('app','Please input the A5 number'),-1);
        }
        //到这里，可以落子了。
        //是不是在下打点？
        $game_object = Games::findOne($game_id);
        //提和id设置为0
        if($game_info['free_opening'] == 0)
        {
            if(($stones == 0 && $coordinate != '88') || ($stones == 1 && (!in_array($coordinate{0},[7,8,9]) || !in_array($coordinate{1},[7,8,9])) ) || ($stones == 2 && (!in_array($coordinate{0},[6,7,8,9,'a']) || !in_array($coordinate{1},[6,7,8,9,'a']))) )
            {
                return $this->renderJSON([],\Yii::t('app','This is not a standard opening'),-1);
            }
        }
        $game_object->offer_draw = 0;
        if($stones == 4 && in_array($game_object->rule,['Yamaguchi','RIF','Soosyrv8']))
        {
            //第五手，有2种情况，都很特殊
            $a5_on_board = strlen($game_object->a5_pos)/2;
            if($a5_on_board < $game_object->a5_numbers)
            {
                //落子进a5_pos
                if(!BoardTool::board_correct($game_object->game_record . $game_object->a5_pos . $coordinate))
                {
                    return $this->renderJSON([],\Yii::t('app','Data error,please refresh and try again'),-1);
                }
                if(BoardTool::a5_symmetry($game_object->game_record,$game_object->a5_pos . $coordinate))
                {
                    return $this->renderJSON([],\Yii::t('app','A5 points can not be symmetry'),-1);
                }
                $game_object->a5_pos = $game_object->a5_pos . $coordinate;
                $game_object->movetime = date('Y-m-d H:i:s');
                $game_object->save(0);
            }
            else
            {
                //这是在选黑5，只能是在a5_pos范围内选点。
                if(!in_array($coordinate, str_split($game_object->a5_pos,2)))
                {
                    return $this->renderJSON([],\Yii::t('app','Please choose from Black A5 points'),-1);
                }
                $game_object->game_record = $game_object->game_record . $coordinate;
                $game_object->movetime = date('Y-m-d H:i:s');
                $game_object->save(0);
            }
        }
        else
        {
            if(!BoardTool::board_correct($game_object->game_record . $coordinate))
            {
                return $this->renderJSON([],\Yii::t('app','Data error,please refresh and try again'),-1);
            }
            $old_board = $game_object->game_record;

            $game_object->game_record = $game_object->game_record . $coordinate;
            $game_object->movetime = date('Y-m-d H:i:s');
            $game_object->save(0);


            $checkwin = new RenjuBoardTool_bit($old_board);
            $color = (strlen($old_board) % 4 == 0) ? 'black':'white';
            $result = ($game_object->rule == 'Gomoku') ? $checkwin->gomokuCheckWin($coordinate,$color) : $checkwin->checkWin($coordinate,$color);
            if($result === RenjuBoardTool_bit::BLACK_FIVE)
            {
                //黑胜
                BoardTool::do_over($game_id,1,false);
                Gateway::sendToGroup($game_id,MsgHelper::build('game_over',[
                    'content' => \Yii::t('app','Black wins')
                ]));
            }
            elseif($stones == 224)//bugfix 这里stones 是盘面已有的棋子
            {
                //和棋
                BoardTool::do_over($game_id,0.5,false);
                Gateway::sendToGroup($game_id,MsgHelper::build('game_over',[
                    'content' => \Yii::t('app','Board is full, Draw')
                ]));
            }
            elseif($result == RenjuBoardTool_bit::BLACK_FORBIDDEN || $result == RenjuBoardTool_bit::WHITE_FIVE)
            {
                //白胜
                BoardTool::do_over($game_id,0,false);
                Gateway::sendToGroup($game_id,MsgHelper::build('game_over',[
                    'content' => ($result == RenjuBoardTool_bit::WHITE_FIVE ? \Yii::t('app','Five') : \Yii::t('app','Black forbidden move')) . " ," . \Yii::t('app','White wins')
                ]));
            }
        }
        //是否要加时间
        if($game_object->step_add_sec)
        {
            $this->add_step_time($game_id,$this->_user()->id);
        }
        Gateway::sendToGroup($game_id,MsgHelper::build('game_info',[
            'game' => GameService::renderGame($game_id)
        ]));
        GameService::sendGamesList();

        return $this->renderJSON([]);
    }

    /**
     * 交换
     */
    public function actionSwap()
    {
        $game_id = intval($this->post('game_id'));

        if(!$this->_user())
        {
            return $this->renderJSON([],\Yii::t('app','Please Login'),-1);
        }
        $game_info = GameService::renderGame($game_id);
        if(!$game_info)
        {
            return $this->renderJSON([],\Yii::t('app',"Game doesn't exist"),-1);
        }
        if($game_info['whom_to_play'] != $this->_user()->id)
        {
            return $this->renderJSON([],\Yii::t('app','Not your turn to play'),-1);
        }

        $stones = strlen($game_info['game_record'])/2;
        $allow_swap = false;
        $game_object = Games::findOne($game_id);
        switch ($game_object->rule)
        {
            case 'RIF':
            case 'Yamaguchi':
                if($stones == 3 && $game_object->a5_numbers > 0 && $game_object->swap == 0)
                {
                    $allow_swap = true;
                }
                break;
            case 'Soosyrv8':
                if($stones == 3 && $game_object->swap == 0)
                {
                    $allow_swap = true;
                }
                elseif ($stones == 4 && $game_object->a5_numbers > 0 && $game_object->a5_pos == '' && $game_object->soosyrv_swap == 0)
                {
                    $allow_swap = true;
                }
                break;
            case 'TaraGuchi':
                $tara_turns = GameService::taraguchi_turn($game_object->game_record , $game_object->swap , $game_object->a5_pos , $game_object->a5_numbers);
                $allow_swap = $tara_turns[1];
        }
        if($allow_swap)
        {
            $game_object->offer_draw = 0;
            $game_object->black_id = $game_info['white_id'];
            $game_object->white_id = $game_info['black_id'];
            $game_object->black_time = $game_info['white_time'];
            $game_object->white_time = $game_info['black_time'];
            if($game_object->rule == 'TaraGuchi'){
                //标记swap
                $game_object->swap = ($game_object->swap | (1 << $stones));
            }else{
                if($stones == 3)
                {
                    $game_object->swap = 1;
                }
                else
                {
                    $game_object->soosyrv_swap = 1;
                }
            }
            $game_object->movetime = date('Y-m-d H:i:s');
            $game_object->save(0);

            if($game_object->step_add_sec)
            {
                $this->add_step_time($game_id,$this->_user()->id);
            }
            Gateway::sendToGroup($game_id,MsgHelper::build('game_info',[
                'game' => GameService::renderGame($game_id)
            ]));
            Gateway::sendToGroup($game_id,MsgHelper::build('notice',[
                'content' => \Yii::t('app','Black and white has swapped.')
            ]));
            return $this->renderJSON([]);
        }
        else
        {
            return $this->renderJSON([],'当前不允许交换',-1);
        }
    }

    /**
     * 提和、同意
     */
    public function actionOffer_draw()
    {
        $game_id = intval($this->post('game_id'));

        if(!$this->_user())
        {
            return $this->renderJSON([],\Yii::t('app','Please Login'),-1);
        }
        $game_info = GameService::renderGame($game_id);
        if(!$game_info)
        {
            return $this->renderJSON([],\Yii::t('app',"Game doesn't exist"),-1);
        }
        if($game_info['black_id'] != $this->_user()->id && $game_info['white_id'] != $this->_user()->id)
        {
            return $this->renderJSON([],\Yii::t('app',"This is not your game"),-1);
        }
        $opponent_id = $this->_user()->id == $game_info['black_id'] ? $game_info['white_id'] : $game_info['black_id'];

        if($game_info['status'] != GameService::PLAYING)
        {
            return $this->renderJSON([],\Yii::t('app',"This game is currently not playing"),-1);
        }

        $game_object = Games::findOne($game_id);
        if($game_object->offer_draw == 0)
        {
            $game_object->offer_draw = $this->_user()->id;
            $game_object->movetime = date('Y-m-d H:i:s');
            $game_object->save(0);
            Gateway::sendToGroup($game_id,MsgHelper::build('game_info',[
                'game' => GameService::renderGame($game_id)
            ]));
            Gateway::sendToGroup($game_id,MsgHelper::build('notice',[
                'content' => $this->_user()->nickname. \Yii::t('app',"Offers draw")
            ]));
            return $this->renderJSON([]);
        }
        elseif ($game_object->offer_draw == $this->_user()->id)
        {
            return $this->renderJSON([],\Yii::t('app',"You already offered draw, please wait."),-1);
        }
        elseif ($game_object->offer_draw == $opponent_id)
        {
            BoardTool::do_over($game_id,0.5);
            Gateway::sendToGroup($game_id,MsgHelper::build('game_over',[
                'content' => $this->_user()->nickname. \Yii::t('app'," Accepts the offer, it's a draw.")
            ]));
            return $this->renderJSON([]);
        }
        else
        {
            return $this->renderJSON([],\Yii::t('app',"System error, please contact the administrator."),-1);
        }
    }

    /**
     * 认输
     */
    public function actionResign()
    {
        $game_id = intval($this->post('game_id'));

        if(!$this->_user())
        {
            return $this->renderJSON([],\Yii::t('app','Please Login'),-1);
        }
        $game_info = GameService::renderGame($game_id);
        if(!$game_info)
        {
            return $this->renderJSON([],\Yii::t('app',"Game doesn't exist"),-1);
        }
        if($game_info['black_id'] != $this->_user()->id && $game_info['white_id'] != $this->_user()->id)
        {
            return $this->renderJSON([],\Yii::t('app',"This is not your game"),-1);
        }

        if($game_info['status'] != GameService::PLAYING)
        {
            return $this->renderJSON([],\Yii::t('app',"This game is currently not playing"),-1);
        }

        $game_result = $this->_user()->id == $game_info['black_id'] ? 0 : 1 ;//黑认输则白胜
        BoardTool::do_over($game_id,$game_result);
        Gateway::sendToGroup($game_id,MsgHelper::build('game_over',[
            'content' => ($game_result ? \Yii::t('app',"White resigns") : \Yii::t('app',"Black resigns"))
        ]));
        return $this->renderJSON([]);
    }

    private function add_step_time($game_id,$uid)
    {
        $game_object = Games::findOne($game_id);
        if(!$game_object || !$game_object->step_add_sec)
        {
            return false;
        }
        //是否要加时间
        $game_info_refresh = GameService::renderGame($game_id);
        //正常下棋，我落子之后，轮到对方走了，就给我的时间加步时.
        if($uid != $game_info_refresh['whom_to_play'])
        {
            if($uid == $game_object->black_id)
            {
                $game_object->black_time += $game_object->step_add_sec;
            }
            else
            {
                $game_object->white_time += $game_object->step_add_sec;
            }
            $game_object->save(0);
        }
    }
}