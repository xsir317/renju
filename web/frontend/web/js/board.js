/**
 * @author xsir317@gmail.com
 * @license http://creativecommons.org/licenses/by-sa/3.0/deed.zh
 */
var boardObj = function()
{
    //棋盘的DOM对象，基本上棋子、棋盘逻辑都在这里面。
    var board = $("#board_main");

    var _obj = this;

    //整个游戏的数据结构，包括对局进程、状态、双方等等。会通过页面变量或者Websocket推过来。
    _obj.gameData = {};

    //字符串，当前局面记录。
    _obj.currgame = '';

    //字符串，记录终局状态。
    _obj.endgame = '';

    // 当前颜色，在初始化时会初始化为黑色
    _obj.curr_color = 'black';

    //当前手数，会被初始化为1
    _obj.curr_step = 1;

    //以下两个变量表示“是否是我参与的游戏”、“当前是否轮到我下棋”
    _obj.is_my_game = false;
    _obj.is_my_turn = false;

    //load 一个游戏数据。
    _obj.load = function( game_data ){
        //为了播放声音，这里对比一下旧盘面和新load的盘面，决定是否播放一次声音
        var play_sound = (_obj.currgame != game_data.game_record);
        _obj.gameData = game_data;
        _obj.show_origin();
        if(play_sound)
        {
            pager.play_sound('Move');
        }
    };

    //setInterval就存在这里，初始化的时候clear一下
    //_obj.timer_handler = 0;

    /**
     * 用于展示时间。 如果对局正在进行，还会负责进行倒计时的显示。
     * 这是一个闭包结构。timer_handler在闭包里。
     */
    _obj.timer = (function(){
        var timer_handler = 0;

        return (function(){
            //首先，获取当前时间，当前游戏的双方剩余时间
            var render_time = function(seconds,player)
            {
                seconds = (seconds > 0) ? seconds : 0;
                var hours = parseInt(seconds/3600).toString();
                if(hours.length == 1) {hours = '0' + hours}
                var minutes = parseInt( (seconds%3600) /60).toString();
                if(minutes.length == 1) {minutes = '0' + minutes}
                var seconds_display = parseInt(seconds % 60).toString();
                if(seconds_display.length == 1) {seconds_display = '0' + seconds_display}

                var display_obj = player ? $("#black_time_display") : $("#white_time_display");
                display_obj.html(hours + ':' + minutes + ':' + seconds_display);
            };

            // 记录当前时间。
            var timer_start = new Date().getTime();
            //先render双方时间显示
            render_time(_obj.gameData.black_time,1);
            render_time(_obj.gameData.white_time,0);
            //如果对局进行中，那么 setInterval 每一秒钟，计算开始时间到当前过了多久；用行棋方时间减去已用时间，再次render。
            //如果对局正在进行中
            if(timer_handler)
            {
                debug_log("we do cleared " + timer_handler + ". we will set up new Interval if needed.");
                clearInterval(timer_handler);
            }
            if(_obj.gameData.status == 1)
            {
                timer_handler = setInterval(function(){
                    var current = new Date().getTime();

                    var delta_time = current - timer_start;
                    var time_left = (_obj.gameData.turn ? _obj.gameData.black_time : _obj.gameData.white_time) - parseInt(delta_time/1000);
                    render_time(time_left,_obj.gameData.turn);
                    if(time_left <= 0)
                    {
                        _obj.notice_timeout();
                    }
                },1000);
                debug_log("setInterval " + timer_handler);
            }
        });
    })();

    /**
     * @description 在指定位置放置一枚棋子。当操作者是行棋一方时，会转交给make_move来处理。
     * 当操作者是玩家之一时，不可以拿棋盘来拆棋，只能按照对局记录前进后退。
     * @param  {string} coordinate 传入坐标。
     * @param  {boolean} play_sound 是否播放声音
     * @returns {boolean}
     */
    _obj.place_stone = function(coordinate,play_sound){
        var target_cell = board.find('.'+coordinate);
        if(!target_cell.hasClass('blank'))
        {
            return false;
        }

        //这里的逻辑解释一下： 如果是轮到我下，而且是完全展示棋局的状态，那么就是“落子状态”。
        //如果是落子状态，就可以不按照之前的记录落下新的一个棋子。
        //如果不是落子状态，则对对局双方作出限制：只能按照之前的记录去落子，不能拿这个棋盘来拆棋。
        var playing = (_obj.is_my_turn && _obj.currgame == _obj.gameData.game_record && _obj.gameData.waiting_for_a5_numbers == 0);
        if(_obj.is_my_game && !playing && _obj.gameData.status == 1)
        {
            if(coordinate != _obj.endgame.substr(_obj.currgame.length,2))
            {
                return false;
            }
        }
        //是否显示五手N打点： 第四手展示在棋盘上，并且前4手的确是符合记录的时候，显示打点。
        if(_obj.curr_step == 4 && _obj.endgame == _obj.gameData.game_record)
        {
            _obj.show_a5();
        }
        else if(_obj.curr_step == 5)
        {
            _obj.hide_a5();
        }
        target_cell.removeClass('blank').addClass(_obj.curr_color).html(_obj.curr_step ++);
        _obj.curr_color = (_obj.curr_color == 'black' ? 'white':'black');
        _obj.currgame += coordinate;
        if(_obj.currgame != _obj.endgame.substr(0,_obj.currgame.length))
        {
            _obj.endgame = _obj.currgame;
        }
        if(play_sound)
        {
            pager.play_sound('Move');
        }

        //最后，如果是落子状态，通知一下服务器。
        if(playing)
        {
            return _obj.make_move(coordinate);
        }

        return true;
    };

    /**
     * @description
     * @param  {string} coordinate 传入坐标。
     * @returns {boolean}
     */
    _obj.make_move = function(coordinate){
        if(!_obj.is_my_turn)
        {
            return false;
        }
        $.post(
            "/games/games/play",
            {
                coordinate:coordinate,
                "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
                game_id:gameObj.id
            },
            function(_data){
                if(_data.code != 200)
                {
                    layer.alert(_data.msg);
                    _obj.show_origin();
                }
            },
            "json"
        );
        return true;
    };

    /**
     * 右键和回退按钮的事件，往回退一个棋子。并不产生任何Ajax，这不是悔棋操作。
     * @returns {boolean}
     */
    _obj.move_pre = function(){
        if(_obj.currgame)
        {
            var last_move = _obj.currgame.substr(_obj.currgame.length-2,2);
            //这个棋子拿起来。。。
            var target_cell = board.find('.'+last_move);
            target_cell.removeClass('black white').addClass('blank').html('');
            _obj.curr_step --;
            _obj.curr_color = (_obj.curr_color == 'black' ? 'white':'black');
            _obj.currgame = _obj.currgame.substr(0,_obj.currgame.length-2);
            //打点显示
            if(_obj.curr_step == 5 && _obj.endgame == _obj.gameData.game_record)
            {
                _obj.show_a5();
            }
            else if(_obj.curr_step == 4)
            {
                _obj.hide_a5();
            }
            return true;
        }
        return false;
    };

    /**
     * 根据endgame，一步一步走下去，把整个棋局展示出来。
     * @returns {boolean}
     */
    _obj.move_next = function(){
        if(_obj.currgame != _obj.endgame)
        {
            var nextstep = _obj.endgame.substr(_obj.currgame.length,2);
            _obj.place_stone(nextstep);
            return true;
        }
        return false;
    };

    _obj.notice_timeout = function(){
        $.post("/games/games/timeout",{
            "_csrf-frontend":$("meta[name=csrf-token]").attr("content"),
            game_id:gameObj.id
        });
    };
    /**
     * 回退到空棋盘状态。
     */
    _obj.board_clean = function(){
        while (_obj.move_pre()) {}
    };

    /**
     * 根据目前的棋局记录一路Next到局面结束的状态。
     */
    _obj.board_end = function(){
        while(_obj.move_next()) {}
    };

    /**
     * 根据gameData 初始化棋盘的文字信息和棋盘Game信息
     */
    _obj.show_origin = function(){
        _obj.render_game_info();

        _obj.board_clean();
        _obj.endgame = _obj.gameData.game_record;
        _obj.board_end();
    };

    /**
     * 展示除了棋盘之外的其他文字信息和对局相关的提示信息。
     * 也负责计算轮到谁落子。
     */
    _obj.render_game_info = function(){
        //计算当前是否是“我”落子的回合。
        _obj.is_my_game = false;
        _obj.is_my_turn = false;
        
        if(userinfo != null)
        {
            _obj.is_my_game = (userinfo.id == _obj.gameData.black_id || userinfo.id == _obj.gameData.white_id);
            _obj.is_my_turn = (_obj.gameData.whom_to_play == userinfo.id);
        }
        $(".black_name>ins").html(_obj.gameData.bplayer.nickname);
        $(".white_name>ins").html(_obj.gameData.wplayer.nickname);
        $(".current_player_name>ins").html(_obj.gameData.turn ? _obj.gameData.bplayer.nickname : _obj.gameData.wplayer.nickname);
        $(".a5_numbers>ins").html(_obj.gameData.a5_numbers);
        $(".is_swap>ins").html(_obj.gameData.swap ? "是":"否");
        $(".game_result>ins>strong").html(result_defines[_obj.gameData.status]);
        if(_obj.is_my_turn)
        {
            _obj.playing_tips();
        }
        else
        {
            $(".turn_to_play_tips").hide();
            $(".swap_button").hide();
        }

        if(_obj.is_my_game && _obj.gameData.status == 1)
        {
            $(".draw_button,.resign_button").show();
        }
        else
        {
            $(".draw_button,.resign_button").hide();
        }

        if(_obj.is_my_game && _obj.gameData.status == 1 && _obj.gameData.offer_draw >0 && _obj.gameData.offer_draw != userinfo.id)
        {
            $(".offer_draw_tips").show();
        }
        else
        {
            $(".offer_draw_tips").hide();
        }
        //计时
        _obj.timer();
    };

    _obj.playing_tips = function(){
        if(!_obj.is_my_turn)
        {
            return false;
        }
        var stones = _obj.gameData.game_record.length / 2;
        var tips = "轮到您下第" + (stones + 1) + "手";
        //按照不同规则去写提示。
        var show_swap = false;
        switch (_obj.gameData.rule)
        {
            case 'RIF':
            case 'Yamaguchi':
                if(stones < 3)
                {
                    tips = "请下前三手";
                }
                else if (stones == 3 && _obj.gameData.a5_numbers > 0 && _obj.gameData.swap == 0)
                {
                    tips += "或选择交换";
                    show_swap = true;
                }
                else if(stones == 4 && _obj.gameData.a5_numbers == (_obj.gameData.a5_pos.length/2))//打点摆完了，等白棋选。
                {
                    tips = "请选择一个黑5作为第五手";
                }
                else if(stones == 4 && _obj.gameData.a5_numbers > (_obj.gameData.a5_pos.length/2))//打点没摆完
                {
                    tips = "第五手请选择" + _obj.gameData.a5_numbers + "个点";
                }
                break;
            case 'Soosyrv8'://索索夫规则描述 三手可交换，第四手时声明打点数量，可交换。其余略。
                if(stones < 3)
                {
                    tips = "请下前三手";
                }
                else if (stones == 3 && _obj.gameData.swap == 0)
                {
                    tips += "或选择交换";
                    show_swap = true;
                }
                else if(stones == 4 && _obj.gameData.a5_numbers > 0 )
                {
                    if(_obj.gameData.a5_numbers > (_obj.gameData.a5_pos.length/2))
                    {
                        tips = "第五手请选择" + _obj.gameData.a5_numbers + "个点";
                    }
                    if(_obj.gameData.a5_pos == '' && _obj.gameData.soosyrv_swap == 0)
                    {
                        tips += "或选择交换";
                        show_swap = true;
                    }

                    if(_obj.gameData.a5_numbers == (_obj.gameData.a5_pos.length/2))
                    {
                        tips = "请选择一个黑5作为第五手";
                    }
                }
                break;
        }

        if(_obj.gameData.waiting_for_a5_numbers)
        {
            tips = "请输入打点数量";
            pager.ask_for_a5();
        }
        $(".turn_to_play_tips").text(tips).show();
        if(show_swap)
        {
            $(".swap_button").show();
        }
        else
        {
            $(".swap_button").hide();
        }
    };

    /**
     * 显示和隐藏五手打点
     * @returns {boolean}
     */
    _obj.show_a5 = function(){
        if(_obj.gameData.a5_pos == '')
            return false;
        var a5_points = '.' + _obj.gameData.a5_pos.substr(0,2);
        for(var sub = 2;sub<_obj.gameData.a5_pos.length;sub += 2)
        {
            a5_points += ',.';
            a5_points += _obj.gameData.a5_pos.substr(sub,2);
        }
        $(a5_points).addClass('black a5stone').html('▲');
    };
    _obj.hide_a5 = function(){
        $(".a5stone").removeClass('black a5stone').html('');
    };
    _obj.show_rule = function () {
        var rule_description = {
            Yamaguchi:"山口规则：<br /><p>1.先手方下3个棋子（黑1，白2，黑3），同时指定第五手的打点数量N；</p><p>2.后手方可以选择执白或者执黑。</p> <p>3.白方下第四手；</p> <p>4.黑方按照约定的五手打点数量放上N个棋子，白方指定其中的一个为实战的第五手，然后白方下第六手；</p> <p>5.双方轮流行棋。</p> <p>注意：先手方的开局仅限26种开局。</p>",
            RIF:"RIF规则：<br /><p>1.先手方下3个棋子（黑1，白2，黑3）；</p> <p>2.后手方可以选择执白或者执黑。</p> <p>3.白方下第四手；</p> <p>4.黑方放上2个棋子，白方指定其中的一个为实战的第五手，然后白方下第六手；</p> <p>5.双方轮流行棋。</p> <p>注意：先手方的开局仅限26种开局。</p>",
            Soosyrv8:"索索夫8规则：<br /><p>1.先手方下3个棋子（黑1，白2，黑3，26种开局）；</p> <p>2.后手方可以选择执白或者执黑。</p> <p>3.白方下第四手，同时指定第五手的打点数量N（N<=8）；</p> <p>4.黑方可以选择交换，或者按照约定的五手打点数量放上N个棋子，白方指定其中的一个为实战的第五手，然后白方下第六手；</p> <p>5.双方轮流行棋。</p>",
            Renju:"有禁手规则：<br />黑白双方轮流落子，先5为胜，黑方不得双3，双4，长连，白方长连视为五连。",
            Gomoku:"无禁手规则：<br />双方轮流行棋。黑白双方均无限制，先5为胜，超过6个不产生胜负。"
        };
        if(typeof rule_description[gameObj.rule] != "undefined")
        {
            pager.show_msg(rule_description[gameObj.rule]);
        }
    };

    /**
     * 画棋盘和按钮。绑定右键事件。
     * 整个页面载入的时候会执行一次。仅此一次。
     */
    _obj.init_board = function(){
        _obj.currgame = '';
        _obj.curr_color = 'black';
        _obj.curr_step = 1;
        board.html('');

        board.mousedown(function(e){
            if(e.which == 3)
            {
                _obj.move_pre();
                return false;
            }
        });
        board.bind("contextmenu", function() { return false; });
        for(var i=1;i<=15;i++)
        {
            //insert a row
            var newrow = $(document.createElement("div"));
            newrow.addClass('row');
            for(var j=1;j<=15;j++)
            {
                //insert a cross point
                var newcell = $(document.createElement("div"));
                newcell.addClass(i.toString(16) + j.toString(16));
                newcell.attr('alt',i.toString(16) + j.toString(16));
                newcell.addClass('blank');
                newrow.append(newcell);
            }
            board.append(newrow);
        }
        board.find('.row div').click(function(){
            _obj.place_stone($(this).attr('alt'),true);
            return true;
        });
        //生成控制按钮
        var controlbar = $(document.createElement("div"));
        controlbar.addClass('controlbar');
        board.after(controlbar);
        //按钮
        $(document.createElement("button")).addClass('button').text('前一手')  .click(_obj.move_pre   ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('后一手')  .click(_obj.move_next  ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('第一手')  .click(_obj.board_clean).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('最后一手').click(_obj.board_end  ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('恢复')    .click(_obj.show_origin).appendTo(controlbar);
    };
};

//1.new出对象
var board = new boardObj();

$(document).ready(function(){
//页面初始化时对棋盘的操作：
//2.调用其init方法
    board.init_board();
//3.把web页输出的数据结构load进来。
    board.load(gameObj);
    board.show_rule();
});
