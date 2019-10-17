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
    _obj.gameData = '';

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
            console.log(_data);
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
            _obj.place_stone($(this).attr('alt'),true);
            return true;
        });
    };
};

//1.new出对象
board = new boardObj();

$(document).ready(function(){
    board.init_board();
});
