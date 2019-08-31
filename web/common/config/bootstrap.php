<?php

if(!defined('BOARDSIZE')) define('BOARDSIZE',15);
if(!defined('BLACKSTONE')) define('BLACKSTONE','X');
if(!defined('WHITESTONE')) define('WHITESTONE','O');
if(!defined('EMPTYSTONE')) define('EMPTYSTONE','.');
if(!defined('BLACKFIVE')) define('BLACKFIVE',0);
if(!defined('WHITEFIVE')) define('WHITEFIVE',1);
if(!defined('BLACKFORBIDDEN')) define('BLACKFORBIDDEN',2);

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
