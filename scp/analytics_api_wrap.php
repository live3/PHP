<?php
//$uuidが'allUser'であれば、全ユーザーに対して実行する
//setWeek='thisWeek' setWeek='lastWeek' 
//setMonth='thisMonth' setMonth='lastMonth' 

/*
※下記全て、１ユーザー辺りの、、、となります。断りがなければ基本INTEGERかFLOATです。
/\/\/\/\/\イベント系
$share_count=0;//シェア回数(facebookとかtwitterとかも準備はしてますので、テーブル構成によって合わせます)
$local_push_open_count=0;//ローカルプッシュからの起動回数
$remote_push_open_count=0;//リモートプッシュからの起動回数
$youtube_count=0;//Youtube総再生回数
$description_bottom_count=0;//ライブ詳細のディスクリプションを最後まで読んだ回数

/\/\/\/\/\ネットワーク系
$network_career = '';//文字列です、　docomo softbank au
$wifi_user_rate = 0;//Wifi使用率　(ex. 0.56287 => の割合でwifi使用)

/\/\/\/\/\デプス系
$avg_depth = $st->average($depth_array);//アプリのどこまで潜ったかの平均値（何ページ目までいったか）
$max_depth = max($depth_array);//最大デプス
$min_depth = min($depth_array);//最小デプス

/\/\/\/\/スクリーン系
$map_rate =	$map_count/$screens_count;//マップを表示した回数、割ることの　全スクリーン表示回数　（どれだけ地図が好きかわかる）

/\/\/\/\/総合系
$visitors = $ar['ga:visitors'];//総訪問回数（総セッション数?仕様変更のあった模様　https://developers.google.com/analytics/devguides/reporting/core/dimsmets#mode=app&cats=session,user）
$screenviews = $ar['ga:screenviews'];//何ページ見たかの総数
$uniqueScreenviews = $ar['ga:uniqueScreenviews'];//何ページ見たかの総数（ユニークなので重複は含まない）
$screenviewsPerSession = $ar['ga:screenviewsPerSession'];//一度のセッションで何ページ見るか
$timeOnScreen = $ar['ga:timeOnScreen'];//合計滞在時間（秒）
$avgScreenviewDuration = $ar['ga:avgScreenviewDuration'];//1ページあたり平均何秒かけているか

/\/\/\/\/位置系
$first_city;//ハッシュ形式です。最多地域 ex.Tokyo Shibuya,12回
$second_city;//最多地域２位(一応)

/\/\/\/\/再訪問系
$average_last_session = $st->average($daysSinceLastSession_array);//最後のセッションから何日後にアクセスしているかの平均
$mode_last_session = $st->mode($daysSinceLastSession_array);//最後のセッションから何日後にアクセスしているかの最頻値
$median_last_session = $st->median($daysSinceLastSession_array);//最後のセッションから何日後にアクセスしているかの中央値

/\/\/\/\/時間系
$average_hour = $st->average($hour_array);//アクセス時間(01-24)の平均値
$mode_hour = $st->mode($hour_array);//アクセス時間(01-24)の最頻値
$median_huor = $st->median($hour_array);//アクセス時間(01-24)の中央値

*/

mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');


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

	if(isset($_REQUEST['setWeek'])){	
		$setWeek = $_REQUEST['setWeek'];	
	}
	if(isset($_REQUEST['setMonth'])){	
		$setMonth = $_REQUEST['setMonth'];	
	}
	if(isset($_REQUEST['setYear'])){	
		$setYear = $_REQUEST['setYear'];	
	}

}

$today = date("Y-m-d"); 
$yesterday = date("Y-m-d", strtotime('-1 day'));

$w = date("w");//今日が今週何番目の日か
$thisweek_firstdate =date('Y-m-d', strtotime("-{$w} day", strtotime($today)));//日曜始まり
$thisweek_lastdate =date('Y-m-d', strtotime("+6 day", strtotime($thisweek_firstdate)));

$lastweek_firstdate =date('Y-m-d', strtotime("-7 day", strtotime($thisweek_firstdate)));
$lastweek_lastdate =date('Y-m-d', strtotime("-7 day", strtotime($thisweek_lastdate)));

if(isset($setWeek)){
	if($setWeek == 'thisWeek'){
		$startTime = $thisweek_firstdate;
		$endTime = $thisweek_lastdate;
	}else if($setWeek == 'lastWeek'){
		$startTime = $lastweek_firstdate;
		$endTime = $lastweek_lastdate;	
	}
}

$thismonth_firstday = date('Y-m-1');//今月の初日を取得する
$thismonth_lastday = date('Y-m-t');//今月の末日を取得する

$lastmonth_firstday = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y'))); //先月の初日を取得する
$lastmonth_lastday = date('Y-m-d', mktime(0, 0, 0, date('m'), 0, date('Y'))); //先月の末日を取得する

if(isset($setMonth)){
	if($setMonth == 'thisMonth'){
		$startTime = $thismonth_firstday;
		$endTime = $thismonth_firstday;
	}else if($setMonth == 'lastMonth'){
		$startTime = $lastmonth_firstday;
		$endTime = $lastmonth_lastday;	
	}
}



if(!isset($startTime)){$startTime='2014-03-28';}//'yesterday';}
if(!isset($endTime) ){$endTime=$today;}
if(!isset($deviseToken) ){$deviseToken='245DF44C-88D5-4471-86F4-E6215701AE7C';}
if(!isset($uuid) ){$uuid='180c8232f2b93e7a655848e6b97021fff0703bd838d8b5de1cbe60b64c63653a';}

if($uuid == 'allUser'){//DB上の全ユーザー
	$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
	if (!$link) {
	    print(mysql_error());
	}
	mysql_query("SET NAMES utf8",$link); 
	$db_selected = mysql_select_db('live3appdb', $link);
	if (!$db_selected){
	    die('データベース選択失敗です。'.mysql_error());
	}
	
	$query = mysql_query("
	select * from all_user_devices aud inner join not_user_devices nud on aud.`all_user_device_uuid` = nud.`not_user_device_uuid`
	where aud.created_at >= '$startTime' AND aud.created_at <= '$endTime'
	");	
	
	$cnt=0;
	while ($row = mysql_fetch_assoc($query)) {	

		$aud_id = $row['id'];
		$aud_uuid = $row['all_user_device_uuid'];
		$nud_deviseToken = $row['not_user_device_token'];		
		
		$url = 'http://util.live3.info/scp/analytics_api.php?startTime='.$startTime.'&endTime='.$endTime.'&deviseToken='.$nud_deviseToken.'&uuid='.$aud_uuid;	
		updateDataBase($url);
		
		echo $aud_id.' <br />\n\r';
		$cnt++;	
	}

	
}else{//特定のユーザーのみに実行
	$url = 'http://util.live3.info/scp/analytics_api.php?startTime='.$startTime.'&endTime='.$endTime.'&deviseToken='.$deviseToken.'&uuid='.$uuid;	
	updateDataBase($url);

}



function updateDataBase($url){
	$response = file_get_contents($url);
	
	$obj = json_decode($response, true);
	if ($obj === NULL) {return;}
	 
	//保留　　タップ回数
	//TBD　　起動頻度
	$st = new Statistics();	
	
	
	$share_count=0;
	$local_push_open_count=0;
	$remote_push_open_count=0;
	$youtube_count=0;
	$description_bottom_count=0;
	foreach($obj['events'] as $ar){
	//'ga:eventCategory,ga:eventAction,ga:eventLabel,ga:dimension1,ga:dimension2,ga:date'	
	//シェア
		if($ar[1] == 'shared Facebook'){
			//insert？
	//[0]DetailTicketView || CountTicketView || FesDetailView || TicketView	  [2]id:9301
			
			$share_count++;
		}else if($ar[1] == 'shared Twitter'){

			$share_count++;		
		}else if($ar[1] == 'shared Mail'){

			$share_count++;		
		}else if($ar[1] == 'shared Message'){
		
			$share_count++;		
		}else if($ar[1] == 'shared Line'){
		
			$share_count++;		
	//プッシュ通知
		//ローカル
		}else if($ar[1] == 'didFinishLaunchingWithOptions_LocalNotification'){
	
			$local_push_open_count++;	
		//リモート
		}else if($ar[1] == 'didFinishLaunchingWithOptions_RemoteNotification'){
	
			$remote_push_open_count++;			
	//youtube	
		}else if($ar[1] == 'youtubeTap'){	
	//[0]DetailView-9577 [2]SphXocQBVRY
			$youtube_count++;
		
	//説明文を全て読んだか	
		}else if($ar[1] == 'scrolled_view_at_bottom'){	
	//[0]DetailView||FesDetailView [2]SUMMER SONIC 2014 9577
			
			$description_bottom_count++;
	//位置情報
		}else if($ar[1] == 'NewLocation'){
			$label_arr = explode('@', $ar[2]);		
			$label_data = $label_arr[0];		
			preg_match_all('%([\d|\.]+)%', $label_data,$res);
			$latitude = $res[0][0];
			$longitude = $res[0][1];
			$accuracy = $res[0][2];
			$speed = $res[0][3];
			$course = $res[0][4];
			
		}
	
	}	
	
	
	$network_career = '';
	$not_wifi_user_count = 0;
	$wifi_user_count_all = 0;
	foreach($obj['networks'] as $ar){
	//'ga:mobileDeviceInfo,ga:networkDomain,ga:networkLocation,ga:dimension1,ga:dimension2,ga:date',	
	//デバイス
		if($ar[0] == 'Apple iPhone'){}
	
	//携帯キャリア
		if($ar[2] == 'japan nation-wide network of softbank bb corp.'){
			$network_career='softbank';
			$not_wifi_user_count++;
		}else if($ar[2] == 'kddi corporation'){
			$network_career='au';
			$not_wifi_user_count++;
		}else if($ar[2] == 'ntt communications corporation' || $ar[2] == 'ntt docomo inc.'){
			$network_career='docomo';
			$not_wifi_user_count++;		
		}
	
		$wifi_user_count_all++;
	}
	//wifi率
	$wifi_user_rate = 1-(float)($not_wifi_user_count/$wifi_user_count_all);
	
	$location_array = array();
	foreach($obj['locations'] as $ar){
	//'ga:region,ga:city,ga:dimension1,ga:dimension2,ga:date'	
	//[0]Tokyo [1]Minato
		$location_array[]=$ar[0].' '.$ar[1];
		
	}
	$location_array = array_count_values($location_array);
	arsort($location_array);
	$location_array_ranking = array_keys($location_array);	

	$first_city=$location_array_ranking[0];
	$second_city=$location_array_ranking[1];
	
		
	
	$depth_array=array();
	$screens_count=0;
	$map_count=0;
	foreach($obj['screens'] as $ar){
	//dimension
	//'ga:landingScreenName,ga:screenName,ga:screenDepth,ga:exitScreenName,ga:dimension1,ga:dimension2,ga:date',	
	
		$depth_array[] = $ar[2];
	/*
	   string(25) "DetailViewController-9807"
	    [1]=>
	    string(25) "DetailViewController-9577"
	    [2]=>
	    string(2) "14"
	    [3]=>
	    string(20) "SearchViewController"
	    [4]=>
	    string(36) "8D8D9150-60B4-41C1-9E09-77829672F7B4"
	    [5]=>
	    string(64) "180c8232f2b93e7a655848e6b97021fff0703bd838d8b5de1cbe60b64c63653a"
	    [6]=>
	    string(8) "20140804"
	    [7]=>
	    string(1) "1"
	    [8]=>
	    string(1) "1"
	    [9]=>
	    string(1) "1"
	    [10]=>
	    string(1) "1"
	    [11]=>
	    string(1) "1"
	    [12]=>
	    string(3) "0.0"
	    [13]=>
	    string(3) "0.0"
	    [14]=>
	    string(4) "16.0"
	    [15]=>
	    string(4) "16.0"
	*/
		//地図
		if($ar[1] == 'MyPageView' || $ar[1] == 'GoogleMapsView'){
			$map_count++;
		}
		$screens_count++;
	}
	
	$avg_depth = $st->average($depth_array);
	$max_depth = max($depth_array);
	$min_depth = min($depth_array);
	
	$map_rate =	$map_count/$screens_count;
	
	foreach($obj['screenTotals'] as $ar){
	//metrics
	//'ga:visitors,ga:screenviews,ga:appviews,ga:uniqueScreenviews,ga:uniqueAppviews,ga:screenviewsPerSession,ga:appviewsPerVisit,ga:timeOnScreen,ga:avgScreenviewDuration'; 
	
	//滞在時間
	//遷移回数	
		$visitors = $ar['ga:visitors'];
		$screenviews = $ar['ga:screenviews'];
		$uniqueScreenviews = $ar['ga:uniqueScreenviews'];
		$screenviewsPerSession = $ar['ga:screenviewsPerSession'];
		$timeOnScreen = $ar['ga:timeOnScreen'];
		$avgScreenviewDuration = $ar['ga:avgScreenviewDuration'];		
	/*
	
	array(9) {
	  ["ga:visitors"]=>
	  string(3) "397"
	  ["ga:screenviews"]=>
	  string(3) "881"
	  ["ga:appviews"]=>
	  string(3) "881"
	  ["ga:uniqueScreenviews"]=>
	  string(3) "387"
	  ["ga:uniqueAppviews"]=>
	  string(3) "387"
	  ["ga:screenviewsPerSession"]=>
	  string(17) "73.41666666666667"
	  ["ga:appviewsPerVisit"]=>
	  string(17) "73.41666666666667"
	  ["ga:timeOnScreen"]=>
	  string(7) "37992.0"
	  ["ga:avgScreenviewDuration"]=>
	  string(17) "43.46910755148741"
	}
	
	*/	
		
	}
	
	$daysSinceLastSession_array=array();
	$hour_array=array();
	
	foreach($obj['frequency'] as $ar){
	//ga:daysSinceLastSession,ga:sessionCount
		
	$daysSinceLastSession_array[] = $ar[1];
	$hour_array[] = $ar[6];
	/*
	  array(8) {
	    [0]=>
	    string(17) "Returning Visitor"//New Visitor どういう条件で判定しているか不明
	    [1]=>
	    string(1) "0"//daysSinceLastSession
	    [2]=>
	    string(1) "5"//sessionCount ???
	    [3]=>
	    string(36) "8D8D9150-60B4-41C1-9E09-77829672F7B4"
	    [4]=>
	    string(64) "180c8232f2b93e7a655848e6b97021fff0703bd838d8b5de1cbe60b64c63653a"
	    [5]=>
	    string(8) "20140804"
	    [6]=>
	    string(2) "08"//hour
	    [7]=>
	    string(1) "1"	
	    [8]=>
	    string(1) "0"//bounces	    
	  }
	*/	
	}

	$average_last_session = $st->average($daysSinceLastSession_array);
	$mode_last_session = $st->mode($daysSinceLastSession_array);
	$median_last_session = $st->median($daysSinceLastSession_array);
	
	$average_hour = $st->average($hour_array);
	$mode_hour = $st->mode($hour_array);
	$median_huor = $st->median($hour_array);	

}


?>

<?php
class Statistics
{
//平均値を求める関数
    public function average(array $values)
    {
        return (float) (array_sum($values) / count($values));
    }
    
    public function variance(array $values)
    {
        // 平均値を求める
        $ave = self::average($values);
 
        $variance = 0.0;
        foreach ($values as $val) {
            $variance += pow($val - $ave, 2);
        }
        return (float) ($variance / count($values));
    }
 
    public function standardDeviation(array $values)
    {
        // 分散を求める
        $variance = self::variance($values);
 
        // 分散の平方根
        return (float) sqrt($variance);
    }
 
    //偏差値を求める
    public function standardScore( $target, array $arr)
    {
        return ( $target - self::average($arr) ) / self::standardDeviation($arr) * 10 + 50;
    }
    
     /*
    * 最頻値を求める
    */
    public function mode(array $values)
    {
    	//最頻値を求める。それぞれの頻出回数を計算して配列に入れる。
    	$data = array_count_values($values);
    	$max = max($data);//配列から最大値を取得する。
    	$result[0] = array_keys($data,$max);
    	return $result[0];
    }
    /*
    *中央値を求める関数
    */
        public function median(array $values){
		sort($values);
		if (count($values) % 2 == 0){
			return (($values[(count($values)/2)-1]+$values[((count($values)/2))])/2);
		}else{
			return ($values[floor(count($values)/2)]);
		}
	}
}


