<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42716969-15', 'live3.info');
  ga('send', 'pageview');

</script>

<h5>Let's enjoy Tokyo</h5>
<script src="./jquery.min.js"></script>
<script>
$(function(){
	$(".enmae_list").click(function(){
		$("#ename").val($(this).text());
	});
});
</script>
<button class='enmae_list btn'>クラシック</button>
<button class='enmae_list btn'>無料</button>
<button class='enmae_list btn'>入場 無料</button>


<form action="" method="post">
	<input id="ename" type="text" name='ename' value='入場 無料'/>
	<label>Concert?<input type="checkbox" name='type'/></label>
	<input type="submit" class='btn' />
</form>

<?php

$ename = '%E5%85%A5%E5%A0%B4%E3%80%80%E7%84%A1%E6%96%99';
$path = 'search/event/';
if(isset($_POST['ename'])){
	$ename = $_POST['ename'];
}else{
	exit;
}
if(isset($_POST['type']) && $_POST['type']== 'on'  ){
	$path = 'concert/event/list/';	
	echo "<p>$ename";
	echo "  // concert On</p><hr/>";
}else{
	echo "<p>$ename";
	echo "  //  concert Off</p><hr/>";	
}

$host = 'http://www.enjoytokyo.jp/';
$source_url ="$host$path?ename=$ename&sort=1&page=";//入場　無料

for($i = 1; $i < 20; $i++ ){
	$url = $source_url.$i;	
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
        'header'=>"Accept-language: ja\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );
    $context = stream_context_create($opts);
    $file = file_get_contents($url, false, $context);
    //$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
    $lines =preg_split('/\r?\n/', $file);

    $start_f=0;    $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $access_f=0;  $clm_f=0;
    $link="";$title="";$date="";$shop="";$access="";$img="";			
    $detail='';
    
    $artist_name="";$startdate="";
    $href_flg = 0;
    foreach($lines as $line){

    	if(strpos( $line,'id="result_list01"')){
    		$start_f=1;
    	}
    	if($start_f == 0){
	    	continue;
    	}    	
    	
    	if($href_flg == 0 && strpos($line,'<h4>') ){
//			$href_flg = 1;
        	$title = $title.$line;
        }
		if($date_f == 1 && strpos($line,'</span>') ){
			$date_f = 0;
        	$date = $date.strip_tags($line);
        	
        }
    	if($date_f == 0 && strpos($line,'rl_main') ){
			$date_f = 1;
        }      
        
        
        if($shop_f==1 ){
			$shop = $line;
			$shop_f=0;
			

    echo $date;
    echo '>>>>>';
    echo str_replace('href="', 'target="_blank" href="http://www.enjoytokyo.jp/', $shop);
    //http://www.enjoytokyo.jp/
    
echo '<br />';
    echo str_replace('href="', 'target="_blank" href="http://www.enjoytokyo.jp/', $title);
echo '<hr />';        
			$title='';
			$date='';
			$shop='';			
        }
    	if($shop_f==0 && strpos($line,'rl_shop_title') ){
			$shop='';
			$shop_f=1;					
        }		
    }


}

function detailPage($host,$url){
	$url = $host.$url;
	$opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
        'header'=>"Accept-language: ja\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );
    $context = stream_context_create($opts);
    $file = file_get_contents($url, false, $context);//URL,include_pathの使用、コンテキスト、読み込み開始のオフセット値、読み込み最大値。
    //$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
    $lines =preg_split('/\r?\n/', $file);
    
    $housename='';
    $lineup='';
    $detail='';
    $detail_start_f = 0;
    
	$address_flg=0;
	$lineup_flg=0;
	$detail_flg=0;
	$result_array =array();;

    foreach($lines as $line){
		if( $detail_start_f == 0 && strpos( $line,'id="news-text"') ){
			$detail_start_f = 1;		
		}
		if( $detail_start_f == 0){
			continue;
		}
		if($detail_start_f == 1 && strpos($line, 'id="news-link"')){
			$detail_start_f = 2;	
			break;		
		}
		
		if( $detail_start_f == 1){
			$detail = $detail.$line;
		}		
	}
	return $detail;	
}

function c($text, $db){
	$text = str_replace(":", "-", $text);
/*
	$text=sqlite_escape_string($text);
	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
*/
	return $text;
}




?>