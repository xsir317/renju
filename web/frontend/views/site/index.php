<?php
$this->title = '首页';
$this->registerJSFile('/js/index.js');
?>
<div class="wrapper" style="overflow:hidden">
    <div id="intro" class="greybox">
        <div id="intro_img">
            <a href="/images/atsuhime.jpg" target="_blank">
                <img src="/images/index.jpg" alt="笃姬剧照-五子棋对弈" />
            </a>
        </div>
        <div id="intro_txt">
            <p>本系统是一个基于workerman和Yii2的Web五子棋系统，目前支持山口规则和RIF规则。</p>
        </div>
    </div>
    <div id="login_reg">
        <div id="cont_login" class="greybox">
            <div id="login">
                <h3>用户登录</h3>
                <form action="/site/login" method="post" onsubmit="return false;">
                    <ul>
                        <li><label>Email</label><input type="text" name="email" class="input" id="loginfocus" /></li>
                        <li><label>密码</label><input type="password" name="passwd" class="input" /></li>
                        <li><input type="submit" id="loginsubmit" value="登录submit" class="button" /></li>
                    </ul>
                </form>
            </div>
            <div id="reg2">还没有帐号？请注册！</div>
        </div>
        <div id="cont_reg" style="display:none;" class="greybox">
                <div id="login2">已经注册过了？请登录！</div>
                <div id="reg">
                    <h3>用户注册</h3>
                    <form action="/site/reg" method="post" onsubmit="return false;">
                        <ul>
                            <li><label>Email</label><input type="text" name="email" class="input" id="email" /><span id="emailchk"></span></li>
                            <li><label>昵称</label><input type="text" name="nickname" class="input" /><span id="nicknamechk"></span></li>
                            <li><label>密码</label><input type="password" name="passwd" class="input" /></li>
                            <li><label>确认密码</label><input type="password" name="passwd2" class="input" /></li>
                            <li><input type="submit" id="regsubmit" value="注册submit" class="button" /></li>
                        </ul>
                    </form>
                </div>
            </div>
    </div>
</div>
