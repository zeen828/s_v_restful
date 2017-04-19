function message_templated(msg_no){
	console.log('message_templated()');
	var templated_html = '<li class="board board_' + msg_no + '">'+
		'<div class="board_row">'+
			'<img class="board_user_img" src="http://images.vidol.tv/vidol_assets/50x50.jpg"  alt="user" />'+
			'<span class="board_user">user</span>'+
			'<div class="board_message">'+
				'<span class="board_message_style">message</span>'+
			'</div>'+
			'<div>'+
				'<span class="call_reply a">回覆</span>&nbsp;&nbsp;'+
				'<span class="board_date">日期</span>'+
			'</div>'+
		'</div>'+
		'<div class="replys_container center">'+
			'<span class="replys_more a">看更多回覆...</span>'+
			'<ul class="replys center">'+
			'</ul>'+
			'<span>總共有<span class="replys_info">0</span>則回覆</span>'+
		'</div>'+
		'<div class="reply_form center">'+
			'<img class="user_img" src="http://images.vidol.tv/vidol_assets/50x50.jpg"  alt="user" />'+
			'<textarea name="reply_msg" rows="3" cols="60"></textarea><br />'+
			'<button class="reply_form_button">回覆</button>'+
		'</div>'+
		'<div class="replys_count">'+
			'<span class="call_reply a"><span class="replys_info">0</span>則回覆</span>'+
		'</div>'+
		'</div>'+
	'<hr/>'+
	'</li>';
	return templated_html;
}

function reply_templated(reply_no){
	console.log('reply_templated()');
	var templated_html = '<li class="reply reply_' + reply_no + '">'+
	'<div class="reply_row">'+
	'<img class="reply_user_img" src="http://images.vidol.tv/vidol_assets/50x50.jpg"  alt="user" />'+
	'<span class="reply_user">user</span>'+
	'<div class="reply_message_style">'+
	'<span class="reply_message_style">message</span>'+
	'</div>'+
	'<div>'+
	'<span class="reply_date">日期</span>'+
	'</div>'+
	'</div>'+
	'<hr/>'+
	'</li>';
	return templated_html;
}

function creat_board(){
	console.log('creat_board()');
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
			url: '/api/boards/message',
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
				alert('Ajax request 發生錯誤');
			},
			statusCode: {  
			    201: function(data, statusText, xhr) {
					var tmp_count = $('.boards_container .boards_info').html();
					$('.boards_container .boards_info').html(parseInt(tmp_count) + 1);
					add_board_message_html('append', data.data.no, data.data);
					restart_event();
			    } 
			} 
		});
	}
}

function add_board_message_html(action, msg_no, data){
	console.log('add_board_message_html()');
	if(action == 'undefined' || action == ''){
		action = 'append';
	}
	if($('ul.boards .board_' + data.no).length == 0) {
		var html = message_templated(data.no);
		if(action == 'append'){
			$('ul.boards').append(html);
		}else{
			$('ul.boards').prepend(html);
		}
		//看回覆
		$('.board_' + data.no + ' .call_reply').data( 'data-message-no', data.no );//存資料
		$('.board_' + data.no + ' .replys_more').data( 'data-message-no', data.no );//存資料
		$('.board_' + data.no + ' .replys_more').data( 'data-reply-page', 1 );//存資料(每則留言回覆頁數)
		//回覆
		$('.board_' + data.no + ' .reply_form_button').data( 'data-message-no', data.no );//存資料
		$('.board_' + data.no + ' .board_user_img').attr('src',data.propic);
		$('.board_' + data.no + ' .board_user').html(data.user);
		$('.board_' + data.no + ' .board_message_style').html(data.messages);
		$('.board_' + data.no + ' .board_message_style').css('color', data.color).css('font-size', data.size + 'px');
		$('.board_' + data.no + ' .board_date').html(data.time_tw);
		if(data.reply.info.count >= 1){
			$('.board_' + data.no + ' .replys_info').html(data.reply.info.count);
			$('.board_' + data.no + ' .replys_container').hide();
			$('.board_' + data.no + ' .reply_form').hide();
			$('.board_' + data.no + ' .replys_count').show();
		}else{
			$('.board_' + data.no + ' .replys_container').hide();
			$('.board_' + data.no + ' .reply_form').hide();
			$('.board_' + data.no + ' .replys_count').hide();
		}

	} else {
		console.log('留言資料' + data.no + '重複！');
	}
}

function call_boards(page, pagesize){
	console.log('call_boards()');
	var programme = $('input[name="programme"]').val();
	var video_type = $('input[name="video_type"]').val();
	var video_id = $('input[name="video_id"]').val();
	if(page == 'undefined' || page == ''){
		page = 1;
	}
	if(pagesize == 'undefined' || pagesize == ''){
		pagesize = 10;
	}
	if (typeof(programme) != 'undefined' || (typeof(video_type) != 'undefined' && typeof(video_id) != 'undefined')) {
		$.ajax({
			url: '/api/boards/message',
			type: 'GET',
			cache: false,
			dataType: 'jsonp',
			data: {
				'programme' : programme,
				'video_type' : video_type,
				'video_id' : video_id,
				'page' : page,
				'pagesize' : pagesize
			},
			error: function(xhr){
				alert('Ajax request 發生錯誤');
			},
			statusCode: {
			    200: function(data, statusText, xhr) {
					if (typeof(data.info) != 'undefined'){
						$('.boards_info').html(data.info.count);
					}
					if (typeof(data.data) != 'undefined'){
						$.each( data.data, function( key, value ) {
							console.log('page' + page);
							if(loop_call === true){
								console.log('append');
								add_board_message_html('append', value.no, value);
							}else{
								console.log('prepend');
								add_board_message_html('prepend', value.no, value);
							}
							loop_call = false;
						});
					}
					restart_event();
			    }
			}
		});
	}
	//loop = setTimeout(call_boards, 2500);//5s
	loop = setTimeout(function(){
		loop_call = true;
		call_boards(1, 10);
	}, 2500);
}

function creat_reply(msg_no){
	console.log('creat_reply()');
	var token = $('input[name="token"]').val();
	var programme = $('input[name="programme"]').val();
	var video_type = $('input[name="video_type"]').val();
	var video_id = $('input[name="video_id"]').val();
	var reoly = msg_no;
	var user = $('input[name="user"]').val();
	var member_id = $('input[name="member_id"]').val();
	var nick_name = $('input[name="nick_name"]').val();
	var propic = $('input[name="propic"]').val();
	var msg = $('.board_' + msg_no + ' textarea[name="reply_msg"]').val();
	var barrage = 'N';
	var color = $('.board_form select[name="color"]').val();
	var size = $('.board_form select[name="size"]').val();
	var video_time = 0;
	var position = 0;
	if(typeof(msg) != 'undefined' && msg != ''){
		$.ajax({
			url: '/api/boards/reply',
			type: 'POST',
			cache: false,
			headers: {
				'token' : token
			},
			dataType: 'jsonp',
			data: {
				'video_type' : video_type,
				'reoly' : reoly,
				'user' : user,
				'member_id' : member_id,
				'nick_name' : nick_name,
				'propic' : propic,
				'msg' : msg,
				'color' : color,
				'size' : size
			},
			error: function(xhr){
				alert('Ajax request 發生錯誤');
			},
			statusCode: {
			    201: function(data, statusText, xhr) {
					var tmp_count = $('.board_' + msg_no + ' .replys_container .replys_info').html();
					$('.board_' + msg_no + ' .replys_container .replys_info').html(parseInt(tmp_count) + 1);
					add_reply_html('append', msg_no, data.data);
					restart_event();
			    }
			}
		});
	}
}

function add_reply_form_html(message_id){
	console.log('add_reply_form_html()');
	$('.reply_form div').remove();
	var html = '<div class="reply_form center"><textarea name="reply_msg" rows="8" cols="80"></textarea><br /><button class="reply_form_button">回覆</button></div>';
	$('.board_' + message_id + ' .reply_form').html('').prepend(html);
	restart_event();
}

function add_reply_html(action, msg_no, data){
	console.log('add_reply_html()');
	if(action == 'undefined' || action == ''){
		action = 'append';
	}
	if($('.board_' + msg_no + ' ul.replys .reply_' + data.no).length == 0) {
		var html = reply_templated(data.no);
		if(action == 'append'){
			$('.board_' + msg_no + ' ul.replys').append(html);
		}else{
			$('.board_' + msg_no + ' ul.replys').prepend(html);
		}
		$('.reply_' + data.no + ' .reply_user_img').attr('src',data.propic);
		$('.reply_' + data.no + ' .reply_user').html(data.user);
		$('.reply_' + data.no + ' .reply_message_style').html(data.messages);
		$('.reply_' + data.no + ' .reply_message_style').css('color', data.color).css('font-size', data.size + 'px');
		$('.reply_' + data.no + ' .reply_date').html(data.time_tw);
	} else {
		console.log('回覆資料' + data.no + '重複！');
	}	
}

function call_replys(msg_no, page, pagesize){
	console.log('call_replys()');
	if(page == 'undefined' || page == ''){
		page = 1;
	}
	if(pagesize == 'undefined' || pagesize == ''){
		pagesize = 10;
	}
	if (typeof(msg_no) != 'undefined') {
		$.ajax({
			url: '/api/boards/reply',
			type: 'GET',
			cache: false,
			dataType: 'jsonp',
			data: {
				'msg_no' : msg_no,
				'page' : page,
				'pagesize' : pagesize
			},
			error: function(xhr){
				alert('Ajax request 發生錯誤');
			},
			statusCode: {
			    200: function(data, statusText, xhr) {
					if (typeof(data.info) != 'undefined'){
						$('.board_' + msg_no + ' .replys_info').html(data.info.count);
					}
					if (typeof(data.data) != 'undefined'){
						$.each( data.data, function( key, value ) {
							add_reply_html('prepend', msg_no, value);
						});
					}
					restart_event();
			    }
			}
		});
	}
}

function restart_event(){
	console.log('restart_event()');
	//看更多留言
	$('#container .boards_more').off('click').on( "click", function() {
		console.log('看更多留言');
		page = page + 1;
		call_boards(page, 10);
	});
	//留言
	$('#container .board_form_button').off('click').on( "click", function() {
		console.log('留言');
		creat_board();
	});
	//看回覆
	$('#container .call_reply').off('click').on( "click", function() {
		console.log('看回覆');
		var msg_no = $(this).data( 'data-message-no' );
		var reply_page = $('.board_' + msg_no + ' .replys_more').data( 'data-reply-page');
		$('.user_img').attr('src', $('input[name="propic"]').val());
		$('.board_' + msg_no + ' .replys_container').show();
		$('.board_' + msg_no + ' .reply_form').show();
		$('.board_' + msg_no + ' .replys_count').hide();
		call_replys(msg_no, reply_page, 10);

	});
	//看更多回覆
	$('#container .replys_more').off('click').on( "click", function() {
		console.log('看更多回覆');
		var msg_no = $(this).data( 'data-message-no' );
		var reply_page = $(this).data( 'data-reply-page');
		reply_page = reply_page + 1;
		$('.board_' + msg_no + ' .replys_more').data( 'data-reply-page', reply_page);
		call_replys(msg_no, reply_page, 10);
	});
	//回覆
	$('#container .reply_form_button').off('click').on( "click", function() {
		console.log('回覆');
		var msg_no = $(this).data( 'data-message-no' );
		creat_reply(msg_no);
	});
}

var page = 1;
var loop_call = false;
var loop;

$(document).ready(function(){
	$('.user_img').attr('src', $('input[name="propic"]').val());
	//讀取
	restart_event();
	call_boards(page, 10);
});