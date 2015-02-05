<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

//$url = 'http://ws.ponpare.jp/ws/wsp0100/Wst0101Action.do?key=ois14a2df76387&format=json';//大エリアAPI
//large area  1/関東 _ 2/関西 _ 3/北海道 _ 4/東北 _ 8/北信越 _ 5/東海 _ 6/中国 _ 9/四国 _ 7/九州・沖縄 _ 

//$url = 'http://ws.ponpare.jp/ws/wsp0100/Wst0102Action.do?key=ois14a2df76387&format=json&large_area=1';//中エリアAPI
//middle area  8/茨城県 _ 9/栃木県 _ 10/群馬県 _ 11/埼玉県 _ 12/千葉県 _ 13/東京都 _ 14/神奈川県 _ 

//$url = 'http://ws.ponpare.jp/ws/wsp0100/Wst0103Action.do?key=ois14a2df76387&format=json&middle_area=13';//小エリアAPI
//small area  8/新宿・高田馬場・中野・吉祥寺 _ 13/池袋・神楽坂・赤羽 _ 11/渋谷・青山・自由が丘 _ 12/恵比寿・目黒・品川 _ 10/赤坂・六本木・麻布 _ 9/銀座・新橋・東京・上野 _ 29/立川・町田・八王子他 _ 

$mid_area = '&middle_area=13,11,12,14';//東京、埼玉、千葉、神奈川
$url = 'http://ws.ponpare.jp/ws/wsp0100/Wst0201Action.do?key=ois14a2df76387'.$mid_area.'&format=json';//チケットAPI
$headers = array(
    'header' => "Content-Type: text/xml"
);
$options = array('http' => array(
    'method' => 'POST',
	'header' => implode("\r\n", $headers),
));
$json = file_get_contents($url, false, stream_context_create($options));
$obj =json_decode($json, true);

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Ponpare</title>
</head>
<body>
<?php
$cnt=0;
foreach($obj['results']["ticket"] as $tk ){
//	echo $tk['shop_address'];
//	var_dump($tk);
	//01//グルメ  02//ホテル　03//ヘアサロン 04//リラクゼーション 05//通学レッスン 06//宅配　07//イベントその他
	$id = $tk['id'];
	$genre_id = $tk['genre_id'];
	$genre = $tk['genre'];
	if($genre != 'イベント'){continue;}	
	
	$cc = $tk['catchcopy'];
	$pics = get_pics($tk);	
	$price = $tk['price']; 
	$usual_price = $tk['usual_price'];
	$sales_from = $tk['sales_from'];
	$sales_until = $tk['sales_until'];
	$valid_from = $tk['valid_from'];
	$valid_until = $tk['valid_until'];
	
	$description = $tk['description'];
	$description = htmlspecialchars_decode($description);
	//ダウブルクオートがどうやっても変換できない
	$description = str_replace('<a href=', '<br>', $description);
	$description = preg_replace('%target=[^>]*?>%', '<br>', $description);
	$description = preg_replace('%<br[^>]*?>%', '&#13;', $description);
	$description = str_replace('&lt;br&gt;', '&#13;', $description);
	$description = str_replace('&lt;b&gt;', '', $description);

	$max_per_purchase = $tk['max_per_purchase'];
	$shop_name = $tk['shop_name'];
	$shop_address = $tk['shop_address'];
	$shop_access = $tk['shop_access'];
	$shop_y = $tk['shop_y'];
	$shop_x = $tk['shop_x'];
//sales_status 	販売ステータス 0:販売中、1:取引成立中、2:SoldOut
	$sales_status = $tk['sales_status'];

	$large_category_name = $tk['large_category_name'];
	$medium_category_name = $tk['medium_category_name'];
	$small_category_name = $tk['small_category_name'];

//	if($genre_id == '02' || $genre_id == '06' || $genre_id == '05' || $genre_id == '03' ){continue;}

	echo '<div style="width:70%;float:left;">';
	echo 'No.'.$cnt;
	echo '//販売ステータス(0:販売中、1:取引成立中、2:SoldOut)：'.$sales_status;
	echo '<br>';
//	echo $genre_id;
//	echo '//';
//	echo $genre;
//	echo '//';
	echo $cc;
	echo '<br>';
	foreach($pics as $pic){
		echo '<img src='.$pic.' width="120" />';		
	}
	echo '<br>';
	echo '価格 '.$price;
	echo '/';
	echo '元値 '.$usual_price;
	echo '<br>';
	echo '販売期間 '.$sales_from;
	echo ' ~ ';
	echo $sales_until;
	echo '<br>';
	echo '有効期間'.$valid_from;
	echo ' ~ ';
	echo $valid_until;
	echo '<br />';
	echo '地名: '.$shop_name;
	echo '<br>';
	echo '住所: '.$shop_address;
	echo '<br>';	
	echo "<a href='http://live3.info/houses/' target='_blank'>House存在確認</a>";
	echo "<form action='http://live3.info/houses/new' method='post' target='_blank'>
			<input type='hidden' name='prep_data' value='1' />
			<input type='hidden' name='house_name_ja' value='$shop_name' />
			<input type='hidden' name='house_address_ja' value='$shop_address' />				
			<input type='hidden' name='house_latitude' value='$shop_y' />
			<input type='hidden' name='house_longitude' value='$shop_x' />		
			<input type='hidden' name='house_nearest_station_ja' value='$shop_access' />
			<input type='submit' value='House作成' />	
		</form>";
	
	
	echo '<br />';	
	echo $description;
	echo '<br />';	
		
	$house_id = 0;
	$house_id = getHouseID($shop_name);
	echo 'house_id: '.$house_id;
	echo "<form action='http://live3.info/events/new' method='post' target='_blank'>
			<input type='hidden' name='prep_data' value='1' />
			<input type='hidden' name='live_title_ja' value='$cc' />
			<input type='hidden' name='live_sub_title_ja' value='pnpr' />
			<input type='hidden' name='house_id' value='$house_id' />				
			<input type='hidden' name='live_description_ja' value='$description' />";
	if(strlen($usual_price) > 0 ){
		echo "<input type='hidden' name='ticket_discount_price' value='$price' />";
		echo "<input type='hidden' name='ticket_price' value='$usual_price' />";
	}else{
		echo "<input type='hidden' name='ticket_price' value='$price' />";		
	}
//ticket_count_limit	
	echo "<input type='hidden' name='ticket_type_id' value='4' />";			
	echo 	"<input type='submit' value='作成するよ new' />	
		</form>";
	
	echo'</div>';
	
	echo '<div style="width:30%;float:right;">';	
//variation sku2_item
	if(isset($tk['variation']) ){
		echo '<table>';
		foreach($tk['variation']['sku2_item'] as $vari ){
			echo '<tr>';
			$temp_date = '';
			$temp_time = '';
			$post_date = '';
			foreach($vari as $va ){
				echo '<td>'.$va.'<td/>';
				
				//date
				if(preg_match('%(\d{1,2})/(\d{1,2})%',$va,$m)){
					$year = date("Y");
					if((int)$m[1]< (int)date("m")){$year = date("Y",strtotime('+1 year')); }
					$temp_date = $year.'-'.addZero($m[1]).'-'.addZero($m[2]);
				}else if(preg_match('%(\d{1,2})月(\d{1,2})日%',$va,$m)){
					$year = date("Y");
					if((int)$m[1]< (int)date("m")){$year = date("Y",strtotime('+1 year')); }
					$temp_date = $year.'-'.addZero($m[1]).'-'.addZero($m[2]);
				}else if(preg_match('%(\d{1,2})日%',$va,$m)){
					$year = date("Y");
					$temp_date = $year.'-'.date("m").'-'.addZero($m[1]);
				}
				
				//time
				if(preg_match('%(\d{1,2}):(\d{1,2})%',$va,$m)){				
					$temp_time = addZero($m[1]).':'.addZero($m[2]);
				}else if(preg_match('%(\d{1,2})時(半)?%',$va,$m)){
					$temp_time = addZero($m[1]).':00';
					if(isset($m[2])){
						$temp_time = addZero($m[1]).':30';
					}
				}

//				echo '<td>'.$temp_date.' '.$temp_time.'<td/>';
				$post_date = $temp_date.' '.$temp_time;	
			}
			echo "<form action='http://live3.info/events/new' method='post' target='_blank'>
					<input type='hidden' name='prep_data' value='1' />
					<input type='hidden' name='live_start_date' value='$post_date' />
					<input type='hidden' name='live_sub_title_ja' value='pnpr' />					
					<input type='hidden' name='live_title_ja' value='$cc' />
					<input type='hidden' name='house_id' value='$house_id' />";
			if(strlen($usual_price) > 0 ){
				echo "<input type='hidden' name='ticket_discount_price' value='$price' />";
				echo "<input type='hidden' name='ticket_price' value='$usual_price' />";
			}else{
				echo "<input type='hidden' name='ticket_price' value='$price' />";		
			}
		//ticket_count_limit	
			echo 	"<input type='hidden' name='ticket_type_id' value='4' />";						
			echo	"<input type='hidden' name='live_description_ja' value='$description' />
					<input type='submit' value='作成するよ new' />	
				</form>";

			echo '<tr/>';			
		}
		echo '</table>';		
	}	
	echo'</div>';	
	echo '<div style="clear:both;"></div>';
	echo '<hr>';
	$cnt++;
}

function get_pics($trg){
	$array = array();
	for($i = 1; $i <= 6; $i++ ){
		$key = 'pic'.$i;
		if(isset($trg[$key]) ){
			if(strlen($trg[$key]) == 0 ){continue;}
			$array[] = $trg[$key];
		}		
	}	
	return $array;
}

function addZero($dateOrTime){
	if(strlen($dateOrTime) == 1){
		$dateOrTime = '0'.$dateOrTime;
	}
	return $dateOrTime;
}

function getHouseID($house_name){
	$house_id = 0; 
	//LIVE3 DB
	$all_live_array[] = array();
	$link = mysql_connect('live3app2.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
	if (!$link) {
	    print(mysql_error());
	}
	mysql_query("SET NAMES utf8",$link); 
	$db_selected = mysql_select_db('live3appdb', $link);
	if (!$db_selected){
	    die('データベース選択失敗です。'.mysql_error());
	}
	$result = mysql_query("SELECT * FROM houses where house_name_ja = ".$house_name);
	if($result){
		$match_c = 0;
		while ($row = mysql_fetch_assoc($result)) {
			$house_id = $row['id'];
		}
	}
	return $house_id;
}

$url = 'http://ws.ponpare.jp/ws/wsp0100/Wst0202Action.do?key=ois14a2df76387&ticket_id=1065&format=json';//販売ステータスAPI
?>

</body>
</html>