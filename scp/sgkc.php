<!doctype html>
<html lang="en">
<head>
	<meta charset="shift_jis" />
	<title>sgck</title>
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
	
<?php
//songkcik for scraping
$db = new SQLite3('sgkc.db');

/*
class NewDB extends SQLite3{
    function __construct() {
        $this->open('sgkc.sqlite');
    }
}
$db = new NewDB();
*/
$db->exec('CREATE TABLE IF NOT EXISTS live (id INTEGER PRIMARY KEY ,title TEXT,frigana TEXT, link TEXT,image TEXT,genre TEXT, price Text, artist TEXT, rate Text,date TEXT,time TEXT, city TEXT,shop TEXT,access TEXT,etc TEXT, address TEXT, station TEXT, tel TEXT, latitude TEXT, longitude TEXT, images TEXT, iflyer_id TEXT, artist_new TEXT, station TEXT, station_one_latlng TEXT, station_line TEXT , station_name TEXT , station_detail TEXT, youtubes TEXT, shop_new TEXT, datetime TEXT, price_new TEXT, price_multi_flg INTEGER, description TEXT, station_new TEXT, datetime_type TEXT, image_new TEXT )');

$source_url = 'http://www.songkick.com/metro_areas/30717-japan-tokyo?page=';
$host = 'http://www.songkick.com/';

$cnt=0;
$pages = array();
for($i = 1; $i<10; $i++){
	$url =$source_url.$i;
	if (!fopen($source_url,'r')) {
		break;		
	}

    // ストリーム(オプション)を作成します
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
        'header'=>"Accept-language: ja\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );
    $context = stream_context_create($opts);
    // 上で設定した HTTP ヘッダを使用してファイルをオープンします
    $file = file_get_contents($url, false, $context);//URL,include_pathの使用、コンテキスト、読み込み開始のオフセット値、読み込み最大値。
    //$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
    //行毎に分割。配列内の各要素が1行。
    $lines =preg_split('/\r?\n/', $file);

    $start_f=0;    $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $access_f=0;  $clm_f=0;
    $link="";$title="";$date="";$shop="";$access="";$img="";
    
    $artist_name="";$startdate="";
    $href_flg = 0;

    foreach($lines as $line){
    
    	if(strpos($line,'class="artists summary')){
			$href_flg = 1;
        }     
        if($href_flg == 1 && preg_match('%href="([^"]*)%',$line, $match)){
			$link = $match[1];
			$href_flg = 0;	   
        }
    	if(strpos($line,'strong itemprop="name"')){
			$artist_name = strip_tags($line);

			$result_array =array();
			$result_array =detailPage($host,$link);
			
			$link = $host.$link;
			$startdate = c($result_array['startdate'],$db);
			$image = c($result_array['image'],$db);
			$housename = c($result_array['housename'],$db);
			$address = c($result_array['address'],$db);
			$lineup = c($result_array['lineup'],$db);
			$detail = c($result_array['detail'],$db);
			$songkick_id　=0;
			if(preg_match('%\d{6,9}%', $link, $match)){
				$songkick_id = $match[0];
			}			
			
			$update_flg=0;
			$state = "SELECT * FROM live where id = $songkick_id";
			$result = $db->query($state );
			while( $row = $result->fetchArray() ) {
				$update_flg=1;
				break;
				echo "update!!".'<br />';
			}
			
			if($update_flg == 1){	
			echo "Update live set link ='$link', date = '$startdate', image = '$image', shop = '$housename', address = '$address', artist = '$lineup', description = '$detail' where id = $songkick_id";
				$db->exec("Update live set link ='$link', date = '$startdate', image = '$image', shop = '$housename', address = '$address', artist = '$lineup', description = '$detail',title = '$artist_name' where id = $songkick_id");				
			}else{
				$db->exec("INSERT INTO live (id, link, date, image, shop, address,artist,description,title ) VALUES (\"$songkick_id\",\"$link\",\"$startdate\",\"$image\" ,\"$housename\" ,\"$address\",\"$lineup\" ,\"$detail\" ,\"$artist_name\" )");	
				echo "insert!!".'<br />';											
			}

			$link = '';
			$startdate = '';
			$image = '';
			$housename = '';
			$address = '';
			$lineup = '';
			$detail = '';
echo '</hr>';

        }    	
/*
//list
$title = c($title,$db);
$link = c($link,$db);
$date = c($date,$db);
$img  = c($img ,$db);
$shop  = c($shop ,$db);
$frigna = c($frigna,$db);
$city  = c($city ,$db);
$genre  = c($genre ,$db);
$time  = c($time ,$db);
$price  = c($price ,$db);
$artist  = c($artist ,$db);
$rate = c($rate,$db);
$read = c($read,$db);
			//$db->exec("INSERT INTO live (title,link ,date ) VALUES(\"$title\",\"$link\",\"$date\")");							
			$db->exec("INSERT INTO live (title,link ,date ,image ,shop ,frigana,city,genre,time,price,artist,rate,etc ) VALUES(\"$title\",\"$link\",\"$date\",\"$img\" ,\"$shop\" ,\"$frigna\",\"$city\" ,\"$genre\" ,\"$time\" ,\"$price\" ,\"$artist\" ,\"$rate\",\"$read\"  )");	
			//$db->exec("INSERT INTO live (title ) VALUES(\"$title\")");	
//$db->exec('CREATE TABLE IF NOT EXISTS live (id INTEGER PRIMARY KEY ,title TEXT,firigna TEXT, link TEXT,image TEXT,genre TEXT, price Text, artist TEXT, rate Text,date TEXT,time TEXT, city TEXT,shop TEXT,access TEXT,etc TEXT)');				
*/

 	$cnt++;   		
    }


}

//$db->exec('delete from live where city != "        TOKYO, JAPAN"');

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
	$address_flg=0;
	$lineup_flg=0;
	$detail_flg=0;
	$result_array =array();;

    foreach($lines as $line){
		if( strpos( $line,'itemprop="startDate"') ){
			if(preg_match('%datetime="([^"]*)%', $line, $match)){
				$startdate = $match[1];
			}
		}

		if( strpos( $line,'class="profile-picture') ){
			if(preg_match('%src="([^"]*)%', $line, $match)){
				$img = str_replace("/medium_avatar", '/col6', $match[1]); 
			}
		}		

		if( strpos( $line,'href="/venues/') && strlen($housename)==0 ){
			$housename = trim(strip_tags($line));
		}
					
		if( $address_flg==1 && strpos($line,'</p>')){
			$address_flg=2;
		}
		if($address_flg==1){
			$address = $address.$line;
		}
		if( strpos( $line,'class="adr"') ){
			$address_flg=1;
			$address = "";
		}		
		
		if( $lineup_flg==1 && strpos($line,'</ul>')){
			$lineup_flg=2;
		}		
		if( $lineup_flg==0 && strpos( $line,'<h4>Line-up</h4>') ){
			$lineup_flg=1;
			$lineup = '';
		}		
		if($lineup_flg==1 && strpos($line,'<a ')){
			if(strlen($lineup) != 0 ){
				$lineup = $lineup.',';			
			}
			$lineup = $lineup.'['.trim($line).']';
		}

		if( $detail_flg ==1 && strpos($line,'footer') ){ 
			$detail_flg=2;
		}		
		if( $detail_flg ==1){
			$detail = $detail.$line;
		}
		if( $detail_flg==0 && strpos( $line,'<h2>Additional details') ){
			$detail_flg=1;
			$detail = '';
		}		

	}	
	$result_array['startdate'] = $startdate;
	$result_array['image'] = $img;
	$result_array['housename'] = $housename;
	$result_array['address'] = $address;
	$result_array['lineup'] = $lineup;	
	$result_array['detail'] = $detail;	
	return $result_array;	
}

function c($text, $db){
//	$text=sqlite_escape_string($text);
	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
	$text=str_replace("'", '’', $text);
	return $text;
}




?>

</body>
</html>