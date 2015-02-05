<?php
date_default_timezone_set('Asia/Tokyo');
class NewDB extends SQLite3{
    function __construct() {
        $this->open('fb_event.sqlite');
    }
}
$db = new NewDB();

$db->exec('CREATE TABLE IF NOT EXISTS events (id INTEGER PRIMARY KEY ,
description TEXT,
is_date_only TEXT,
location TEXT,
name TEXT,
owner_name TEXT,
owner_id TEXT,
privacy TEXT,
start_time TEXT,
end_time TEXT,
timezone TEXT,
updated_time TEXT,
venue_id TEXT,
venue_city TEXT,
venue_country TEXT,
venue_latitude TEXT,
venue_longitude TEXT,
venue_state TEXT,
event_id TEXT,
pic TEXT,
pic_big TEXT,
pic_small TEXT,
pic_cover TEXT,
attending_count TEXT,
all_members_count TEXT,
ticket_uri TEXT
 )');

require '/var/www/html/scp/facebook-php-sdk/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
//live_test
$facebook = new Facebook(array(
  'appId'  => '572978279428847',
  'secret' => '21a27882f4b387351477d846c22f3f86',
));

  	$logout = $facebook->getLogoutUrl();
/*
  	$facebook->destroySession();
  	$facebook->setAccessToken('');      
    echo 'Please <a href="' . $logout . '">logout.</a>';      
*/

$user_id = $facebook->getUser();
?>
 <?php
if(isset($user_id)) {
  // We have a user ID, so probably a logged in user.
  // If not, we'll get an exception, which we handle below.
  try {

//        $user_profile = $facebook->api('/me','GET');
//        echo "Name: " . $user_profile['name'] . '<br />';

//      $location = '東京';
      $since = date("Y-m-d");
      $until = date("Y-m-d",strtotime("+ 31 day"));
      $query = 'ライブ';//'イベント'
      $q_array = array('ライブ','イベント','live','gig','コンサート','concert','フェス','fes','%20');
      
foreach($q_array as $query){

//      $events= $facebook->api("/search?q=$location&type=event&since=$since&until=$until",'GET');
      $events= $facebook->api("/search?q=$query&type=event&since=$since&until=$until&center=35.65,139.7&distance=5000",'GET');
//	  var_dump($events);
$result = array();

foreach($events['data'] as $e){
	$eid= $e['id'];
//	$response = $facebook->api("/$eid");

$query = "SELECT pic,pic_big,pic_small,eid, name, pic_cover, start_time, end_time, location, description,location,is_date_only,privacy,timezone,venue,update_time, attending_count,all_members_count,ticket_uri, creator FROM event WHERE eid=$eid";
$response = $facebook->api(array('method' => 'fql.query','query' => $query));
	//$result[] = $response;
$response = $response[0];	


$description = c($response['description']);
$is_date_only =c($response['is_date_only']);
$location =c($response['location']);
$name =c($response['name']);
$owner_name ='';//$response['owner']['name'];
$owner_id =$response['creator'];//$response['owner']['id'];
$privacy =c($response['privacy']);
$start_time =c($response['start_time']);
$end_time =c($response['end_time']);
$updated_time =c($response['update_time']);//graphだとupdated?
$venue_id =c($response['venue']['id']);
$venue_city =c($response['venue']['city']);
$venue_country =c($response['venue']['country']);
$venue_latitude =c($response['venue']['latitude']);
$venue_longitude =c($response['venue']['longitude']);
$venue_state =c($response['venue']['state']);
$pic = c($response['pic']);
$pic_big = c($response['pic_big']);
$pic_small =c($response['pic_small']);
$pic_cover =c($response['pic_cover']["source"]);
$attending_count = c($response['attending_count']);
$all_members_count = c($response['all_members_count']);
$ticket_uri = c($response['ticket_uri']);

	
	$update_flg=0;
	$state = "SELECT * FROM events where event_id = $eid";
	$result = $db->query($state );
	while( $row = $result->fetchArray() ) {
		$update_flg=1;
		break;
	}
	
	if($update_flg == 1){	
		$db->exec("Update events set 
description = '$description',
is_date_only = '$is_date_only',
location = '$location',
name = '$name',
owner_name = '$owner_name',
owner_id = '$owner_id',
privacy = '$privacy',
start_time = '$start_time',
end_time = '$end_time',
updated_time = '$updated_time',
venue_id = '$venue_id',
venue_city = '$venue_city',
venue_country = '$venue_country',
venue_latitude = '$venue_latitude',
venue_longitude = '$venue_longitude',
venue_state = '$venue_state',
pic ='$pic',
pic_big ='$pic_big',
pic_small ='$pic_small',
pic_cover ='$pic_cover',
attending_count = '$attending_count',
all_members_count ='$all_members_count',
ticket_uri = '$ticket_uri'
where event_id = $eid

");				
	}else{
		$db->exec("INSERT INTO events (
description,
is_date_only,
location,
name,
owner_name,
owner_id,
privacy,
start_time,
end_time,
updated_time,
venue_id,
venue_city,
venue_country,
venue_latitude,
venue_longitude,
venue_state,
event_id,
pic,
pic_big,
pic_small,
pic_cover,
attending_count,
all_members_count,
ticket_uri
) VALUES (
'$description',
'$is_date_only',
'$location',
'$name',
'$owner_name',
'$owner_id',
'$privacy',
'$start_time',
'$end_time',
'$updated_time',
'$venue_id',
'$venue_city',
'$venue_country',
'$venue_latitude',
'$venue_longitude',
'$venue_state',
'$eid',
'$pic',
'$pic_big',
'$pic_small',
'$pic_cover',
'$attending_count',
'$all_members_count',
'$ticket_uri'
)");	
		echo "insert!!".'<br />';											
	}

	
}


}//foreach    
//var_dump($result);


/*
[0]=>
  array(11) {
    ["description"]=>
    string(902) "四天王寺の食堂虹の仏、２周年おめでとう企画！"
    ["is_date_only"]=>
    bool(false)
    ["location"]=>
    string(16) "食堂 虹の仏"
    ["name"]=>
    string(48) "虹の仏２周年記念アラブ音楽ライブ"
    ["owner"]=>
    array(2) {
      ["name"]=>
      string(17) "Kiyotaka Moriuchi"
      ["id"]=>
      string(10) "1200688013"
    }
    ["privacy"]=>
    string(4) "OPEN"
    ["start_time"]=>
    string(24) "2014-04-29T19:00:00+0900"
    ["timezone"]=>
    string(10) "Asia/Tokyo"
    ["updated_time"]=>
    string(24) "2014-03-16T06:40:05+0000"
    ["venue"]=>
    array(6) {
      ["id"]=>
      string(15) "367683463264406"
      ["city"]=>
      string(9) "Osaka-shi"
      ["country"]=>
      string(5) "Japan"
      ["latitude"]=>
      float(34.6561534389)
      ["longitude"]=>
      float(135.513972059)
      ["state"]=>
      string(5) "Osaka"
    }
    ["id"]=>
    string(15) "445951672203508"
  }
  */



///////////////////////// using fql  /////////////////////////////

	  //$query = 'SELECT uid2 FROM friend WHERE uid1=me()';//my friends
//	  $query = ' SELECT page_id FROM place WHERE distance(latitude, longitude, "37.76", "-122.427") < 1000';//near place
	  $query = " SELECT eid, name, pic_cover, start_time, end_time, location, description, attending_count FROM event 
		  WHERE 
		  	eid IN ( SELECT eid FROM event_member WHERE uid = 630271447011839 ) 
		  AND 
		  	(start_time >= now() OR end_time >= now())
		  ORDER BY 
		  	start_time ASC";//near 
		  	
$created_time = 'now()';		  	
$lat = "35.6";
$long = "139.6";
// using offset gives us a "square" on the map from where to search the events
$offset = 0.4;	
$limit = '5000';
$query = 'SELECT pic_big, name, venue, location, start_time, eid FROM event WHERE eid IN (SELECT eid FROM event_member WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND start_time > '. $created_time .' OR uid = me()) AND start_time > '. $created_time .' AND venue.longitude < \''. ($long+$offset) .'\' AND venue.latitude < \''. ($lat+$offset) .'\' AND venue.longitude > \''. ($long-$offset) .'\' AND venue.latitude > \''. ($lat-$offset) .'\' ORDER BY start_time ASC ';

		  	
//$query = "SELECT name,location FROM event WHERE contains('東京')";
	  
/*
	  $query = "SELECT eid, name, start_time, end_time, location, venue, description 
        FROM event WHERE eid IN ( SELECT eid FROM event_member WHERE uid = 213367312037345 ) AND end_time > now()
        ORDER BY end_time asc";

	  $query = "SELECT eid, name, start_time, end_time, location, venue, description 
        FROM event WHERE eid IN ( SELECT eid FROM event_member ) AND end_time > now()
        ORDER BY end_time asc";
*/	  

//      $events= $facebook->api(array('method' => 'fql.query','query' => $query));
//      var_dump($events);



//["paging"]=> array(2) { ["previous"]=> string(126) "https://graph.facebook.com/search?type=event&q=Shibuya&limit=5000&since=1951776000&__paging_token=573018702780058&__previous=1" ["next"]=> string(114) "https://graph.facebook.com/search?type=event&q=Shibuya&limit=5000&until=1395705600&__paging_token=1452616734970803" } }      
//      exit;   
/*         
      foreach($events as $e){
      	echo '<tr>';
      var_dump($e);
      exit;
      	echo '<td>' . c($e['name']) . '</td>';
      	echo '<td>' . c($e['start_time']) . '</td>';
      	echo '<td>' . c($e['end_time']) . '</td>';
      	echo '<td>' . c($e['location']) . '</td>';
      	echo '<td>' . c($e['timezone']) . '</td>';
      	echo '<td>' . c($e['id']) . '</td>';
      	
      	echo '</tr>';      
      	
//$response = $facebook->api( "/{event-id}");

      			
	  }
*/	  

  } catch(FacebookApiException $e) {
    // If the user is logged out, you can have a 
    // user ID even though the access token is invalid.
    // In this case, we'll get an exception, so we'll
    // just ask the user to login again here.
    $login_url = $facebook->getLoginUrl(); 
    echo 'Error!!! Please <a href="' . $login_url . '">login.</a>';
    var_dump($e);
    error_log($e->getType());
    error_log($e->getMessage());
  }   
  
} else {

  // No user, print a link for the user to login
  $login_url = $facebook->getLoginUrl();
  $login_url = $facebook->getLoginUrl(array("scope" => "user_events,friends_events"));      
  echo 'Please <a href="' . $login_url . '">login!</a>';

}

    
/*
    array(6) {
      ["name"]=>
      string(97) "“Sống trong đời sống cần có một tấm lòng Để làm gì em biết không ?...♥"
      ["start_time"]=>
      string(24) "2023-01-01T01:55:00+0700"
      ["end_time"]=>
      string(24) "2023-04-01T23:00:00+0700"
      ["timezone"]=>
      string(16) "Asia/Ho_Chi_Minh"
      ["location"]=>
      string(21) "http://filvietnam.com"
      ["id"]=>
      string(15) "266180860184129"
    }
*/

function c($v){
	if(isset($v)){
		return  $v;	
	}
	return '';
}

?>
