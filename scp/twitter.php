<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Tw Search</title>
	<script type="text/javascript" src="./jquery.min.js" ></script>
</head>
<body style="margin:0;">
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42716969-15', 'live3.info');
  ga('send', 'pageview');

</script>

<div style="width:100%;position:fixed;background:#aaa;">
	<h5 style="float:left;">Search!</h5>
	<div style="float:left;margin:10px;">
		<form action="" method="post">
		  <select name="select_date" id="select_date">
		<script>
		$(function(){
			for (var i=1 ; i<=31 ; i++){
				var option = '<option>'+i+'</option>';
				$("#select_date").append(option);
			}
		});
		</script>
		  </select>
			<input type="submit" />
		</form>
	</div>	
<?php
if(isset($_POST['select_date']) || isset($_GET['select_date']) ){
	if(isset($_POST['select_date'])) {
		$select_date = $_POST['select_date'];
	}else if(isset($_GET['select_date'])){
		$select_date = $_GET['select_date'];
	}
	$next_date = intval($select_date)+1;
	echo '<div style="float:left;">';
	echo '<a href="./twitter.php?select_date='.$next_date.'" style="margin-right:10px">next day</a>';
	$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	echo '<a href="./twitter.php?select_date='.$select_date.'&free=1" style="margin-right:10px">フリィ</a>';
	echo '<a href="./twitter.php?select_date='.$select_date.'&free=0" style="margin-right:10px">非フリィつまり全部</a>';
	echo '</div>';
	
}
?>	
	
	<div style="float:right;margin:10px;">
		<h5 style="float:left;">Get!</h5>
		<form action="" method="post">
		  <select name="location">
		    <option>渋谷</option>
		    <option>新宿</option>
		    <option>下北</option>
		    <option>池袋</option>
		    <option>品川</option>
		    <option>六本木</option>    
		  </select>
		
		  <select name="date" id="form_date">
		<script>
		$(function(){
			for (var i=1 ; i<=31 ; i++){
				var option = '<option>'+i+'</option>';
				$("#form_date").append(option);
			}
		});
		</script>
		  </select>
			<input type="submit" />
		</form>
	</div>
</div>

<div style=" padding:130px 10px; 0; ">

<?php
date_default_timezone_set('Asia/Tokyo');

class NewDB extends SQLite3{
    function __construct() {
        $this->open('twitter.db');
    }
}
$db = new NewDB();
$db->exec('CREATE TABLE IF NOT EXISTS tweet (id INTEGER PRIMARY KEY , kw_free_flg INTEGER, kw_live_string TEXT, kw_location_string TEXT, kw_tweet_date TEXT, user_screen_name TEXT,tweet_id TEXT, tweet_text TEXT, tweet_date TEXT,retweet_count INTEGER,favorite_count INTEGER, retweet_flg INTEGER, new_flg INTEGER, date_str TEXT )');

//$timestamp = strtotime("2011-10-01");
$date_array = array();
$date_str_array = array();
if(isset($_POST['select_date']) || isset($_GET['select_date']) ){//for getting data from database
	$select_date=0;
	if(isset($_POST['select_date'])) {
		$select_date = ($_POST['select_date'] < 10)?'0'.$_POST['select_date']:$_POST['select_date'];
	}else if(isset($_GET['select_date'])){
		$select_date = ($_GET['select_date'] < 10)?'0'.$_GET['select_date']:$_GET['select_date'];
	}

	$date_str="";
	if(date('d') <= $select_date ){//同月
		$date_str =  date('Ym').$select_date;
	}else{//翌月
		$next_month = date("m", (strtotime("+1 month", strtotime("now"))));
		$date_str = date('Y').$next_month.$select_date;
	}

	echo $date_str;
	echo '<hr />';
	
	$state = "SELECT * FROM tweet where date_str = '$date_str'; ";
	
	if(isset($_GET["free"]) && $_GET["free"] == 1){
		$state = "SELECT * FROM tweet where date_str = '$date_str' AND kw_free_flg = 1; ";	
	}
	$result = $db->query($state );
	$text_array = array();
	while( $row = $result->fetchArray() ) {
		if( $row['retweet_flg']==1){continue;}
		foreach($text_array as $old_text){
			if($old_text == mb_substr($row['tweet_text'], 0,30)){ continue; }
		}
		array_push($text_array, mb_substr($row['tweet_text'], 0,30));
		
		echo '<p>'.$row['tweet_date']."|| <a href='http://twitter.com/".$row['user_screen_name']."' target='_blank'>".$row['user_screen_name'].'</a>||';
		echo $row['retweet_count'].'/'.$row['favorite_count'].'</p>';
		echo '<p>'.$row['tweet_text'].'</p>';
		echo '<hr />';
		
//kw_free_flg, kw_live_string, kw_location_string, kw_tweet_date, 
//tweet_id,
//retweet_count,favorite_count, retweet_flg, new_flg 		
	}	
	
	
}else if(isset($_POST['date'])){//for Scraping twitter
	if(date('d') <= $_POST['date'] ){//同月
		$timestamp = strtotime( (date('Y-m-').$_POST['date']) );
		for($i=0;$i<7;$i++){
			$format = "+".$i." day";
			$next_day = date("j", (strtotime($format, $timestamp)));
			array_push($date_array,$next_day);
			$next_str_day = date("Ymd", (strtotime($format, $timestamp)));
			array_push($date_str_array,$next_str_day);
		}
	}else{//翌月
		$next_month = date("m", (strtotime("+1 month", strtotime("now"))));
		$timestamp = strtotime( (date('Y-').$next_month."-".$_POST['date']) );
		for($i=0;$i<7;$i++){
			$format = "+".$i." day";
			$next_day = date("j", (strtotime($format, $timestamp)));
			array_push($date_array,$next_day);
			$next_str_day = date("Ymd", (strtotime($format, $timestamp)));
			array_push($date_str_array,$next_str_day);			
		}
	}
	setKeyword($db, $date_array,$_POST['location'],$date_str_array);
	
}else if(isset($_GET['date'])){//for Scraping twitter
	if(date('d') <= $_GET['date'] ){//同月
		$timestamp = strtotime( (date('Y-m-').$_GET['date']) );
		for($i=0;$i<7;$i++){
			$format = "+".$i." day";
			$next_day = date("j", (strtotime($format, $timestamp)));
			array_push($date_array,$next_day);
			$next_str_day = date("Ymd", (strtotime($format, $timestamp)));
			array_push($date_str_array,$next_str_day);
		}
	}else{//翌月
		$next_month = date("m", (strtotime("+1 month", strtotime("now"))));
		$timestamp = strtotime( (date('Y-').$next_month."-".$_GET['date']) );
		for($i=0;$i<7;$i++){
			$format = "+".$i." day";
			$next_day = date("j", (strtotime($format, $timestamp)));
			array_push($date_array,$next_day);
			$next_str_day = date("Ymd", (strtotime($format, $timestamp)));
			array_push($date_str_array,$next_str_day);			
		}
	}	
	setKeyword($db, $date_array,$_GET['location'],$date_str_array);	
}


//$db = new SQLite3('twitter.db');



//aoa();

/*
無料　ライブ　渋谷　8日
無料　あり・なし
ライブ　ライブ・コンサート・live・イベント
渋谷　（渋谷・新宿・下北・池袋・品川・六本木・など）・なし
8日　1−31日・○月○日
*/

//for($date=1; $date<32; $date++){
function setKeyword($db, $date_array,$loc,$date_str_array){

require_once 'twitteroauth/twitteroauth.php';
define('CONSUMER_KEY', 'LpPeayvG1WVrw0o452tSeA');
define('CONSUMER_SECRET', '6qL3uQFtQaIqoZmyqk2PAb8RYKnKzuFFitewgxL1E');
define('ACCESS_TOKEN', '200468161-866S9O4LxTpLhYgOy9UZ3kkDAV4pgkW8Frzdt1Ef');
define('ACCESS_TOKEN_SECRET', 'IpU7uPeJoURbDsXEx2CJBi1LpJhMkfOU63Jf8C2bhqA5m');

	$cnt = 0;
	foreach($date_array as $date){
		echo "<hr /><h2>".$date."日</h2><hr />";
		//$loc = $_POST['location'];
	//	$location_array = array("渋谷","新宿",'下北','池袋','品川','六本木');
	//	foreach($location_array as $loc){
			$live_array = array("ライブ","コンサート",'live','イベント','ギグ');		
			foreach($live_array as $live){
				for($free_flg=0;$free_flg<2;$free_flg++ ){
					$free = '無料';
					if($free_flg==0){$free = '';}
	//				$keywords = $free.' '.$live.' '.$loc.' '.$i."日";
					$date_str=$date_str_array[$cnt];	
					twitterSearch($free,$live,$loc,$date,$db,$date_str,$free_flg);
				}
			}
	//	}
		$cnt++;
	}	
}

function twitterSearch($free,$live,$loc,$date,$db,$date_str,$free_flg){
	$keywords = $free.' '.$live.' '.$loc.' '.$date."日";	

	$twitterOAuth = new TwitterOAuth(
	CONSUMER_KEY,
	CONSUMER_SECRET,
	ACCESS_TOKEN,
	ACCESS_TOKEN_SECRET
	);	
/*
	if(isset($_SESSION['token'])){
		$twitterOAuth->setBearerToken( $_SESSION['token'] );		
	}
*/	
//	$btoken = $twitterOAuth->getBearerToken();

//	if($twitterOAuth->http_header['x_rate_limit_remaining'] == 0 ){
//		echo "ちょっと待ってね☆";
	$false = 1;
	if($false == 0){		
	}else{
		echo $keywords.'<br />';
	
		$param = array(
	    "q"=>$keywords,                  // keyword
	    "lang"=>"ja",                   // language
	    "count"=>30,                   // number of tweets
	    "result_type"=>"recent");       // result type
		$json = $twitterOAuth->OAuthRequest(
	    "https://api.twitter.com/1.1/search/tweets.json",
	    "GET",
	    $param);  
//	echo $twitterOAuth->http_header['x_rate_limit_remaining'];	    
//	exit;	    
		$result = json_decode($json, true); 
		if($result['statuses']){
		
		    foreach($result['statuses'] as $tweet){ 
		    	  
		    	$tweet_id = $tweet['id_str'];
		    	$tweet_screenname = $tweet['user']['screen_name'];
		    	$tweet_text = str_replace('"', '”', $tweet['text']);
		    	$tweet_created_at = $tweet['created_at'];
		    	$tweet_rt_count = $tweet['retweet_count'];
		    	$tweet_favorite_count = $tweet['favorite_count'];
				$tweet_bool_rt = (isset($tweet['retweeted_status']))?1:0;
		    	
				$update_flg=0;
				$state = "SELECT * FROM tweet where id = $tweet_id";
				$result = $db->query($state );
				while( $row = $result->fetchArray() ) {
					$update_flg=1;break;
				}
				if($update_flg == 1){	
	// 				$db->exec("Update tweet set  kw_free_flg = '', kw_live_string = '', kw_location_string = '', kw_tweet_date = '', user_screen_name = '',tweet_id = '', tweet_text = '', tweet_date = '',retweet_count = '',favorite_count = '', retweet_flg = '', new_flg = '' where id = $songkick_id");		 		
				}else{
					$db->exec("INSERT INTO tweet ( kw_free_flg, kw_live_string, kw_location_string, kw_tweet_date, user_screen_name,tweet_id, tweet_text, tweet_date,retweet_count,favorite_count, retweet_flg, new_flg, date_str ) VALUES (\"$free_flg\",\"$live\",\"$loc\",\"$date\",\"$tweet_screenname\",\"$tweet_id\",\"$tweet_text\",\"$tweet_created_at\",\"$tweet_rt_count\",\"$tweet_favorite_count\",\"$tweet_bool_rt\",1 ,$date_str)");	
				}
	/*
		    	echo $tweet["id_str"];
				echo $tweet['created_at'].' || <a href="'.$tweet['user']["screen_name"].'"></a>'.$tweet['user']['name'].'<br />';
				echo $tweet['text']; //つぶやき
		//		echo $tweet['user']['profile_image_url']; //アイコンURL
				echo '<hr />';
	*/

				echo $tweet['created_at'].' || <a href="'.$tweet['user']["screen_name"].'"></a>'.$tweet['user']['name'].'<br />';
				echo $tweet['text'];				
					
				echo '<hr />';	
			}	
		 }else{
			 echo '<div class="twi_box"><p class="twi_tweet">関連したつぶやきがありません。</p></div>';
		 } 		
	}

}
 ?>
</div>
</body>
</html>
<?php

function allReaded($db){
$db->exec("Update tweet set  new_flg = 0");	
	//$db->exec("Update tweet set  new_flg = 0  where id = $songkick_id");	
}


function aoa(){//Application-only authentication
	$consumerKey = CONSUMER_KEY;
	$consumerSecret = CONSUMER_SECRET;
	// consumer key と secret をエンコードして : で繋げる
	$bearerToken = urlencode($consumerKey).':'.urlencode($consumerSecret);
	// 上で繋げたものを base64 encode
	// これがトークンになる
	$encodedBearerToken = base64_encode($bearerToken);
	// https://dev.twitter.com/docs/api/1.1/post/oauth2/token
	$url = "https://api.twitter.com/oauth2/token/";
	// ヘッダーをセット
	// Content-Type で application/x-www-form-urlencoded をセットする
	$headers = array(
	    "POST /oauth2/token HTTP/1.1",
	    "Host: api.twitter.com",
	    "User-Agent: My Twitter App",
	    "Authorization: Basic ".$encodedBearerToken,
	    "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
	    "Content-Length: 29"
	);
	// cURL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, false);
	// POST で
	curl_setopt($ch, CURLOPT_POST, true);
	// 値はこれで固定
	curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$ret = curl_exec($ch);
	if ($ret === false) {
	    var_dump(curl_error($ch));
	}
	curl_close($ch);
	$baser = json_decode($ret);
	$_SESSION['token'] = $baser;
}

/*
array(25) {
  ["metadata"]=>
  array(2) {
    ["result_type"]=>
    string(6) "recent"
    ["iso_language_code"]=>
    string(2) "ja"
  }
  ["created_at"]=>
  string(30) "Wed Mar 05 10:57:12 +0000 2014"
  ["id"]=>
  int(441165525029109760)
  ["id_str"]=>
  string(18) "441165525029109760"
  ["text"]=>
  string(326) "RT @minami25arashi: 嵐好きな人！
あらしっくは絶対RT!!
このライブなにかわかった人RT!

#RTしてくれたあらしっく全員フォローする 
#ニノの誕生日までに617RT目指す 
ニノの誕生日までにできるといいです！
あらしっくのみなさんお願いします！ h…"
  ["source"]=>
  string(82) "<a href="http://twitter.com/download/iphone" rel="nofollow">Twitter for iPhone</a>"
  ["truncated"]=>
  bool(false)
  ["in_reply_to_status_id"]=>
  NULL
  ["in_reply_to_status_id_str"]=>
  NULL
  ["in_reply_to_user_id"]=>
  NULL
  ["in_reply_to_user_id_str"]=>
  NULL
  ["in_reply_to_screen_name"]=>
  NULL
  ["user"]=>
  array(40) {
    ["id"]=>
    int(1883454565)
    ["id_str"]=>
    string(10) "1883454565"
    ["name"]=>
    string(12) "momo☆amnos"
    ["screen_name"]=>
    string(9) "1126os_ss"
    ["location"]=>
    string(0) ""
    ["description"]=>
    string(0) ""
    ["url"]=>
    NULL
    ["entities"]=>
    array(1) {
      ["description"]=>
      array(1) {
        ["urls"]=>
        array(0) {
        }
      }
    }
    ["protected"]=>
    bool(false)
    ["followers_count"]=>
    int(1523)
    ["friends_count"]=>
    int(1567)
    ["listed_count"]=>
    int(1)
    ["created_at"]=>
    string(30) "Thu Sep 19 15:02:50 +0000 2013"
    ["favourites_count"]=>
    int(219)
    ["utc_offset"]=>
    NULL
    ["time_zone"]=>
    NULL
    ["geo_enabled"]=>
    bool(false)
    ["verified"]=>
    bool(false)
    ["statuses_count"]=>
    int(2872)
    ["lang"]=>
    string(2) "ja"
    ["contributors_enabled"]=>
    bool(false)
    ["is_translator"]=>
    bool(false)
    ["is_translation_enabled"]=>
    bool(false)
    ["profile_background_color"]=>
    string(6) "C0DEED"
    ["profile_background_image_url"]=>
    string(48) "http://abs.twimg.com/images/themes/theme1/bg.png"
    ["profile_background_image_url_https"]=>
    string(49) "https://abs.twimg.com/images/themes/theme1/bg.png"
    ["profile_background_tile"]=>
    bool(false)
    ["profile_image_url"]=>
    string(75) "http://pbs.twimg.com/profile_images/438951801648214016/HPqxGucm_normal.jpeg"
    ["profile_image_url_https"]=>
    string(76) "https://pbs.twimg.com/profile_images/438951801648214016/HPqxGucm_normal.jpeg"
    ["profile_banner_url"]=>
    string(59) "https://pbs.twimg.com/profile_banners/1883454565/1393489239"
    ["profile_link_color"]=>
    string(6) "0084B4"
    ["profile_sidebar_border_color"]=>
    string(6) "C0DEED"
    ["profile_sidebar_fill_color"]=>
    string(6) "DDEEF6"
    ["profile_text_color"]=>
    string(6) "333333"
    ["profile_use_background_image"]=>
    bool(true)
    ["default_profile"]=>
    bool(true)
    ["default_profile_image"]=>
    bool(false)
    ["following"]=>
    bool(false)
    ["follow_request_sent"]=>
    bool(false)
    ["notifications"]=>
    bool(false)
  }
  ["geo"]=>
  NULL
  ["coordinates"]=>
  NULL
  ["place"]=>
  NULL
  ["contributors"]=>
  NULL
  ["retweeted_status"]=>
  array(24) {
    ["metadata"]=>
    array(2) {
      ["result_type"]=>
      string(6) "recent"
      ["iso_language_code"]=>
      string(2) "ja"
    }
    ["created_at"]=>
    string(30) "Tue Feb 04 15:13:33 +0000 2014"
    ["id"]=>
    int(430720788908343297)
    ["id_str"]=>
    string(18) "430720788908343297"
    ["text"]=>
    string(324) "嵐好きな人！
あらしっくは絶対RT!!
このライブなにかわかった人RT!

#RTしてくれたあらしっく全員フォローする 
#ニノの誕生日までに617RT目指す 
ニノの誕生日までにできるといいです！
あらしっくのみなさんお願いします！ http://t.co/BVQA3PwpZk"
    ["source"]=>
    string(82) "<a href="http://twitter.com/download/iphone" rel="nofollow">Twitter for iPhone</a>"
    ["truncated"]=>
    bool(false)
    ["in_reply_to_status_id"]=>
    NULL
    ["in_reply_to_status_id_str"]=>
    NULL
    ["in_reply_to_user_id"]=>
    NULL
    ["in_reply_to_user_id_str"]=>
    NULL
    ["in_reply_to_screen_name"]=>
    NULL
    ["user"]=>
    array(40) {
      ["id"]=>
      int(2280326372)
      ["id_str"]=>
      string(10) "2280326372"
      ["name"]=>
      string(9) "みなみ"
      ["screen_name"]=>
      string(14) "minami25arashi"
      ["location"]=>
      string(0) ""
      ["description"]=>
      string(0) ""
      ["url"]=>
      NULL
      ["entities"]=>
      array(1) {
        ["description"]=>
        array(1) {
          ["urls"]=>
          array(0) {
          }
        }
      }
      ["protected"]=>
      bool(false)
      ["followers_count"]=>
      int(301)
      ["friends_count"]=>
      int(310)
      ["listed_count"]=>
      int(0)
      ["created_at"]=>
      string(30) "Tue Jan 07 09:13:20 +0000 2014"
      ["favourites_count"]=>
      int(22)
      ["utc_offset"]=>
      NULL
      ["time_zone"]=>
      NULL
      ["geo_enabled"]=>
      bool(false)
      ["verified"]=>
      bool(false)
      ["statuses_count"]=>
      int(200)
      ["lang"]=>
      string(2) "ja"
      ["contributors_enabled"]=>
      bool(false)
      ["is_translator"]=>
      bool(false)
      ["is_translation_enabled"]=>
      bool(false)
      ["profile_background_color"]=>
      string(6) "C0DEED"
      ["profile_background_image_url"]=>
      string(48) "http://abs.twimg.com/images/themes/theme1/bg.png"
      ["profile_background_image_url_https"]=>
      string(49) "https://abs.twimg.com/images/themes/theme1/bg.png"
      ["profile_background_tile"]=>
      bool(false)
      ["profile_image_url"]=>
      string(75) "http://pbs.twimg.com/profile_images/439722510548869120/FWqfVEGn_normal.jpeg"
      ["profile_image_url_https"]=>
      string(76) "https://pbs.twimg.com/profile_images/439722510548869120/FWqfVEGn_normal.jpeg"
      ["profile_banner_url"]=>
      string(59) "https://pbs.twimg.com/profile_banners/2280326372/1393334435"
      ["profile_link_color"]=>
      string(6) "0084B4"
      ["profile_sidebar_border_color"]=>
      string(6) "C0DEED"
      ["profile_sidebar_fill_color"]=>
      string(6) "DDEEF6"
      ["profile_text_color"]=>
      string(6) "333333"
      ["profile_use_background_image"]=>
      bool(true)
      ["default_profile"]=>
      bool(true)
      ["default_profile_image"]=>
      bool(false)
      ["following"]=>
      bool(false)
      ["follow_request_sent"]=>
      bool(false)
      ["notifications"]=>
      bool(false)
    }
    ["geo"]=>
    NULL
    ["coordinates"]=>
    NULL
    ["place"]=>
    NULL
    ["contributors"]=>
    NULL
    ["retweet_count"]=>
    int(567)
    ["favorite_count"]=>
    int(46)
    ["entities"]=>
    array(5) {
      ["hashtags"]=>
      array(2) {
        [0]=>
        array(2) {
          ["text"]=>
          string(56) "RTしてくれたあらしっく全員フォローする"
          ["indices"]=>
          array(2) {
            [0]=>
            int(38)
            [1]=>
            int(59)
          }
        }
        [1]=>
        array(2) {
          ["text"]=>
          string(41) "ニノの誕生日までに617RT目指す"
          ["indices"]=>
          array(2) {
            [0]=>
            int(61)
            [1]=>
            int(79)
          }
        }
      }
      ["symbols"]=>
      array(0) {
      }
      ["urls"]=>
      array(0) {
      }
      ["user_mentions"]=>
      array(0) {
      }
      ["media"]=>
      array(1) {
        [0]=>
        array(10) {
          ["id"]=>
          int(430720788782530560)
          ["id_str"]=>
          string(18) "430720788782530560"
          ["indices"]=>
          array(2) {
            [0]=>
            int(118)
            [1]=>
            int(140)
          }
          ["media_url"]=>
          string(46) "http://pbs.twimg.com/media/Bfo6RnzCQAA2h54.jpg"
          ["media_url_https"]=>
          string(47) "https://pbs.twimg.com/media/Bfo6RnzCQAA2h54.jpg"
          ["url"]=>
          string(22) "http://t.co/BVQA3PwpZk"
          ["display_url"]=>
          string(26) "pic.twitter.com/BVQA3PwpZk"
          ["expanded_url"]=>
          string(67) "http://twitter.com/minami25arashi/status/430720788908343297/photo/1"
          ["type"]=>
          string(5) "photo"
          ["sizes"]=>
          array(4) {
            ["medium"]=>
            array(3) {
              ["w"]=>
              int(480)
              ["h"]=>
              int(360)
              ["resize"]=>
              string(3) "fit"
            }
            ["thumb"]=>
            array(3) {
              ["w"]=>
              int(150)
              ["h"]=>
              int(150)
              ["resize"]=>
              string(4) "crop"
            }
            ["small"]=>
            array(3) {
              ["w"]=>
              int(340)
              ["h"]=>
              int(255)
              ["resize"]=>
              string(3) "fit"
            }
            ["large"]=>
            array(3) {
              ["w"]=>
              int(480)
              ["h"]=>
              int(360)
              ["resize"]=>
              string(3) "fit"
            }
          }
        }
      }
    }
    ["favorited"]=>
    bool(false)
    ["retweeted"]=>
    bool(false)
    ["possibly_sensitive"]=>
    bool(false)
    ["lang"]=>
    string(2) "ja"
  }
  ["retweet_count"]=>
  int(567)
  ["favorite_count"]=>
  int(0)
  ["entities"]=>
  array(5) {
    ["hashtags"]=>
    array(2) {
      [0]=>
      array(2) {
        ["text"]=>
        string(56) "RTしてくれたあらしっく全員フォローする"
        ["indices"]=>
        array(2) {
          [0]=>
          int(58)
          [1]=>
          int(79)
        }
      }
      [1]=>
      array(2) {
        ["text"]=>
        string(41) "ニノの誕生日までに617RT目指す"
        ["indices"]=>
        array(2) {
          [0]=>
          int(81)
          [1]=>
          int(99)
        }
      }
    }
    ["symbols"]=>
    array(0) {
    }
    ["urls"]=>
    array(0) {
    }
    ["user_mentions"]=>
    array(1) {
      [0]=>
      array(5) {
        ["screen_name"]=>
        string(14) "minami25arashi"
        ["name"]=>
        string(9) "みなみ"
        ["id"]=>
        int(2280326372)
        ["id_str"]=>
        string(10) "2280326372"
        ["indices"]=>
        array(2) {
          [0]=>
          int(3)
          [1]=>
          int(18)
        }
      }
    }
    ["media"]=>
    array(1) {
      [0]=>
      array(10) {
        ["id"]=>
        int(430720788782530560)
        ["id_str"]=>
        string(18) "430720788782530560"
        ["indices"]=>
        array(2) {
          [0]=>
          int(139)
          [1]=>
          int(140)
        }
        ["media_url"]=>
        string(46) "http://pbs.twimg.com/media/Bfo6RnzCQAA2h54.jpg"
        ["media_url_https"]=>
        string(47) "https://pbs.twimg.com/media/Bfo6RnzCQAA2h54.jpg"
        ["url"]=>
        string(22) "http://t.co/BVQA3PwpZk"
        ["display_url"]=>
        string(26) "pic.twitter.com/BVQA3PwpZk"
        ["expanded_url"]=>
        string(67) "http://twitter.com/minami25arashi/status/430720788908343297/photo/1"
        ["type"]=>
        string(5) "photo"
        ["sizes"]=>
        array(4) {
          ["medium"]=>
          array(3) {
            ["w"]=>
            int(480)
            ["h"]=>
            int(360)
            ["resize"]=>
            string(3) "fit"
          }
          ["thumb"]=>
          array(3) {
            ["w"]=>
            int(150)
            ["h"]=>
            int(150)
            ["resize"]=>
            string(4) "crop"
          }
          ["small"]=>
          array(3) {
            ["w"]=>
            int(340)
            ["h"]=>
            int(255)
            ["resize"]=>
            string(3) "fit"
          }
          ["large"]=>
          array(3) {
            ["w"]=>
            int(480)
            ["h"]=>
            int(360)
            ["resize"]=>
            string(3) "fit"
          }
        }
      }
    }
  }
  ["favorited"]=>
  bool(false)
  ["retweeted"]=>
  bool(false)
  ["possibly_sensitive"]=>
  bool(false)
  ["lang"]=>
  string(2) "ja"
}
*/
