
<!DOCTYPE html>
<html>
    <head>
<?php $this->load->view(sprintf("%s/include/meta" , 'vidol'), array('view_data'=>null));?>
<?php $this->load->view(sprintf("%s/include/meta_style" , 'vidol'), array('view_data'=>null));?>
<?php $this->load->view(sprintf("%s/include/meta_javascript" , 'vidol'), array('view_data'=>null));?>
    </head>
    <body>
    	<article class="loading">
    		<span class="block"></span>
    	</article>
    	<div id="wrapper">
<?php $this->load->view(sprintf("%s/include/body_header" , 'vidol'), array('view_data'=>null));?>
    		<article id="kv" class="sunslide kv">
    			<article class="slide-wrap">
    				<ul class="slide-ul">
    					<li style="background: url('http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/032/original/BFSLABCD.jpg?1469011730');" navImg="http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/032/original/BFSLABCD.jpg?1469011730" data-banner="32" data-title="華八ON檔"><a
    						href="/programmes/155"></a></li>
    					<li style="background: url('http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/038/original/HOTSL.jpg?1469011756');" navImg="http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/038/original/HOTSL.jpg?1469011756" data-banner="38" data-title="綜藝ON檔-1"><a
    						href="/programmes/119"></a></li>
    					<li style="background: url('http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/112/original/slide1.jpg?1468988492');" navImg="http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/112/original/slide1.jpg?1468988492" data-banner="112" data-title="啟動愛情Ｘ進擊的大叔 發佈會">
    						<a href="/event/55"></a>
    					</li>
    					<li style="background: url('http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/048/original/MMA2.jpg?1468500160');" navImg="http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/048/original/MMA2.jpg?1468500160" data-banner="48" data-title="都會台直播"><a
    						href="/channel/20"></a></li>
    					<li style="background: url('http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/056/original/%E5%8F%B0%E7%81%A3%E5%8F%B01.jpg?1468500172');" navImg="http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/056/original/%E5%8F%B0%E7%81%A3%E5%8F%B01.jpg?1468500172"
    						data-banner="56" data-title="台灣台直播"><a href="/channel/2"></a></li>
    					<li style="background: url('http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/107/original/slide0715_KKTIX1920x1080%28400k%29.jpg?1468559097');"
    						navImg="http://djs8249b4hgi9.cloudfront.net/highlight_banners/images/000/000/107/original/slide0715_KKTIX1920x1080%28400k%29.jpg?1468559097" data-banner="107" data-title="MTV最強音-最強療癒情歌"><a href="/event/50"></a></li>
    				</ul>
    			</article>
    			<article class="in">
    				<span class="prev kv-prev"></span> <span class="next kv-next"></span>
    				<article class="editNews"></article>
    			</article>
    		</article>
    		<div class="content">
<?php $this->load->view(sprintf("%s/%s" , 'vidol', $content['view_path']), $content['view_data']);?>
    		</div>
<?php $this->load->view(sprintf("%s/include/body_footer" , 'vidol'), array('view_data'=>null));?>
    		<span class="goTop"></span>
    	</div>
<?php $this->load->view(sprintf("%s/include/body_javascript" , 'vidol'), array('view_data'=>null));?>
    </body>
</html>
