<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>jQuery数字随机滚动抽奖特效代码 - JS代码网</title>
<script type="text/javascript" src="/assets/plugins/jQuery/2.2.3/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
var g_Interval = 1;
var g_PersonCount = 20000;//参加抽奖人数
var g_Timer;
var running = false;
function beginRndNum(trigger){
	console.log('beginRndNum');
	if(running){
		running = false;
		clearTimeout(g_Timer);		
		$(trigger).val("開始");
		$('#ResultNum').css('color','red');
	}
	else{
		running = true;
		$('#ResultNum').css('color','black');
		$(trigger).val("停止");
		beginTimer();
	}
}

function updateRndNum(){
	console.log('updateRndNum');
	var num = Math.floor(Math.random()*g_PersonCount+1);
	$('#ResultNum').html(num);
}

function beginTimer(){
	console.log('beginTimer');
	g_Timer = setTimeout(beat, g_Interval);
}

function beat() {
	console.log('beat');
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
	<h1 style="color: #40AA53">抽奖结果</h1>
	<div id="Result" style="color: #40AA53">
		<span id="ResultNum">0</span>
	</div>
	<div id="Button">
		<input type='button' id="btn" value='开始' onclick='beginRndNum(this)' />
	</div>
</body>
</html>