$("#sign_button").click(function() {
    if(!window.confirm('您正在报名参加'+$("#tour_title").html()+"，请确认您已经了解了赛制、规则和时限，\n并承诺认真完成比赛。"))
        return false;
    $.getJSON('/tournament/join', {id: tournament_id}, function(data) {
        if(data.status)
        {
            alert('报名成功');
            window.location.reload();
        }
        else
        {
            if(data.msg == 'need_login')
                window.location.href = data.redirect;
            else
                alert(data.msg);
        }
    });
});
$("#unsign_button").click(function() {
    $.getJSON('/tournament/quit', {id: tournament_id}, function(data) {
        if(data.status)
        {
            alert('取消报名成功');
            window.location.reload();
        }
        else
        {
            if(data.msg == 'need_login')
                window.location.href = data.redirect;
            else
                alert(data.msg);
        }
    });
});