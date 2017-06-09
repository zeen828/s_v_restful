<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex,nofollow" />
<title>Vidol 抽獎活動</title>
<script type="text/javascript" src="/assets/plugins/jQuery/2.2.3/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="/assets/js/lottery/iphone8.js?<?php echo time();?>"></script>
<script type="text/javascript">
g_LotteryList = <?php echo json_encode($lottery);?>;//預設抽獎名單避免AJAX錯誤沒名單
start_date = '<?php echo $start;?>';
end_date = '<?php echo $end;?>';
</script>
<style type="text/css">
body {
	background-image:url(assets/lottery/OB_iphone8/background.jpg); 
	background-repeat: no-repeat; 
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
</head>
<body>
	<h1 style="color: #40AA53">抽獎結果</h1>
	<div id="Result" style="color: #40AA53">
		<span id="ResultNum">Vidol</span>
	</div>
	<div id="Button">
		<input type='button' id="btn" value='開始' onclick='beginRndNum(this)' />
	</div>
</body>
</html>