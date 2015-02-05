<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<link href="./assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="./assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/tools.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/grid.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="./assets/plugins/jquery.bxslider/jquery.bxslider.css" type="text/css" />
	<link href="./assets/css/pages/pricing-tables.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/ex_style.css" rel="stylesheet" type="text/css"/>	
	<title>mokushi</title>
</head>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42716969-15', 'live3.info');
  ga('send', 'pageview');

</script>
<!-- jQuery Library --> 
<script src="./assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script> 
<script src="./assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script> 
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip --> 
<script src="./assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script> 
<script src="./assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<!--[if lt IE 9]>
	<script src="./assets/plugins/excanvas.min.js"></script>
	<script src="./assets/plugins/respond.min.js"></script>  
	<![endif]--> 
<script src="./assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<script src="./assets/plugins/jquery.blockui.min.js" type="text/javascript"></script> 
	

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Music</a></li>
    <li><a href="#tab2" data-toggle="tab">Food</a></li>
    <li><a href="./stats.php" >Stats</a></li>
    <li><a href="./stats2.php" >Stats2</a></li>
    <li><a href="../mng/list.php" >管理画面へ</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">

<h3>Scraped</h3>
<ul>
	<li><a href="./tower.php">Tower Record</a></li>
	<li><a href="./parcrew.php">Parcrew</a></li>
	<li><a href="./feed.php">チェルシー　バカモン　アイドルユニットライブ情報　Bingニュース無料ライブ </a></li>
	<li><a href="http://live3.info/static/livefeed">SongKick</a></li>
	<li><a href="./lastFm.php">lastFm</a></li>
	<li><a href="./enjTokyo.php">Let's Enjoy Tokyo</a></li>
</ul>
<h3>facebook</h3>
<ul>
	<li><a href="./facebook_events/display.php">Link</a></li>
</ul>

<h3>Twitter</h3>
<ul>
	<li><a href="./twitter.php">Link</a></li>
</ul>

<h3>Google Calendar</h3>
<ul>
	<li><a href="https://www.google.com/calendar/embed?src=atmkblog%40gmail.com&ctz=Asia/Tokyo">無銭カレンダー　アイドル googleカレンダー</a></li>
	<li><a href="https://www.google.com/calendar/embed?src=bhc%40hy-creative.com&ctz=Asia/Tokyo">ムーンストンプ営業カレンダー googleカレンダー</a></li>
</ul>
<h3>未着手</h3>
<ul>
	<li><a href="http://www.vision-tokyo.com/schedule#event-2014-03-6">Vision</a></li>
	<li><a href="http://www.braits.net/">パーティー・イベント企画・異業種交流会のブライツ</a></li>
	<li><a href="http://rubyroomtokyo.com/?cat=10">RubyRoom</a></li>
	<li><a href="http://www-shibuya.jp/schedule/">WWW</a></li>
	<li><a href="http://shibuya-o.com/east/2014/03">Tsutaya</a></li>
	<li><a href="http://www.loft-prj.co.jp/schedule/loft">Loft Shelter他</a></li>
	<li><a href="http://bighitcompany.com/crawl/category/live/2014mar">Club Crawl</a></li>
	<li><a href="http://www.imix-ekoda.com/schedule201403.html">LIVEHOUSE Imix EKODA</a></li>
	<li><a href="http://woman.infoseek.co.jp/news/photo/search/%E3%83%95%E3%83%AA%E3%83%BC+%E3%83%A9%E3%82%A4%E3%83%96/">楽天woman フリーライブ</a></li>
	<li><a href="http://natalie.mu/music/feed/news">natalie</a></li>
	<li><a href="http://www.pit-inn.com/sche_j.html">Pit in</a></li>
	<li><a href="http://www.studio-coast.com/schedule/2014/03.html">studio-coast</a></li>
	<li><a href="http://www.boundee.jp/calendar/">boundee</a></li>
	<li><a href="http://www.qetic.jp/music/wilkojohnson-140106/108835/">qetic</a></li>
	<li><a href="http://www.eventernote.com/events/">eventernote</a></li>
	<li><a href="http://www.paselabo.tv/parms/">paselabo</a></li>
	<li><a href="http://www.alice-project.biz/parms">alice-project</a></li>
	<li><a href="http://shibuya-o.com/">O-Group</a></li>
</ul>

<h3>済</h3>
<ul>
	<li>Parcrew
		<ul>
		<li><a href="http://parcrew.com/contents/guest/1/2014/03/8.html">guest</a></li>
		<li><a href="http://parcrew.com/contents/event/1/2014/03/8.html">event</a></li>			
		</ul>
	</li>
	<li><a href="http://tower.jp/store/event">tower.jp</a></li>	
</ul>

    </div>
    <div class="tab-pane" id="tab2">
<?php
require_once('./rss_atom.php');

date_default_timezone_set('Asia/Tokyo');

$rss_urls = array(
	"https://spreadsheets.google.com/feeds/list/0ArlzDg8lK0nRdHRRWEs3TWxwb3pZVTY2ZnB5R3FzYlE/od6/public/basic?alt=rss"
);
 
foreach ($rss_urls as $url) :
$result = rss_get_contents($url);
$rss = simplexml_load_string($result);
?>
<dl>
<?php outPutRss($rss,10);?>
</dl>
<?php endforeach;?>    

    
  </div>
  </div>
</div>


</body>
</html>