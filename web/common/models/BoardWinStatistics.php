<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "board_win_statistics".
 *
 * @property int $id
 * @property string $board 二进制的棋盘
 * @property int $white_wins
 * @property int $black_wins
 * @property int $draws
 * @property string $next_move { "aa" :[1,2,3]} 这样的下一手走法胜率记录
 */
class BoardWinStatistics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'board_win_statistics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['board'], 'required'],
            [['white_wins', 'black_wins', 'draws'], 'integer'],
            [['board'], 'string', 'max' => 60],
            [['next_move'], 'string', 'max' => 2048],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'board' => 'Board',
            'white_wins' => 'White Wins',
            'black_wins' => 'Black Wins',
            'draws' => 'Draws',
            'next_move' => 'Next Move',
        ];
    }
}