<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

$db = new SQLite3('cdj1415_master.sqlite');

$db->exec('CREATE TABLE IF NOT EXISTS events (id INTEGER PRIMARY KEY ,band_id INTEGER, name TEXT, ruby TEXT, filename TEXT, urlString TEXT, artistCode TEXT, date TEXT, start TEXT, end TEXT, indexNum TEXT, scCode TEXT, stageCode TEXT)');
	
	
/*
$filepath = "./schedule.csv";	
$records;
$cnt = 0;
if (($handle = fopen($filepath, "r")) !== false) {
    while (($line = fgetcsv($handle, 1000, ",")) !== false) {
        $records[] = $line; 
        if($cnt > 0){
	        $code = $line[0];
	        $date = $line[1];
	        $stageCode = $line[2];
	        $index = $line[3];
	        $artistCode = $line[4];
	        $start = $line[5];
	        $end = $line[6];
	        
			$db->exec("insert into events (id, artistCode, date, start, end, indexNum, scCode, stageCode) VALUES ($code,\"$artistCode\",\"$date\",\"$start\",\"$end\",\"$index\",\"$code\",\"$stageCode\")");        	        
        }
		$cnt++;
		echo $cnt;
    } 
    fclose($handle); 
}
*/

/*
$filepath = "./artist.csv";	
$records;
$cnt = 0;
if (($handle = fopen($filepath, "r")) !== false) {
    while (($line = fgetcsv($handle, 1000, ",")) !== false) {
        $records[] = $line; 
        if($cnt > 0){
	        $code = $line[0];
	        $name = c($line[1],$db);
	        $ruby = c($line[2],$db);
	        $filename = $line[3];
	        $urlString = $line[4];
	        
	        
			$state = "select * from events where artistCode = $code";
			$result = $db->query($state );
			$start_i = 1;
			$sqlite_bands_array = array();
			//DBと一致確認
			while( $row = $result->fetchArray() ) {
				$id = (int)$row['id'];
				$db->exec("update events set name = \"$name\", ruby = \"$ruby\", filename = \"$filename\", urlString = \"$urlString\" where id = $id ");
			}	
  	        
        }
		$cnt++;
    } 
    fclose($handle); 
} 

var_dump($records);
*/

/*

$state = "select * from events";
$result = $db->query($state );
$start_i = 1;

//LIVE3 DB
$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

//DBと一致確認
while( $row = $result->fetchArray() ) {
	$id = (int)$row['id'];
	$name = $row['name'];
	
	$res_mysql = mysql_query("SELECT * FROM bands where band_name_ja = \"$name\" collate utf8_unicode_ci");
	$match_c = 0;
	while ($row_mysql = mysql_fetch_assoc($res_mysql)) {
		$band_id = $row_mysql["id"];
		$db->exec("update events set band_id = \"$band_id\" where id = $id ");		
	}

}	


*/

//$db->exec('update tickets set deleted_at = 1');

	
	
/*	
BandGenreManagement.create(band_id: 502, genre_id: 2 ,band_genre_management_other_txt: '',deleted_at_flg: 0)

BandMediaSource.create(band_id: 502 ,band_media_source_material:'http://rsr.wess.co.jp/2014/artists/lineup/profile/images/artists/012ki.jpg',band_media_type: 1, band_media_source_seq_no: 6,deleted_at_flg: 0)

BandMediaSource.create(band_id: 502 ,band_media_source_material: 'z_TSLlTRZQ4',band_media_type: 2, band_media_source_seq_no: 2,deleted_at_flg: 0)

FesStageBand.create(fes_stage_id: 36 , band_id: 502, fes_stage_band_start_date: DateTime.strptime('08/15/2014 19:00', '%m/%d/%Y %H:%M'), fes_stage_band_end_date: DateTime.strptime('08/15/2014 20:00', '%m/%d/%Y %H:%M'), deleted_at_flg: 0)
*/



$state = "select * from events";
$result = $db->query($state );
$start_i = 1;

//LIVE3 DB
$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

//DBと一致確認
while( $row = $result->fetchArray() ) {
	$id = (int)$row['id'];
	$band_id = (int)$row['band_id'];
	$name = c($row['name'],$db);
	$urlString = $row['urlString'];
	$date = $row['date'];
	$start = $row['start'];
	$end = $row['end'];
	$stageCode = (int)$row['stageCode'];


	$start_date = wordwrap($date, 2, "/", true).'/2014 '.wordwrap($start, 2, ":", true);//09/01/2009 17:00
	if((int)substr($start, 0,2) >= 24){
		$hour = (int)substr($start, 0,2) - 24;
		$min = substr($start, 2,2);
		$start_date = '01/01/2015 0'.$hour.':'.$min;//09/01/2009 17:00	
	}

	$end_date = wordwrap($date, 2, "/", true).'/2014 '.wordwrap($end, 2, ":", true);//09/01/2009 17:00
	if((int)substr($end, 0,2) >= 24){
		$hour = (int)substr($end, 0,2) - 24;
		$min = substr($end, 2,2);
		$end_date = '01/01/2015 0'.$hour.':'.$min;//09/01/2009 17:00			
	}	
	
	$stage_num = $stageCode + 48;

	if($band_id == NULL){
		echo "band = Band.create(band_icon_url: '', band_email: '', band_phone_number: '', band_name_ja: '$name', band_name_en: '$name', band_name_zh: '$name', band_description_ja: '', band_description_en: '', band_description_zh: '', band_country_type: 1, deleted_at_flg: 0 )";
		echo '<br>';
		echo "BandGenreManagement.create(band_id: band.id, genre_id: 1 ,band_genre_management_other_txt: '')";
		echo '<br>';
		echo "FesStageBand.create(fes_stage_id: ($stage_num), band_id: band.id, ";

	}else{
		echo "FesStageBand.create(fes_stage_id: ($stage_num), band_id: $band_id, ";		
	}

	echo "fes_stage_band_start_date: DateTime.strptime(\"$start_date\", \"%m/%d/%Y %H:%M\"), ";
	echo "fes_stage_band_end_date: DateTime.strptime(\"$end_date\", \"%m/%d/%Y %H:%M\"), deleted_at_flg: 0)";
	echo '<br>';

}	


# 	band = Band.create(band_icon_url: '', band_email: '', band_phone_number: '', band_name_ja: '///', band_name_en: '///', band_name_zh: '///', 
# 	band_description_ja: '', band_description_en: '', band_description_zh: '', band_country_type: 1, deleted_at_flg: 0 )
# 	BandMediaSource.create(band_id: band.id ,band_media_source_material: '///',band_media_type: 1, band_media_source_seq_no: 1)
# 	BandGenreManagement.create(band_id: band.id, genre_id: 1 ,band_genre_management_other_txt: '')
# 	
# 	FesStageBand.create(fes_stage_id: (count%stage_count), band_id: band.id, 
# 		fes_stage_band_start_date: DateTime.strptime("09/01/2009 17:00", "%m/%d/%Y %H:%M"), 
# 		fes_stage_band_end_date: DateTime.strptime("09/01/2009 17:00", "%m/%d/%Y %H:%M"), 
# 		deleted_at_flg: 0)

function c($text, $db){
//	$text=sqlite_escape_string($text);
//	$text=mb_convert_encoding($text, "UTF-8",'auto');
//	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
	$text=str_replace("'", '’', $text);
	return $text;
}



