<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vidol 抽獎活動</title>
<script type="text/javascript" src="/assets/plugins/jQuery/2.2.3/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
var g_Interval = 1;//間隔
var g_PersonCount = 20000;//人數
var g_Lottery;//抽獎
var g_LotteryList = <?php echo json_encode($lottery);?>;//抽獎
var g_Timer;//計時器
var running = false;//
function getLottery(){
	$.ajax({
		url: '/api/lottery/event_2017_1',
		type: 'POST',
		cache: false,
		dataType: 'json',
		data: {
			'token' : 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpZGVudGl0eSI6eyJpZCI6NDMyMDc0LCJ1aWQiOiJzM2dYR0JDbUYwIiwiZW1haWxfdmVyaWZpZWQiOnRydWV9LCJhcHBsaWNhdGlvbl9pZCI6MSwiZXhwaXJlc19hdCI6MTQ4NDcyODY3NiwicmFuZF9rZXkiOiI5NDA1ZGMwYmQxMGE2ZGMwMTA1NGFiZGI1ZjQ2NzVhMiJ9.0gF3EgZhHHnxoZdWDqM4UBlCEKR9EPaW0qSFZrRsRuBlfgxhEqb_qR2vQzGdoLeC8bdAaIl1MC_2s7xE8wjMxQ',
			'vip' : '<?php echo $vip;?>',
			'start' : '2017-06-01',
			'end' : '2017-06-07',
			'cache' : '',
			'debug' : 'debug'
		},
		error: function(xhr){
			alert('Ajax request error');
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
	console.log('beginRndNum-開始&停止');
	if(running){
		//開獎
		console.log('開獎');
		running = false;
		clearTimeout(g_Timer);		
		$(trigger).val("開始");
		$('#ResultNum').css('color','red');
	}
	else{
		getLottery();
		running = true;
		$('#ResultNum').css('color','black');
		$(trigger).val("停止");
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
</script>
</head>
<body>
	<style type="text/css">
body {
	background-color: #fff;
	text-align: center;
	padding-top: 50px;
}

#Result {
	border: 3px solid #40AA53;
	margin: 0 auto;
	text-align: center;
	width: 400px;
	padding: 50px 0;
	background: #efe;
}

#ResultNum {
	font-size: 50pt;
	font-family: Verdana
}

#Button {
	margin: 50px 0 0 0;
}

#Button input {
	font-size: 40px;
	padding: 0 50px;
}

#btn {
	background-color: #40AA53;
	border: 1px solid #40AA53;
	width: 20%;
	height: 45px;
	margin: 0em auto;
	font-size: 1em;
	border-radius: 2.5px;
	-moz-border-radius: 2.5px;
	-webkit-border-radius: 2.5px;
	color: #FFF;
}
</style>
	<h1 style="color: #40AA53">抽獎結果</h1>
	<div id="Result" style="color: #40AA53">
		<span id="ResultNum">0</span>
	</div>
	<div id="Button">
		<input type='button' id="btn" value='開始' onclick='beginRndNum(this)' />
	</div>
</body>
</html>