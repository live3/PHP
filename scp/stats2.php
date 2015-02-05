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
	<script src="./Chart.js-master/Chart.js"></script>
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
$lastMonth       = date('Y-m-d', strtotime('2014-03-28'));//date('Y-m-d', strtotime('-10 day'));
$today          = date('Y-m-d');

$metrics = 'ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる

// その他指定する場合は連想配列にまとめる
$optParams = array(
        'dimensions'=> 'ga:eventAction', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => 'ga:eventAction', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 1000,// 取得する最大件数
        'filters' => 'ga:eventCategory==StepViewController_Category',//StepViewController_HotOrNot
);

try {
    $results = $analytics->data_ga->get($analytics_id, $lastMonth, $today,$metrics, $optParams);
//    echo '<b>Number of visits/visitors this week:</b> ';
//    echo $results['totalsForAllResults']['ga:visits'];
//    echo '/';
//    echo $results['totalsForAllResults']['ga:visitors'];
    
	$list = array();
	foreach ($results['rows'] as $row => $value) {
	    $res = array();
	    foreach ($results['columnHeaders'] as $key => $header) {
	        $res[$header['name']] = $value[$key];
	    }
	    $list[] = $res;
	}    
	
	$category_result_array = array(0,0,0,0,0,0,0,0,0);
	
	foreach($list as $l){		
		$ar=explode(',',$l["ga:eventAction"]);
		
		for($i=0; $i<9;$i++){
			$category_result_array[$i]=$category_result_array[$i]+($ar[$i]*$l["ga:visitors"]);
		}
	
	}
//	var_dump($category_result_array);
	
	echo '<div class="col_6"><p>Popular genres / <span class="small">Users: '.$results['totalsForAllResults']['ga:visitors'].'</span></p>';
	echo '<canvas id="myChart" width="500" height="400"></canvas></div>';
	

} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
?>
<script>
//Get context with jQuery - using jQuery's .get() method.
var ctx = $("#myChart").get(0).getContext("2d");
//This will get the first returned node in the jQuery collection.
var data = {
	labels : ["Musical","Comedy","Movie","Music","NightLife","Sports","Food+Drink","Museum","Performance"],
	datasets : [
/*		{
			fillColor : "rgba(220,220,220,0.5)",
			strokeColor : "rgba(220,220,220,1)",
			data : [65,59,90,81,56,55,40]
		},*/
		{
			fillColor : "rgba(151,187,205,0.5)",
			strokeColor : "rgba(151,187,205,1)",
			data : [
<?php foreach($category_result_array as $c){ 
	echo $c . ',';
}
?>
			]
		}
	]
}

var myNewChart = new Chart(ctx).Bar(data);

</script>
<div class="cB"></div>
<hr />
<?php
date_default_timezone_set('Asia/Tokyo');
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
$result = mysql_query("SELECT * FROM lives");

while ($row = mysql_fetch_assoc($result)) {
	//$all_live_array[]= $row;
	$row['hot'] = 0;
	$row['not'] = 0;
	$all_live_array[$row['id']]= $row;
}
unset($all_live_array[0]);
mysql_close($link);	


$optParams = array(
        'dimensions'=> 'ga:eventAction', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => 'ga:eventAction', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 30000,// 取得する最大件数
        'filters' => 'ga:eventCategory==StepViewController_HotOrNot',//
);
$lastWeek       = date('Y-m-d', strtotime('-3 month'));
try {
    $results = $analytics->data_ga->get($analytics_id, $lastWeek, $today,$metrics, $optParams);
    

    
	$list = array();
	foreach ($results['rows'] as $row => $value) {
	    $res = array();
	    foreach ($results['columnHeaders'] as $key => $header) {
	        $res[$header['name']] = $value[$key];
	    }
	    $list[] = $res;
	}    

	foreach($list as $l){
	
		$visitors = $l["ga:visitors"];
		$ar=explode(',',$l["ga:eventAction"]);
		foreach($ar as $a){
			$val_id =explode('_',$a);
			$hot_or_not =(int)$val_id[0];
			if(!isset($val_id[1])){continue;}
			$id = $val_id[1];
			
			if($hot_or_not === 1){
				$all_live_array[$id]['hot'] = $all_live_array[$id]['hot']+$visitors;
			}else if($hot_or_not === 0){
				$all_live_array[$id]['not'] = $all_live_array[$id]['not']+$visitors;				
			}
			
		}
	
	}
	
/*
	var_dump($all_live_array);
	exit;
*/
	
	////order hot 
	usort($all_live_array, function($a, $b) {//hot の多い順に
		return $a['hot'] < $b['hot'];	
	});	
	$labels = '';
	$data_hot = '';
	$data_not = '';
	
	$cnt = 0 ;
	echo '<div class="col_6"><h2>Hot or Not</h2>';
	echo '<table class="table mB30"><tr><th>Hot</th><th>Not</th><th>Title</th><th></th></tr>';
	foreach($all_live_array as $al){
		if($al['not'] == 0 && $al['hot'] == 0 ){
			continue;
		}
		if($cnt == 30){break;}
		
		$labels = $labels."'" .str_replace("'","’",$al['live_title_ja']) ."',";
		$data_hot = $data_hot. $al['hot'] .',';
		$data_not = $data_not. $al['not'] .',';
	
		if((int)$al['hot'] >= (int)$al['not']){	
			echo '<tr><td>' . $al['hot'].'</td><td>' .$al['not'].'</td><td  style="color:red;">'.$al['live_title_ja']. '</td><td><a href="http://live3.info/events/'.$al['id'].'">Link</a></td></tr>';			
		}else{
			echo '<tr><td>' . $al['hot'].'</td><td>' .$al['not'].'</td><td  style="color:blue;">'.$al['live_title_ja']. '</td><td><a href="http://live3.info/events/'.$al['id'].'">Link</a></td></tr>';			
		}

	//	echo '<hr />';
		$cnt++;	
	}	
	echo '</table></div>';

	///order not
	usort($all_live_array, function($a, $b) {//hot の多い順に
		return $a['not'] < $b['not'];
	});	
	$cnt = 0 ;
	echo '<div class="col_6"><h2> Not or Hot</h2>';
	echo '<table class="table mB30"><tr><th>Hot</th><th>Not</th><th>Title</th><th></th></tr>';
	foreach($all_live_array as $al){
		if($al['not'] == 0 && $al['hot'] == 0 ){
			continue;
		}
		if($cnt == 30){break;}
		
		if((int)$al['hot'] >= (int)$al['not']){	
			echo '<tr><td>' . $al['hot'].'</td><td>' .$al['not'].'</td><td  style="color:red;">'.$al['live_title_ja']. '</td><td><a href="http://live3.info/events/'.$al['id'].'">Link</a></td></tr>';			
		}else{
			echo '<tr><td>' . $al['hot'].'</td><td>' .$al['not'].'</td><td  style="color:blue;">'.$al['live_title_ja']. '</td><td><a href="http://live3.info/events/'.$al['id'].'">Link</a></td></tr>';			
		}		
//		echo '<tr><td>' . $al['hot'].'</td><td>' .$al['not'].'</td><td>'.$al['live_title_ja']. '</td><td><a href="http://live3.info/events/'.$al['id'].'">Link</a></td></tr>';
	//	echo '<hr />';
		$cnt++;	
	}	
	echo '</table></div>';
	
	

	$cnt_per = 0;
	foreach($all_live_array as $al){
		if((int)$al['not']==0){
//			echo (int)$al['hot']/(int)$al['not'];	
			$all_live_array[$cnt_per]['percent'] = 0;
		}else{
			$all_live_array[$cnt_per]['percent'] = (int)$al['hot']/(int)$al['not'];
		}
		$cnt_per++;
	}	

	///order %
	usort($all_live_array, function($a, $b) {//hot の多い順に
		return $a['percent'] < $b['percent'];
	});	
	$cnt = 0 ;
	echo '<div style="clear:both;"></ div>';
	echo '<div class="col_6"><h2> Hot/Not</h2>';
	echo '<table class="table mB30"><tr><th>Percent</th><th> hot</th><th> not</th><th>Title</th><th></th></tr>';
	foreach($all_live_array as $al){
		if($al['not'] == 0 && $al['hot'] == 0 ){
			continue;
		}
		if($cnt == 30){break;}

			echo '<tr><td>' . round($al['percent'],2).'</td><td>' . $al['hot'].'</td><td>' .$al['not'].'</td><td>'.$al['live_title_ja']. '</td><td><a href="http://live3.info/events/'.$al['id'].'">Link</a></td></tr>';			

//		echo '<tr><td>' . $al['hot'].'</td><td>' .$al['not'].'</td><td>'.$al['live_title_ja']. '</td><td><a href="http://live3.info/events/'.$al['id'].'">Link</a></td></tr>';
	//	echo '<hr />';
		$cnt++;	
	}	
	echo '</table></div>';	
	
	

//	echo '<div class="col_12"><h2>Hot or Not</h2>';
//	echo '<canvas id="myChart2"　width="1200" ></canvas></div>';
	
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
?>

<script>
/*
//Get context with jQuery - using jQuery's .get() method.
var ctx2 = $("#myChart2").get(0).getContext("2d");
//This will get the first returned node in the jQuery collection.
var data2 = {
	labels : [<?php echo $labels?>],
	datasets : [
		{
			fillColor : "rgba(220,220,220,0.5)",
			strokeColor : "rgba(220,220,220,1)",
			data : [<?php echo $data_hot?>]
		},
		{
			fillColor : "rgba(151,187,205,0.5)",
			strokeColor : "rgba(151,187,205,1)",
			data : [<?php echo $data_not?>]
		}
	]
}

var myNewChart2 = new Chart(ctx2).Bar(data2);
*/
</script>

</body>
</html>