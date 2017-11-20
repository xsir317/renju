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

use \GatewayWorker\Lib\CustomGateway as Gateway;
use \GatewayWorker\Lib\Helpers\MsgHelper;
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
        // 判断是否有房间号
        if(!isset($msg['game_id']))
        {
            throw new \Exception("\$message_data['game_id'] not set. client_ip:{$_SERVER['REMOTE_ADDR']} \$message:".json_encode($msg));
        }

        //默认
        $game_id = $msg['game_id'];
        $uid = isset($msg['uid']) ? intval($msg['uid']) : 0;
        $nickname = !empty($msg['nickname']) ? $msg['nickname'] : '游客';

        if($uid)
        {
            Gateway::bindUid($client_id,$uid);
        }
        Gateway::joinGroup($client_id, $game_id);

        // 把房间号昵称放到session中
        $_SESSION['game_id'] = $game_id;
        $_SESSION['uid'] = $uid;
        $_SESSION['nickname'] = $nickname;

        // 获取房间内所有用户列表
        Gateway::sendToCurrentClient(MsgHelper::build('enter',[
            'client_id' => $client_id,
            'history_msg' => MsgHelper::getRecentMsgs($game_id),
        ]));

        Gateway::sendToGroup($game_id, MsgHelper::build('login',['user' => [
            'uid' => $uid,
            'nickname' => $nickname,
        ]]));
        Gateway::sendToGroup($game_id, MsgHelper::build(
            'client_list',
            [
                'client_list' => Gateway::getClientSessionsByGroup($game_id),
            ]
        ));
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
       if(isset($_SESSION['game_id']))
       {
           $game_id = $_SESSION['game_id'];
           Gateway::leaveGroup($client_id,$game_id);
           Gateway::sendToGroup($game_id, MsgHelper::build('logout',[
                'user' => [
                    'uid' => $_SESSION['uid'],
                    'nickname' => $_SESSION['nickname'],
                ],
           ]));
           Gateway::sendToGroup($game_id, MsgHelper::build(
               'client_list',
               [
                   'client_list' => Gateway::getClientSessionsByGroup($game_id),
               ]
           ));
           if($_SESSION['uid'])
           {
               Gateway::unbindUid($client_id,$_SESSION['uid']);
           }

       }
   }
}
