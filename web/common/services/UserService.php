<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/21/2017
 * Time: 5:50 PM
 */

namespace common\services;


use common\models\Player;

class UserService extends BaseService
{
    public static function render(&$data,$key='id',$info_name='user')
    {
        $uid_list = array_column($data,$key);
        if(!empty($uid_list))
        {
            $user_list = Player::find()->where(['id' => $uid_list])->indexBy('id')->all();

            foreach ($data as &$tmp)
            {
                $uid = $tmp[$key];
                if(isset($user_list[$uid]))
                {
                    $tmp[$info_name] = [
                        'id' => $user_list[$uid]->id,
                        'email' => $user_list[$uid]->email,
                        'nickname' => $user_list[$uid]->nickname,
                        'score' => $user_list[$uid]->score,
                        'intro' => $user_list[$uid]->intro,
                    ];
                }
            }
        }

        return $data;
    }

    public static function renderUser($uid)
    {
        $user = $uid ? Player::findOne($uid) : null;
        return $user ? [
            'id' => $user->id,
            'email' => $user->email,
            'nickname' => $user->nickname,
            'score' => $user->score,
            'intro' => $user->intro,
        ] : null;
    }
}