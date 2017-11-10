/*
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};*/
$("#reg2").hover(function(){$("#cont_login").hide();$("#cont_reg").show();$("#email").focus();});
$("#login2").hover(function(){$("#cont_reg").hide();$("#cont_login").show();$("#loginfocus").focus();});
$("#loginsubmit,#regsubmit").click(function(){
	var button = $(this);
	var buttontext = button.val();
	button.val('请稍候...');
	var myform = button.parentsUntil("form").parent();
	var submit_data = {};
	var _serialized = myform.serializeArray();
	for(var _i in _serialized)
    {
        submit_data[_serialized[_i]['name']] = _serialized[_i]['value'];
    }
    submit_data['_csrf-frontend'] = $("meta[name=csrf-token]").attr("content");
	$.post(myform.attr("action"),submit_data,function(_data){
		button.val(buttontext);
		if(_data.code == 200)
		{
			//$.cookie('username',$("#loginfocus").val(),{expires:30,path:'/'});
			window.location.href = _data.data.redirect;
		}
		else
		{
			alert(_data.msg);
		}
	},'json');
});
//$("#loginfocus").focus().val($.cookie('username'));

