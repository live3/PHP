<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Document</title>
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

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1&appId=572978279428847";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-like-box" data-href="http://www.facebook.com/BarComeOnRock" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="true" data-show-border="false" style="float:left;"></div>



<iframe src="http://www.chelseahotel.jp/free/" frameborder="0" width="70%"  height="400px" style="float:left; border:1px solid gray;"></iframe> 
 
<hr  style="clear:both;"/>
<hr />
<hr />

<?php
$xml_array =array(
'http://feedblog.ameba.jp/rss/ameblo/idollive/rss20.xml',//アイドルユニットライブ・イベント情報☆アイドルライブ情報☆
'http://www.bing.com/news/search?q=%E3%83%A9%E3%82%A4%E3%83%96%20%E7%84%A1%E6%96%99&FORM=Z9LH2&format=RSS',//bing news live　無料
//'http://www.tokyogigguide.com/gigs/eventlist?format=feed&type=rss',//Tokyo gig guide
);
foreach($xml_array as $xml_url){
//$xml_url = 'http://feedblog.ameba.jp/rss/ameblo/idollive/rss20.xml';
	xmlLoad($xml_url);
}

function xmlLoad($xml_url){
	$xml = simplexml_load_file(utf8_for_xml($xml_url));
//	$nameSpaces = $xml->getNamespaces(true);
	$item=$xml->channel->item;
	
	$title ='';
	$link ='';
	$pub_date='';
	//for($i = 0; $i< 10; $i++ ){
	foreach($item as $it){
		$title = $it->title;
		$link = $it->link;
		
		$pub_date = $it->pubDate;
		if(mb_strlen($title) ==0){continue;}

		if(strpos($link, 'feedblog.ameba')){
			if(strpos($title,'PR:')=== 0 ){continue;}
			
			$opts = array(
			  'http'=>array(
			    'method'=>"GET",
			    'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
			    'header'=>"Accept-language: ja\r\n" .
			              "Cookie: foo=bar\r\n"
			  )
			);
			$url = $link;
			
			$context = stream_context_create($opts);
			$file = file_get_contents($url, false, $context);//URL,include_pathの使用、コンテキスト、読み込み開始のオフセット値、読み込み最大値。
			//$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
			$lines =preg_split('/\r?\n/', $file);
			$detail = '';
			$detail_flg=0;
			foreach($lines as $line){
	
				if($detail_flg==1 && strpos($line, '</div>')){
					break;
				}
				if($detail_flg==1){
					$detail = $detail.$line;
				}
				if($detail_flg == 0 && strpos($line, 'class="articleText"')){
					$detail_flg=1;
				}				
			}
		}else if(strpos($link, 'www.tokyogigguide.com')){
			$detail = $it->description;
			$genre = $it->category;				
		}else{
			
		}

		echo '<div style="width:49%; float:left;border-right:1px solid gray;">';		
		echo $title;
		echo '<br />';
		echo "<a href='$link'>Link</a>";
		echo '<br />';
		if(isset($detail)){
			echo preg_replace('%<[img|div][^>]*?>%', "", $detail);
			echo '<br />';			
		}
		if(isset($genre)){
			echo $genre;
			echo '<br />';
		}
		echo '<hr />';
		echo '</div>';		
	}
}

function utf8_for_xml($string){
    return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
}
//var_dump($xml->channel->item[2]);
//exit;
/*
$gNode = $item->children($nameSpaces['atom']);
print($gNode);
*/
?>
</body>
</html>