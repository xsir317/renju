<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "games".
 *
 * @property integer $id
 * @property integer $black_id
 * @property integer $white_id
 * @property string $status
 * @property integer $offer_draw
 * @property string $rule
 * @property integer $free_opening
 * @property string $game_record
 * @property integer $black_time
 * @property integer $white_time
 * @property integer $totaltime
 * @property integer $swap
 * @property string $a5_pos
 * @property integer $a5_numbers
 * @property string $updtime
 * @property string $movetime
 * @property string $comment
 * @property integer $tid
 * @property string $create_time
 */
class Games extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'games';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['black_id', 'white_id', 'black_time', 'white_time', 'totaltime', 'swap', 'a5_numbers', 'comment'], 'required'],
            [['black_id', 'white_id', 'offer_draw', 'free_opening', 'black_time', 'white_time', 'totaltime', 'swap', 'a5_numbers', 'tid'], 'integer'],
            [['status', 'rule'], 'string'],
            [['updtime', 'movetime', 'create_time'], 'safe'],
            [['game_record'], 'string', 'max' => 450],
            [['a5_pos'], 'string', 'max' => 40],
            [['comment'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'black_id' => 'Black ID',
            'white_id' => 'White ID',
            'status' => 'Status',
            'offer_draw' => 'Offer Draw',
            'rule' => 'Rule',
            'free_opening' => 'Free Opening',
            'game_record' => 'Game Record',
            'black_time' => 'Black Time',
            'white_time' => 'White Time',
            'totaltime' => 'Totaltime',
            'swap' => 'Swap',
            'a5_pos' => 'A5 Pos',
            'a5_numbers' => 'A5 Numbers',
            'updtime' => 'Updtime',
            'movetime' => 'Movetime',
            'comment' => 'Comment',
            'tid' => 'Tid',
            'create_time' => 'Create Time',
        ];
    }
}
