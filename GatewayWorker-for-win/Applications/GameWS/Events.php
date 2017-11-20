<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
use GatewayWorker\Lib\Helpers\MsgHelper;
use \GatewayWorker\Lib\Security;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        //Gateway::sendToClient($client_id, "Hello $client_id\r\n");
        echo "{$client_id} has a connection in\n";
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
       // debug
       echo date('Y-m-d H:i:s')."\t client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id session:".json_encode($_SESSION)." onMessage: \n".$message."\n";

       // 客户端传递的是json数据
       $message_data = @json_decode($message, true);
       if(!$message_data)
       {
           return ;
       }
       if(empty($message_data['type']) || !is_string($message_data['type']))
       {
           echo "message type not set,abort\n";
           return;
       }
       //进行安全性检查
       $security = new Security($message_data);
       if(!$security->verifyRequest())
       {
           Gateway::sendToCurrentClient(MsgHelper::build('shutdown',['content' => '校验错误，请刷新页面']));
           Gateway::closeCurrentClient();
       }

       if(is_callable('self::action'.ucfirst($message_data['type'])))
       {
           try
           {
               call_user_func('self::action'.ucfirst($message_data['type']),$client_id,$message_data);
           }
           catch(\Exception $e)
           {
               Gateway::sendToCurrentClient(MsgHelper::build('notice',['content' => '发生错误: '.$e->getMessage()]));
           }
       }
       else
       {
           echo "unknown type:{$message_data['type']}\n";
       }
       return;
   }

    public static function actionPong($client_id,$msg)
    {
        // 客户端回应服务端的心跳
        return;
    }

    public static function actionLogin($client_id,$msg)
    {
        var_dump($msg);
        return;
        // 判断是否有房间号
        if(!isset($msg['room_id']))
        {
            throw new \Exception("\$message_data['room_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:".json_encode($msg));
        }

        //默认
        $uid = 0;
        $client_name = '游客';
        if(isset($msg['uid']))
        {
            $uid = intval($msg['uid']);
        }
        if($uid)
        {
            $clients = Gateway::getClientIdByUid($uid);
            if(!empty($clients))
            {
                foreach ($clients as $exist_client_id)
                {
                    //如果已经有client_id 则强制登出
                    if($exist_client_id != $client_id)
                    {
                        Gateway::sendToClient($exist_client_id,MsgHelper::build('shutdown',['content' => '您已经在其他浏览器登录']));
                        Gateway::disconnect($exist_client_id);
                    }
                }
            }
            UsersService::touch($uid);
            Gateway::bindUid($client_id,$uid);
            $user = UsersService::getBasic($uid,1);
            $client_name = $user['nickname'];
        }
        if(empty($user))
        {
            $user = [
                'id' => 0,
                'login_name' => $client_name,
                'nickname' => $client_name,
                'avatar' => 'no_avatar',
                'client_id' => [$client_id]
            ];
        }

        // 把房间号昵称放到session中
        $room_id = $msg['room_id'];
        $client_name = htmlspecialchars($client_name);
        $_SESSION['room_id'] = $room_id;
        $_SESSION['client_name'] = $client_name;
        $_SESSION['uid'] = $uid;
        if(isset($msg['device_id']))
        {
            $_SESSION['device_id'] = $msg['device_id'];
        }

        // 获取房间内所有用户列表

        Gateway::joinGroup($client_id, $room_id);
        Gateway::sendToCurrentClient(MsgHelper::build('enter',[
            'client_id' => $client_id,
            'history_msg' => array_merge(MsgHelper::getRecentMsgs($room_id),AnnounceHelper::getRecentAnnounce($room_id)),
        ]));
        if(RoomService::getConfig($room_id,'show_user_enter'))
        {
            Gateway::sendToGroup($room_id, MsgHelper::build('login',['user' => $user,'car' => $car]));
        }
        if(!RoomService::send_client_list($room_id,1))
        {
            Gateway::sendToCurrentClient(MsgHelper::build(
                'client_list',
                [
                    'client_list' => UsersService::getUsersByRoomId($room_id,1),
                    'apply_list' => RedisConnection::_get_instance()->lRange("room_apply_list::{$room_id}",0,-1)
                ]
            ));
        }
        self::update_user_count($room_id);
        return;
    }
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {

       // debug
       echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";

       // 从房间的客户端列表中删除
       if(isset($_SESSION['room_id']))
       {
           $room_id = $_SESSION['room_id'];
           Gateway::leaveGroup($client_id,$room_id);
           $user = UsersService::getBasicByClientId($client_id);
           if(RoomService::getConfig($room_id,'show_user_enter'))
           {
               Gateway::sendToGroup($room_id, MsgHelper::build('logout',[
                   'client' => $user,
               ]));
           }
           RoomService::send_client_list($room_id,1);
           self::update_user_count($room_id);
       }
       //Gateway::disconnect($client_id);
   }
}
