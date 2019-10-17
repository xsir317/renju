<?php

$this->title = '查询';
$this->registerJsFile('/js/record_query.js');
?>
<div class="layui-col-xs8"><div id="board_main"></div></div>
<div id="gameinfo" class="layui-col-xs4" style="padding: 0 5px 0 20px;">
    <ul class="greybox">
        <li class="total_games" style="border-top:none;">
            <span>总对局数: </span><ins>0</ins>
        </li>
        <li class="black_wins">
            <span>黑胜: </span><ins>0</ins>
        </li>
        <li class="white_wins">
            <span>白胜: </span><ins>0</ins>
        </li>
        <li class="draws">
            <span>和棋: </span><ins>0</ins>
        </li>
        <li class="rel_games">
            <span>相关对局: </span>
            <ol>

            </ol>
        </li>
    </ul>
</div>