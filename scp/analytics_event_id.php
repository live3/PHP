<?php
header("Access-Control-Allow-Origin:http://live3.info:3000");
//header("Access-Control-Allow-Origin:http://live3.info:3000");
header('Content-Type: application/json');

set_include_path("./lib/google-api-php-client/src/" . PATH_SEPARATOR . get_include_path());			
require('Google/Client.php');
require('Google/Service/Analytics.php');
			
//if(!isset($_POST['key'])){exit;}			
//if($_POST['key'] != 'fhjkladhjkf8y374fy734yr78t3w8tr98w983'){exit;}
			
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

$event_id = '9577';
if(isset($_GET['event_id'])){
	$event_id =	$_GET['event_id'];
}
$post_month = 1;
if(isset($_GET['month'])){
	$post_month =	$_GET['month'];
}

// Get this from the Google Console, API Access page
$client->setClientId($client_id);
$client->setAccessType('offline_access');
$analytics = new Google_Service_Analytics($client);			

$analytics_id   = 'ga:80534488';//ビューID
$lastMonth       = date('Y-m-d', strtotime("-$post_month month"));//date('Y-m-d', strtotime('-10 day'));
$today          = date('Y-m-d');

$metrics = 'ga:users,ga:screenviews'; // 取得する指標(複数指定する場合は「,」でつなげる

$detailView = 'DetailViewController-'.$event_id;

// その他指定する場合は連想配列にまとめる
$optParams = array(
        'dimensions'=> 'ga:date', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => 'ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 1000,// 取得する最大件数
        'filters' => "ga:screenName==$detailView",//StepViewController_HotOrNot
);

$users_sv_date = array();

try {
    $results = $analytics->data_ga->get($analytics_id, $lastMonth, $today,$metrics, $optParams);

	$users_sv_date = $results['rows'];

} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}


///Mysql
$ticket_buys = array();
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

//var_dump($ticket_buys);
//var_dump($users_sv_date);

$analytics_db_result = array();
foreach($users_sv_date as $usd){
	$result = array(); 
	$result['date']=$usd[0];
	$result['users']=$usd[1];
	$result['screenviews']=$usd[2];
	
	$result['conv_user'] = 0;
	$result['conv_count'] = 0;
	foreach($ticket_buys as $tb){
		if($result['date'] == $tb['str_date']){
			$result['conv_user'] = $result['conv_user']+1;
			$result['conv_count'] = $result['conv_count']+$tb['ticket_buy_count'];
		}	
	}
	
	$analytics_db_result[] = $result;
}

//var_dump($analytics_db_result);
echo json_encode($analytics_db_result);
exit;

?>
