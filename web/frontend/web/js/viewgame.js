/**
 * @author xsir317@gmail.com
 * @license http://creativecommons.org/licenses/by-sa/3.0/deed.zh
 */
var boardObj = function()
{
    var board = $("#board_main");
    var _obj = this;

    //整个游戏的结构体
    _obj.gameData = {};

    //字符串，当前局面记录。
    _obj.currgame = '';

    // 当前颜色，在初始化时会初始化为黑色
    _obj.curr_color = 'black';
    
    //当前手数，会被初始化为1
    _obj.curr_step = 1;

    _obj.load = function( game_data ){
        _obj.gameData = game_data;
        _obj.board_load();
    };

    _obj.move_pre = function(){};
    _obj.move_next = function(){};
    _obj.board_clean = function(){};
    _obj.board_end = function(){};
    _obj.board_load = function(){};

    _obj.init_board = function(){
        board.html('');
        board.mousedown(function(e){
            if(e.which == 3)
            {
                move_pre();
                return false;
            }
        });
        board.bind("contextmenu", function() { return false; });
        for(var i=1;i<=15;i++)
        {
            //insert a row
            var newrow = $(document.createElement("div"));
            newrow.addClass('row');
            for(var j=1;j<=15;j++)
            {
                //insert a cross point
                var newcell = $(document.createElement("div"));
                newcell.addClass(i.toString(16) + j.toString(16));
                newcell.attr('alt',i.toString(16) + j.toString(16));
                newcell.addClass('blank');
                newrow.append(newcell);
            }
            board.append(newrow);
        }
        //生成控制按钮
        var controlbar = $(document.createElement("div"));
        controlbar.addClass('controlbar');
        board.after(controlbar);
        var nextbtn = $(document.createElement("input"));
        var pre = $(document.createElement("input"));
        var end = $(document.createElement("input"));
        var init = $(document.createElement("input"));
        var first = $(document.createElement("input"));
        pre.attr('type','button').addClass('button').val('前一手').click(_obj.move_pre).appendTo(controlbar);
        nextbtn.attr('type','button').addClass('button').val('后一手').click(_obj.move_next).appendTo(controlbar);
        first.attr('type','button').addClass('button').val('第一手').click(_obj.board_clean).appendTo(controlbar);
        end.attr('type','button').addClass('button').val('最后一手').click(_obj.board_end).appendTo(controlbar);
        init.attr('type','button').addClass('button').val('恢复').click(_obj.board_load).appendTo(controlbar);
    };
};

//
var board = new boardObj();
board.init_board();
board.load(gameObj);


	//websocket
if (typeof console == "undefined") {    this.console = { log: function (msg) {  } };}
Object.keys = Object.keys || function(obj){/**兼容IE**/
    var result = [];
        for(var key in obj )
            if(({}).hasOwnProperty.call(obj,key)){
                result.push(key) ;
            }
        return result;
    };
WEB_SOCKET_SWF_LOCATION = "/swf/WebSocketMain.swf?time="+new Date().getTime();
WEB_SOCKET_DEBUG = true;
var ws;
function object_md5(obj) {
    var keys = Object.keys(obj).sort();
    var stringfy = '', prop;
    for (var i = 0; i < keys.length; i++) {
        prop = keys[i];
        if(stringfy != '') stringfy += '&';
        stringfy += (prop + '=' + obj[prop]);
    }
    return md5(stringfy);
}

var global_current_client_id = '';
var chat = function (){
    var that=this;
    // 连接服务端
    this.connect = function () {
        // 创建websocket
        ws = new WebSocket("ws://"+document.domain+":8282");
        // 当socket连接打开时，输入用户名
        ws.onopen = that.onopen;
        // 当有消息时根据消息类型显示不同信息
        ws.onmessage = that.onmessage;
        ws.onclose = function(e) {
            console.log("连接关闭，定时重连");
            window.setTimeout(that.connect,3000);
        };
        ws.onerror = function(e) {
            console.log(e);
        };
    };

    // 连接建立时发送WEBSOCKET登录信息
    this.onopen = function ()
    {
        var login_data = {"type":"login","game_id":gameObj.id,'uid':userinfo ?　userinfo.id : 0};
        that.dosend(login_data);
    };

// 服务端发来消息时
    this.onmessage = function (e)
    {
        console.log("resultData="+e.data);
        var data = JSON.parse(e.data);
        that.agentDistribute(data);
    };

    this.sendMsg = function(_data){
        that.dosend(_data);
    };

    this.dosend = function(data){
        console.log("sendData=="+JSON.stringify(data));
        var string_data = '';
        switch (typeof data)
        {
            case 'string':
                string_data = data;
                break;
            case 'object':
                data['_token'] = ws_token['token'];
                data['_timestamp'] = ts_delta + Math.round(new Date().getTime()/1000);
                var full_data_obj = JSON.parse(JSON.stringify(data));//copy
                full_data_obj['_secret'] = ws_token['secret'];//secret 不会打包进数据
                data['_checksum'] = object_md5(full_data_obj);
                string_data = JSON.stringify(data);
                break;
            default:
                break;
        }
        console.log("sendData_trans=="+string_data);
        ws.send(string_data);
    };

    //消息代理分发
    this.agentDistribute = function(data){
        if(typeof data.type != 'string' || data.type == '')
        {
            return;
        }
        var function_name = 'action'+data['type'].charAt(0).toUpperCase() + data['type'].slice(1);
        if(typeof that[function_name] == 'function')
        {
            return that[function_name](data);
        }
    };

    // 服务端ping客户端
    this.actionPing = function(_data){
        that.dosend({type:"pong"});
    };


    //进入房间之后会接收到自己的client_id 和一些历史消息
    this.actionEnter = function(_data)
    {
        global_current_client_id = _data['client_id'];
        if(_data!=null && _data['history_msg']!=null&&_data['history_msg'].length>0){
            for(var i in _data['history_msg']){
                that.agentDistribute(_data['history_msg'][i]);
            }
        }
    };


    this.actionClient_list = function(_data){
    };

    this.actionGame_info = function(_data){
        board.load(_data.game);
    };

    // 接收发言消息
    this.actionSay = function(_data){
    };


    //系统公告
    this.actionGlobal_announce = function(_data){
    };

    // 用户退出 更新用户列表
    this.actionLogout = function(_data){
    };

    this.actionNotice = function(_data){
        if(_data.content)
        {
            alert(_data.content);
        }
    };

    this.actionShutdown = function(_data)
    {
        //正常业务结束，服务器端要求关闭并且不再重连
        //if(typeof shutdownCallback == 'function') shutdownCallback(_data);
        ws.onclose = function () {
            return false;
        };
        global_current_client_id = '';
        ws.close();
    }
};

var  _chat={
    chatObj:null,
    getChat:function(){
        if(this.chatObj==null)this.chatObj = new chat();
        return this.chatObj;
    }
};
_chat.getChat().connect();