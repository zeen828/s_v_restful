<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Boards Demo</title>
<!-- JQuery -->
<script src="/assets/js/jQuery/2.2.3/jquery-2.2.3.min.js?"></script>
<!-- JQuery UI -->
<script src="/assets/js/jQueryUi/1.11.4/jquery-ui.min.js?"></script>
<link href="/assets/js/jQueryUi/1.11.4/jquery-ui.min.css"
	rel="stylesheet">
<!-- demo留言板用 -->
<script src="/assets/js/custom/demo/board.class.js?<?php echo time();?>"></script>
<style type="text/css">
body {
	width: 760px;
	background-color: #000000;
	color: #c0c0c0;
}

ul {
	list-style-type: none;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

.board_form {
	width: 80%;
}

.boards_container {
	width: 80%;
}

.board_message_style {
    word-break: break-all;
}

.reply_form {
	width: 80%;
}

.replys_container {
	width: 80%;
}

.center {
	margin: 0 auto;
}

.center_text {
	text-align: center;
	/*line-height: 100px;*/
}

.a {
	color: #0000ff;
}

img {
	-webkit-border-radius: 25px;
	-moz-border-radius: 25px;
	border-radius: 25px;
}
</style>
<script type="text/javascript">
</script>
</head>
<body>
	<div id="container">
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
		<h1 class="center_text">留言板</h1>
		<div class="board_form center">
			token : <input type="text" name="token" value="" />
			<br/>
			<select name="color">
				<option value="#ff0000"
					style="background-color: #ff0000; color: #ff0000;">#ff0000</option>
				<option value="#00ff00"
					style="background-color: #00ff00; color: #00ff00;">#00ff00</option>
				<option value="#0000ff"
					style="background-color: #0000ff; color: #0000ff;">#0000ff</option>
			</select> <select name="size">
				<option value="8" style="font-size: 8px;">8</option>
				<option value="10" style="font-size: 10px;">10</option>
				<option value="12" style="font-size: 12px;">12</option>
			</select>
			<br/>
			<img class="user_img" src="http://images.vidol.tv/vidol_assets/50x50.jpg"  alt="user" />
			<textarea name="board_msg" rows="3" cols="60"></textarea>
			<br />
			<button class="board_form_button">留言</button>
		</div>
		<div class="boards_container center">
			<span class="boards_more a">看更多留言...</span>
			<ul class="boards center"></ul>
			<span>總共有<span class="boards_info">0</span>則留言</span>
		</div>
	</div>
</body>
</html>
