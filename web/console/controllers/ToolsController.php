<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/12/9
 * Time: 18:14
 */

namespace console\controllers;


use common\components\BoardTool;
use common\components\GameStatistics;
use common\models\GameRecords;
use yii\console\Controller;

class ToolsController extends Controller
{
    public function actionCombine_js()
    {
        $files = ['page','board','swfobject','web_socket','md5.min','websocket'];
        $folder = dirname(\Yii::$app->getBasePath()).'/frontend/web/js/';
        file_put_contents($folder.'all.js',"/*".date('Y-m-d H:i:s')."*/\n");
        foreach ($files as $_f)
        {
            $file_name = $folder.$_f.'.js';
            $tmp = file_get_contents($file_name);
            file_put_contents($folder.'all.js',"/* {$_f}.js */\n\n {$tmp}\n",FILE_APPEND);
        }
    }

    public function actionRead_rif()
    {
        $source_file = 'E:\\downloads\\renjunet_v10_20190301.rif';
        $obj = simplexml_load_file($source_file);
        $rules = [];
        foreach ($obj->rules->children() as $r)
        {
            $rules[intval($r['id'])] = strval($r['name']);
        }
        $players = [];

        foreach ($obj->players->children() as $p)
        {
            $players[intval($p['id'])] = strval($p['name']) . ' ' .strval($p['surname']);
        }
        //上次写到哪儿了
        $max_recorded = GameRecords::getDb()
            ->createCommand("select max(rel_id) from game_records where data_from='renju.net'")
            ->queryScalar();
        $max_recorded = intval($max_recorded);


        $result_map = [
            '1' => 2,
            '0.5' => 1,
            '0' => 0,
        ];
        foreach ($obj->games->children() as $g)
        {
            $move = strval($g->move);
            $result = 0;
            if(isset($result_map[strval($g['bresult'])]))
            {
                $result = $result_map[strval($g['bresult'])];
            }
            //black_player white_player rule source origin_game
            $board_str = $this->rif_record_convert($move);
            if(!BoardTool::board_correct($board_str))
            {
                continue;
            }
            $rel_id = intval($g['id']);
            if($rel_id <= $max_recorded)
            {
                continue;
            }
            $extra = [
                'black_player' => isset($players[strval($g['black'])]) ? $players[strval($g['black'])] : '',
                'white_player' => isset($players[strval($g['white'])]) ? $players[strval($g['white'])] : '',
                'rule' => isset($rules[strval($g['rule'])]) ? $rules[strval($g['rule'])] : '',
                'source' => 'renju.net',
                'rel_id' => $rel_id,
                'origin_game' => $move,
            ];
            echo $rel_id,"\t{$board_str} \n";
            GameStatistics::do_record($board_str,$result,$extra);
            if(file_exists(\Yii::$app->getRuntimePath().'/rif.stop'))
            {
                break;
            }
        }
    }

    public function actionRead_offline()
    {
        $source_file = 'E:\\downloads\\games.xml';
        $obj = simplexml_load_file($source_file);

        foreach ($obj->games->children() as $g)
        {
            $move = strval($g->board);
            $winner = strval($g->winner);
            $result = 1;

            $result_map = [
                'black' => 2,
                '' => 1,
                'unknown' => 1,
                'white' => 0,
            ];
            if(isset($result_map[$winner]))
            {
                $result = $result_map[$winner];
            }
            $board_str = $this->rif_record_convert($move);//这俩一样啊

            if(strlen($board_str) <= 20) //10手棋，以及15手以内和棋都没啥用。
            {
                continue;
            }
            if(strlen($board_str) <= 30 && $result == 1)
            {
                continue;
            }
            if(!BoardTool::board_correct($board_str))
            {
                continue;
            }

            $game_time = date('Y-m-d H:i:s',intval($g->creation_time));
            $extra = [
                'black_player' => strval($g->black),
                'white_player' => strval($g->white),
                'rule' => strval($g->rule),
                'source' => 'renjuoffline',
                'rel_id' => strval($g->id),
                'game_time' =>$game_time,
                'origin_game' => $move,
            ];
            echo strval($g->id),"\t{$board_str} \n";
            GameStatistics::do_record($board_str,$result,$extra);
            if(file_exists(\Yii::$app->getRuntimePath().'/offline.stop'))
            {
                break;
            }
        }
    }

    private function rif_record_convert($rif_moves)
    {
        $converted  = '';
        $moves = explode(' ',$rif_moves);
        foreach ($moves as $stone)
        {
            $stone = trim($stone);
            if(strlen($stone) < 2)
            {
                continue;
            }
            $converted .= dechex(ord($stone{0}) - ord('a') + 1) . dechex(intval(substr($stone,1)));
        }
        return $converted;
    }
}