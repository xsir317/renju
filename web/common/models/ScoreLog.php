<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "score_log".
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $player_id
 * @property integer $op_id
 * @property string $before_score
 * @property string $op_score
 * @property integer $k_val
 * @property string $delta_score
 * @property string $after_score
 * @property string $logtime
 */
class ScoreLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'score_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['game_id', 'player_id', 'op_id', 'before_score', 'op_score', 'k_val', 'delta_score', 'after_score'], 'required'],
            [['game_id', 'player_id', 'op_id', 'k_val'], 'integer'],
            [['before_score', 'op_score', 'delta_score', 'after_score'], 'number'],
            [['logtime'], 'safe'],
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
            'player_id' => 'Player ID',
            'op_id' => 'Op ID',
            'before_score' => 'Before Score',
            'op_score' => 'Op Score',
            'k_val' => 'K Val',
            'delta_score' => 'Delta Score',
            'after_score' => 'After Score',
            'logtime' => 'Logtime',
        ];
    }
}
