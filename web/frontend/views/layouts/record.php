<?php
use common\services\CommonService;
use common\services\GameService;
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
    <title><?= Html::encode($this->title) ?>--谱库</title>
    <link href="/css/style.css?v=10" type="text/css" media="screen" rel="stylesheet" />
    <link rel="stylesheet" href="/layui/css/layui.css"  media="all">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="layui-layout layui-layout-admin">
    <div class="layui-header header header-doc">
        <div class="layui-main" style="width: 1140px;margin: 0 auto;">
            <ul class="layui-nav">
                <?php if(!Yii::$app->user->isGuest):?><li class="layui-nav-item"><?= Yii::t('app','Welcome')?> ,<?php echo Html::encode(Yii::$app->user->identity->nickname);?>. </li><?php endif;?>
                <li class="layui-nav-item ">
                    <a href="/"><?= Yii::t('app','Hall')?></a>
                </li>
                <li class="layui-nav-item">
                    <a href="mailto:xsir317@gmail.com"><?= Yii::t('app','Contact Us')?></a>
                </li>
                <li class="layui-nav-item">
                    <a href="/about.html"><?= Yii::t('app','Rules&ELO')?></a>
                </li>
                <li class="layui-nav-item">
                    <a href="/records/show/index">查询</a>
                </li>
                <li class="layui-nav-item">
                    <!--<span class="layui-badge-dot" style="margin: -4px 3px 0;"></span>-->
                    <a href="javascript:void(0);"><?= Yii::t('app','Language')?></a>
                    <dl class="layui-nav-child">
                    <?php foreach (Yii::$app->params['languages'] as $k => $name):?>
                        <dd><a href="javascript:void(0);" onclick="pager.switch_language('<?= $k ?>');"><?= $name ?></a></dd>
                    <?php endforeach;?>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="layui-main site-inline" style="margin-top: 20px;overflow: hidden;">
    <?php echo $content?>
</div>
<div class="layui-footer footer footer-doc">
    <div class="layui-main">
        <p>
            查询页面棋盘数字表示胜率和总对局数。颜色代表得分率（赢棋数 + 0.5 * 和棋数）/ 总对局数 ，越偏绿的颜色表示得分率越高，越红表示得分率越低。
        </p>
        <p>
            查询页面操作有频率限制，操作不要太快了。
        </p>
    </div>
</div>
<audio src="" id="global-audio"></audio>
<script src="/layui/layui.all.js" charset="utf-8"></script>
<script type="text/javascript">
layui.config({
    base: '/layui/'
});
</script>
<script src="//cdn.bootcss.com/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
