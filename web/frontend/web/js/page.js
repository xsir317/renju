var pager = {
    /**
     *
     * @param _data 可能是指定用户的UserID和名称，发起一个邀请
     *  可能是一个邀请数据结构，用于接受一个邀请。
     */
    invite:function(_data){

    }
};

$(document).ready(function () {
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
});