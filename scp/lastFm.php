<?php
//http://www.lastfm.jp/events/+place/Japan
$host = 'http://www.lastfm.jp/';
$source_url ="$host/events/+place/Japan?page=";

for($i = 1; $i < 50; $i++ ){//
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
    $link="";$title="";$date="";$shop="";$access="";$img="";	$genre="";		$housename='';
    $detail='';
    
    $artist_name="";$startdate="";
    $href_flg = 0;
    foreach($lines as $line){

    	if(strpos( $line,'id="eventResults"')){
    		$start_f=1;
    	}
    	if($start_f == 0){
	    	continue;
    	}    	
    	if(strpos($line,'class="day') ){    	
        	if(preg_match('%(\d{2}-\d{2}-\d{4})%',$line, $match)){
        		$date = $match[1];
        	}			
		}
    	
    	if(strpos($line,'data-title') ){
//			$href_flg = 1;
        	$title = strip_tags($line);
        	if(preg_match('%<span data[^>]*?>([^<]*?)<%',$line, $match)){
        		$title = $match[1];
        	}
        	if(preg_match('%(<img[^>]*?>)%',$line, $match)){
        		$img = $match[1];
        	}
        	if(preg_match('%<span class[^>]*?>([^<]*?)<%',$line, $match)){
        		$genre = $match[1];
        	}
			if(preg_match('%href="([^"]*)%',$line, $match)){
				$link = $match[1];
			}
			
        }
        
		if( strpos( $line,'href="/venues/') ){
			$housename = $line;
		}

		if( strpos( $line,'locality') ){
			$address = $line;
			
echo '<div style="width:49%; float:left;border-right:1px solid gray;">';		
    echo $date; 
echo '<br />';   
    echo $title;
echo '<br />';
    echo $img;    
echo '<br />';
    echo $genre;    
echo '<br />';
    echo "<a href='$host$link' target='_blank'>Link</a>";
echo '<br />';
    echo $housename;
echo '<br />';
    echo $address;
echo '<hr />';        
echo '</div>';
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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42716969-15', 'live3.info');
  ga('send', 'pageview');

</script>