<?php
#!/usr/bin/php
date_default_timezone_set('Asia/Tokyo');
mb_language("Japanese");
mb_internal_encoding("UTF-8");

$db = new SQLite3('twitter_follow.sqlite');
$db->exec('CREATE TABLE IF NOT EXISTS twitter_follow (id INTEGER PRIMARY KEY ,user_id TEXT, next_cursor TEXT,previous_cursor TEXT, date TEXT)');
$db->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY ,user_id TEXT, follow_flg TEXT)');

$path = realpath(dirname(__FILE__));
require_once $path.'/twitteroauth/twitteroauth.php';
define('CONSUMER_KEY', 'dMjBRWgeB0V7Y6z4O6mzJgrfN');
define('CONSUMER_SECRET', 'RUQYoHoifCAWjZursqqpa2J3Gc1GpU4U2OKghviXsxeQQkYW5i');
define('ACCESS_TOKEN', '2436464550-6PVxdGD2ALNvGPZA90D5qp7m65rDnH3ATRzLuhD');
define('ACCESS_TOKEN_SECRET', 'Ult7RaiiKPlIwfnY6R60URD8YcDM9DifttKUXBmje8Hls');

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>twitter</title>
</head>
<body style="padding:30px;">
	<a href="https://twitter.com/" target="_blank">Twitterへ</a>
	<hr />
	<form action="./twitter_follow.php" method="post">
		<input type="text" name="follow" placeholder="フォローする人数　デフォ600" style="width:180px;" />
		<input type="submit" value="Follow" />
	</form>
	<hr />
	<form action="./twitter_follow.php" method="post">
		<input type="text" name="unfollow" placeholder="アンフォローする人数　デフォ300"  style="width:180px;"/>
		<input type="submit" value="Unfollow" />
	</form>
	<hr />
	<form action="./twitter_follow.php" method="post">
		<input type="text" name="favorite" placeholder="ふぁぼる人数　デフォ100"  style="width:180px;"/>
		<input type="text" name="favorite_keyword" placeholder="keyword" value="LIVE3" style="width:180px;"/>		
		<input type="submit" value="Favorite" />
	</form>
</body>
</html>

<?php
if(isset($_POST['follow'])){

	$state = "select * from twitter_follow";
	$result = $db->query($state );
	$cursor=NULL;
	while( $row = $result->fetchArray() ) {
		$cursor = $row['next_cursor'];
	}
	!isset($cursor) ? -1 : $cursor;	
	
	$user_id = 280304477;
	//候補　http://meyou.jp/ranking/follower_allcat/100
	// 183484937  https://twitter.com/Cuteeens/following
	//　90834212 https://twitter.com/j_goshi
	// 280304477 http://meyou.jp/hakuryu_kimura
	//　https://twitter.com/ELMEXX_TRADE　売り案件とカイ案件
	//　http://meyou.jp/03yumiko
	//http://meyou.jp/bintarou
	//http://meyou.jp/e_kimuchiya
	
	//http://tik.dignet.info/web/idname/  scren_name id 相互変換
	$count = 600;//一度に取得する件数　MAX5000
	//フォームから入力された値があれば！
	if($_POST['follow'] != ''){
		$count = $_POST['follow'];		
	}

	
	//get followers
	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,ACCESS_TOKEN,ACCESS_TOKEN_SECRET); 
	$req = $connection->OAuthRequest("https://api.twitter.com/1.1/followers/ids.json","GET",array('user_id' => $user_id,'cursor' => $cursor,'count'=>$count));
	$json = json_decode($req, true);
	
	$next_cursor= $json['next_cursor_str'];
	$previous_cursor= $json['previous_cursor_str'];
	$date = date("Y/m/d h:i:s");
	$ids = $json['ids'];
	$c=0;
	foreach($ids as $id){
		$continue_flg = 0;
		$result = $db->query("select * from users where user_id = $id");
		while( $row = $result->fetchArray() ) {
			//既に登録したことがある
			$continue_flg=1;
		}	
		if($continue_flg == 1){
			continue;
		}	
		// フォロー
		$req = $connection->OAuthRequest("https://api.twitter.com/1.1/friendships/create.json", "POST", array("user_id"=>$id, "follow"=>"false"));
		//DBに保存
		$db->exec("INSERT INTO users (user_id,follow_flg ) VALUES (\"$id\",\"1\")");
		$c++;
	}
	$db->exec("INSERT INTO twitter_follow (user_id, next_cursor,previous_cursor, date ) VALUES (\"$user_id\",\"$next_cursor\",\"$previous_cursor\",\"$date\")");
	echo 'フォローした件数：'.$c;
	exit;

}else if(isset($_POST['unfollow'])){

	//POST friendships/create
	$my_screen_name = '30inc';
	$threshold=300;//一度にアンフォローするユーザー数
	//フォームから入力された値があれば！
	if($_POST['unfollow'] != ''){
		$threshold = $_POST['unfollow'];		
	}	
	
	//自分がフォローしているユーザーidの一覧を取得
	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,ACCESS_TOKEN,ACCESS_TOKEN_SECRET); 	
	$req = $connection->OAuthRequest("https://api.twitter.com/1.1/friends/ids.json","GET",array('cursor' => -1, 'screen_name' => $my_screen_name));
	$json = json_decode($req, true);
	$ids = $json['ids'];
	$c=0;
	foreach($ids as $id){
		if($c > $threshold){break;}
		//データベースにあれば＝（自然流入以外を）
		$continue_flg = 0;
		$result = $db->query("select * from users where user_id = $id");
		while( $row = $result->fetchArray() ) {
			// アンフォロー
			$req = $connection->OAuthRequest("https://api.twitter.com/1.1/friendships/destroy.json", "POST", array("user_id"=>$id));
			$c++;
		}	
	}
	echo 'アンフォローした件数：'.$c;
	
}else if(isset($_POST['favorite'])){	
	$connection = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET,ACCESS_TOKEN,ACCESS_TOKEN_SECRET); 		
	$keywords = 'LIVE3';
	if($_POST['favorite'] != ''){
		$keywords = $_POST['favorite_keyword'];		
	}			
	 
	$threshold = 100;
	if($_POST['favorite'] != ''){
		$threshold = $_POST['favorite'];		
	}		 
	 
	$param = array(
	    "q"=>$keywords,                  // keyword
	    "lang"=>"ja",                   // language
	    "count"=>$threshold,            // number of tweets
	    "result_type"=>"recent");       // result type
	  
	$json = $connection->OAuthRequest(
	    "https://api.twitter.com/1.1/search/tweets.json",
	    "GET",
	    $param);
	  
	$result = json_decode($json, true); 
	$tweets = $result['statuses'];
	foreach($tweets as $tw){
		$tw_id = $tw['id_str'];
		$vRequest = $connection->OAuthRequest("https://api.twitter.com/1.1/favorites/create.json","POST",array('id'=>$tw_id));
	}

/*	
	//XMLデータをsimplexml_load_string関数を使用してオブジェクトに変換する
	$oXml = simplexml_load_string($vRequest);
	
	//オブジェクトを展開
	if(isset($oXml->error) && $oXml->error != ''){
	    echo "お気に入り追加に失敗しました。<br/>\n";
	    echo "パラメーターの指定を確認して下さい。<br/>\n";
	    echo "エラーメッセージ:".$oXml->error."<br/>\n";
	}else{
	    echo "お気に入りに追加しました。<br/>\n";
	    echo "つぶやきid:(".$oXml->id.")<br/>\n";
	    echo "つぶやき:(".$oXml->text.")<br/>\n";
	    echo "ユーザー名:(".$oXml->user->name.")<br/>\n";
	    echo "ユーザーscreen_name名:(".$oXml->user->screen_name.")<br/>\n";
	    echo "<hr/>\n";
	}	
*/	
}
