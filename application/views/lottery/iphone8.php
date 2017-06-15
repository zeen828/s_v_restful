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
	text-align: center;
	position: static;
	background-image: url(/assets/img/lottery/OB_iphone8/background.jpg);
	background-repeat: no-repeat;
	background-attachment: fixed;
	background-position: left bottom;
	background-size: cover;
	background-repeat: no-repeat;
	background-attachment: fixed;
	background-position: center;
	background-size: cover;
}

#main {
	position: fixed;
	top: 0;
	left: 0;
	bottom: 0;
	right: 0;
	z-index: -999;
}

#main img {
	min-height: 100%;
	width: 100%;
}

#Result {
	position: relative;
	top: 45%;
	left: 58%;
	width: 20%;
}

#ResultNum {
	font-size: 60pt;
	font-family: Verdana;
}

#Button {
	position: relative;
	top: 55%;
	left: 58%;
	width: 20%;
}

#btn {
	width: 70%;
	height: 10%;
	font-size: 30pt;
	font-family: Verdana;
	border-radius: 10px;
}
</style>
</head>
<body>
	<div id="main">
		<div id="Result">
			<span id="ResultNum">Vidol</span>
		</div>
		<div id="Button">
			<input type='button' id="btn" value='開始抽獎' onclick='beginRndNum(this)' />
		</div>
	</div>
</body>
</html>