<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_undo_log".
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $uid
 * @property string $current_board
 * @property integer $to_number
 * @property string $comment
 * @property integer $status
 * @property string $created_time
 */
class GameUndoLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_undo_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'uid', 'to_number'], 'required'],
            [['game_id', 'uid', 'to_number', 'status'], 'integer'],
            [['created_time'], 'safe'],
            [['current_board'], 'string', 'max' => 512],
            [['comment'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'game_id' => 'Game ID',
            'uid' => 'Uid',
            'current_board' => 'Current Board',
            'to_number' => 'To Number',
            'comment' => 'Comment',
            'status' => 'Status',
            'created_time' => 'Created Time',
        ];
    }
}
