<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
unset($this->assetBundles['yii\web\JqueryAsset']);
unset($this->assetBundles['yii\web\YiiAsset']);
unset($this->assetBundles['yii\bootstrap\BootstrapAsset']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?>--Web五子棋</title>
    <link href="/css/style.css" type="text/css" media="screen" rel="stylesheet" />
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="container">
    <div id="page-top">
        <div class="wrapper">
            <div id="toolbar">
                <ul>
                    <?php if(!Yii::$app->user->isGuest):?><li>亲爱的<?php echo Html::encode(Yii::$app->user->nickname);?>。</li><?php endif;?>
                    <li>|<a class="switch" href="/">首页</a></li>
                    <li>|<a href="mailto:xsir317@gmail.com" class="switch">联系我们</a></li>
                </ul>
            </div>
        </div>
    </div>
    <?php echo $content?>
    <div class="footer">
        <div class="wrapper greybox">
            <p>请使用Chrome或Firefox访问。</p>
        </div>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
