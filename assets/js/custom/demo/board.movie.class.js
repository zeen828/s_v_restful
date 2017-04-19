var VidolBoard = function VidolBoard() {
	var _this = this;
	this.myClass = '#movie_boards';
	this.api_url = 'http://plugin-background.vidol.tv';
	this.page = {'main':1};
	this.pagesize = 10;
	this.loop = {'main':{}};

	/**
	 * 留言html結構
	 */
	this.message_templated = function (msg_no){
		var templated_html = '<section class="boards_message board_' + msg_no + '">'+
			'<hr size="1" noshade="noshade" class="hr_" />'+
			'<div class="boards_message_header row100 float_clear">'+
				'<div class="row25 float_left">'+
					'<img class="board_user_img" alt="user_icon" src="http://images.vidol.tv/vidol_assets/50x50.jpg">'+
				'</div>'+
				'<div class="row75 float_right">'+
					'<span class="board_user">user</span><br/>'+
					'<span class="board_area">台灣</span>'+
				'</div>'+
			'</div>'+
			'<div class="boards_message_content row100 float_clear">'+
				'<div class="row75 float_right">message</div>'+
			'</div>'+
			'<div class="boards_message_footer row100 float_clear">'+
				'<div class="row75 float_right">'+
					'<span class="">讚</span>'+
					'<span class="call_reply a">回覆</span>'+
					'<span class="board_date">日期</span>'+
				'</div>'+
			'</div>'+
		'</section>';
		return templated_html;
	}

	/**
	 * 添加留言html
	 */
	this.add_board_message_html = function (action, msg_no, data){
		if($('.board_' + data.no).length == 0) {
			var html = _this.message_templated(data.no);
			$(_this.myClass + ' .boards').append(html);
			$('.board_' + data.no + ' .board_user_img').attr('src',data.propic);
			$('.board_' + data.no + ' .board_user').html(data.nick_name);
			$('.board_' + data.no + ' .boards_message_content div').html(data.messages);
			$('.board_' + data.no + ' .board_date').html(data.time_tw);
		} else {
			//console.log('留言資料' + data.no + '重複！');
		}
	}

	/**
	 * 取得留言
	 */
	this.call_boards = function (auto){
		console.log('call_boards');
		var programme = $('input[name="programme"]').val();
		var video_type = $('input[name="video_type"]').val();
		var video_id = $('input[name="video_id"]').val();
		var page = _this.page.main;
		if (typeof(programme) != 'undefined' || (typeof(video_type) != 'undefined' && typeof(video_id) != 'undefined')) {
			$.ajax({
				url: _this.api_url + '/api/boards/message.json',
				type: 'GET',
				cache: false,
				dataType: 'jsonp',
				data: {
					'programme' : programme,
					'video_type' : video_type,
					'video_id' : video_id,
					'page' : page,
					'pagesize' : _this.pagesize
				},
				error: function(xhr){
					console.log('Ajax request error');
				},
				statusCode: {
				    200: function(data, statusText, xhr) {
				    	console.log(data);
						if (typeof(data.info) != 'undefined'){
							$('.boards_info').html(data.info.count);
						}
						if (typeof(data.data) != 'undefined'){
							$.each( data.data, function( key, value ) {
						    	if(auto == true){
						    		_this.add_board_message_html('append', value.no, value);
						    	}else{
						    		_this.add_board_message_html('prepend', value.no, value);
						    	}
							});
						}
						_this.restart_event();
				    }
				}
			});
		}
		_this.loop.main = setTimeout(function(){
			_this.call_boards(true);
		}, 2500);
	}

	/**
	 * 建立留言
	 */
	this.creat_board = function (){
		var token = $('input[name="token"]').val();
		var programme = $('input[name="programme"]').val();
		var video_type = $('input[name="video_type"]').val();
		var video_id = $('input[name="video_id"]').val();
		var user = $('input[name="user"]').val();
		var member_id = $('input[name="member_id"]').val();
		var nick_name = $('input[name="nick_name"]').val();
		var propic = $('input[name="propic"]').val();
		var msg = $('textarea[name="board_msg"]').val();
		var barrage = $('.board_form select[name="barrage"]').val();
		var color = $('.board_form select[name="color"]').val();
		var size = $('.board_form select[name="size"]').val();
		var video_time = $('.board_form select[name="video_time"]').val();
		var position = $('.board_form select[name="position"]').val();
		if(typeof(msg) != 'undefined' && msg != ''){
			var confirm_val = confirm('需要彈幕？');
			if (confirm_val == true) {
				barrage = 'Y';
				video_time = 160;
			}else{
				barrage = 'N';
				video_time = 0;
			}
			$.ajax({
				url: _this.api_url + '/api/boards/message.json',
				type: 'POST',
				cache: false,
				headers: {
					'token' : token
				},
				dataType: 'jsonp',
				data: {
					'programme' : programme,
					'video_type' : video_type,
					'video_id' : video_id,
					'user' : user,
					'member_id' : member_id,
					'nick_name' : nick_name,
					'propic' : propic,
					'msg' : msg,
					'barrage' : barrage,
					'color' : color,
					'size' : size,
					'video_time' : video_time,
					'position' : position
				},
				error: function(xhr){
					console.log('Ajax request error');
				},
				statusCode: {  
				    201: function(data, statusText, xhr) {
						var tmp_count = $('.boards_container .boards_info').html();
						$('.boards_container .boards_info').html(parseInt(tmp_count) + 1);
						_this.add_board_message_html('append', data.data.no, data.data);
						_this.restart_event();
				    } 
				} 
			});
		}
	}

	/**
	 * 重刷事件
	 */
	this.restart_event = function (){
		//看更多留言
		$(_this.myClass + ' .boards_more').off('click').on('click', function() {
			_this.page.main = _this.page.main + 1;
			_this.call_boards(false);
		});
		//留言
		$(_this.myClass + ' .board_form_button').off('click').on('click', function() {
			_this.creat_board();
		});
	}
}

$(document).ready(function(){
	$('.user_img').attr('src', $('input[name="propic"]').val());
	var run = new VidolBoard();
	run.restart_event();
	run.call_boards();
});