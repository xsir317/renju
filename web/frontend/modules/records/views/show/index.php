<?php

$this->title = '查询';
$this->registerJsFile('/js/record_query.js');
$this->registerCss("
    .good1 { background-color:#C7F69C } 
    .good2 { background-color:#90E344 } 
    .good3 { background-color:#63C10D } 
    .bad1 { background-color:#F94A45 } 
    .bad2 { background-color:#FC6D6A } 
    .bad3 { background-color:#F49694 } 
    .unknown { background-color:#D3D899 }
    .rel_games a{display: block;margin:5px 10px;}
    .statistics>.win_rate{ display:block;height:18px;width:36px;font-size:8px;text-align:center;font-weight:normal;line-height:18px; }
    .statistics>.total_game{ display:block;height:18px;width:36px;font-size:8px;text-align:center;font-weight:normal;line-height:18px; }
    .statistics:hover{border:none; height:37px;width:37px;}
");
?>
<div class="layui-col-xs8"><div id="board_main"></div></div>
<div id="gameinfo" class="layui-col-xs4" style="padding: 0 5px 0 20px;">
    <ul class="greybox">
        <li class="total_games" style="border-top:none;">
            <span>总对局数： </span><ins>0</ins>
        </li>
        <li class="black_wins">
            <span>黑胜： </span><ins>0</ins>
        </li>
        <li class="black_score">
            <span>黑得分率： </span><ins>0</ins>
        </li>
        <li class="white_wins">
            <span>白胜： </span><ins>0</ins>
        </li>
        <li class="draws">
            <span>和棋： </span><ins>0</ins>
        </li>
        <li>
            <span>相关对局： </span></li>
        <li class="rel_games">
            <span><p></p></span>
        </li>
    </ul>
</div>