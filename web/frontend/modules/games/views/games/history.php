<?php
use yii\helpers\Html;

$this->title = $player['nickname'] . Yii::t('app',"'s history games");
?>
<h1><?= Html::encode($player['nickname']) ?> <?= Yii::t('app',"'s history games") ?></h1>
<ul id="games_list">
    <li>
        <span class="game_list_title layui-col-xs1">ID</span>
        <span class="game_list_title layui-col-xs3"><?= Yii::t('app','Black') ?></span>
        <span class="game_list_title layui-col-xs3"><?= Yii::t('app','White') ?></span>
        <span class="game_list_title layui-col-xs1"><?= Yii::t('app','Rule') ?></span>
        <span class="game_list_title layui-col-xs1"><?= Yii::t('app','Stones') ?></span>
        <span class="game_list_title layui-col-xs1"><?= Yii::t('app','Result') ?></span>
        <span class="game_list_title layui-col-xs2"><?= Yii::t('app','Comment') ?></span>
    </li>
</ul>
<?php
$this->registerJs("
layui.flow.load({
    elem: '#games_list',
    done: function(page, next){
        $.getJSON('/games/history/".$player['id']."?page=' + page,{},function(_data){
            let list = [];
            $.each(_data.data.games, function(index, item){
                list.push('<li><a href=\"/game/'+ item.id +'\" target=\"_blank\">'
                    + '<span class=\"layui-col-xs1\">' + item.id + '</span>'
                    + '<span class=\"layui-col-xs3\">' + item.black.nickname + '</span>'
                    + '<span class=\"layui-col-xs3\">' + item.white.nickname + '</span>'
                    + '<span class=\"layui-col-xs1\">' + rule_defines[item.rule] + '</span>'
                    + '<span class=\"layui-col-xs1\">' + (item.game_record.length / 2) + '</span>'
                    + '<span class=\"layui-col-xs1\">' + pager.t(result_defines[item.status]) + '</span>'
                    + '<span class=\"layui-col-xs2\">' + (item.comment ? item.comment : '-') + '</span>'
                + '</a></li>');
            }); 
            next(list.join(''), _data.data.has_next); 
        });
    }
});
");
?>
