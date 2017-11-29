var pager = {
    /**
     *
     * @param _data 可能是指定用户的UserID和名称，发起一个邀请
     *  可能是一个邀请数据结构，用于接受一个邀请。
     */
    invite:function(_data){
        debug_log(_data);
        if(!userinfo)
        {
            layer.alert("请先登录");
            return false;
        }
        var invite_form = $("#invite_form");
        var invite_btn = $("#invite_submit_button");
        invite_btn.removeAttr("disabled");
        if(typeof _data.user_id != "undefined")//click on someone's invite button
        {
            invite_form.find("input[name=to_user]").val(_data.user_id);
            invite_form.find("input[name=id]").val(0);
            invite_form.find("input[name=use_black]").eq(0).prop("checked",true);
            invite_form.find(".opponent_name").text(_data.nickname);
            invite_form.find("input[name=hours]").val(0);
            invite_form.find("input[name=minutes]").val(30);
            invite_form.find("select[name=rule]").val("Yamaguchi");
            invite_form.find("input[name=comment]").val("");
            invite_form.find("input[name=free_open]").prop("checked",false);
            invite_btn.val("发出邀请");
        }
        else// 被人邀请，弹出被邀请的窗口
        {
            invite_form.find("input[name=to_user]").val(0);
            invite_form.find("input[name=id]").val(_data.id);
            //use black
            if(_data.black_id == _data.to)
            {
                invite_form.find("input[name=use_black]").eq(0).prop("checked",true);
                invite_form.find("input[name=use_black]").eq(1).prop("checked",false);
            }
            else
            {
                invite_form.find("input[name=use_black]").eq(0).prop("checked",false);
                invite_form.find("input[name=use_black]").eq(1).prop("checked",true);
            }
            invite_form.find(".opponent_name").text(_data.from_user.nickname);
            invite_form.find("input[name=hours]").val(parseInt(_data.totaltime / 3600));
            invite_form.find("input[name=minutes]").val(parseInt(_data.totaltime / 60) % 60);
            invite_form.find("select[name=rule]").val(_data.rule);
            invite_form.find("input[name=comment]").val(_data.message);
            invite_form.find("input[name=free_open]").prop("checked",(_data.free_opening == "1"));
            invite_btn.val("接受邀请");
        }
        layer.open({type:1,content:$("#invite_box"),title:"邀请对局"});
    },
    ask_for_a5:function(){
        layer.prompt({
            formType: 0,
            value: '2',
            title: '请输入打点数量'
        }, function(value, index, elem){
            $.post("/games/games/a5_number",{
                number:value,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
                game_id:gameObj.id
            },function(_data){
                if(_data.code == 200)
                {
                    layer.close(index);
                }
                else
                {
                    alert(_data.msg);
                    board.show_origin();
                }
            },"json");
        });
    },
    show_msg: function(content){
        if($("#chat_content").find("li").length > 150)
        {
            $("#chat_content").empty();
        }
        var new_li = $(document.createElement("li"));
        new_li.html(content).appendTo($("#chat_content"));
        //滚动。
        $("#chat_content_list").scrollTop($("#chat_content_list")[0].scrollHeight - $("#chat_content_list").height());
    },
    show_game_list: function(games){
        $("#hall_games>ul").find("li:not(:first)").remove();
        for(var i in games)
        {
            var new_li = $(document.createElement("li"));
            $(document.createElement('span')).addClass("layui-col-xs2").text(games[i].id).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").text(games[i].black.nickname).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").text(games[i].white.nickname).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").text(games[i].game_record.length/2 + 1).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").text(result_defines[games[i].status]).appendTo(new_li);
            $(document.createElement('span')).addClass("layui-col-xs2").html("<a href='/game/"+games[i].id+"'>进入</a>").appendTo(new_li);
            new_li.appendTo($("#hall_games>ul"));
        }
    }
};

$(document).ready(function () {
    //聊天
    $("#chat_operate_area .send").click(function(){
        var content = $("#msg").val().trim();
        if(!content)
        {
            alert("请勿发送空信息");
        }
        $.post(
            "/games/chat/say",
            {
                content:content,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
                game_id: typeof gameObj == "undefined" ? "HALL" : gameObj.id
            },
            function(_data){
                if(_data.code != 200)
                {
                    alert(_data.msg);
                }
                $("#msg").val("");
            },
            "json"
        );
    });

    //邀请游戏
    $("#invite_submit_button").click(function () {
        $("#invite_submit_button").attr("disabled","disabled");
        $.post("/games/invite/create",$("#invite_form").serialize(),function(_return){
            alert(_return.msg);
            $("#invite_submit_button").removeAttr("disabled");
            layer.closeAll();
            $("#invite_box").hide();
        },"json");
    });

    $("#draw_button").click(function(){
        if(window.confirm('您要提出和棋请求吗？'))
        {
            $.post('/games/games/offer_draw',{
                game_id:gameObj.id,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
            },function(data){
                layer.alert(data.msg);
            },"json");
        }
    });

    $("#swap_button").click(function(){
        $.post('/games/games/swap',{
            game_id:gameObj.id,
            "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
        },function(data){
            if(data.code != 200)
            {
                layer.alert(data.msg);
            }
        },"json");
    });

    $("#resign_button").click(function(){
        if(window.confirm('您确定要认输吗？'))
        {
            $.post('/games/games/resign',{
                game_id:gameObj.id,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content")
            },function(data){
                if(data.code != 200)
                {
                    layer.alert(data.msg);
                }
            },"json");
        }
    });

    if(typeof game_list != "undefined")
    {
        pager.show_game_list(game_list);
    }
});