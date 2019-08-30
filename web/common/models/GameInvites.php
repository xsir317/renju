<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_invites".
 *
 * @property integer $id
 * @property integer $from
 * @property integer $to
 * @property integer $black_id
 * @property string $message
 * @property integer $totaltime
 * @property string $rule
 * @property integer $free_opening
 * @property integer $allow_undo
 * @property integer $is_private
 * @property integer $status
 * @property integer $game_id
 * @property string $updtime
 */
class GameInvites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_invites';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to', 'black_id', 'message', 'totaltime', 'free_opening'], 'required'],
            [['from', 'to', 'black_id', 'totaltime', 'free_opening', 'allow_undo','is_private', 'status', 'game_id'], 'integer'],
            [['rule'], 'string'],
            [['updtime'], 'safe'],
            [['message'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from' => 'From',
            'to' => 'To',
            'black_id' => 'Black ID',
            'message' => 'Message',
            'totaltime' => 'Totaltime',
            'rule' => 'Rule',
            'free_opening' => 'Free Opening',
            'allow_undo' => 'Allow Undo',
            'is_private' => 'is private',
            'status' => 'Status',
            'game_id' => 'Game ID',
            'updtime' => 'Updtime',
        ];
    }
}
