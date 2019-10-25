<?php
$this->title = Yii::t('app','Hall');

?>
    <div id="hall_games" class="layui-col-md9">
        <ul class="grid_content">
            <li class="hall_game_title">
                <span class="game_id layui-col-xs2">ID</span>
                <span class="black_name layui-col-xs2"><?= Yii::t('app','Black')?></span>
                <span class="white_name layui-col-xs2"><?= Yii::t('app','White')?></span>
                <span class="current_step layui-col-xs2"><?= Yii::t('app','Stones')?></span>
                <span class="game_result layui-col-xs2"><?= Yii::t('app','Result')?></span>
                <span class="view_game layui-col-xs2"><?= Yii::t('app','Observe')?></span>
            </li>
        </ul>
    </div>
    <div class="layui-col-md3">
        <div id="chat_user_list" class="grid_content">
            <ul>
                <li class="user_title">
                    <span class="ulist_name layui-col-xs7"><?= Yii::t('app','Nickname')?></span>
                    <span class="ulist_score layui-col-xs5"><?= Yii::t('app','Ranking')?></span>
                </li>
            </ul>
        </div>
        <div id="chat_content_list" class="grid_content">
            <ul id="chat_content"></ul>
        </div>
        <div id="chat_operate_area" class="custom-tab-oper grid_content">
            <div>
                <span class="to-emoji">
                    <a><i class="icon-menu-2 icon-emoji icon-room-custom-bar"></i></a>
                </span>
            </div>
            <div class="component-send">
                <textarea id="msg" placeholder="你怎么看……" maxlength="100"></textarea><a class="send"><?= Yii::t('app','Send') ?></a>
            </div>
        </div>
        <div id="face_pop" class="face-pop">
            <div class="face-content">
            </div>
            <div class="tab"></div>
        </div>
    </div>
<script type="text/javascript">
    const ws_token = (<?php echo json_encode($ws_token);?>);
    const userinfo = (<?php echo json_encode($userinfo);?>);
    const game_list = (<?php echo json_encode($game_list);?>);
</script>
<?php $this->registerJs('pager.show_msg(\'<span style="color: #3367d6;">欢迎，请点击他人昵称邀请对局，休闲对局请选择<strong style="color: #2050c0;">无禁手</strong>规则。</span>\');'); ?>