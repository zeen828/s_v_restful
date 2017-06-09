<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<title>Vidol 抽獎活動</title>
<script type="text/javascript"
	src="/assets/plugins/jQuery/2.2.3/jquery-2.2.3.min.js"></script>
<script type="text/javascript"
	src="/assets/js/lottery/iphone8.js?<?php echo time();?>"></script>
<script type="text/javascript">
g_LotteryList = <?php echo json_encode($lottery);?>;//預設抽獎名單避免AJAX錯誤沒名單
start_date = '<?php echo $start;?>';
end_date = '<?php echo $end;?>';
</script>
<style type="text/css">
html {
	width: 100%;
	height: 100%;
}

body {
	position: static;
	background-image: url(/assets/img/lottery/OB_iphone8/background.jpg);
	background-repeat: no-repeat;
	text-align: center;
}

#Result {
	position: relative;
	top: 50%;
	left: 60%;
	width: 400px;
}

#ResultNum {
	font-size: 60pt;
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
</head>
<body>
	<div id="Result">
		<span id="ResultNum">Vidol</span>
	</div>
	<div id="Button">
		<input type='button' id="btn" value='開始' onclick='beginRndNum(this)' />
	</div>
</body>
</html>