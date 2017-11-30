
<?php
$this->title = "积分榜";
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
        <th>排名</th>
        <th>ID</th>
        <th>昵称</th>
        <th>对局数</th>
        <th>等级分</th>
        <th>个人简介</th>
    </tr>
    </thead>
    <tbody>
    <?php use yii\helpers\Html;
    foreach($players as $k=>$row): ?>
    <tr>
        <td><?= $k+1 ?></td>
        <td><?= $row['id'] ?></td>
        <td><a href="/player/<?= $row['id'] ?>"><?= Html::encode($row['nickname']) ?></a></td>
        <td><?= $row['games'] ?></td>
        <td><?= $row['score'] ?></td>
        <td><?= Html::encode($row['intro']) ?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>