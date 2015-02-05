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


<input id="chck_h" type="checkbox" name="1" value="1" checked="checked" onclick="boxclick(this,'houses')" >Houses
<!--
<input id="chck_sv" type="checkbox" name="2" value="2" checked="checked" onclick="boxclick(this,'SearchView')" >初回起動時
<input id="chck_mv" type="checkbox" name="3" value="3" checked="checked" onclick="boxclick(this,'MyPageView')" >全体
<input id="chck_gv" type="checkbox" name="4" value="4" checked="checked" onclick="boxclick(this,'GoogleMapsView')" >個別
-->

||


<?php 


$self_url = $_SERVER["REQUEST_URI"];
if(strstr( $self_url,'?')){
	
}else{
	$self_url =$self_url.'?dummy=0';
}

echo '<a href="./stats3.php">Clear!</a>||';
echo '<a href="'.$self_url.'&ec=SearchView">起動時</a>||';
echo '<a href="'.$self_url.'&ec=MyPageView">全体</a>||';
echo '<a href="'.$self_url.'&ec=GoogleMapsView">個別</a>||';


if(isset($_GET['more_ac'])){
	if($_GET['more_ac']==1){
		echo'<a href="'.$self_url.'&more_ac=0">質より数！</a>';
	}else{
		echo'<a href="'.$self_url.'&more_ac=1">もっと正確に！</a>'	;
	}
}else{
	echo'<a href="'.$self_url.'&more_ac=1">もっと正確に！</a>'	;
}

$eventCategory = 'SearchView';
if(isset($_GET['ec'])){
	$eventCategory = $_GET['ec'];
}	

?>



<?php

$count_search_view = 0;
$count_mypage_view = 0;
$count_googlemaps_view = 0;

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
$lastMonth       = date('Y-m-d', strtotime('-6 month'));//date('Y-m-d', strtotime('-10 day'));
$today          = date('Y-m-d');

$metrics = 'ga:visitors'; // 取得する指標(複数指定する場合は「,」でつなげる

// その他指定する場合は連想配列にまとめる
$optParams = array(
        'dimensions'=> 'ga:eventCategory,ga:eventAction,ga:eventLabel', // ディメンション(複数指定する場合は「,」でつなげる
        'sort' => 'ga:eventAction', // 何でソートするか(降順の場合は先頭に「-」をつける
        'max-results' => 10000,// 取得する最大件数
        'filters' => 'ga:eventAction==NewLocation;ga:eventCategory=='.$eventCategory,//https://developers.google.com/analytics/devguides/reporting/core/v3/reference?hl=ja#filters
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

	$result_array = array();

	
	foreach($list as $l){		
		$page = $l["ga:eventCategory"];
		$users = $l["ga:visitors"];
		$labels = $l["ga:eventLabel"];
		$label_arr = explode('@', $labels);
		$label_data = $label_arr[0];
		$datetime = $label_arr[1];

		preg_match_all('%([\d|\.]+)%', $label_data,$res);
		$latitude = $res[0][0];
		$longitude = $res[0][1];
		$accuracy = $res[0][2];
		$speed = $res[0][3];
		$course = $res[0][4];
		
		if(floatval($accuracy) > 100){
			if(isset($_GET['more_ac'])){
				continue;
			}
		}

		$result_array[]=array('lat'=> $latitude,'long'=> $longitude,'ac'=>$accuracy ,'sp'=> $speed,'c'=>$course,'pg'=>$page,'us'=>$users, 'date'=> $datetime );

		if( $page ==  'GoogleMapsView'){
			$count_googlemaps_view++;
		}else if( $page ==  'MyPageView'){
			$count_mypage_view++;
		}else if( $page ==  'SearchView'){
			$count_search_view++;
		}

	}
?>
<table class="table " style='width:95%;'>
	<tr><th>全件</th><th>表示中</th><th>全体</th><th>個別</th><th>起動時</th></tr>
	<tr>
		<th><?php echo count($list)?></th>
		<th><?php echo count($result_array)?></th>
		<th><?php echo $count_mypage_view?></th>
		<th><?php echo $count_googlemaps_view?></th>
		<th><?php echo $count_search_view?></th></tr>
</table>
<?php	
//get livehouses
date_default_timezone_set('Asia/Tokyo');
$all_house_array[] = array();
$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}
$result = mysql_query("SELECT * FROM houses");

while ($row = mysql_fetch_assoc($result)) {
$all_house_array[]= $row;
//	$row['hot'] = 0;
//	$row['not'] = 0;
//	$all_live_array[$row['id']]= $row;
}
unset($all_house_array[0]);
mysql_close($link);	
?>	

    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map_canvas { height: 100% }
    </style>
    <script type="text/javascript"src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAUwmLacw0Hmn_YyisWYHvrA2BqiRpkmow&sensor=false"></script>
    <script type="text/javascript">
      var markers = [];
      function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(35.664035, 139.698212),
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),mapOptions);
<?php	
	$cnt = 0;
	foreach($result_array as $r){
		$contentString = $r['date']."/".$r['ac']."/".$r['sp']."/".$r['pg']."/".$r['us'];
		echo 'var myLatlng'.$cnt.' = new google.maps.LatLng('.$r["lat"].', '.$r["long"].');';
		echo 'var marker'.$cnt.' = new google.maps.Marker({
			position: myLatlng'.$cnt.',
			map: map,';
		if( $r['pg'] ==  'GoogleMapsView'){
			echo 'icon: "/scp/assets/img/marker/marker_s.png",';
		}else if( $r['pg'] ==  'MyPageView'){
			echo 'icon: "/scp/assets/img/marker/marker_a.png",';		
		}else if( $r['pg'] ==  'SearchView'){
			echo 'icon: "/scp/assets/img/marker/marker_l.png",';		
		}
		echo 'title:"'.$r["pg"].'"
			});';

//mMng = new MarkerManager(map);
		echo 'markers.push(marker'.$cnt.');';			
		echo 'var infowindow'.$cnt.' = new google.maps.InfoWindow({
		    content:"'.$contentString.'"
		});';
		echo 'google.maps.event.addListener(marker'.$cnt.', "click", function() {
		  infowindow'.$cnt.'.open(map,marker'.$cnt.');
		  });';
		$cnt++;
	}

//for house///////////

	foreach($all_house_array as $r){
		//var_dump($r);
		if( strlen($r["house_latitude"])== 0 ){ continue;}
		/*
		if($cnt < 179){ $cnt++; continue; }
		if($cnt > 179){ break; }
		*/
		$contentString = $r['house_name_ja']."/".$r['house_address_ja']."/".$r['house_nearest_station_ja'];
		$contentString  = preg_replace( '/[\n\r]/', ' ', $contentString);
		$lat =$r["house_latitude"];
		$lng =$r["house_longitude"];
		if(is_null($lat )){$lat=0;}
		if(is_null($lng )){$lng=0;}		
		echo 'var myLatlng'.$cnt.' = new google.maps.LatLng('.$lat.', '.$lng.');';
		echo 'var marker'.$cnt.' = new google.maps.Marker({
			position: myLatlng'.$cnt.',
			map: map,
			icon: "/scp/assets/img/marker/marker_home.png",
			title:"houses"
			});	 ';
		echo 'markers.push(marker'.$cnt.');';			
		echo 'var infowindow'.$cnt.' = new google.maps.InfoWindow({
		    content:"'. str_replace('"', '', $contentString) .'"
		});';
		echo 'google.maps.event.addListener(marker'.$cnt.', "click", function() {
		  infowindow'.$cnt.'.open(map,marker'.$cnt.');
		  });';
		$cnt++;
	}

//	echo '<div class="col_6"><h2>Popular genres / <span class="small">Users: '.$results['totalsForAllResults']['ga:visitors'].'</span></h2>';
//	echo '<canvas id="myChart" width="500" height="400"></canvas></div>';
?>      
      }
      $(function(){
	     initialize(); 
      });

      function hide(category) {  
        for (var i=0; i<markers.length; i++) {
          if ( markers[i]['title'] == category){
            markers[i].setVisible(false)
          }
        }
      }

      function show(category) { 
        for (var i=0; i<markers.length; i++) {
          if ( markers[i]['title'] == category){
            markers[i].setVisible(true)
          }
        }
      }

      function boxclick(box,category) {
        if (box.checked) {
          show(category);
        } else {
          hide(category);
        }
      }
      
    </script>

 <div id="map_canvas" style="width:95%; height:95%;"></div>
            
<?php            
} catch(Exception $e) {
    echo 'There was an error : - ' . $e->getMessage();
}
?>

<script>
	
</script>


</body>
</html>