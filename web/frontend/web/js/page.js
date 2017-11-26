var pager = {
    /**
     *
     * @param _data 可能是指定用户的UserID和名称，发起一个邀请
     *  可能是一个邀请数据结构，用于接受一个邀请。
     */
    invite:function(_data){
        if(!userinfo)
        {
            layer.alert("请先登录");
            return false;
        }
        $("#invite_submit_button").removeAttr("disabled");
        if(typeof _data.user_id != "undefined")//click on someone's invite button
        {
            $("#invite_form input[name=to_user]").val(_data.user_id);
            $("#invite_form input[name=id]").val(0);
            $("#invite_form input[name=use_black]").eq(0).prop("checked",true);
            $("#invite_form .opponent_name").text(_data.nickname);
            $("#invite_form input[name=hours]").val(0);
            $("#invite_form input[name=minutes]").val(30);
            $("#invite_form select[name=rule]").val("Yamaguchi");
            $("#invite_form input[name=comment]").val("");
            $("#invite_submit_button").val("发出邀请");
        }
        else// 被人邀请，弹出被邀请的窗口
        {
            $("#invite_form input[name=to_user]").val(0);
            $("#invite_form input[name=id]").val(_data.id);
            //use black
            if(_data.black_id == _data.to)
            {
                $("#invite_form input[name=use_black]").eq(0).prop("checked",true);
                $("#invite_form input[name=use_black]").eq(1).prop("checked",false);
            }
            else
            {
                $("#invite_form input[name=use_black]").eq(0).prop("checked",false);
                $("#invite_form input[name=use_black]").eq(1).prop("checked",true);
            }
            $("#invite_form .opponent_name").text(_data.from_user.nickname);
            $("#invite_form input[name=hours]").val(parseInt(_data.totaltime / 3600));
            $("#invite_form input[name=minutes]").val(parseInt(_data.totaltime / 60) % 60);
            $("#invite_form select[name=rule]").val(_data.rule);
            $("#invite_form select[name=comment]").val(_data.message);
            $("#invite_form input[name=free_open]").prop("checked",(_data.free_opening == "1"));
            $("#invite_submit_button").val("接受邀请");
        }
        layer.open({type:1,content:$("#invite_box"),title:"邀请对局"});
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
        },"json");
    });
});