<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "player".
 *
 * @property integer $id
 * @property string $email
 * @property string $nickname
 * @property string $password
 * @property integer $login_times
 * @property integer $b_win
 * @property integer $b_lose
 * @property integer $w_win
 * @property integer $w_lose
 * @property integer $draw
 * @property integer $games
 * @property string $reg_time
 * @property string $reg_ip
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property string $score
 * @property string $language
 * @property string $intro
 */
class Player extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'player';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'nickname', 'password', 'login_times', 'score'], 'required'],
            [['login_times', 'b_win', 'b_lose', 'w_win', 'w_lose', 'draw', 'games'], 'integer'],
            [['reg_time', 'last_login_time'], 'safe'],
            [['score'], 'number'],
            [['email'], 'string', 'max' => 64],
            [['nickname', 'password'], 'string', 'max' => 32],
            [['reg_ip', 'last_login_ip'], 'string', 'max' => 15],
            [['language'], 'string', 'max' => 8],
            [['intro'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'nickname' => 'Nickname',
            'password' => 'Password',
            'login_times' => 'Login Times',
            'b_win' => 'B Win',
            'b_lose' => 'B Lose',
            'w_win' => 'W Win',
            'w_lose' => 'W Lose',
            'draw' => 'Draw',
            'games' => 'Games',
            'reg_time' => 'Reg Time',
            'reg_ip' => 'Reg Ip',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
            'score' => 'Score',
            'language' => 'Language',
            'intro' => 'Intro',
        ];
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * 一个简单的hash，实际项目中需要更靠谱的加密
     * @param $password
     * @return string
     */
    public static function hash_pwd($password)
    {
        return md5($password);
    }

}
