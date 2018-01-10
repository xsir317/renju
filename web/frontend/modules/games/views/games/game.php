<?php
use common\services\CommonService;

$this->title = '对局';

$this->registerJSFile('/js/all.js?v=3');
?>
    <div class="layui-col-xs6"><div id="board_main"></div></div>
    <div id="gameinfo" class="layui-col-xs3" style="padding: 0 5px 0 20px;">
        <ul class="greybox">
            <li class="black_name" style="border-top:none;">
                <span><?= Yii::t('app','Black') ?>: </span><ins></ins>
            </li>
            <li class="white_name">
                <span><?= Yii::t('app','White') ?>: </span><ins></ins>
            </li>
            <li>
                <span><?= Yii::t('app','Start Time') ?>: </span><ins style="width:140px;"><?php echo substr($game['create_time'],0,16)?></ins>
            </li>
            <li class="rule_name">
                <span><?= Yii::t('app','Rule') ?>:</span><ins><?= CommonService::getRules($game['rule']) ?></ins>
            </li>
            <li class="a5_numbers">
                <span><?= Yii::t('app','Number of the 5th moves') ?>: </span><ins><?php echo $game['a5_numbers']?></ins>
            </li>
            <li class="is_swap">
                <span><?= Yii::t('app','Swap') ?>: </span><ins></ins>
            </li>
            <li class="game_result">
                <span><?= Yii::t('app','Result') ?>: </span><ins><strong></strong></ins>
            </li>
            <li class="current_player_name">
                <span><?= Yii::t('app','Turn') ?>: </span><ins></ins>
            </li>
            <li class="total_time">
                <span><?= Yii::t('app','Total Time') ?>: </span><ins><?php echo intval($game['totaltime']) / 60 ?><?= Yii::t('app','Minutes') ?></ins>
            </li>
            <li>
                <span><?= Yii::t('app','Black') ?> <?= Yii::t('app','Time left') ?>: </span><ins style="width:120px;" id="black_time_display">00:00:00</ins>
            </li>
            <li>
                <span><?= Yii::t('app','White') ?> <?= Yii::t('app','Time left') ?>: </span><ins style="width:120px;" id="white_time_display">00:00:00</ins>
            </li>
            <li class="undo_records" style="display: none;">
                <select style="margin: 0 0 0 10px;max-width:230px;"><option value="">====<?= Yii::t('app','Undo Record') ?>====</option></select>
            </li>
            <li class="turn_to_play_tips" style="display: none;color: #3367d6;font-weight:bold;">
                <span></span>
            </li>
            <li class="draw_button" style="display: none;"><span><?= Yii::t('app','Offer Draw') ?>: </span><input type="button" value="<?= Yii::t('app','Draw') ?>" class="button" id="draw_button" /></li>
            <li class="swap_button" style="display: none;"><span><?= Yii::t('app','Swap') ?>: </span><input type="button" value="<?= Yii::t('app','Swap') ?>" class="button" id="swap_button" /></li>
            <li class="resign_button" style="display: none;"><span><?= Yii::t('app','Resign') ?>: </span><input type="button" value="<?= Yii::t('app','Resign') ?>" class="button" id="resign_button" /></li>
            <li class="undo_button" style="display: none;"><span><?= Yii::t('app','Undo') ?>: </span><input type="button" value="<?= Yii::t('app','Undo') ?>" class="button" id="undo_button" /></li>
            <li class="offer_draw_tips" style="display: none;"><span style="color:#ff3333"><?= Yii::t('app','Your opponent offers draw,press the "Draw" button if you accept it.') ?></span></li>
        </ul>
    </div>
    <div class="chat_area layui-col-xs3" style="padding: 0 5px 0 10px;">
        <div id="chat_user_list">
            <ul>
                <li class="user_title">
                    <span class="layui-col-xs7"><?= Yii::t('app','Nickname(Invite)') ?></span>
                    <span class="layui-col-xs5"><?= Yii::t('app','Ranking') ?></span>
                </li>
            </ul>
        </div>
        <div id="chat_content_list">
            <ul id="chat_content"></ul>
        </div>
        <div id="chat_operate_area" class="custom-tab-oper">
            <div>
                <span class="to-emoji">
                    <a><i class="icon-menu-2 icon-emoji icon-room-custom-bar" title="表情"></i></a>
                </span>
                <span class="to-board_icon">
                    <a><i class="icon-menu-2 icon-board icon-room-custom-bar" title="<?= Yii::t('app','Post current board') ?>"></i></a>
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
<!-- TODO 聊天部分需要emoji 参考 https://juejin.im/entry/596dbc68f265da6c2810e6ac-->
<script type="text/javascript">
    const userinfo = (<?php echo json_encode($userinfo);?>);
    const gameObj = (<?php echo json_encode($game);?>);
    const ws_token = (<?php echo json_encode($ws_token);?>);
</script>