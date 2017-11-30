
<?php
$this->title = "积分榜";
?>
<table class="layui-table" lay-skin="line">
    <colgroup>
        <col width="50">
        <col width="120">
        <col width="50">
        <col width="120">
        <col width="300">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>ID</th>
        <th>昵称</th>
        <th>对局次数</th>
        <th>等级分</th>
        <th>个人简介</th>
    </tr>
    </thead>
    <tbody>
    <?php use yii\helpers\Html;
    foreach($players as $row): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><a href="/player/<?= $row['id'] ?>"><?= Html::encode($row['nickname']) ?></a></td>
        <td><?= $row['games'] ?></td>
        <td><?= $row['score'] ?></td>
        <td><?= Html::encode($row['intro']) ?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>