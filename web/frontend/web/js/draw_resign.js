	$("#draw_button").click(function(){
		if(window.confirm('您要提出和棋请求吗？如果双方都同意和棋，则棋局会立刻结束。'))
		{
			$.getJSON('/site/draw',{id:game_id},function(data){
				alert(data.msg);
				window.location.href = data.redirect;
			});
		}
	});

	$("#resign_button").click(function(){
		if(window.confirm('您确定要认输吗？'))
		{
			$.getJSON('/site/resign',{id:game_id},function(data){
				alert(data.msg);
				window.location.href = data.redirect;
			});
		}
	});