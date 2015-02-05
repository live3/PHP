<?php
header("Access-Control-Allow-Origin:http://live3.info:3000");
//header("Access-Control-Allow-Origin:http://live3.info:3000");
header('Content-Type: application/json');

set_include_path("./lib/google-api-php-client/src/" . PATH_SEPARATOR . get_include_path());			
require('Google/Client.php');
require('Google/Service/Analytics.php');
			
if(!isset($_GET['key'])){exit;}			
if($_GET['key'] != 'fhjkladhjkf8y374fy734yr78t3w8tr98w983'){exit;}
			
$client_id = '147998632799-8ov9g257qcltchd00lus50ba2q46r5sn.apps.googleusercontent.com';
$service_account_name = '147998632799-8ov9g257qcltchd00lus50ba2q46r5sn@developer.gserviceaccount.com';

$keyfile = './lib/9b147973f60012b49af0bf617766b594351fa203-privatekey.p12';
$redirect_url = 'http://util.live3.info/scp/mokushi.php';
//$client_secret = 'notasecret';

// Initialise the Google Client object
$client = new Google_Client();
$client->setApplicationName('analyticsiOSLiv3');
$client->setRedirectUri($redirect_url);
//$client->setClientSecret($client_secret);

$client->setAssertionCredentials(
        new Google_Auth_AssertionCredentials(
            $service_account_name,
            array('https://www.googleapis.com/auth/analytics'),
            file_get_contents($keyfile)
        )
);

$uuid = '';//'599B28BE-D2C0-4E8A-BCEB-FCA4EA30785B';
if(isset($_GET['uuid'])){
	$uuid =	$_GET['uuid'];
}
$deviceToken = '';//180c8232f2b93e7a655848e6b97021fff0703bd838d8b5de1cbe60b64c63653a
if(isset($_GET['deviceToken'])){
	$deviceToken =	$_GET['deviceToken'];
}
$post_month = 12;//全期間
if(isset($_GET['month'])){
	$post_month =	$_GET['month'];
}

// Get this from the Google Console, API Access page
$client->setClientId($client_id);
$client->setAccessType('offline_access');
$analytics = new Google_Service_Analytics($client);			

$analytics_id   = 'ga:80534488';//ビューID
$start_day       = date('Y-m-d', strtotime("-$post_month month"));//date('Y-m-d', strtotime('-10 day'));
$end_day          = date('Y-m-d');

$result_month_data = get_sv_asd_svp_by_month($analytics, $analytics_id, $end_day, $start_day,$uuid,$deviceToken);
$result_screen_data = get_screen_by_week($analytics, $analytics_id, $end_day, $start_day,$uuid,$deviceToken);


$result_for_json = array();
$result_for_json['month_data'] = $result_month_data;
$result_for_json['screen_data'] = $result_screen_data;

echo json_encode($result_for_json);
exit;



function get_screen_by_week($analytics, $analytics_id, $end_day, $start_day,$uuid,$deviceToken){
	$metrics = 'ga:users,ga:screenviews,ga:avgScreenviewDuration,ga:uniqueScreenviews'; // 取得する指標(複数指定する場合は「,」でつなげる
	//$metrics = 'ga:visits,ga:visitors';
	$dimensions = 'ga:screenName,ga:dimension1,ga:week,ga:year';//ga:screenName,ga:sessionCount';//ga:city,
	$filters = "ga:dimension1==$uuid";
	
	if( strlen($uuid) == 0 ){//uuid
		$dimensions = str_replace('dimension1', 'dimension2', $dimensions);
		$filters = "ga:dimension2==$deviceToken";
	}
	
	$optParams = array(
	        'dimensions'=> $dimensions, // ディメンション(複数指定する場合は「,」でつなげる
	        'sort' => 'ga:week,-ga:screenviews', // 何でソートするか(降順の場合は先頭に「-」をつける
	        'max-results' => 1000,// 取得する最大件数
	        'filters' => $filters
	);	
	
	$analytics_rows = array();
	$analytics_headers = array();
	try {
	    $results = $analytics->data_ga->get($analytics_id, $start_day, $end_day,$metrics, $optParams);
	    $analytics_rows =array();
		if(isset($results['rows'])){
			$analytics_rows = $results['rows'];			
		}
		$analytics_headers = $results['columnHeaders'];
	} catch(Exception $e) {
	    echo 'There was an error : - ' . $e->getMessage();
	}
	
	$analytics_db_result = array();
	foreach($analytics_rows as $usd){
	
		$result = array(); 

		for($i=0;$i< count($usd); $i++ ){		
			$data_header = $analytics_headers[$i]['name'];
			$result[$data_header]=$usd[$i];
		}
		$analytics_db_result[] = $result;
	}
	
	return $analytics_db_result;
}


function get_sv_asd_svp_by_month($analytics, $analytics_id, $end_day, $start_day,$uuid,$deviceToken){
	$metrics = 'ga:users,ga:screenviews,ga:avgSessionDuration,ga:screenviewsPerSession'; // 取得する指標(複数指定する場合は「,」でつなげる
	//$metrics = 'ga:visits,ga:visitors';
	$dimensions = 'ga:month,ga:dimension1';//ga:screenName,ga:sessionCount';//ga:city,
	$filters = "ga:dimension1==$uuid";
	
	if( strlen($uuid) == 0 ){//uuid
		$dimensions = str_replace('dimension1', 'dimension2', $dimensions);
		$filters = "ga:dimension2==$deviceToken";
	}
	
	$optParams = array(
	        'dimensions'=> $dimensions, // ディメンション(複数指定する場合は「,」でつなげる
	        'sort' => 'ga:month', // 何でソートするか(降順の場合は先頭に「-」をつける
	        'max-results' => 1000,// 取得する最大件数
	        'filters' => $filters
	);	
	
	$analytics_rows = array();
	$analytics_headers = array();
	try {
	    $results = $analytics->data_ga->get($analytics_id, $start_day, $end_day,$metrics, $optParams);
	
	    $analytics_rows =array();
		if(isset($results['rows'])){
			$analytics_rows = $results['rows'];			
		}
		$analytics_headers = $results['columnHeaders'];
	} catch(Exception $e) {
	    echo 'There was an error : - ' . $e->getMessage();
	}
	
	$analytics_db_result = array();
	foreach($analytics_rows as $usd){
	
		$result = array(); 

		for($i=0;$i< count($usd); $i++ ){
			$data_header = $analytics_headers[$i]['name'];
			$result[$data_header]=$usd[$i];
		}
		$analytics_db_result[] = $result;
	}
	
	return $analytics_db_result;
}


/********************************************/

///Mysql
$ticket_buys = array();
/*
$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}
//$result = mysql_query('select l.id,l.live_title_ja,l.live_start_date,l.deleted_at_flg,count(lms.id) from lives l left join live_media_sources lms on l.id = lms.live_id group by l.id order by l.live_start_date');
$result = mysql_query("SELECT * FROM ticket_buys where live_id = $event_id");

while ($row = mysql_fetch_assoc($result)) {
	$row['str_date'] = str_replace('-', '', substr($row['created_at'], 0,10));
	$ticket_buys[]= $row;
}
unset($ticket_buys[0]);
*/

//var_dump($ticket_buys);
//var_dump($users_sv_date);




function getUserProf($deviceTokenStringOrUuid){
	$shared_user_array_result[] = array();
	$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
	if (!$link) {
	    print(mysql_error());
	}
	mysql_query("SET NAMES utf8",$link); 
	$db_selected = mysql_select_db('live3appdb', $link);
	if (!$db_selected){
	    die('データベース選択失敗です。'.mysql_error());
	}
	//$result = mysql_query('select l.id,l.live_title_ja,l.live_start_date,l.deleted_at_flg,count(lms.id) from lives l left join live_media_sources lms on l.id = lms.live_id group by l.id order by l.live_start_date');
	$result = mysql_query("
	select * from fan_users 
	inner join fan_user_devices on fan_users.id = fan_user_devices.fan_user_id 
	inner join facebook_users on fan_users.id = facebook_users.fan_user_id  
	where `fan_user_device_token`= '$deviceTokenString'
	");
	
	while ($row = mysql_fetch_assoc($result)) {
	
		if(isset($row['facebook_id'])){
			return array('facebook_name'=>$row['facebook_name'],'facebook_id'=> $row['facebook_id']);
		}else{
			return ;
		}	
	}
	unset($shared_user_array_result[0]);
	mysql_close($link);	
}


?>
