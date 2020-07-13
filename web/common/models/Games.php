<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "games".
 *
 * @property integer $id
 * @property integer $black_id
 * @property integer $white_id
 * @property integer $status
 * @property integer $offer_draw
 * @property string $rule
 * @property integer $free_opening
 * @property integer $allow_undo
 * @property integer $allow_ob_talk
 * @property integer $is_private
 * @property string $game_record
 * @property integer $black_time
 * @property integer $white_time
 * @property integer $totaltime
 * @property integer $step_add_sec
 * @property integer $swap
 * @property integer $soosyrv_swap
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
            [['black_id', 'white_id', 'status', 'offer_draw', 'free_opening', 'allow_undo','allow_ob_talk','is_private', 'black_time', 'white_time', 'totaltime','step_add_sec', 'swap', 'soosyrv_swap', 'a5_numbers', 'tid'], 'integer'],
            [['rule'], 'string'],
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
            'allow_undo' => 'Allow Undo',
            'allow_ob_talk' => 'allow ob talk',
            'is_private' => 'is private',
            'game_record' => 'Game Record',
            'black_time' => 'Black Time',
            'white_time' => 'White Time',
            'totaltime' => 'Totaltime',
            'step_add_sec' => 'step add sec',
            'swap' => 'Swap',
            'soosyrv_swap' => 'Soosyrv Swap',
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
