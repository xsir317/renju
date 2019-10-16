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
        $source_file = 'D:\\downloads\\renjunet_v10_20191016.rif';
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

        foreach ($obj->games->children() as $g)
        {
            $move = strval($g->move);
            //black_player white_player rule source origin_game
            $board_str = $this->rif_record_convert($move);
            if(!BoardTool::board_correct($board_str))
            {
                continue;
            }
            $extra = [
                'black_player' => isset($players[strval($g['black'])]) ? $players[strval($g['black'])] : '',
                'white_player' => isset($players[strval($g['white'])]) ? $players[strval($g['white'])] : '',
                'rule' => isset($rules[strval($g['rule'])]) ? $rules[strval($g['rule'])] : '',
                'source' => json_encode(['src' => 'rif', 'id' => intval($g['id'])]),
                'origin_game' => $move,
            ];
            echo intval($g['id']),"{$board_str} \n";
            $record_id = GameStatistics::do_record($board_str,intval($g['bresult'] * 2),$extra);
            if($record_id > 10)
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
            $converted .= dechex(ord($stone{0}) - ord('a') + 1) . dechex(intval(substr($stone,1)));
        }
        return $converted;
    }
}