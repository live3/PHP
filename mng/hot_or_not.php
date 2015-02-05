<?php
date_default_timezone_set('Europe/London');
$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

/*
//$result_house = mysql_query('SELECT id, house_name_ja from houses');
$result_genre = mysql_query('SELECT * from live_genres');
while ($row = mysql_fetch_assoc($result_genre)) {
	$array=array();
	$array += array("id"=>$row['id']);
	$array += array("live_genre_name_ja"=>$row['live_genre_name_ja']);
	$genre_array[] =  $array;
}
*/

$result = mysql_query("SELECT
t.id AS ticket_id
,t.live_id
,t.ticket_type_id
,t.currency_type_id
,t.ticket_seat_type
,t.ticket_description_ja
,t.ticket_description_en
,t.ticket_description_zh
,t.ticket_price
,t.ticket_count_limit
,t.ticket_sort_no
,t.ticket_link_url
,l.id AS live_id
,l.house_id
,l.live_city_id
,l.live_tel
,l.live_title_ja
,l.live_title_en
,l.live_title_zh
,l.live_sub_title_ja
,l.live_sub_title_en
,l.live_sub_title_zh
,l.live_description_ja
,l.live_description_en
,l.live_description_zh
,l.live_start_date
,l.live_end_date
,l.live_fixed_flg
,l.deleted_at_flg
,lms.id AS live_media_id
,lms.live_id
,lms.live_media_type
,lms.live_media_source_material
,lms.live_media_source_seq_no
,lgm.id AS live_genre_management_id
,lgm.live_id
,lgm.live_genre_id
,lgm.live_genre_child_id
,lgm.live_other_txt
,lg.id AS genre_id
,lg.live_genre_name_ja
,lg.live_genre_description_ja
,lgc.id AS genre_child_id
,lgc.live_genre_child_name_ja
,lgc.live_genre_child_description_ja
,h.id AS house_id
,h.house_name_ja
,h.house_nearest_station_name_ja
,h.house_nearest_station_name_en
FROM lives l 
INNER JOIN houses h ON l.house_id = h.id
LEFT JOIN tickets t ON l.id = t.live_id 
LEFT JOIN live_media_sources lms ON t.live_id = lms.live_id 
LEFT JOIN live_genre_managements lgm ON l.id = lgm.live_id 
LEFT JOIN live_genres lg ON lg.id = lgm.live_genre_id 
LEFT JOIN live_genre_childs lgc ON lgc.id = lgm.live_genre_child_id 
WHERE l.deleted_at_flg = 0 AND lms.live_media_type = 1
Group by l.id
");
$cnt =0;
$music_array = array();
$other_array = array();

$all_array = array();

while ($row = mysql_fetch_assoc($result)) {

	$price = 'FREE';
	if($row['ticket_price'] > 0){
		$price = '¥'.str_replace('.000', '', $row['ticket_price']);
	}

	$genre = $row['live_genre_name_ja'].' | '.$row['live_genre_child_name_ja'];
	if($row['live_genre_child_name_ja'] == ''){
		$genre = $row['live_genre_name_ja'];
		if($genre == ''){
			continue;
		}
	}
	if( $row['live_genre_name_ja'] == 'Music'){
		$music_array[] = 'dic'.$cnt;
	}else{
		$other_array[] = 'dic'.$cnt;
	}
	
	$all_array[] = 'dic'.$cnt;

	$weekday = array( "日", "月", "火", "水", "木", "金", "土" );
	$date = $row['live_start_date'];
	$date_ja = date('y/m/d(', strtotime($date)).$weekday[date("w")].date(') H:i ', strtotime($date));
	$date_en = date('y/m/d(D) H:i ', strtotime($date));
/*	
echo $date;
echo '<br />';
echo date('y/m/d(D) H:i ', strtotime($date));
exit;
*/

	echo 'NSDictionary *dic'.$cnt.'=@{@"name": @"'.str_replace('"', '\"', $row['live_title_ja']).' / '.$price.'",
                          @"name_en": @"'.str_replace('"', '\"', $row['live_title_en']).' / '.$price.'",
                          @"id": @'.$row['live_id'].',
                          @"detail": @"'.$genre.'",
                          @"station": @"'.$row['house_nearest_station_name_ja'].'",
                          @"station_en": @"'.$row['house_nearest_station_name_en'].'",
                          @"date": @"'.$date_ja.'",
                          @"date_en": @"'.$date_en.'",
                          @"image":@"'.$row['live_media_source_material'].'"};';
	echo '<br />';


	$cnt++;	
}
/*
    NSDictionary *dic86=@{@"name": @"黒猫チェルシー / Free",
                          @"id": @86,
                          @"detail": @"Music | Rock",
                          @"image":@"http://images.ro69.jp/images/entry/80597/date_20120612212422_640x520_4FD734C3F9E4.jpg"};
*/

/*
echo 'allMusicArray = [@[';
foreach($music_array as $m ){
	echo $m.',';
}
echo ']mutableCopy];';


echo 'allOtherArray = [@[';
foreach($other_array as $o ){
	echo $o.',';
}
echo ',]mutableCopy];';
*/

echo 'allArray = [@[';
foreach($all_array as $m ){
	echo $m.',';
}
echo ']mutableCopy];';

