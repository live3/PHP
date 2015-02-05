<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

$db = new SQLite3('natalie_artist.sqlite');

$db->exec('CREATE TABLE IF NOT EXISTS bands (id INTEGER PRIMARY KEY ,name TEXT,nameF TEXT, link TEXT,image TEXT,member TEXT, description1 Text, description2 TEXT, rate Text,description3 TEXT )');

$state = "select * from bands";
$result = $db->query($state );
$start_i = 1;
$sqlite_bands_array = array();
//DBと一致確認
while( $row = $result->fetchArray() ) {
	$id = (int)$row['id'];
//	$sqlite_bands_array[] = $row['name'];
	$sqlite_bands_array[$id] = mb_convert_kana($row['name'],'KV');

	//特定のアーティストを出す exit忘れずに
/*
	if(mb_strstr(mb_convert_kana($row['name'], 'KV'), "でんぱ")){
		echo $row['name'];
		echo '<hr />';
		echo $row['description1'];
		echo '<hr />';
		echo tranlate_temp($row['description1']);
		echo '<hr />';
	}
*/
}
//exit;

function tranlate_temp($desc_short){
	$sqlite_description_en='';
		$url = 'http://util.live3.info/scp/bing_transV2.php?param='.urlencode($desc_short).'&key=nfjkanjkfnkad7i3riqhf3qffji3aljfj';
		$headers = array(
		    'header' => "Content-Type: text/xml"
		);
		$options = array('http' => array(
		    'method' => 'POST',
			'header' => implode("\r\n", $headers),
		));
		$xml_trans = file_get_contents($url, false, stream_context_create($options));
		$xml = simplexml_load_string($xml_trans);
		$sqlite_description_en = $sqlite_description_en.($xml->elem->data);

	return $sqlite_description_en;
}


//LIVE3 DB
$all_live_array[] = array();
$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}
$result = mysql_query("SELECT * FROM bands");

/*
var_dump($sqlite_bands_array);
exit;
*/

//$sqlite_bands_array=array('10-FEET');
$match_c = 0;
while ($row = mysql_fetch_assoc($result)) {
	//$all_live_array[]= $row;
	$band_name_ja =  $row['band_name_ja'];
	$mysql_band_id =  $row['id'];
	$band_desc =  $row['band_description_ja'];
	$band_desc_flg = 0;
	if(mb_strlen($band_desc) > 10){ $band_desc_flg=1; }

//echo mb_substr(conv($band_name_ja), -3,3);echo'<br />';

//	echo $band_name_ja;echo'<hr />';
//  continue;	
//	$key = array_search($band_name_ja, $sqlite_bands_array);

  	if($band_desc_flg === 0){//descrtiptionが無ければ
		$temp_match_f = 0;
		foreach($sqlite_bands_array as $sqlite_band_id => $sqlite_name ){		
/*
			if(strpos($sqlite_name, 'the ') ){continue;}//とりあえず！！ the 系は除く
			if(mb_strpos($sqlite_name, 'The ') ){continue;}//とりあえず！！ the 系は除く
			if(mb_strpos($sqlite_name, 'THE ') ){continue;}//とりあえず！！ the 系は除く
*/
//			if (strstr($sqlite_name, mb_substr($band_name_ja, 0,5))) {
			if ( mb_strstr(conv($sqlite_name),mb_substr(conv($band_name_ja), 0,4))) {			
//			if (  urlencode(trim($sqlite_name)) == urlencode(trim($band_name_ja)) ) {
				echo $band_name_ja.'||'. $sqlite_name;
				echo "<form action='./__natalie_update.php' method='post' target='_blank'>
				<input type='hidden' name='band_id' value='$mysql_band_id' />
				<input type='hidden' name='sqlite_band_id' value='$sqlite_band_id' />
				<input type='submit' />
				</form>";
				echo '<hr />';
			  	$temp_match_f = 1;
			 } else {
			 }
		}	  	
	  	$match_c = $match_c+ $temp_match_f;
	  	
  	}


	
//	echo $key;
	
//	echo '<hr />';
	
//	$all_live_array[$row['id']]= $row;
}
unset($all_live_array[0]);
mysql_close($link);	

echo $match_c;

exit;

function conv($str){
	$str =str_replace(' ', '', $str);
	$str =str_replace('　', '', $str);
	$str =str_replace('"', '', $str);
	$str =str_replace("'", '', $str);
	$str = mb_convert_kana($str,'KHV');
	return $str;
}

///scraping
while( $row = $result->fetchArray() ) {
	$id = (int)$row['id'];
	if( $id > $start_i){
		$start_i=$id;	
	}
	echo $row['name'];
	echo '<hr />';
}

echo $start_i;
exit;

$end_i = 11112;//140718

$host = 'http://natalie.mu/';
$url = 'http://natalie.mu/music/artist/';//無料 ライブ
for($i=$start_i; $i <= $end_i; $i++ ){
	echo $i;
	echo '<hr />';

	$url ='http://natalie.mu/music/artist/'.$i;
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C28 Safari/419.3", 
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
    	if(strpos( $line,'id="NA_main"')){
    		$start_f=1;
    	}
    	if($start_f == 0){
	    	continue;
    	}    	
    	
    	if(strpos($line,'class="profile-image"') ){
	    	if(preg_match('%src="([^"]*)%',$line, $match)){
	    		$img = "http://cdn2.natalie.mu/".$match[1];
	    	}
        }    
    	if(strpos($line,'<h1>') ){
    		$title= strip_tags($line);
    	}
    	if(strpos($line,'class="NA_ruby"') ){
    		$title_f= strip_tags($line);
    	}
    	if(strpos($line,'class="NA_profileText"') && $detail == "" ){
    		$detail = strip_tags($line);   		
    	}
    	if(strpos($line,'class="NA_profileSummary"') && $detail == "" ){
    		$detail = strip_tags($line);  		
    	}    	
    	
         
        if($title !== "" && $img != "" && $detail !="" ){
			 $title=c($title,$db);
			 $title_f=c($title_f,$db);
			 $detail=c($detail,$db);
			$db->exec("INSERT INTO bands (id,name, nameF, image,  description1,link ) VALUES (\"$i\",\"$title\",\"$title_f\",\"$img\",\"$detail\",\"$url\")");
//			$db->exec("Update bands set name ='$name', nameF = '$nameF', image = '$image',  description1 = '$detail' where id = $i ");			
			break;
        }
    }
}

function c($text, $db){
//	$text=sqlite_escape_string($text);
//	$text=mb_convert_encoding($text, "UTF-8",'auto');
//	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
	$text=str_replace("'", '’', $text);
	return $text;
}
?>

<!--



$host = 'http://natalie.mu/';
$url = 'http://natalie.mu/music/search/news/query/free%20live';//無料 ライブ
$url ='http://natalie.mu/music/search/news/query/無料%20ライブ';
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C28 Safari/419.3", 
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
    echo $line;
    	if(strpos( $line,'id="search-articles"')){
    		$start_f=1;
    	}
    	if($start_f == 0){
	    	continue;
    	}    	
    	
    	if($href_flg == 0 && strpos($line,'class="news-title"') ){
			$href_flg = 1;
			echo $line.'<hr />';
        }     
        if($href_flg == 1 && preg_match('%href="([^"]*)%',$line, $match)){
			$link = $match[1];
			$href_flg = 0;	
			$title = strip_tags($line);
			
			$detail='';
			$detail =detailPage($host,$link);
			echo $title;
			echo $detail;
			echo '<hr />';			
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


-->