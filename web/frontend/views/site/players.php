
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
    <?php
    foreach($players as $k=>$row): ?>
    <tr>
        <td><?= $k+1 ?></td>
        <td><?= $row['id'] ?></td>
        <td><a href="/games/history/<?= $row['id'] ?>"><?= Html::encode($row['nickname']) ?></a></td>
        <td><?= $row['games'] ?></td>
        <td><?= $row['score'] ?></td>
        <td><?= Html::encode($row['intro']) ?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>