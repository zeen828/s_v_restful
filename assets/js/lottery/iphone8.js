var g_Interval = 1;//間隔
var g_Lottery = [];//抽獎
var g_LotteryList = [];//預設抽獎名單避免AJAX錯誤沒名單
var g_LotteryArray = new Array();//中獎清單避開重副
var g_Timer;//計時器
var running = false;
var start_date = '';
var end_date = ''
function getLottery(start_date, end_date){
	$.ajax({
		url: '/api/lottery/iphone8',
		type: 'GET',
		cache: false,
		dataType: 'json',
		data: {
			'token' : 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpZGVudGl0eSI6eyJpZCI6NDMyMDc0LCJ1aWQiOiJzM2dYR0JDbUYwIiwiZW1haWxfdmVyaWZpZWQiOnRydWV9LCJhcHBsaWNhdGlvbl9pZCI6MSwiZXhwaXJlc19hdCI6MTQ4NDcyODY3NiwicmFuZF9rZXkiOiI5NDA1ZGMwYmQxMGE2ZGMwMTA1NGFiZGI1ZjQ2NzVhMiJ9.0gF3EgZhHHnxoZdWDqM4UBlCEKR9EPaW0qSFZrRsRuBlfgxhEqb_qR2vQzGdoLeC8bdAaIl1MC_2s7xE8wjMxQ',
			'start' : start_date,
			'end' : end_date
		},
		error: function(xhr){
			console.log('Ajax request error');
		},
		success: function(response) {
			console.log('Ajax OK');
		},
		statusCode: {
			200: function(json, statusText, xhr) {
				g_Lottery = json.lottery;
			}
		}
	});
}

function writeLotteryList(){
	$.ajax({
		url: '/api/lottery/iphone8',
		type: 'POST',
		cache: false,
		dataType: 'json',
		data: {
			'token' : 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpZGVudGl0eSI6eyJpZCI6NDMyMDc0LCJ1aWQiOiJzM2dYR0JDbUYwIiwiZW1haWxfdmVyaWZpZWQiOnRydWV9LCJhcHBsaWNhdGlvbl9pZCI6MSwiZXhwaXJlc19hdCI6MTQ4NDcyODY3NiwicmFuZF9rZXkiOiI5NDA1ZGMwYmQxMGE2ZGMwMTA1NGFiZGI1ZjQ2NzVhMiJ9.0gF3EgZhHHnxoZdWDqM4UBlCEKR9EPaW0qSFZrRsRuBlfgxhEqb_qR2vQzGdoLeC8bdAaIl1MC_2s7xE8wjMxQ',
			'mongo_id' : $('#ResultNum').html(),
			'member_id' : $('#ResultNum').html()
		},
		error: function(xhr){
			console.log('Ajax request error');
		},
		success: function(response) {
			console.log('Ajax OK');
		},
		statusCode: {
			200: function(json, statusText, xhr) {
				console.log('Ajax OK 200');
			}
		}
	});
}

function getRandomArrayElements(arr, count) {
    var shuffled = arr.slice(0), i = arr.length, min = i - count, temp, index;
    while (i-- > min) {
        index = Math.floor((i + 1) * Math.random());
        temp = shuffled[index];
        shuffled[index] = shuffled[i];
        shuffled[i] = temp;
    }
    return shuffled.slice(min);
}

function beginRndNum(trigger){
	if(running){
		if(g_Lottery.length >= 1){
			var user = getRandomArrayElements(g_Lottery, 1);
			$('#ResultNum').html(user[0].member_id);
		}
		//
		var lottery = $('#ResultNum').html();
		if($.inArray(lottery, g_LotteryArray) != -1){
			var user = getRandomArrayElements(g_LotteryList, 1);
			$('#ResultNum').html(user[0].member_id);
		}
		running = false;
		clearTimeout(g_Timer);
		writeLotteryList();//
		g_LotteryArray.push($('#ResultNum').html());
		$(trigger).val("開始抽獎");
		$('#ResultNum').css('color','red');
	}else{
		getLottery();
		running = true;
		$('#ResultNum').css('color','black');
		$(trigger).val("幸運得主");
		beginTimer();
	}
}

function updateRndNum(){
	var user = getRandomArrayElements(g_LotteryList, 1);
	$('#ResultNum').html(user[0].member_id);
}

function beginTimer(){
	g_Timer = setTimeout(beat, g_Interval);
}

function beat() {
	g_Timer = setTimeout(beat, g_Interval);
	updateRndNum();
}
