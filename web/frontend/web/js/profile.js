$("#profile_submit").click(function(){
	$.getJSON($("#profile_form").attr("action"),$("#profile_form").serialize(),function(data){
		alert(data.msg);
		if(data.status)
		{
			window.location.href = '/home.html';
		}
	});
});