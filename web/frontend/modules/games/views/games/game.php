<?php
use common\services\GameService;

$this->title = '对局';
//棋盘
$this->registerJSFile('/js/board.js');

//websocket连接
$this->registerJSFile('/js/swfobject.js');
$this->registerJSFile('/js/web_socket.js');
$this->registerJSFile('/js/md5.min.js');
$this->registerJSFile('/js/websocket.js');
?>
    <div class="layui-col-xs6"><div id="board_main"></div></div>
    <div id="gameinfo" class="layui-col-xs3" style="padding: 0 5px 0 20px;">
        <ul class="greybox">
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
                <span>规则:</span><ins><?php echo Yii::$app->params['rules'][$game['rule']]?></ins>
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
                <span>对局时限:</span><ins><?php echo intval($game['totaltime']) / 60 ?>分钟</ins>
            </li>
            <li>
                <span>黑方剩余时间:</span><ins style="width:135px;" id="black_time_display">00:00:00</ins>
            </li>
            <li>
                <span>白方剩余时间:</span><ins style="width:135px;" id="white_time_display">00:00:00</ins>
            </li>
            <li class="turn_to_play_tips" style="display: none;color: #3367d6;font-weight:bold;">
                <span>轮到您下第<?php echo strlen($game['game_record'])/2 +1?>手</span>
            </li>
            <li class="draw_button" style="display: none;"><span>提和：</span><input type="button" value="和棋" class="button" id="draw_button" /></li>
            <li class="swap_button" style="display: none;"><span>交换：</span><input type="button" value="交换" class="button" id="swap_button" /></li>
            <li class="resign_button" style="display: none;"><span>认输：</span><input type="button" value="认输" class="button" id="resign_button" /></li>
            <li class="offer_draw_tips" style="display: none;"><span style="color:#ff3333">您的对手向您提和，如果您同意，请点击和棋按钮。</span></li>
        </ul>
    </div>
    <div class="chat_area layui-col-xs3" style="padding: 0 5px 0 10px;">
        <div id="chat_user_list">
            <ul>
                <li class="user_title">
                    <span class="layui-col-xs7">昵称（点击邀请）</span>
                    <span class="layui-col-xs5">等级分</span>
                </li>
            </ul>
        </div>
        <div id="chat_content_list">
            <ul id="chat_content"></ul>
        </div>
        <div id="chat_operate_area" class="custom-tab-oper">
            <div>
                        <span class="to-emjo">
                            <a><i class="icon-menu-2 icon-emjo icon-room-custom-bar"></i></a>
                        </span>
            </div>
            <div class="component-send">
                <textarea id="msg" placeholder="你怎么看……" maxlength="100"></textarea><a class="send">发送</a>
            </div>
        </div>
        <div id="face_pop" class="face-pop">
            <div class="face-content">
            </div>
            <div class="tab"></div>
        </div>
    </div>
<!-- TODO 聊天部分需要emoji 参考 https://juejin.im/entry/596dbc68f265da6c2810e6ac-->
<script type="text/javascript">
    var result_defines = <?php echo json_encode(GameService::$status_define) ?>;
    var gameObj = <?php echo json_encode($game);?>;
    var ws_token = <?php echo json_encode($ws_token);?>;
    var userinfo = <?php echo json_encode($userinfo);?>;
    var ts_delta = <?php echo time() ?> - Math.round(new Date().getTime()/1000);
</script>