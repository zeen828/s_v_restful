<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="robots" content="noindex,nofollow">
        <meta name=viewport content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title></title>
		<style type="text/css">
		body {
			
		}
		#main {
		}
		header {
			clear: both;
		}
		.left {
			width: 65%;
			float: left;
		}
		.view_box {
			width: 800px;
			height: 600px;
			background-color: #FFBB73;
		}
		.right {
			width: 30%;
			float: left;
		}
		ul li {
			list-style-type:none;
		}
		footer {
			clear: both; 
		}
		footer .but li {
			float: left;
			padding: 10px;
		}
		.but_box {
			width: 100px;
			height: 100px;
			border-style: solid;
			background-color: #FFBB73;
		}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>
        $(function(){
    		$.ajax({
    			url: 'http://plugin-background.vidol.tv/v1/tvboxs/ovo/ad.json',
    			type: 'GET',
    			cache: false,
    			dataType: 'json',
    			error: function(xhr){
    				alert('Ajax request error');
    			},
    			success: function(response) {
    				var json_obj = response.data;
    				var dataLength = json_obj.length;
    				if( dataLength!=0 ){
        				$('.ad').html('');
    					for( e=0 ; e<dataLength ; e++ ){
    						$('.ad').append('<li><a href="'+json_obj[e].appUri+'"><img src="'+json_obj[e].thumbnail+'"></a></li>');
    					}
    					$('.ad').append('<li><a href="vidoltv://tv?type=programmes&id=185 "><img src="http://a1.att.hudong.com/33/75/01300001009299144334759842161_s.jpg"></a></li>');
    					$('.ad').append('<li><a href="vidoltv://tv?type=events&id=111"><img src="http://a1.att.hudong.com/33/75/01300001009299144334759842161_s.jpg"></a></li>');
    					$('.ad').append('<li><a href="vidoltv://tv?type=episodes&id=16370"><img src="http://a1.att.hudong.com/33/75/01300001009299144334759842161_s.jpg"></a></li>');
    				}
    			}
    		});
        });
        </script>
    </head>
    <body>
    	<div id="main">
    		<header>Vidol TV</header>
    		<aside>
    			<section class="left">
    				<div class="view_box">看三小</div>
    			</section>
				<section class="right">
					<ul class="ad"></ul>
				</section>    		
    		</aside>
    		<footer>
    			<ul class="but">
    				<li><div class="but_box">功能A</div></li>
    				<li><div class="but_box">功能B</div></li>
    			</ul>
    		</footer>
    	</div>
    </body>
</html>