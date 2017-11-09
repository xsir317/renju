if(window.confirm('这盘对局还没有开始，您想加入对局吗？'))
{
	if(window.confirm('请确认您将加入这盘对局！'))
	{
		$.getJSON('/site/join',{id:game_id},function(data){
			if(data.status)
			{
				window.location.reload();
			}
			else
			{
				alert(data.msg);
				window.location.href = data.redirect;
			}
		});
	}
}