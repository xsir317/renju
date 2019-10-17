<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_records".
 *
 * @property int $id
 * @property string $black_player
 * @property string $white_player
 * @property string $game
 * @property string $origin_game
 * @property string $rule 规则
 * @property int $result 2/1/0 黑方得分，胜平负
 * @property string $data_from 来源
 * @property string $rel_id
 * @property string $game_time
 * @property string $created_time
 */
class GameRecords extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'game_records';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['white_player', 'origin_game', 'data_from'], 'required'],
            [['result'], 'integer'],
            [['game_time', 'created_time'], 'safe'],
            [['black_player', 'white_player', 'rule', 'data_from', 'rel_id'], 'string', 'max' => 16],
            [['game'], 'string', 'max' => 450],
            [['origin_game'], 'string', 'max' => 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'black_player' => 'Black Player',
            'white_player' => 'White Player',
            'game' => 'Game',
            'origin_game' => 'Origin Game',
            'rule' => 'Rule',
            'result' => 'Result',
            'data_from' => 'Data From',
            'rel_id' => 'Rel ID',
            'game_time' => 'Game Time',
            'created_time' => 'Created Time',
        ];
    }
}
