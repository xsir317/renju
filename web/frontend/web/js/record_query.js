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

    //字符串，当前局面记录。
    _obj.currgame = '';

    // 当前颜色，在初始化时会初始化为黑色
    _obj.curr_color = 'black';

    //当前手数，会被初始化为1
    _obj.curr_step = 1;

    _obj.in_ajax = false;


    /**
     * @description 在指定位置放置一枚棋子。当操作者是行棋一方时，会转交给make_move来处理。
     * 当操作者是玩家之一时，不可以拿棋盘来拆棋，只能按照对局记录前进后退。
     * @param  {string} coordinate 传入坐标。
     * @returns {boolean}
     */
    _obj.place_stone = function(coordinate){
        if(_obj.in_ajax)
        {
            return false;
        }
        let target_cell = board.find('.'+coordinate);
        if(!target_cell.hasClass('blank'))
        {
            return false;
        }
        target_cell.removeClass('blank').addClass(_obj.curr_color);//.html(_obj.curr_step ++);
        _obj.curr_color = (_obj.curr_color == 'black' ? 'white':'black');
        _obj.currgame += coordinate;
        _obj.do_query();
        return true;
    };


    _obj.get_current_board = function () {
        return _obj.currgame;
    };

    /**
     * @returns {boolean}
     */
    _obj.move_pre = function(){
        if(_obj.in_ajax)
        {
            return false;
        }
        if(_obj.currgame)
        {
            let last_move = _obj.currgame.substr(_obj.currgame.length-2,2);
            //这个棋子拿起来。。。
            let target_cell = board.find('.'+last_move);
            target_cell.removeClass('black white').addClass('blank').html('');
            _obj.curr_step --;
            _obj.curr_color = (_obj.curr_color == 'black' ? 'white':'black');
            _obj.currgame = _obj.currgame.substr(0,_obj.currgame.length-2);
            _obj.do_query();
            return true;
        }
        return false;
    };


    _obj.do_query = function()
    {
        if(_obj.currgame == '')
        {
            return false;
        }
        _obj.in_ajax = true;

        $.getJSON('/records/ajax/query',{game:_obj.currgame},function(_data){
            setTimeout(function(){_obj.in_ajax = false;},1000);
            if(_data.code != 200)
            {
                layer.alert(_data.msg);
            }

            let data = _data.data;
            let board_total_games = data.white_wins + data.black_wins + data.draws;
            let board_e_score = board_total_games ? ((data.black_wins + 0.5 * data.draws) / board_total_games) : 0;
            $("#gameinfo ul .total_games>ins").html( board_total_games );
            $("#gameinfo ul .black_wins>ins").html( data.black_wins );
            $("#gameinfo ul .black_score>ins").html( Math.round(board_e_score * 1000) / 1000 );
            $("#gameinfo ul .white_wins>ins").html( data.white_wins );
            $("#gameinfo ul .draws>ins").html( data.draws );

            //清理其他的统计点
            board.find(".statistics").removeClass('statistics good1 good2 good3 bad1 bad2 bad3 unknown').empty();
            for(let coord in data.next_move)
            {
                let curr_color_game = _obj.curr_color == 'black' ? data.next_move[coord][0] : data.next_move[coord][2];
                let total_game = data.next_move[coord][0] + data.next_move[coord][1] + data.next_move[coord][2]; // 黑棋胜平负
                let e_score = total_game ? ((curr_color_game + 0.5 * data.next_move[coord][1]) / total_game) : -1;
                let stat_color = 'unknown';//统计颜色，根据得分率给出 很好或者很不好
                let win_rate = total_game ? ( Math.round(curr_color_game * 1000 / total_game) /10 ) : 0;//胜率
                if(total_game == 0)
                {
                    stat_color = 'unknown';
                }
                else if(e_score > 0.8)
                {
                    stat_color = 'good3';
                }
                else if(e_score > 0.6)
                {
                    stat_color = 'good2';
                }
                else if(e_score > 0.53)
                {
                    stat_color = 'good1';
                }
                else if(e_score < 0.2)
                {
                    stat_color = 'bad1';
                }
                else if(e_score < 0.4)
                {
                    stat_color = 'bad2';
                }
                else if(e_score < 0.47)
                {
                    stat_color = 'bad3';
                }
                board.find("." + coord)
                    .addClass('statistics ' + stat_color)
                    .html("<em class='win_rate'>" + win_rate +  "</em>" + "<em class='total_game'>" + total_game +  "</em>");
            }

            $("#gameinfo ul .rel_games>span>p").empty();
            for(let i in  data.related_games)
            {
                let new_link = $("<a>").attr({
                    href: "/records/show/game?id=" + data.related_games[i]["record_id"],
                    target: "_blank"
                }).text(data.related_games[i]["black_player"] + ' - ' + data.related_games[i]["white_player"]);
                $("#gameinfo ul .rel_games>span>p").append( new_link );
            }
        });
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
        for(let i=15;i>=1;i--)
        {
            //insert a row
            let newrow = $(document.createElement("div"));
            newrow.addClass('row');
            for(let j=1;j<=15;j++)
            {
                //insert a cross point
                let newcell = $(document.createElement("div"));
                newcell.addClass(j.toString(16) + i.toString(16));
                newcell.attr('alt',j.toString(16) + i.toString(16));
                newcell.addClass('blank');
                newrow.append(newcell);
            }
            board.append(newrow);
        }
        board.find('.row div').click(function(){
            _obj.place_stone($(this).attr('alt'));
            return true;
        });


        //生成控制按钮
        let controlbar = $(document.createElement("div"));
        controlbar.addClass('controlbar');
        board.after(controlbar);
        //按钮
        $(document.createElement("button")).addClass('button').text('清空').click(function () {
            _obj.currgame = '';
            _obj.curr_color = 'black';
            _obj.curr_step = 1;
            board.find(".statistics").removeClass('statistics good1 good2 good3 bad1 bad2 bad3 unknown').empty();
            board.find(".black, .white").removeClass('black white').addClass('blank');
            board.find(".88").click();
        }).appendTo(controlbar);
    };
};

//1.new出对象
board = new boardObj();

$(document).ready(function(){
    board.init_board();
});
