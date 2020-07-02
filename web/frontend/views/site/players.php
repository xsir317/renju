
<?php
use yii\helpers\Html;
$this->title = Yii::t('app','Top Players') ;
?>
<table class="layui-table" lay-skin="line">
    <colgroup>
        <col width="50">
        <col width="50">
        <col width="100">
        <col width="80">
        <col width="120">
        <col width="300">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th><?= Yii::t('app','Rank') ?></th>
        <th>ID</th>
        <th><?= Yii::t('app','Nickname') ?></th>
        <th><?= Yii::t('app','Games') ?></th>
        <th><?= Yii::t('app','ELO') ?></th>
        <th><?= Yii::t('app','Intro') ?></th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table><?php
$this->registerJs("
let counter = 1;
layui.flow.load({
    elem: '.layui-table tbody',
    done: function(page, next){
        $.getJSON('/site/players/?page=' + page,{},function(_data){
            let list = [];
            $.each(_data.data.players, function(index, item){
                list.push('<tr>'
                    + '<td>' + counter + '</td>'
                    + '<td>' + item.id + '</td>'
                    + '<td><a href=\"/games/history/'+ item.id +'\" target=\"_blank\">' + item.nickname + '</a></td>'
                    + '<td>' + item.games + '</td>'
                    + '<td>' + item.score + '</td>'
                    + '<td>' + item.intro + '</td>'
                + '</tr>');
                counter ++;
            }); 
            next(list.join(''), _data.data.has_next); 
        });
    }
});
");
?>