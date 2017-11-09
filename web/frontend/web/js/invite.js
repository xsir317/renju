$("#accept_button").click(function(){
	invite('accept');
});
$("#create_button").click(function(){
	invite('create');
});
$("#reject_button").click(function(){
	if(confirm('您确定要拒绝这个邀请吗？'))
	invite('reject');
});
$("#cancel_button").click(function(){	
	if(confirm('您已经发出了这个邀请，您确认要取消它吗？'))
	{
		invite('cancel');
	}
});

invite = function(act){
	$("input[name='action']").val(act);
	postdata = $("#invite_form").serialize();
	$.getJSON('/invite/invite',postdata,function(data){
		if(data.msg) alert(data.msg);
		if(data.redirect) window.location.href = data.redirect;
	},'json');
}