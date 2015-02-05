<?php session_start(); ?>
<!doctype html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<link href="./assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="./assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/tools.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/grid.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="./assets/plugins/jquery.bxslider/jquery.bxslider.css" type="text/css" />
	<link href="./assets/css/pages/pricing-tables.css" rel="stylesheet" type="text/css"/>
	<link href="./assets/css/ex_style.css" rel="stylesheet" type="text/css"/>	
	<title>stats</title>
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

<!-- jQuery Library --> 
<script src="./assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script> 
<script src="./assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script> 
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip --> 
<script src="./assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script> 
<script src="./assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<!--[if lt IE 9]>
	<script src="./assets/plugins/excanvas.min.js"></script>
	<script src="./assets/plugins/respond.min.js"></script>  
	<![endif]--> 
<script src="./assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<script src="./assets/plugins/jquery.blockui.min.js" type="text/javascript"></script> 

<div id="active_user" class="col_8">
<?php
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
$lastWeek       = date('Y-m-d', strtotime('-1 week'));//date('Y-m-d', strtotime('-10 day'));
$today          = date('Y-m-d');

$metrics = 'ga:visits,ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる

// その他指定する場合は連想配列にまとめる
$optParams = array(
        'dimensions'=> 'ga:date', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => 'ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 10,// 取得する最大件数
);

try {
    $results = $analytics->data_ga->get($analytics_id, $lastWeek, $today,$metrics, $optParams);
	
    echo '<b>Number of visits/visitors this week:</b> ';
    echo $results['totalsForAllResults']['ga:visits'];
    echo '/';
    echo $results['totalsForAllResults']['ga:visitors'];

	$list = array();
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

date_default_timezone_set('Asia/Tokyo');
$all_user_array[] = array();
$link = mysql_connect('live3app2.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
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
SELECT
     DATE_FORMAT(created_at, '%Y%m%d') as regist_time,
     count(*)
FROM(
     (SELECT
          n.created_at
     FROM
         not_user_devices as n
     )
UNION
     (SELECT
          f.created_at
     FROM
         fan_users as f
     )
)AS uni
GROUP BY
    DATE_FORMAT(regist_time, '%Y%m%d')");

while ($row = mysql_fetch_assoc($result)) {
	$all_user_array[]= $row;
}
unset($all_user_array[0]);
//mysql_close($link);

//1ヶ月以内に開いたユーザーの数
$monthly_updated_users_count = 0; 
$result = mysql_query("select count(*) from not_user_devices where updated_at > (select NOW() - INTERVAL 1 MONTH)");
while ($row = mysql_fetch_assoc($result)) {
	$monthly_updated_users_count = $row["count(*)"];
}
mysql_close($link);		

?>						
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>date</th>
            <th>visits</th>
            <th>visitors</th>
            <th>all users</th>
            <th>DAU</th>
            <th>uu/mu</th>
        </tr>
    </thead>
    <tbody>
    <?php
	    $user_count =600;//0;/暫定
	    $user_count_week_ago =0;
		foreach ($all_user_array as $user) {//1週間前までの総ユーザー数を計算
	    	if( $user['regist_time'] < date('Ymd', strtotime('-1 week')) ){
				$user_count +=$user["count(*)"];
	    	}else{
				break;
			}
	    }
	    
        foreach ($list as $val) {
            echo "<tr>";
            $date = $val['ga:date'];
            $visits = $val['ga:visits'];
            $visitors = $val['ga:visitors'];			            
            echo "<td>$date</td>";
            echo "<td>$visits</td>";
            echo "<td>$visitors</td>";
            
			foreach ($all_user_array as $user) {
                if( $val['ga:date'] == $user['regist_time'] ){
                	$user_count += $user["count(*)"];
	                echo "<td>$user_count</td>";
	                
	                $dau = round(($val['ga:visitors'] /$user_count), 3)*100;
	                echo "<td>$dau</td>";
	                
					$mdau = round(($val['ga:visitors'] / $monthly_updated_users_count ), 3)*100;            
					echo "<td>$mdau</td>";

                }			                
            }
            echo "</tr>";
        }
    ?>
    </tbody>
</table>



<?php 
//week
foreach ($all_user_array as $user) {//1週間前までの総ユーザー数を計算
	if( $user['regist_time'] < date('Ymd', strtotime('last Saturday')) ){
		$user_count_week_ago +=$user["count(*)"];
	}else{
    	break;
	}
}

$WeekBeforeLast = date('Y-m-d', strtotime('-2 week'));//date('Y-m-d', strtotime('-10 day'));
$metrics = 'ga:visits,ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる

// その他指定する場合は連想配列にまとめる
$optParams = array(
        'dimensions'=> 'ga:week', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => 'ga:week', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 2,// 取得する最大件数
);

try {
    $results = $analytics->data_ga->get($analytics_id, $WeekBeforeLast, $today,$metrics, $optParams);

    echo '<b>Number of visits/visitors last 2 week:</b> ';
    echo $results['totalsForAllResults']['ga:visits'];
    echo '/';
    echo $results['totalsForAllResults']['ga:visitors'];			    

    
	$list = array();
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
?>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>week</th>
            <th>visits</th>
            <th>visitors</th>
            <th>all users</th>
            <th>WAU</th>
        </tr>
    </thead>
    <tbody>
<?php			    
//var_dump($list);
            echo "<tr>";
            $date = $list[0]['ga:week'];
            $visits = $list[0]['ga:visits'];
            $visitors = $list[0]['ga:visitors'];			            
            echo "<td>$date</td>";
            echo "<td>$visits</td>";
            echo "<td>$visitors</td>";
			echo "<td>$user_count_week_ago</td>";
			$wau = round(($visitors /$user_count_week_ago), 3)*100;
			echo "<td>$wau</td>";			            
            echo "</tr>";	
            
            echo "<tr>";
            $date = $list[1]['ga:week'];
            $visits = $list[1]['ga:visits'];
            $visitors = $list[1]['ga:visitors'];			            
            echo "<td>$date</td>";
            echo "<td>$visits</td>";
            echo "<td>$visitors</td>";
			echo "<td>$user_count</td>";
			$wau = round(($visitors /$user_count), 3)*100;
			echo "<td>$wau</td>";			            
            echo "</tr>";			            		            
?>			    
    </tbody>
</table>

<?php 
//month
$user_count_last_month=0;
foreach ($all_user_array as $user) {//月初までの総ユーザー数を計算
	if( $user['regist_time'] < date('Ym01') ){
		$user_count_last_month +=$user["count(*)"];
	}else{
    	break;
	}
}
$lastMonth = date('Y-m-d', strtotime('-3 month'));//date('Y-m-d', strtotime('-10 day'));
$metrics = 'ga:visits,ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる

// その他指定する場合は連想配列にまとめる
$optParams = array(
	'dimensions'=> 'ga:month', // ディメンション(複数指定する場合は「,」でつなげる
	'sort' => 'ga:month', // 何でソートするか(降順の場合は先頭に「-」をつける
	'max-results' => 3,// 取得する最大件数
);

try {
    $results = $analytics->data_ga->get($analytics_id, $lastMonth, $today,$metrics, $optParams);

    echo '<b>Number of visits/visitors last 2 month:</b> ';
    echo $results['totalsForAllResults']['ga:visits'];
    echo '/';
    echo $results['totalsForAllResults']['ga:visitors'];			    

	$list = array();
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
//			var_dump($list);	
?>

<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>month</th>
            <th>visits</th>
            <th>visitors</th>
            <th>all users</th>
            <th>MAU</th>
        </tr>
    </thead>
    <tbody>
<?php			    
$user_count_last_month += 600; 
        echo "<tr>";
        $date = $list[1]['ga:month'];
        $visits = $list[1]['ga:visits'];
        $visitors = $list[1]['ga:visitors'];			            
        echo "<td>$date</td>";
        echo "<td>$visits</td>";
        echo "<td>$visitors</td>";
		echo "<td>$user_count_last_month</td>";
		$wau = round(($visitors /$user_count_last_month), 3)*100;
		echo "<td>$wau</td>";			            
        echo "</tr>";	
        
        echo "<tr>";
        $date = $list[2]['ga:month'];
        $visits = $list[2]['ga:visits'];
        $visitors = $list[2]['ga:visitors'];			            
        echo "<td>$date</td>";
        echo "<td>$visits</td>";
        echo "<td>$visitors</td>";
		echo "<td>$user_count</td>";
		$wau = round(($visitors /$user_count), 3)*100;
		echo "<td>$wau</td>";			            
        echo "</tr>";			            	            		            
?>			    
    </tbody>
</table>

</div>

<div id="shared_users" class="col_4">
<?php
//Shared guys
$optParams = array(
        'dimensions'=> 'ga:eventAction,ga:eventLabel,ga:dimension2,ga:date', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => '-ga:date', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 1000,// 取得する最大件数
        'filters' => 'ga:eventAction==shared Facebook,ga:eventAction==shared Twitter,ga:eventAction==shared Mail,ga:eventAction==shared Message,ga:eventAction==shared Line',
);
$all_days       = date('Y-m-d', strtotime('-2 months'));//暫定
try {
    $results = $analytics->data_ga->get($analytics_id, $all_days, $today,$metrics, $optParams);

    echo '<b>Number of visits/visitors last 2 m:</b> ';
    echo $results['totalsForAllResults']['ga:visits'];
    echo '/';
    echo $results['totalsForAllResults']['ga:visitors'];

	$list = array();
	foreach ($results['rows'] as $row => $value) {
	    $res = array();
	    foreach ($results['columnHeaders'] as $key => $header) {
	        $res[$header['name']] = $value[$key];
	    }
	    $list[] = $res;
	}    
	
	$shared_user_array[] = array();
	foreach($list as $l){
		$deviceTokenString = $l['ga:dimension2'];		
		$temp_arr = getUserProf($deviceTokenString);
		$temp_arr['date'] = $l['ga:date'];
		$temp_arr['event_id'] = str_replace('id:', '', $l['ga:eventLabel']);
		$temp_arr['event_action'] = $l["ga:eventAction"];
		$shared_user_array[] = $temp_arr;
//		$visitors = $l["ga:visitors"];
//		$ar=explode(',',$l["ga:eventAction"]);
	}
	
	unset($shared_user_array[0]);
	
	echo '<table class="table table-bordered table-striped table-hover"<tr><th colspan="2">Shared Users</th></tr>';
	foreach($shared_user_array as $s){

		echo '<tr>';
		$date = $s['date'];
		echo "<td>$date</td>";		
		$event_link = 'http://live3.info/events/'.$s['event_id'];
		$event_title = getEventTitle($s['event_id']);
		echo "<td><a href='$event_link'>$event_title</a></td>";		
		if(isset($s['facebook_id'])){
			$link = 'http://facebook.com/'.$s['facebook_id'];
			$name = $s['facebook_name'];			
			echo "<td><a href='$link'>$name</a></td>";		
		}else{
			echo "<td></td>";		
		}
		$event_action = $s['event_action'];
		echo "<td>$event_action</td>";		
		
		echo '</tr>';
	}	
	echo '</table>';
		
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}

function getUserProf($deviceTokenString){
	$shared_user_array_result[] = array();
	$link = mysql_connect('live3app2.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
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

function getEventTitle($event_id){
	$link = mysql_connect('live3app2.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
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
	select * from lives 
	where `id`= '$event_id'
	");
	
	while ($row = mysql_fetch_assoc($result)) {
		if(isset($row['live_title_ja'])){
			return $row['live_title_ja'];
		}else{
			return ;
		}	
	}
	mysql_close($link);	
}
?>
</div>


</body>
</html>