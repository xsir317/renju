<?php
use common\services\GameService;

$this->title = '大厅';

//websocket连接
$this->registerJSFile('/js/swfobject.js');
$this->registerJSFile('/js/web_socket.js');
$this->registerJSFile('/js/md5.min.js');
$this->registerJSFile('/js/websocket.js');
?>
<div class="wrapper" style="min-height:366px;overflow:hidden;">
    <div class="content greybox" style="padding:10px;" id="hall_games">
        <div style="float:left;margin-right:10px;padding:10px 0 0 0;" class="greybox">
            <ul>
                <li class="hall_game_title">
                    <span class="game_id">ID</span>
                    <span class="black_name">执黑方</span>
                    <span class="white_name">执白方</span>
                    <span class="current_step">手数</span>
                    <span class="view_game">旁观游戏</span>
                </li>
            </ul>
            <ul id="hall_game_list">
            </ul>
        </div>
        <div class="greybox chat_area">
            <div id="chat_user_list">
                <ul>
                    <li class="user_title">
                        <span class="ulist_name">昵称</span>
                        <span class="ulist_score">等级分</span>
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
                <div class="componet-send">
                    <textarea id="msg" placeholder="你怎么看……" maxlength="100"></textarea>
                    <a class="send">发送</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var result_defines = <?php echo json_encode(GameService::$status_define) ?>;

    var ws_token = <?php echo json_encode($ws_token);?>;
    var userinfo = <?php echo json_encode($userinfo);?>;
    var ts_delta = <?php echo time() ?> - Math.round(new Date().getTime()/1000);
</script>
