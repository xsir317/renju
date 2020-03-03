<?php
use yii\helpers\Html;

$this->title = Yii::t('app','Welcome');
$this->registerJSFile('/js/index.js?v=1');
?>
<div id="intro" class="layui-col-md8">
    <div id="intro_img" class="grid_content">
        <a href="/images/atsuhime.jpg" target="_blank" style="padding-left: 32px;">
            <img src="/images/index.jpg" alt="笃姬剧照-五子棋对弈" />
        </a>
    </div>
    <div id="intro_txt" class="grid_content">
        <p><?= Yii::t('app','This is a online renju game system, based on workerman and Yii2, now supports RIF, Yamaguchi,Soosyrv8,Gomoku and free renju.')?></p>
    </div>
</div>
<div id="login_reg" class="layui-col-md4">
        <div id="cont_login" class="greybox grid_content">
            <div id="login">
                <h3><?= Yii::t('app','User Login') ?></h3>
                <?= Html::beginForm("/user/login","post",["onsubmit"=>"return false;"]) ?>
                    <ul>
                        <li><label>Email</label><input type="text" name="email" class="layui-input" id="loginfocus" /></li>
                        <li><label><?= Yii::t('app','Password') ?></label><input type="password" name="passwd" class="layui-input" /></li>
                        <li><input type="submit" id="loginsubmit" value="<?= Yii::t('app','Login') ?>" class="layui-btn layui-btn-normal" /></li>
                    </ul>
                <?= Html::endForm(); ?>
            </div>
            <div id="reg2"><?= Yii::t('app',"Don't have an account? Create your account!") ?></div>
        </div>
        <div id="cont_reg" style="display:none;" class="greybox grid_content">
                <div id="login2"><?= Yii::t('app','Already have an account? Login here!') ?></div>
                <div id="reg">
                    <h3><?= Yii::t('app','New User') ?></h3>
                    <?= Html::beginForm("/user/reg","post",["onsubmit"=>"return false;"]) ?>
                        <ul>
                            <li><label>Email</label><input type="text" name="email" class="layui-input" id="email" /></li>
                            <li><label><?= Yii::t('app','Nickname') ?></label><input type="text" name="nickname" class="layui-input" /></li>
                            <li><label><?= Yii::t('app','Password') ?></label><input type="password" name="passwd" class="layui-input" /></li>
                            <li><label><?= Yii::t('app','Repeat Password') ?></label><input type="password" name="passwd2" class="layui-input" /></li>
                            <li><input type="submit" id="regsubmit" value="<?= Yii::t('app','Register') ?>" class="layui-btn layui-btn-normal" /></li>
                        </ul>
                    <?= Html::endForm(); ?>
                </div>
            </div>
    </div>
