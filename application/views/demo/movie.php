<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Boards Demo</title>
<!-- JQuery -->
<script src="/assets/js/jQuery/2.2.3/jquery-2.2.3.min.js?"></script>
<!-- JQuery UI -->
<script src="/assets/js/jQueryUi/1.11.4/jquery-ui.min.js?"></script>
<link href="/assets/js/jQueryUi/1.11.4/jquery-ui.min.css" rel="stylesheet">
<!-- demo留言板用 -->
<script src="/assets/js/custom/demo/board.movie.class.js?<?php echo time();?>"></script>
<style type="text/css">
body {
	width: 760px;
	background-color: #000000;
	color: #c0c0c0;
}

ul {
	list-style-type: none;
}

img {
	-webkit-border-radius: 25px;
	-moz-border-radius: 25px;
	border-radius: 25px;
}

#movie_boards {
	width: 300px;
}

#movie_boards header {
	height: 20px;
}

.boards_message {
	
}

.boards_message_header {
	height: 52px;
}

.boards_message_content {
	word-break: break-all;
}

.boards_message_footer {
	height: 20px;
}

.row100 {
	width: 100%;
}

.row75 {
	width: 75%;
}

.row50 {
	width: 50%;
}

.row25 {
	width: 25%;
}

.hr_ {
	border: 1px #c0c0c0 dotted;
}

.text_left {
	text-align: left;
}

.text_center {
	text-align: center;
}

.text_right {
	text-align: right;
}

.float_left {
	float: left;
}

.float_right {
	float: right;
}

.float_clear {
	clear: both;
}
</style>
<script type="text/javascript">
</script>
</head>
<body>
	<div id="movie_boards">
		<input type="hidden" name="programme" value="<?php echo $programme;?>" />
		<input type="hidden" name="video_type" value="<?php echo $video_type;?>" />
		<input type="hidden" name="video_id" value="<?php echo $video_id;?>" />
		<input type="hidden" name="user" value="<?php echo $user;?>" />
		<input type="hidden" name="member_id" value="<?php echo $member_id;?>" />
		<input type="hidden" name="nick_name" value="<?php echo $nick_name;?>" />
		<input type="hidden" name="propic" value="<?php echo $propic;?>" />
		<input type="hidden" name="barrage" value="N" />
		<input type="hidden" name="video_time" value="0" />
		<input type="hidden" name="position" value="123,456" />
		<header class="row100 float_clear">
			<div class="row50 text_left float_left"><span class="boards_info">0</span>則回應</div>
			<div class="row50 text_right float_right">
				排序依據 <select name="sort">
					<option value="DESC">最舊</option>
					<option value="ASC">最新</option>
				</select>
			</div>
		</header>
		<hr />
		<article class="row100">
			<section class="boards_input">
				<textarea rows="3" cols="46"></textarea>
			</section>
		</article>
		<article class="boards row100">
			<section class="boards_message">
				<hr size="1" noshade="noshade" class="hr_" />
				<div class="boards_message_header row100 float_clear">
					<div class="row25 float_left">
						<img alt="user_icon" src="http://images.vidol.tv/vidol_assets/50x50.jpg">
					</div>
					<div class="row75 float_right">
						Vidol<br /> 桃園區
					</div>
				</div>
				<div class="boards_message_content row100 float_clear">
					<div class="row75 float_right">這是看到鬼</div>
				</div>
				<div class="boards_message_footer row100 float_clear">
					<div class="row75 float_right">讚 回覆 2016年3月31日 22:30</div>
				</div>
			</section>
		</article>
		<footer class="row1"> </footer>
	</div>
</body>
</html>
