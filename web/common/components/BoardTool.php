<?php
namespace common\components;

use common\models\Games;
use common\models\Player;
use common\models\ScoreLog;
use common\services\GameService;

class BoardTool
{

	/**
	 * 
	 * 检查棋盘字符串是否合法。
	 * @param string $boardstr
	 * @return boolean
	 */
	public static $openings = array(
		'887868' => '寒星',
		'887869' => '溪月',
		'88786a' => '疏星',
		'887879' => '花月',
		'88787a' => '残月',
		'887889' => '雨月',
		'88788a' => '金星',
		'887898' => '松月',
		'887899' => '丘月',
		'88789a' => '新月',
		'8878a8' => '瑞星',
		'8878a9' => '山月',
		'8878aa' => '游星',
		'88796a' => '长星',
		'88797a' => '峡月',
		'88798a' => '恒星',
		'88799a' => '水月',
		'8879aa' => '流星',
		'887989' => '云月',
		'887999' => '浦月',
		'8879a9' => '岚月',
		'887998' => '银月',
		'8879a8' => '明星',
		'887997' => '斜月',
		'8879a7' => '名月',
		'8879a6' => '彗星',
		);
	public static function board_correct($boardstr)
	{
		$count = strlen($boardstr);
		if($count%2 != 0)
			return false;
		$arr_pos = str_split($boardstr,2);
		$arr_pos = array_unique($arr_pos);
		if(count($arr_pos) != $count/2)
			return false;
		for ($i = 0;$i<$count;$i ++)
		{
			$pos = hexdec($boardstr{$i});
			if($pos >15 || $pos <1)
			return false;
		}
		return true;
	}
	

	/**
	 * 
	 * 如果有对称打点，返回true，否则返回false
	 * @param string $board
	 * @param string $a5
     * @return bool 是否有对称
	 */
	public static function a5_symmetry($board,$a5)
	{
		if(strlen($a5) < 4)
			return false;
		$b = array(substr($board,0,2),substr($board,4,2));//黑棋1，3
		$w = array(substr($board,2,2),substr($board,6,2));//白棋2，4
		$a5_pos = str_split($a5,2);
		//计算1，2，3，4的形状，看是不是对称
		//水平翻转  如果是，则依次计算每个a5的对称点。如果对称点是本身，跳过；如果不是本身，则看是否在$a5_pos里面。如果在，返回true
		$black_and_white_x_y_symmetry = ($b[0]{1} == $b[1]{1} || $b[0]{0} == $b[1]{0}) && ($w[0]{1} == $w[1]{1} || $w[0]{0} == $w[1]{0});
		$x_symmetry = ($black_and_white_x_y_symmetry && (hexdec($b[0]{1}) + hexdec($b[1]{1})) == (hexdec($w[0]{1}) + hexdec($w[1]{1})));
		$in_x_line = ($b[0]{1} == $b[1]{1} && $b[0]{1} == $w[0]{1} && $b[0]{1} == $w[1]{1});
		if($x_symmetry || $in_x_line)
		{
			$y_sum = hexdec($b[0]{1}) + hexdec($b[1]{1});
			//对每个打点，找对称点。
			foreach ($a5_pos as $point)
			{
				$symmetry_x = $point{0};
				$symmetry_y = $y_sum - hexdec($point{1});
				$symmetry = dechex($symmetry_x).dechex($symmetry_y);
				if($symmetry != $point && in_array($symmetry, $a5_pos))
				return true;
			}
		}
		//竖直翻转
		$y_symmetry = ($black_and_white_x_y_symmetry && (hexdec($b[0]{0}) + hexdec($b[1]{0})) == (hexdec($w[0]{0}) + hexdec($w[1]{0})));
		$in_y_line = ($b[0]{0} == $b[1]{0} && $b[0]{0} == $w[0]{1} && $b[0]{1} == $w[1]{0});
		if($y_symmetry || $in_y_line)
		{
			$x_sum = hexdec($b[0]{0}) + hexdec($b[1]{0});
			foreach ($a5_pos as $point)
			{
				$symmetry_x = $x_sum - hexdec($point{0});
				$symmetry_y = $point{1};
				$symmetry = dechex($symmetry_x).dechex($symmetry_y);
				if($symmetry != $point && in_array($symmetry, $a5_pos))
				return true;
			}
		}
		//水平+竖直翻转，坐标特征：黑棋的x坐标和等于白棋x坐标和。黑棋y坐标和等于白棋y坐标和
		$x_distance_semmetry = (hexdec($b[0]{0}) + hexdec($b[1]{0})) == (hexdec($w[0]{0}) + hexdec($w[1]{0}));
		$y_distance_semmetry = (hexdec($b[0]{1}) + hexdec($b[1]{1})) == (hexdec($w[0]{1}) + hexdec($w[1]{1}));
		if($x_distance_semmetry && $y_distance_semmetry)
		{
			$x_sum = hexdec($b[0]{0}) + hexdec($b[1]{0});
			$y_sum = hexdec($b[0]{1}) + hexdec($b[1]{1});
			foreach ($a5_pos as $point)
			{
				$symmetry_x = $x_sum - hexdec($point{0});
				$symmetry_y = $y_sum - hexdec($point{1});
				$symmetry = dechex($symmetry_x).dechex($symmetry_y);
				if($symmetry != $point && in_array($symmetry, $a5_pos))
				return true;
			}
		}
		//斜线翻转
		//反斜线翻转
		$black_diagonal = (hexdec($b[0]{0}) - hexdec($b[0]{1}) == hexdec($b[1]{0}) - hexdec($b[1]{1}));
		$black_rev_diagonal = (hexdec($b[0]{0}) + hexdec($b[0]{1}) == hexdec($b[1]{0}) + hexdec($b[1]{1}));
		$white_diagonal = (hexdec($w[0]{0}) - hexdec($w[0]{1}) == hexdec($w[1]{0}) - hexdec($w[1]{1}));
		$white_rev_diagonal = (hexdec($w[0]{0}) + hexdec($w[0]{1}) == hexdec($w[1]{0}) + hexdec($w[1]{1}));
		if(($black_diagonal || $black_rev_diagonal) && ($white_diagonal || $white_rev_diagonal))
		{
			if($black_diagonal)
			{
				$black_delta = hexdec($b[0]{0}) - hexdec($b[0]{1});
				$black_sum = (hexdec($b[0]{0}) + hexdec($b[0]{1}) + hexdec($b[1]{0}) + hexdec($b[1]{1})) / 2;
			}
			else 
			{
				$black_delta = (hexdec($b[0]{0}) + hexdec($b[1]{0}) - hexdec($b[0]{1}) - hexdec($b[1]{1}))/2;
				$black_sum = hexdec($b[0]{0}) + hexdec($b[0]{1});
			}
			
			if($white_diagonal)
			{
				$white_delta = hexdec($w[0]{0}) - hexdec($w[0]{1});
				$white_sum = (hexdec($w[0]{0}) + hexdec($w[0]{1}) + hexdec($w[1]{0}) + hexdec($w[1]{1})) / 2;
			}
			else 
			{
				$white_delta = (hexdec($w[0]{0}) + hexdec($w[1]{0}) - hexdec($w[0]{1}) - hexdec($w[1]{1}))/2;
				$white_sum = hexdec($w[0]{0}) + hexdec($w[0]{1});
			}
			
			
			if($black_delta == $white_delta)//斜线对称
			{
				foreach ($a5_pos as $point)
				{
					$symmetry_x = $black_delta + hexdec($point{1});
					$symmetry_y = hexdec($point{0}) - $black_delta;
					$symmetry = dechex($symmetry_x).dechex($symmetry_y);
					if($symmetry != $point && in_array($symmetry, $a5_pos))
					return true;
				}
			}
			if($black_sum == $white_sum)//反斜线对称
			{
				foreach ($a5_pos as $point)
				{
					$symmetry_x = $black_sum - hexdec($point{1});
					$symmetry_y = $black_sum - hexdec($point{0});
					$symmetry = dechex($symmetry_x).dechex($symmetry_y);
					if($symmetry != $point && in_array($symmetry, $a5_pos))
					return true;
				}
			}
		}
		
		return false;
	}

	/**
	 * 
	 * 结束棋局，设置棋局状态，结算分数。
	 * @param int $game_id
	 * @param int $result  1黑胜  0.5和棋   0 白胜
     * @param boolean $do_send_msg 是否发送Push消息。默认发送。
     * @return boolean
	 */
	public static function do_over($game_id,$result,$do_send_msg = true)
	{
        $game = Games::findOne($game_id);
		if (!$game || $game->status != GameService::PLAYING)
		{
			return false;
		}
		$moves = strlen($game->game_record)/2;
		$black_player = Player::findOne($game->black_id);
		$white_player = Player::findOne($game->white_id);
		switch($result)
		{
			case 1:
                $black_player->b_win ++;
                $white_player->w_lose ++;
				$game->status = GameService::BLACK_WIN;
				break;
			case 0:
                $black_player->b_lose ++;
                $white_player->w_win ++;
				$game->status = GameService::WHITE_WIN;
				break;
			case 0.5:
                $black_player->draw ++;
                $white_player->draw ++;
				$game->status = GameService::GAME_DRAW;
				break;
		}
        $black_player->games ++;
        $white_player->games ++;
		$game->movetime = date('Y-m-d H:i:s');
		$game->save();
		if($do_send_msg)
        {
            Gateway::sendToGroup($game_id,MsgHelper::build('game_info',[
                'game' => GameService::renderGame($game_id)
            ]));
            GameService::sendGamesList();
        }
		//对局量前50局,K值取30,然后,一般取20-15之间,等级分达到2400后K值取10 TODO
		//惩罚性规则：如果手数小于16，则变动系数K降为5,如果手数小于5，则降为1。
		$k_black = 30;
		$k_white = 30;
		if($black_player->games >=50)
		{
			$k_black = 16; 
		}
		if($white_player->games >=50)
		{
			$k_white = 16; 
		}
		if($black_player->score >=2400)
		{
			$k_black = 10; 
		}
		if($white_player->score >=2400)
		{
			$k_white = 10; 
		}
		if($moves < 5)
		{
			$k_black = 1;
			$k_white = 1;
		}
		$before_black_score = $black_player->score;
		$before_white_score = $white_player->score;
		$we = 1/(1+pow(10,(($before_white_score - $before_black_score)/400)));
		$delta_black = $k_black * ($result - $we);
		/*记录积分变动log*/
		$log = new ScoreLog();
		$log->game_id = $game->id;
		$log->player_id = $game->black_id;
		$log->op_id = $game->white_id;
		$log->before_score = $before_black_score;
		$log->op_score = $before_white_score;
		$log->k_val = $k_black;
		$log->delta_score = $delta_black;
		$log->after_score = floatval($before_black_score) + $delta_black;
		$log->save();
		/*记录积分变动log end */
		$black_player->score = $log->after_score;//$black_player->score + $delta_black;
		$black_player->save();
		$delta_white = -1 * $k_white * ($result - $we);
		/*记录积分变动log*/
		$log = new ScoreLog();
		$log->game_id = $game->id;
		$log->player_id = $game->white_id;
		$log->op_id = $game->black_id;
		$log->before_score = $before_white_score;
		$log->op_score = $before_black_score;
		$log->k_val = $k_white;
		$log->delta_score = $delta_white;
		$log->after_score = floatval($before_white_score) + $delta_white;
		$log->save();
		/*记录积分变动log end */
		$white_player->score = $log->after_score;//$white_player->score + $delta_white;
		$white_player->save();
		//如果是比赛，调用一下比赛结束
		//if($game->tid)
		//{
		//	TournamentTool::end_tournament($game->tid);
		//}
		return true;
	}

	public static function opening_name($boardstr)
	{
		if(strlen($boardstr) < 6)
			return '未知';
		$opening_str = strtolower(substr($boardstr, 0,6));
		$stones = str_split($opening_str,2);
		if($stones[0] != '88')
		{
			//平移
			$delta_row = 8 - hexdec($stones[0]{0});
			$delta_col = 8 - hexdec($stones[0]{1});
			foreach ($stones as $key => $value) {
				$stones[$key]{0} = dechex(hexdec($stones[$key]{0}) + $delta_row);
				$stones[$key]{1} = dechex(hexdec($stones[$key]{1}) + $delta_col);
			}
		}
		//print_r($stones);
		if(!in_array($stones[1]{0}, array('7','8','9')) || !in_array($stones[1]{1}, array('7','8','9')) || !in_array($stones[2]{0}, array('6','7','8','9','a')) || !in_array($stones[2]{1}, array('6','7','8','9','a')))
			return '未知';
		if(hexdec($stones[1]{0}) > 8)
		{
			$stones[1]{0} = dechex(16 - hexdec($stones[1]{0}));
			$stones[2]{0} = dechex(16 - hexdec($stones[2]{0}));
		}
		if(hexdec($stones[1][1]) < 8)
		{
			$stones[1]{1} = dechex(16 - hexdec($stones[1]{1}));
			$stones[2]{1} = dechex(16 - hexdec($stones[2]{1}));
		}
		if($stones[1] == '89')
		{
			$stones[1] = '78';
			$b_3_row = $stones[2]{0};
			$stones[2]{0} = dechex(16 - hexdec($stones[2]{1}));
			$stones[2]{1} = dechex(16 - hexdec($b_3_row));
		}
		if($stones[1] == '78' && hexdec($stones[2]{1}) < 8)
		{
			$stones[2]{1} = dechex(16 - hexdec($stones[2]{1}));
		}
		else if($stones[1] == '79' && (hexdec($stones[2]{0})+hexdec($stones[2]{1}) < 17))
		{
			$b_3_row = $stones[2]{0};
			$stones[2]{0} = dechex(16 - hexdec($stones[2]{1}));
			$stones[2]{1} = dechex(16 - hexdec($b_3_row));
		}
		$formatted_open = implode('', $stones);
		return self::$openings[$formatted_open];
	}
}