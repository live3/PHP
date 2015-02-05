<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

/////////バンドの名前出すよ
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
$db_bands_array = array();
//rock in
$mysql_res = mysql_query("select * from bands inner join fes_stage_bands on bands.id = fes_stage_bands.band_id where fes_stage_bands.fes_stage_id >= 15 AND fes_stage_bands.fes_stage_id <= 20");
//summer sonic tokyo
$mysql_res = mysql_query("select * from bands inner join fes_stage_bands on bands.id = fes_stage_bands.band_id where fes_stage_bands.fes_stage_id >= 24 AND fes_stage_bands.fes_stage_id <= 30");
//summer sonic osaka
$mysql_res = mysql_query("select * from bands inner join fes_stage_bands on bands.id = fes_stage_bands.band_id where fes_stage_bands.fes_stage_id >= 31 AND fes_stage_bands.fes_stage_id <= 35");
//rising
$mysql_res = mysql_query("select * from bands inner join fes_stage_bands on bands.id = fes_stage_bands.band_id where fes_stage_bands.fes_stage_id >= 36 AND fes_stage_bands.fes_stage_id <= 42");
//sonimani
$mysql_res = mysql_query("select * from bands inner join fes_stage_bands on bands.id = fes_stage_bands.band_id where fes_stage_bands.fes_stage_id >= 43 AND fes_stage_bands.fes_stage_id <= 45");

$mysql_res = mysql_query("
select * from bands inner join fes_stage_bands on bands.id = fes_stage_bands.band_id inner join fes_stages on fes_stage_bands.fes_stage_id = fes_stages.id inner join fes on fes.id = fes_stages.fes_id order by fes.id
");

$weekday = array( "日", "月", "火", "水", "木", "金", "土" );

while ($row = mysql_fetch_assoc($mysql_res)) {
	$band_id = $row['id'];
	$band_name = $row['band_name_ja'];
	$db_bands_array[$band_name] = $band_id;
//	echo '<a href="http://live3.info/bands/'.$band_id.'">'.$band_name.'</a>';
//	echo '<hr />';

	$fes_name_ja = $row['fes_name_ja'];
	$stage_name = $row['fes_stage_name_ja'];
	$date = $row["fes_stage_band_start_date"];
	
	//何日目？
	
	$day_count = "";
	
	$day_week_ja = $weekday[date("w", strtotime($date))];
	$day_week_en = date('l', strtotime($date));
	
	$youtube_name='';

	echo $fes_name_ja;
	echo '<br />';
	echo $band_name;
	echo '<br />';
	echo $date;
	echo '<br />';
	echo $day_count;
	echo '<br />';	
	echo $day_week_ja.'曜日';
	echo '<br />';	
	echo $day_week_en;
	echo '<br />';	
	echo $stage_name;
	echo '<br />';	
	echo $youtube_name;
	
	echo '<hr />';
	
	exit;
}	
exit;
/////////バンドの名前出すよ　えんど