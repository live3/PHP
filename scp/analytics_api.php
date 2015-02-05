<?php session_start(); ?>
<?php
header("Access-Control-Allow-Origin:http://live3.info");
//header("Access-Control-Allow-Origin:http://live3.info:3000");
header('Content-Type: application/json');

if(isset($argv)){
	foreach($argv as $strArg){
		$arrTmp = explode('=', $strArg);
		$_REQUEST[$arrTmp[0]] = $arrTmp[1];
	}
}
if(isset($_REQUEST)){
	if(isset($_REQUEST['startTime'])){
		$startTime = $_REQUEST['startTime'];	
	}
	if(isset($_REQUEST['endTime'])){	
		$endTime = $_REQUEST['endTime'];
	}
	if(isset($_REQUEST['deviseToken'])){	
		$deviseToken = $_REQUEST['deviseToken'];
	}
	if(isset($_REQUEST['uuid'])){	
		$uuid = $_REQUEST['uuid'];	
	}
}

if(!isset($startTime)){$startTime='2014-03-28';}//'yesterday';}
if(!isset($endTime) ){$endTime='today';}
if(!isset($deviseToken) ){$deviseToken='245DF44C-88D5-4471-86F4-E6215701AE7C';}
if(!isset($uuid) ){$uuid='180c8232f2b93e7a655848e6b97021fff0703bd838d8b5de1cbe60b64c63653a';}


set_include_path("./lib/google-api-php-client/src/" . PATH_SEPARATOR . get_include_path());			
require('Google/Client.php');
require('Google/Service/Analytics.php');
			
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

// Get this from the Google Console, API Access page
$client->setClientId($client_id);
$client->setAccessType('offline_access');
$analytics = new Google_Service_Analytics($client);			

$analytics_id   = 'ga:80534488';//ビューID

//goto a;

/////:01
//特定のユーザーの全てのイベントを取得　start/////////////////////////////////
$metrics = 'ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる
//まずUUIDで取得を試みる
$optParams = array(
        'dimensions'=> 'ga:eventCategory,ga:eventAction,ga:eventLabel,ga:dimension1,ga:dimension2,ga:date',
        'sort' => '-ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 10000,// 取得する最大件数
        'filters' => 'ga:dimension2=='.$uuid
);
$all_results = array();
try {
    $results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
	if(isset($results['rows'])){
		$all_results['events'] = $results['rows'];
	}else{
		//deviseTokenで試すために書き換え
		$optParams['filters'] = 'ga:dimension1=='.$deviseToken;
		try {
			$results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
			if(isset($results['rows'])){
				$all_results['events'] = $results['rows'];
			}else{
				$all_results['events'] = '';
			}
		} catch(Exception $e) {
			echo 'There was an error : - ' . $e->getMessage();
		}
		
	}    
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
//特定のユーザーの全てのイベントを取得　end/////////////////////////////////


/////:02
//特定のユーザーのデバイス、ネットワーク環境を取得　start/////////////////////////////////
$metrics = 'ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる
//まずUUIDで取得を試みる
$optParams = array(
        'dimensions'=> 'ga:mobileDeviceInfo,ga:networkDomain,ga:networkLocation,ga:dimension1,ga:dimension2,ga:date',
        'sort' => '-ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 10000,// 取得する最大件数
        'filters' => 'ga:dimension2=='.$uuid
);
try {
    $results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
	if(isset($results['rows'])){
		$all_results['networks'] = $results['rows'];
	}else{
		//deviseTokenで試すために書き換え
		$optParams['filters'] = 'ga:dimension1=='.$deviseToken;
		try {
			$results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
			if(isset($results['rows'])){
				$all_results['networks'] = $results['rows'];
			}else{
				$all_results['networks'] = '';
			}
		} catch(Exception $e) {
			echo 'There was an error : - ' . $e->getMessage();
		}
		
	}
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
//特定のユーザーのデバイス、ネットワーク環境を取得　end/////////////////////////////////

/////:03
//特定のユーザーの位置情報を取得　start/////////////////////////////////
$metrics = 'ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる
//まずUUIDで取得を試みる
$optParams = array(
        'dimensions'=> 'ga:region,ga:city,ga:dimension1,ga:dimension2,ga:date',
        'sort' => '-ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 10000,// 取得する最大件数
        'filters' => 'ga:dimension2=='.$uuid
);
try {
    $results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
	if(isset($results['rows'])){
		$all_results['locations'] = $results['rows'];		
	}else{
		//deviseTokenで試すために書き換え
		$optParams['filters'] = 'ga:dimension1=='.$deviseToken;
		try {
			$results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
			if(isset($results['rows'])){
				$all_results['locations'] = $results['rows'];
			}else{
				$all_results['locations'] = '';
			}
		} catch(Exception $e) {
			echo 'There was an error : - ' . $e->getMessage();
		}
		
	}
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
//特定のユーザーの位置情報を取得　end/////////////////////////////////

/////:04
//特定のユーザーのページビュー数、滞在時間を取得　start/////////////////////////////////
$metrics = 'ga:visitors,ga:screenviews,ga:appviews,ga:uniqueScreenviews,ga:uniqueAppviews,ga:screenviewsPerSession,ga:appviewsPerVisit,ga:timeOnScreen,ga:avgScreenviewDuration'; // 取得する指標(複数指定する場合は「,」でつなげる
//まずUUIDで取得を試みる
$optParams = array(
        'dimensions'=> 'ga:landingScreenName,ga:screenName,ga:screenDepth,ga:exitScreenName,ga:dimension1,ga:dimension2,ga:date',
        'sort' => '-ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 10000,// 取得する最大件数
        'filters' => 'ga:dimension2=='.$uuid
);
try {
    $results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
	if(isset($results['rows'])){	
		$all_results['screens'] = $results['rows'];
		$all_results['screenTotals'] = $results['totalsForAllResults'];
	}else{
		//deviseTokenで試すために書き換え
		$optParams['filters'] = 'ga:dimension1=='.$deviseToken;
		try {
			$results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
			if(isset($results['rows'])){
				$all_results['screens'] = $results['rows'];
				$all_results['screenTotals'] = $results['totalsForAllResults'];
			}else{
				$all_results['screens'] ='';
				$all_results['screenTotals'] ='';
			}
		} catch(Exception $e) {
			echo 'There was an error : - ' . $e->getMessage();
		}	
	}
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
//特定のユーザーのページビュー数、滞在時間を取得　end/////////////////////////////////


//a://goto::

/////:05
//特定のユーザーの新規リターン、際のごのセッションからの経過時間、セッションカウント、アクセス時間01-24を取得　start/////////////////////////////////
$metrics = 'ga:visits,ga:bounces';//'ga:visitors,ga:bounces'; // 取得する指標(複数指定する場合は「,」でつなげる
//まずUUIDで取得を試みる

//////dimension 
//
//ga:sessionCount 
//ga:bounces (The total number of single page (or single engagement hit) sessions for your property.)
$optParams = array(
        'dimensions'=> 'ga:userType,ga:daysSinceLastSession,ga:sessionCount,ga:dimension1,ga:dimension2,ga:date,ga:hour',
        'sort' => '-ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 10000,// 取得する最大件数
        'filters' => 'ga:dimension2=='.$uuid
);
try {
    $results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
	if(isset($results['rows'])){	
		$all_results['frequency'] = $results['rows'];		
	}else{
		//deviseTokenで試すために書き換え
		$optParams['filters'] = 'ga:dimension1=='.$deviseToken;
		try {
			$results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
			if(isset($results['rows'])){
				$all_results['frequency'] = $results['rows'];
			}else{
				$all_results['frequency'] = '';
			}
		} catch(Exception $e) {
			echo 'There was an error : - ' . $e->getMessage();
		}	
	}
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
//特定のユーザーの新規リターン、際のごのセッションからの経過時間、セッションカウント、アクセス時間01-24を取得　end/////////////////////////////////


//var_dump($all_results);
echo json_encode($all_results);
exit;

/************************************************  元のコード控え  *************************************************************************************************
*******************************************************************************************************************************************************************
*******************************************************************************************************************************************************************/


/*
$lastWeek       = date('Y-m-d', strtotime('-1 week'));//date('Y-m-d', strtotime('-10 day'));
$today          = date('Y-m-d');
*/

//&start-date=yesterday
//&end-date=today

/*
$metrics = 'ga:visits,ga:visitors';//取得する指標(複数指定する場合は「,」でつなげる
// その他指定する場合は連想配列にまとめる
$optParams = array(
        'dimensions'=> 'ga:date', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => 'ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 1000,// 取得する最大件数
);
*/
$metrics = 'ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる
//dimension1 vendorUUID//dimension2 deviseToken//dimension3 userNameFB//dimension4 userNameBirthDay//dimension5 userLinkFB//dimension6 gender

//dimensions => の値 7つまで
//https://developers.google.com/analytics/devguides/reporting/core/dimsmets#mode=api&cats=custom_variables_or_columns,user,event_tracking

$optParams = array(
        'dimensions'=> 'ga:eventCategory,ga:eventAction,ga:eventLabel,ga:dimension1,ga:dimension2', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => 'ga:eventCategory', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 10000,// 取得する最大件数
        'filters' => 'ga:dimension1=='.$deviseToken,//StepViewController_HotOrNot        
//        'filters' => 'ga:dimension2=='.$uuid,//StepViewController_HotOrNot        
//        'filters' => 'ga:eventAction==NewLocation',//StepViewController_HotOrNot
);

//https://www.google.com/analytics/web/?hl=ja&pli=1#report/app-content-pages/a42716969w77911020p80534488/%3F_u.date00%3D20140803%26_u.date01%3D20140813%26explorer-table.secSegmentId%3Danalytics.customDimension2%26explorer-table.plotKeys%3D%5B%5D/

try {
    $results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);
	
/*
    echo '<b>Number of visits/visitors this week:</b> ';
    echo $results['totalsForAllResults']['ga:visits'];
    echo '/';
    echo $results['totalsForAllResults']['ga:visitors'];
*/

	$list = array();

	if(isset($results['rows'])){
		var_dump($results['rows']);		
	}else{
		//uuidで試すために書き換え
		$optParams['filters'] = 'ga:dimension2=='.$uuid;
		try {
			$results = $analytics->data_ga->get($analytics_id, $startTime,$endTime ,$metrics, $optParams);		
		} catch(Exception $e) {
			echo 'There was an error : - ' . $e->getMessage();
		}
		
	}

	exit;
	
	foreach ($results['rows'] as $row => $value) {
		
	
	    $res = array();
	    foreach ($results['columnHeaders'] as $key => $header) {
	        $res[$header['name']] = $value[$key];
	    }
	    $list[] = $res;
	}    
    
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
