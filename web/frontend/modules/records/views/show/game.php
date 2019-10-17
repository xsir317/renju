<?php

$this->title = '对局';
$this->registerJsFile('/js/records.js');
?>
<div class="layui-col-xs8"><div id="board_main"></div></div>
<div id="gameinfo" class="layui-col-xs4" style="padding: 0 5px 0 20px;">
    <ul class="greybox">
        <li class="black_name" style="border-top:none;">
            <span>黑方: </span><ins><?= $record->black_player ?></ins>
        </li>
        <li class="white_name">
            <span>白方: </span><ins><?= $record->white_player ?></ins>
        </li>
        <li class="rule_name">
            <span>规则:</span><ins><?= $record->rule ?></ins>
        </li>
        <li class="game_result">
            <span>结果: </span><ins><strong><?= $record->result == 2 ? '黑胜' : ($record->result == 1 ? "和棋" :'白胜') ?></strong></ins>
        </li>
        <li class="game_result">
            <span>来源: </span><ins><?= $record->data_from ?></ins>
        </li>
        <li class="origin_link">
            <span>原始链接: </span><ins><a target="_blank" href="<?php
                switch ($record->data_from)
                {
                    case 'renju.net':
                        echo "http://www.renju.net/media/games.php?gameid={$record->rel_id}";
                        break;
                    case 'renjuoffline':
                        echo "http://www.renjuoffline.com/showboard.php?game={$record->rel_id}";
                        break;
                    default:
                        echo "#";
                        break;
                }
                ?>">点击打开</a></ins>
        </li>
    </ul>
</div>
<script type="text/javascript">
    let game_str = '<?= $record->game ?>';
</script>