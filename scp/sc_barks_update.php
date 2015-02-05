<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

$sqlite_number = 0;
if(isset($_POST['sqlite_number'])){
	$sqlite_number = $_POST['sqlite_number'];
}else{
	echo 'sqlite Number None!!!';
	exit;
}

$db = new SQLite3('barks_artist'.$sqlite_number.'.sqlite');


$post_band_id = 0;
if(isset($_POST['band_id'])){
	$post_band_id = $_POST['band_id'];
}
$post_sqlite_band_id = 0;
if(isset($_POST['sqlite_band_id'])){
	$post_sqlite_band_id = $_POST['sqlite_band_id'];
}

if($post_band_id == 0 || $post_sqlite_band_id == 0){
	echo 'invalid params';
	exit;
}

$state = "select * from bands where id = $post_sqlite_band_id";
$result = $db->query($state );
$start_i = 1;
$sqlite_bands_array = array();

$sqlite_description = '';
while( $row = $result->fetchArray() ) {
	$id = (int)$row['id'];
	$sqlite_description = $row['description1'];
	$sqlite_band_name = $row['name'];
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

//translate
$sqlite_description_array = mb_str_split($sqlite_description, 900);

function mb_str_split($str, $split_len = 1) {//マルチバイト分割 str_splitのマルチバイト版が無い
    mb_internal_encoding('UTF-8');
    mb_regex_encoding('UTF-8');
    if ($split_len <= 0) {
        $split_len = 1;
    }
    $strlen = mb_strlen($str, 'UTF-8');
    $ret    = array();
    for ($i = 0; $i < $strlen; $i += $split_len) {
        $ret[ ] = mb_substr($str, $i, $split_len);
    }
    return $ret;
}

$sqlite_description_en='';
foreach($sqlite_description_array as $desc_short ){
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
}

echo $sqlite_band_name; echo'<br />';
echo $sqlite_description;
echo '<hr />';
echo $sqlite_description_en;
echo '<hr />';

//update ja 
$sql = sprintf("UPDATE bands SET band_description_ja = %s WHERE id = %s", quote_smart($sqlite_description), quote_smart($post_band_id));
$result_flag = mysql_query($sql);
echo 'result ja: '. $result_flag;
echo'<br />';
$sql_en = sprintf("UPDATE bands SET band_description_en = %s WHERE id = %s", quote_smart($sqlite_description_en), quote_smart($post_band_id));
$result_flag_en = mysql_query($sql_en);
echo 'result en: '. $result_flag_en;
echo'<br />';
$sql_zh = sprintf("UPDATE bands SET band_description_zh = %s WHERE id = %s", quote_smart($sqlite_description_en), quote_smart($post_band_id));
$result_flag_zh = mysql_query($sql_zh);
echo 'result zh: '. $result_flag_zh;

/*
var_dump($sqlite_bands_array);
exit;
*/

function quote_smart($value)
{
    // 数値以外をクオートする
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}
exit;


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