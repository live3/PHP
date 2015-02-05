<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

//LIVE3 DB
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

	$genre_array = array();
	$mysql_res = mysql_query("select * from live_genre_childs ");//全部
	while ($row = mysql_fetch_assoc($mysql_res)) {

//		$id = $row['id'];
		$live_genre_child_name_ja = $row['live_genre_child_name_ja'];
		$genre_array[] = $live_genre_child_name_ja;
//		$genre_name_en = $row['genre_name_en'];
//		$genre_name_zh = $row['genre_name_zh'];		
		
	}

	$row_count = 0;
//	$mysql_res = mysql_query("select * from bands where band_description_en IS NULL OR band_description_en = '' ");//空のものだけ
	$mysql_res = mysql_query("select * from genres ");//全部
	while ($row = mysql_fetch_assoc($mysql_res)) {

		$id = $row['id'];
		$genre_name_ja = $row['genre_name_ja'];
		$genre_name_en = $row['genre_name_en'];
		$genre_name_zh = $row['genre_name_zh'];		

		$live_genre_id = 1;//Music

		$row_count++;

		$key = array_search($genre_name_ja, $genre_array); 
		if($key !== false){
			continue;			
		}else{
			echo 'else<br />';			
			$sql_title = sprintf("Insert into live_genre_childs (live_genre_id, live_genre_child_name_ja,live_genre_child_name_en,live_genre_child_name_zh) values ( %d, %s, %s, %s)",$live_genre_id, quote_smart($genre_name_ja), quote_smart($genre_name_en), quote_smart($genre_name_zh));
			$result_flag_ja = mysql_query($sql_title);
//			echo $result_flag_ja;
		}
		echo $id;
		echo '<br/>';
		echo $genre_name_ja;		
		echo '<hr/>';

	}


	
	exit;

function quote_smart($value)
{
    // 数値以外をクオートする
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}
exit;
