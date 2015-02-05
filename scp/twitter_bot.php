<?php
#!/usr/bin/php
date_default_timezone_set('Asia/Tokyo');
mb_language("Japanese");
mb_internal_encoding("UTF-8");

$api_key = '';
if(isset($_GET['api_key'])){
	$api_key = $_GET['api_key'];
}  
if(isset($argv)){
	$api_key = $argv[4];	
}

if($api_key !== 'hih4rh8rf4rsr8s4rh4srhsihfai4'){
	exit;
}

$path = realpath(dirname(__FILE__));
require_once $path.'/twitteroauth/twitteroauth.php';
define('CONSUMER_KEY', 'dMjBRWgeB0V7Y6z4O6mzJgrfN');
define('CONSUMER_SECRET', 'RUQYoHoifCAWjZursqqpa2J3Gc1GpU4U2OKghviXsxeQQkYW5i');
define('ACCESS_TOKEN', '2436464550-6PVxdGD2ALNvGPZA90D5qp7m65rDnH3ATRzLuhD');
define('ACCESS_TOKEN_SECRET', 'Ult7RaiiKPlIwfnY6R60URD8YcDM9DifttKUXBmje8Hls');

$array = dataFromDataBase();

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime("+1 days"));		

$today_arr = array();
$tomorrow_arr = array();
foreach($array as $ar){
	if(!isset($ar['live_start_date_short'])){continue;}
	if($ar['live_start_date_short'] == $today){
		$ar['ticket_price'] = getBestPrice($ar['id']);
		$ar['live_media_sources'] = getImage($ar['id']);
		$ar['free_flg'] = getFreeFlg($ar['ticket_price']);
		$today_arr[] = $ar;
	}elseif($ar['live_start_date_short'] == $tomorrow){
		$ar['ticket_price'] = getBestPrice($ar['id']);
		$ar['live_media_sources'] = getImage($ar['id']);
		$tomorrow_arr[] = $ar;
	}
}

$date_type = 0;
if(isset($_GET['date_type']) ){
	$date_type = $_GET['date_type']+0;	
}elseif(isset($argv[1])){
	$date_type = $argv[1]+0;	
}

$title_type = 0;
if(isset($_GET['title_type'])){
	$title_type = $_GET['title_type']+0;	
}elseif(isset($argv[2])){
	$title_type = $argv[2]+0;
}

$message_type = 2;
if(isset($_GET['message_type'])){
	$message_type = $_GET['message_type']+0;	
}elseif(isset($argv[3])){
	$message_type = $argv[3]+0;	
}

$title = "今日のイベント!!  ";
if($date_type == 1){
	$title = "明日のイベント!!  ";
}

if($title_type == 1){
	$title = "全部無料!!".$title;
}

$signature = "\n 詳細はLIVE3で→ 　http://deeplink.me/live3.info #LIVE3";

$body="";

if($date_type == 0){
	$body = createMessageTodayTest($today_arr,$title, $message_type);//0 house 1 time 2 station 3 station+house	
}elseif($date_type == 1){
	$body = createMessageTodayTest($tomorrow_arr,$title, $message_type);//0 house 1 time 2 station 3 station+house
}

if(mb_strlen($body) <10){
	echo 'bodyが10文字以下です。';
	exit;
}

$message = $body.$signature;

//tweet
$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,ACCESS_TOKEN,ACCESS_TOKEN_SECRET); 
$req = $connection->OAuthRequest("https://api.twitter.com/1.1/statuses/update.json","POST",array("status"=> $message ));

var_dump($req);


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


function dataFromDataBase(){
	$live_array[] =  array();
	$link = mysql_connect('live3app2.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
	if (!$link) {
	    print(mysql_error());
	}
	mysql_query("SET NAMES utf8",$link); 
	$db_selected = mysql_select_db('live3appdb', $link);
	if (!$db_selected){
	    die('データベース選択失敗です。'.mysql_error());
	}
	
	$result = mysql_query('select l.id,l.live_title_ja,l.live_start_date,h.house_name_ja,h.house_nearest_station_name_ja,l.deleted_at_flg,count(lms.id) from lives l left join live_media_sources lms on l.id = lms.live_id inner join houses h on l.house_id = h.id group by l.id order by l.live_start_date');
	
	while ($row = mysql_fetch_assoc($result)) {
		$start_date = $row['live_start_date'];
//		$row['live_start_date'] = date('Y-m-d H:i:s', strtotime("$start_date +9 hours"));
		$row['live_start_date_short'] = date('Y-m-d', strtotime("$start_date"));
		
		$live_array[]= $row;
	}	
	mysql_close($link);
	return $live_array;
	
}

function getImage($live_id){
	$array =  array();
	$link = mysql_connect('live3app2.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
	if (!$link) {
	    print(mysql_error());
	}
	mysql_query("SET NAMES utf8",$link); 
	$db_selected = mysql_select_db('live3appdb', $link);
	if (!$db_selected){
	    die('データベース選択失敗です。'.mysql_error());
	}
	
	$result = mysql_query("select lms.live_id,lms.live_media_type,lms.live_media_source_material,lms.live_media_source_seq_no from live_media_sources lms where lms.live_media_type = 1 AND lms.live_id = $live_id order by lms.live_media_source_seq_no ;");
	
	while ($row = mysql_fetch_assoc($result)) {
		$array[]= $row;
	}	
	mysql_close($link);	
	return $array;
}

function getBestPrice($live_id){
	$array =  array();
	$link = mysql_connect('live3app2.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
	if (!$link) {
	    print(mysql_error());
	}
	mysql_query("SET NAMES utf8",$link); 
	$db_selected = mysql_select_db('live3appdb', $link);
	if (!$db_selected){
	    die('データベース選択失敗です。'.mysql_error());
	}

	$result = mysql_query("select tk.ticket_price from tickets tk where tk.live_id = $live_id order by tk.ticket_price;");

	while ($row = mysql_fetch_assoc($result)) {
		if(!isset($row['ticket_price'])){continue;}
		$array[]= $row;
	}	
	mysql_close($link);	
	return $array[0]['ticket_price'];
}

function getFreeFlg($ticket_price){
	$ticket_price+=0;
	if($ticket_price == 0){
		return 1;
	}else{
		return 0;
	}	
}


function createMessageTodayTest($lives_arr,$title, $type){
	$limit = 80; //暫定。
	
	$message = $title;//"今日の注目ライブ!! "
	
	foreach($lives_arr as $ar){
		if( mb_strlen( $message ) > $limit){ break;} 
		
		$live_title = str_replace(' ', '', $ar["live_title_ja"]);
		$live_title = str_replace('　', '', $live_title);
		if(mb_strlen($live_title) > 10 ){
			$message = $message." #".mb_substr($live_title, 0,10).".."; 	
		}else{
			$message = $message." #".$live_title;
		}
		
		
		if($type == 0){
			$message = $message."@ #".$ar["house_name_ja"]." | ";			
		}elseif($type == 1){
			$message = $message."@".$ar["live_start_date"]." | ";
		}elseif($type == 2){
			$message = $message."@ #".$ar["house_nearest_station_name_ja"]." | ";		
		}elseif($type == 3){
			$message = $message."@ #".$ar["house_nearest_station_name_ja"]." ".$ar["house_name_ja"]." | ";
		}

	}

	return $message;

}

