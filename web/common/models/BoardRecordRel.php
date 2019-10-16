<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "board_record_rel".
 *
 * @property int $board_id
 * @property int $record_id
 */
class BoardRecordRel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'board_record_rel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['board_id', 'record_id'], 'required'],
            [['board_id', 'record_id'], 'integer'],
            [['board_id', 'record_id'], 'unique', 'targetAttribute' => ['board_id', 'record_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'board_id' => 'Board ID',
            'record_id' => 'Record ID',
        ];
    }
}
