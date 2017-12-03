<?php
$this->title = '大厅';

//websocket连接
$this->registerJSFile('/js/swfobject.js');
$this->registerJSFile('/js/web_socket.js');
$this->registerJSFile('/js/md5.min.js');
$this->registerJSFile('/js/websocket.js');
?>
    <div id="hall_games" class="layui-col-xs9">
        <ul class="grid_content">
            <li class="hall_game_title">
                <span class="game_id layui-col-xs2">ID</span>
                <span class="black_name layui-col-xs2">执黑方</span>
                <span class="white_name layui-col-xs2">执白方</span>
                <span class="current_step layui-col-xs2">手数</span>
                <span class="game_result layui-col-xs2">结果</span>
                <span class="view_game layui-col-xs2">旁观游戏</span>
            </li>
        </ul>
    </div>
    <div class="layui-col-xs3">
        <div id="chat_user_list" class="grid_content">
            <ul>
                <li class="user_title">
                    <span class="ulist_name layui-col-xs7">昵称（点击邀请）</span>
                    <span class="ulist_score layui-col-xs5">等级分</span>
                </li>
            </ul>
        </div>
        <div id="chat_content_list" class="grid_content">
            <ul id="chat_content"></ul>
        </div>
        <div id="chat_operate_area" class="custom-tab-oper grid_content">
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
<script type="text/javascript">
    var ws_token = <?php echo json_encode($ws_token);?>;
    var userinfo = <?php echo json_encode($userinfo);?>;
    var game_list = <?php echo json_encode($game_list);?>;
</script>
<?php $this->registerJs('pager.show_msg(\'<span style="color: #3367d6;font-weight:bold;">欢迎，请点击他人昵称邀请对局。</span>\');'); ?>