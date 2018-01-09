/**
 * @author xsir317@gmail.com
 * @license http://creativecommons.org/licenses/by-sa/3.0/deed.zh
 */
let board = null;
let boardObj = function()
{
    //棋盘的DOM对象，基本上棋子、棋盘逻辑都在这里面。
    let board = $("#board_main");

    let _obj = this;

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
        let play_sound = (_obj.currgame != game_data.game_record);
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
        let timer_handler = 0;

        return (function(){
            //首先，获取当前时间，当前游戏的双方剩余时间
            let render_time = function(seconds,player)
            {
                seconds = (seconds > 0) ? seconds : 0;
                let hours = parseInt(seconds/3600).toString();
                if(hours.length == 1) {hours = '0' + hours}
                let minutes = parseInt( (seconds%3600) /60).toString();
                if(minutes.length == 1) {minutes = '0' + minutes}
                let seconds_display = parseInt(seconds % 60).toString();
                if(seconds_display.length == 1) {seconds_display = '0' + seconds_display}

                let display_obj = player ? $("#black_time_display") : $("#white_time_display");
                display_obj.html(hours + ':' + minutes + ':' + seconds_display);
            };

            // 记录当前时间。
            let timer_start = new Date().getTime();
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
                    let current = new Date().getTime();

                    let delta_time = current - timer_start;
                    let time_left = (_obj.gameData.turn ? _obj.gameData.black_time : _obj.gameData.white_time) - parseInt(delta_time/1000);
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

    //自动切换模式。
    _obj.switch_mode = (function(){
        let _mode = 'game';// game or analyze
        return function(mode,do_switch){
            if(mode == _mode)
            {
                return true;
            }
            if(typeof do_switch == 'boolean' && !do_switch)
            {
                return false;
            }
            _mode = mode;
            switch(mode)
            {
                case 'game':
                    //board.removeClass("mode_analyze").addClass("mode_game");
                    board.css("background-image","url(/images/board.png)");
                    break;
                case 'analyze':
                    pager.show_msg(pager.t('Switched to Analyze mode, you can use the board freely.'));
                    board.css("background-image","url(/images/board-grey.png)");
                    //board.removeClass("mode_game").addClass("mode_analyze");
                    break;
                default:
                    break;
            }
        };
    })();

    /**
     * @description 在指定位置放置一枚棋子。当操作者是行棋一方时，会转交给make_move来处理。
     * 当操作者是玩家之一时，不可以拿棋盘来拆棋，只能按照对局记录前进后退。
     * @param  {string} coordinate 传入坐标。
     * @param  {boolean} play_sound 是否播放声音
     * @returns {boolean}
     */
    _obj.place_stone = function(coordinate,play_sound){
        let target_cell = board.find('.'+coordinate);
        if(!target_cell.hasClass('blank'))
        {
            return false;
        }

        //这里的逻辑解释一下： 如果是轮到我下，而且是完全展示棋局的状态，那么就是“落子状态”。
        //如果是落子状态，就可以不按照之前的记录落下新的一个棋子。
        //如果不是落子状态，则对对局双方作出限制：只能按照之前的记录去落子，不能拿这个棋盘来拆棋。
        let playing = (_obj.is_my_turn && _obj.currgame == _obj.gameData.game_record && !_obj.gameData.waiting_for_a5_number);
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
            //在改变了endgame时，如果不是playing ,则都进入研究模式。
            if(!playing)
            {
                _obj.switch_mode('analyze');
            }
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

    _obj.show_analyze = function(board_str){
        //不允许对弈棋手使用此方法。
        if(_obj.is_my_game && _obj.gameData.status == 1)
        {
            return false;
        }
        _obj.switch_mode('analyze');
        _obj.board_clean();
        _obj.endgame = board_str;
        _obj.board_end();
    };

    _obj.get_current_board = function () {
        return _obj.currgame;
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
            "/games/play/play",
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
            let last_move = _obj.currgame.substr(_obj.currgame.length-2,2);
            //这个棋子拿起来。。。
            let target_cell = board.find('.'+last_move);
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
            let nextstep = _obj.endgame.substr(_obj.currgame.length,2);
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

        _obj.switch_mode('game');
        _obj.board_clean();
        _obj.endgame = _obj.gameData.game_record;
        _obj.board_end();
    };

    /**
     * 展示除了棋盘之外的其他文字信息和对局相关的提示信息。
     * 也负责计算轮到谁落子。
     */
    _obj.render_game_info = (function(){
        let check_game_timer = 0;
        return function(){
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
            $(".is_swap>ins").html(_obj.gameData.swap ? pager.t('Yes'):pager.t('No'));
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
            //undo btn
            if(_obj.is_my_game && _obj.gameData.status == 1 && _obj.gameData.allow_undo)
            {
                $(".undo_button").show();
            }
            else
            {
                $(".undo_button").hide();
            }
            //undo logs
            if(_obj.gameData.undo_log.length > 0)
            {
                $(".undo_records>select").find("option:not(:first)").remove();
                for(let i in _obj.gameData.undo_log)
                {
                    $("<option>").text(
                        _obj.gameData.undo_log[i].user.nickname
                        + ' ' + (_obj.gameData.undo_log[i].current_board.length/2)
                        + ' >> ' +  _obj.gameData.undo_log[i].to_number
                    ).val(_obj.gameData.undo_log[i].current_board).appendTo($(".undo_records>select"));
                }
                $(".undo_records").show();
            }
            else
            {
                $(".undo_records").hide();
            }

            if(_obj.is_my_game && _obj.gameData.status == 1 && _obj.gameData.offer_draw >0 && _obj.gameData.offer_draw != userinfo.id)
            {
                $(".offer_draw_tips").show();
            }
            else
            {
                $(".offer_draw_tips").hide();
            }
            //悔棋
            if(_obj.gameData.undo && _obj.is_my_game && _obj.gameData.status == 1 && _obj.gameData.undo.uid != userinfo.id)
            {
                pager.show_undo(_obj.gameData.undo);
            }

            if(check_game_timer)
            {
                clearInterval(check_game_timer);
            }
            //仅在我是对局者，但当前不轮到我落子的时候，每隔一段时间进行一次检查。这是为了防止Websocket通知失败时，对局者等待导致超时。
            if(_obj.is_my_game && !_obj.is_my_turn && _obj.gameData.status == 1)
            {
                check_game_timer = setInterval(function(){
                    $.getJSON("/games/games/info",{id:_obj.gameData.id,_time:new Date().getTime()},function (_data) {
                        _obj.load(_data.data.game);
                    });
                },15 * 1000);
            }
            //计时
            _obj.timer();
        };
    })();

    _obj.playing_tips = function(){
        if(!_obj.is_my_turn)
        {
            return false;
        }
        let stones = _obj.gameData.game_record.length / 2;
        let tips = pager.t("Your turn to play") + (stones + 1) + pager.t("th move");
        //按照不同规则去写提示。
        let show_swap = false;
        switch (_obj.gameData.rule)
        {
            case 'RIF':
            case 'Yamaguchi':
                if(stones < 3)
                {
                    tips = pager.t('Please play the first 3 moves.');
                }
                else if (stones == 3 && _obj.gameData.a5_numbers > 0 && _obj.gameData.swap == 0)
                {
                    tips += pager.t(",Or swap");
                    show_swap = true;
                }
                else if(stones == 4 && _obj.gameData.a5_numbers == (_obj.gameData.a5_pos.length/2))//打点摆完了，等白棋选。
                {
                    tips = pager.t('Please choose one 5th point as the 5th move.');
                }
                else if(stones == 4 && _obj.gameData.a5_numbers > (_obj.gameData.a5_pos.length/2))//打点没摆完
                {
                    tips = pager.t('Please choose ') + _obj.gameData.a5_numbers + pager.t('points as 5th move');
                }
                break;
            case 'Soosyrv8'://索索夫规则描述 三手可交换，第四手时声明打点数量，可交换。其余略。
                if(stones < 3)
                {
                    tips = pager.t('Please play the first 3 moves.');
                }
                else if (stones == 3 && _obj.gameData.swap == 0)
                {
                    tips += pager.t(",Or swap");
                    show_swap = true;
                }
                else if(stones == 4 && _obj.gameData.a5_numbers > 0 )
                {
                    if(_obj.gameData.a5_numbers > (_obj.gameData.a5_pos.length/2))
                    {
                        tips = pager.t('Please choose ') + _obj.gameData.a5_numbers + pager.t('points as 5th move');
                    }
                    if(_obj.gameData.a5_pos == '' && _obj.gameData.soosyrv_swap == 0)
                    {
                        tips += pager.t(",Or swap");
                        show_swap = true;
                    }

                    if(_obj.gameData.a5_numbers == (_obj.gameData.a5_pos.length/2))
                    {
                        tips = pager.t('Please choose one 5th point as the 5th move.');
                    }
                }
                break;
        }

        if(_obj.gameData.waiting_for_a5_number)
        {
            tips = pager.t("How many 5th would you offer");
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
        let a5_points = '.' + _obj.gameData.a5_pos.substr(0,2);
        for(let sub = 2;sub<_obj.gameData.a5_pos.length;sub += 2)
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
        const rule_description = { // 这里不影响游戏的先不翻译了。。。
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
        for(let i=1;i<=15;i++)
        {
            //insert a row
            let newrow = $(document.createElement("div"));
            newrow.addClass('row');
            for(let j=1;j<=15;j++)
            {
                //insert a cross point
                let newcell = $(document.createElement("div"));
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
        let controlbar = $(document.createElement("div"));
        controlbar.addClass('controlbar');
        board.after(controlbar);
        //按钮
        $(document.createElement("button")).addClass('button').text('<')  .click(_obj.move_pre   ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('>')  .click(_obj.move_next  ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('|<<')  .click(_obj.board_clean).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text('>>|').click(_obj.board_end  ).appendTo(controlbar);
        $(document.createElement("button")).addClass('button').text(pager.t('Restore'))    .click(_obj.show_origin).appendTo(controlbar);
        $(document.createElement("button")).addClass('button show').text(pager.t('Hide Numbers')).click(function(){
            let _btn = $(this);
            if(_btn.hasClass("show"))
            {
                _btn.text(pager.t('Show Numbers')).removeClass('show');
                $("<style>").attr("id",'hide_number').html('.row div{text-indent:-999px;overflow:hidden;}').appendTo("head");
            }
            else
            {
                _btn.text(pager.t('Hide Numbers')).addClass('show');
                $("#hide_number").remove();
            }
        }).appendTo(controlbar);
    };
};

if(typeof gameObj == 'object')
{
//1.new出对象
    board = new boardObj();

    $(document).ready(function(){
//页面初始化时对棋盘的操作：
//2.调用其init方法
        board.init_board();
//3.把web页输出的数据结构load进来。
        board.load(gameObj);
        board.show_rule();
    });
}
