<?php
use common\services\GameService;

$this->title = '对局';
$this->registerJSFile('/js/md5.min.js');
$this->registerJSFile('/js/swfobject.js');
$this->registerJSFile('/js/web_socket.js');
$this->registerJSFile('/js/viewgame.js');
?>
<div class="container">
    <div class="wrapper" style="min-height:366px;overflow:hidden;">
        <div class="content greybox" style="padding:10px;">
            <div style="float:left;margin-right:10px;"><div id="board_main"></div></div>
            <div class="greybox" id="gameinfo">
                <ul>
                    <li class="black_name" style="border-top:none;">
                        <span>黑方:</span><ins></ins>
                    </li>
                    <li class="white_name">
                        <span>白方:</span><ins></ins>
                    </li>
                    <li>
                        <span>开始时间:</span><ins style="width:140px;"><?php echo substr($game['create_time'],0,16)?></ins>
                    </li>
                    <li class="rule_name">
                        <span>规则:</span><ins><?php echo $game['rule']?></ins>
                    </li>
                    <li class="a5_numbers">
                        <span>五手打点数:</span><ins><?php echo $game['a5_numbers']?></ins>
                    </li>
                    <li class="is_swap">
                        <span>交换:</span><ins></ins>
                    </li>
                    <li class="game_result">
                        <span>结果:</span><ins><strong></strong></ins>
                    </li>
                    <li class="current_player_name">
                        <span>轮到:</span><ins></ins>
                    </li>
                    <li class="total_time">
                        <span>对局时限:</span><ins><?php echo $game['totaltime']?>小时</ins>
                    </li>
                    <li>
                        <span>黑方剩余时间:</span><ins style="width:135px;" id="black_time_display">00:00:00</ins>
                    </li>
                    <li>
                        <span>白方剩余时间:</span><ins style="width:135px;" id="white_time_display">00:00:00</ins>
                    </li>
                    <li class="turn_to_play_tips">
                        <span>轮到您下第<?php echo strlen($game['game_record'])/2 +1?>手</span>
                    </li>
                    <li class="draw_button"><span>提和：</span><input type="button" value="和棋" class="button" id="draw_button" /></li>
                    <li class="resign_button"><span>认输：</span><input type="button" value="认输" class="button" id="resign_button" /></li>
                    <li class="offer_draw_tips"><span style="color:#ff3333">您的对手向您提和，如果您同意，请点击和棋按钮。</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var game_id = <?php echo $game['id']?>;
    var gameinit = '<?php echo $game['game_record']?>';
    var board_a5 = '<?php echo $game['a5_pos']?>';
    var a5_numbers = <?php echo $game['a5_numbers']?>;
    var turn = '<?php echo $game['turn'] ?'black' : 'white' ?>';
    var game_upd = '<?php echo $game['movetime']?>';

    var result_defines = <?php echo json_encode(GameService::$status_define) ?>;
    var gameObj = <?php echo json_encode($game);?>;
    var ws_token = <?php echo json_encode($ws_token);?>;
    var userinfo = <?php echo json_encode($userinfo);?>;
    var ts_delta = <?php echo time() ?> - Math.round(new Date().getTime()/1000);
</script>