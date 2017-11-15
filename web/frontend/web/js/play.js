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
			if(gameinit == '')
			{
				$("input[name='move']").val(currgame);
			}
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
			if(gameinit == '')
			{
				$("input[name='move']").val(currgame);
			}
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
	iwzq = function(){
		if(currgame == '')
		{
			alert("盘面无棋子");
		}
		else
		{
			iwzq_str = '';
			currlength = currgame.length;
			for(i=0;i<currlength;i++)
			{
				mychar = currgame.charAt(i);
				if(i%2 == 0)
				{
					y_val = parseInt(mychar,16).toString();
				}
				else
				{
					iwzq_str += String.fromCharCode(parseInt(mychar,16)+'a'.charCodeAt()-1);
					iwzq_str += y_val;
				}
			}
			prompt('请复制下面的iwzq代码',iwzq_str);
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
	for(i=1;i<=15;i++)
	{
		//insert a row
		newrow = $(document.createElement("div"));
		newrow.addClass('row');
		boardobj.append(newrow);
		for(j=1;j<=15;j++)
		{
			//insert a cross point
			newcell = $(document.createElement("div"));
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
	iwzqbtn = $(document.createElement("input"));
	pre.attr('type','button').addClass('button').val('前一手').click(move_pre).appendTo(controlbar);
	nextbtn.attr('type','button').addClass('button').val('后一手').click(move_next).appendTo(controlbar);
	first.attr('type','button').addClass('button').val('第一手').click(board_clean).appendTo(controlbar);
	end.attr('type','button').addClass('button').val('最后一手').click(board_end).appendTo(controlbar);
	init.attr('type','button').addClass('button').val('恢复').click(board_init).appendTo(controlbar);
	iwzqbtn.attr('type','button').addClass('button').val('iwzq').click(iwzq).appendTo(controlbar);
	
	boardobj.find('.row div').click(function(){
		//落子
		if(!$(this).hasClass('blank') && currstepnumber != 5)
		{
			return false;
		}
		if(gameinit == '' && currstepnumber <= 3)//开局前3手
		{
			$(this).removeClass('blank').addClass(currcolor).html(currstepnumber++);
			currcolor = (currcolor == 'black' ? 'white':'black');
			currgame += $(this).attr('alt');
			endgame = currgame;
			$("input[name='move']").val($("input[name='move']").val()+$(this).attr('alt'));
			return true;
		}
		if(currstepnumber == 5)//
		{
			if(turn == 'black')//黑方落打点
			{
				if($(this).hasClass('blank'))
				{
					if(a5_numbers == $(".a5stone").length)
					{
						alert('您已经下满了'+a5_numbers+'个打点了。');
						return false;
					}
					$(this).removeClass('blank').addClass('black a5stone').html('▲');
					board_a5 = board_a5 + '' + $(this).attr('alt');
					$("input[name='a5pos']").val(board_a5);
					return true;
				}
				else if($(this).hasClass('a5stone'))
				{
					$(this).removeClass('black a5stone').addClass('blank').html('');
					$("input[name='a5pos']").val('');
					$(".a5stone").each(function(){
						$("input[name='a5pos']").val($("input[name='a5pos']").val() + '' + $(this).attr('alt'));
					});
					board_a5 = $("input[name='a5pos']").val();
					return true;
				}
				else 
				{
					return false;
				}
			}
			else //白方选择打点，落第六手
			{
				if(!$(this).hasClass('a5stone'))
					return false;
				hide_a5();
				$(this).removeClass('blank').addClass(currcolor).html(currstepnumber++);
				currcolor = 'white';
				currgame += $(this).attr('alt');
				endgame = currgame;
				$("input[name='a5pos']").val($(this).attr('alt'));
				return true;
			}
		}
		if(currgame != gameinit && currstepnumber != 6)
		{	
			move_next();
			return true;
		}
		$(this).removeClass('blank').addClass(currcolor).html(currstepnumber++);
		currcolor = (currcolor == 'black' ? 'white':'black');
		currgame += $(this).attr('alt');
		endgame = currgame;
		$("input[name='move']").val($(this).attr('alt'));
		return true;
	});
	chkupd = function(){
		$.getJSON("/site/gameupd",{id:game_id,_rand:Math.random()},function(data){
			if(data.msg)
			{
				if(data.msg != game_upd)
				{
					window.location.reload();
				}
			}
		});
	}
	setInterval(chkupd,5000);
	//恢复棋盘。
	board_init();
	if(top.location != self.location){
		top.location = self.location;
	}