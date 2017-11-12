<?php
$this->title = '对局';
$this->registerJSFile('/js/viewgame.js');
?>
<div class="container">
    <div class="wrapper" style="min-height:366px;overflow:hidden;">
        <div class="content greybox" style="padding:10px;">
            <div style="float:left;margin-right:10px;"><div id="board_main"></div></div>
            <div class="greybox" id="gameinfo">
                <ul>
                    <li style="border-top:none;">
                        <span>黑方:</span><ins></ins>
                    </li>
                    <li>
                        <span>白方:</span><ins></ins>
                    </li>
                    <li>
                        <span>开始时间:</span><ins style="width:140px;"><?php echo substr($game['create_time'],0,16)?></ins>
                    </li>
                    <li>
                        <span>规则:</span><ins><?php echo $game['rule']?></ins>
                    </li>
                    <li>
                        <span>五手打点数:</span><ins><?php echo $game['a5_numbers']?></ins>
                    </li>
                    <li>
                        <span>交换:</span><ins><?php echo $game['swap'] ?'是':'否'?></ins>
                    </li>
                    <li>
                        <span>结果:</span><ins><strong><?php echo $game['status']?></strong></ins>
                    </li>
                    <li>
                        <span>轮到:</span><ins></ins>
                    </li>
                    <li>
                        <span>对局时限:</span><ins><?php echo $game['totaltime']?>小时</ins>
                    </li>
                    <li>
                        <span>黑方剩余时间:</span><ins style="width:135px;"></ins>
                    </li>
                    <li>
                        <span>白方剩余时间:</span><ins style="width:135px;"></ins>
                    </li>
                        <li>
                            <span>轮到您下第<?php echo strlen($game['game_record'])/2 +1?>手</span>
                            <span>
                                <form action="/site/move" id="move_form">
                                    <input type="hidden" name="move" />
                                    <input type="hidden" name="id" value="<?php echo $game['id']?>" />
                                    <input type="hidden" name="swap" value="0" id="do_swap" />
                                    <input type="hidden" name="a5pos" value="" />
                                    <label>请输入打点数量：</label><input type="text" name="a5number" style="width:30px"/>
                                    <input type="button" value="交换" class="button" onclick="if(confirm('您确定要交换吗？')){$('#do_swap').val('1');$('#move_form').submit();}"/>
                                    <input type="submit" value="确认落子" class="button" />
                                </form>
                            </span>
                        </li>
                        <li><span>提和：</span><input type="button" value="和棋" class="button" id="draw_button" /></li>
                        <li><span>认输：</span><input type="button" value="认输" class="button" id="resign_button" /></li>
                            <li><span style="color:#ff3333">您的对手向您提和，如果您同意，请点击和棋按钮。</span></li>
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
</script>