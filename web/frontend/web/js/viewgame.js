/**
 * @author xsir317@gmail.com
 * @license http://creativecommons.org/licenses/by-sa/3.0/deed.zh
 */
	var boardobj = $("#board_main");
	var currgame = '';
	var endgame = '';
	var currcolor;
	var currstepnumber;
	boardobj.html('');
	boardobj.mousedown(function(e){
		if(e.which == 3)
		{
			move_pre();
			return false;
		}
	});
	boardobj.bind("contextmenu", function() { return false; }); 
	//根据endgame的记录，落下后面一手棋
	move_next = function(){
		if(endgame != currgame)
		{
			//插入打点逻辑：如果是第四手，根据a5字段来设置盘面上的打点
			if(currstepnumber == 4)
			{
				show_a5();
			}
			else if(currstepnumber == 5)
			{
				hide_a5();
			}
			//逻辑结束
			nextstep = endgame.substr(currgame.length,2);
			nextstepcell = boardobj.find('.'+nextstep);
			nextstepcell.removeClass('blank').addClass(currcolor).html(currstepnumber++);
			currcolor = (currcolor == 'black' ? 'white':'black');
			currgame += nextstep;
			return true;
		}
		else
		{
			return false;
		}
	};

	//前一手
	move_pre = function(){
		if(currgame != '')
		{
			//逻辑结束
			currstep = currgame.substr(currgame.length-2,2);
			currstepcell = boardobj.find('.'+currstep);
			currstepcell.removeClass('black white a5stone').addClass('blank').html('');
			currcolor = (currcolor == 'black' ? 'white':'black');
			currgame = currgame.substr(0,currgame.length-2);
			currstepnumber --;
			//插入打点逻辑：如果是第四手，根据a5字段来设置盘面上的打点
			if(currstepnumber == 5)
			{
				show_a5();
			}
			else if(currstepnumber == 4)
			{
				hide_a5();
			}
			return true;
		}
		else
		{
			return false;
		}
	};
	//回到第一手
	board_clean = function(){
		while(move_pre());
	};
	//到最后一手
	board_end = function(){
		while(move_next());
	};
	//根据gameinit显示整盘棋
	board_init = function(){
		endgame = gameinit;
		currgame = '';
		currcolor = 'black';
		currstepnumber = 1;
		boardobj.find('.row div').removeClass('black white').addClass('blank').html('');
		board_end();
	};
	show_a5 = function(){
		if(board_a5 == '')
			return false;
		var a5_points = '.' + board_a5.substr(0,2);
		for(var sub = 2;sub<board_a5.length;sub += 2)
		{
			a5_points += ',.';
			a5_points += board_a5.substr(sub,2);
		}
		arr_a5 = $(a5_points);
		arr_a5.removeClass('blank').addClass('black a5stone').html('▲');
	}
	hide_a5 = function(){
		$(".a5stone").removeClass('black a5stone').addClass('blank').html('');
	}
	//生成棋盘
	for(var i=1;i<=15;i++)
	{
		//insert a row
		var newrow = $(document.createElement("div"));
		newrow.addClass('row');
		boardobj.append(newrow);
		for(var j=1;j<=15;j++)
		{
			//insert a cross point
			var newcell = $(document.createElement("div"));
			newcell.addClass(i.toString(16) + j.toString(16));
			newcell.attr('alt',i.toString(16) + j.toString(16));
			newcell.addClass('blank');
			newrow.append(newcell);
		}
	}
	//生成控制按钮
	controlbar = $(document.createElement("div"));
	controlbar.addClass('controlbar');
	boardobj.after(controlbar);
	nextbtn = $(document.createElement("input"));
	pre = $(document.createElement("input"));
	end = $(document.createElement("input"));
	init = $(document.createElement("input"));
	first = $(document.createElement("input"));
	pre.attr('type','button').addClass('button').val('前一手').click(move_pre).appendTo(controlbar);
	nextbtn.attr('type','button').addClass('button').val('后一手').click(move_next).appendTo(controlbar);
	first.attr('type','button').addClass('button').val('第一手').click(board_clean).appendTo(controlbar);
	end.attr('type','button').addClass('button').val('最后一手').click(board_end).appendTo(controlbar);
	init.attr('type','button').addClass('button').val('恢复').click(board_init).appendTo(controlbar);
	boardobj.find('.row div').click(function(){
		//落子
		if(!$(this).hasClass('blank'))
		{
			return false;
		}
		if(currstepnumber == 5)
		{
			hide_a5();
		}		
		if(currstepnumber < 5)
		{
			board_a5 = '';
		}
		$(this).removeClass('blank').addClass(currcolor).html(currstepnumber++);
		currcolor = (currcolor == 'black' ? 'white':'black');
		currgame += $(this).attr('alt');
		endgame = currgame;
		return true;
	});
	//恢复棋盘。
	board_init();
	if(top.location != self.location){
		top.location = self.location;
	}


	//websocket
;
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
    var _return =  md5(stringfy);
    return _return;
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
            window.setTimeout(that.connect,1000);
        };
        ws.onerror = function(e) {
            console.log(e);
        };
        //ws.onopen();
    }

    //TODO  游客模式，只能看，不能发消息

    // 连接建立时发送WEBSOCKET登录信息
    this.onopen = function ()
    {
        console.log("open!");
        var login_data = {"type":"login","game_id":gameObj.id,'uid':userinfo ?　userinfo.id : 0};
        that.dosend(login_data);
    }

// 服务端发来消息时
    this.onmessage = function (e)
    {
        //console.log("resultData="+e.data);
        var data = JSON.parse(e.data);
        that.agentDistribute(data);
    }
    this.sendMsg = function(_data){
        that.dosend(_data);
    }
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
    }

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
        return;
    }
    //页面渲染代理接口
    this.agentReader = function (data)
    {
        page.render("room",data["type"],data,"websocket");
        return;
    }
    // 服务端ping客户端
    this.actionPing = function(_data){
        that.dosend({type:"pong"});
    }

    // 登录 更新用户列表
    this.actionLogin = function(_data){
        that.agentReader(_data);
    }

    //进入房间之后会接收到自己的client_id 和一些历史消息
    this.actionEnter = function(_data)
    {
        global_current_client_id = _data['client_id'];
        var chat_msg=[],gift_msg=[];
        if(_data!=null && _data['history_msg']!=null&&_data['history_msg'].length>0){
            for(var i in _data['history_msg']){
                that.agentDistribute(_data['history_msg'][i]);
            }
        }
    }

    this.actionClient_list = function(_data){
        that.agentReader(_data);
    }
    this.actionRoom_info = function(_data){
        that.agentReader(_data);
    }
    // 接收发言消息
    this.actionSay = function(_data){
        that.agentReader(_data);
    }
    // 接收送礼消息
    this.actionGift = function(_data){
        that.agentReader(_data);
    }
    //系统公告
    this.actionGlobal_announce = function(_data){
        that.agentReader(_data);
    }
    //房间公告
    this.actionRoom_announce = function(_data){
        that.agentReader(_data);
    }
    this.actionBroadcast = function(_data){
        that.agentReader(_data);
    }
    //彩条
    this.actionGift_banner = function(_data){
        that.agentReader(_data);
    }
    // 用户退出 更新用户列表
    this.actionLogout = function(_data){
        that.agentReader(_data);
        //page.render("room","userList",_data['client_list']);//这里不直接带用户列表数据了，而是额外发一个用户列表消息。
    }
    this.actionNotice = function(_data){
        //系统提示，显示提示文字。
        if(_data.content)
        {
            alertMsg(_data.content);
        }
    }
    this.actionShutdown = function(_data)
    {
        //正常业务结束，服务器端要求关闭并且不再重连
        if(typeof shutdownCallback == 'function')shutdownCallback(_data);
        ws.onclose = function () {
            return false;
        }
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