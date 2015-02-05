<?php
ini_set("max_execution_time",0);
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

$sqlite_bands_array = array();
for($i=1;$i<=6;$i++){
	$db = new SQLite3('barks_artist'.$i.'.sqlite');
	
	$state = "select * from bands";
	$result = $db->query($state );
	$start_i = 1;
	
	//DBと一致確認
	while( $row = $result->fetchArray() ) {
		$id = (int)$row['id'];
		$sqlite_bands_array[$id] = array('name'=>mb_convert_kana($row['name'],'KV'),'sqlite_number'=>$i,'desc'=>$row['description1']);
	}

}


function tranlate_temp($desc_short){
	$sqlite_description_en='';
		$url = 'http://util.live3.info/scp/bing_transV2.php?param='.urlencode($desc_short).'&key=nfjkanjkfnkad7i3riqhf3qffji3aljfj';
		$headers = array(
		    'header' => "Content-Type: text/xml"
		);
		$options = array('http' => array(
		    'method' => 'POST',
			'header' => implode("\r\n", $headers),
		));
		$xml_trans = file_get_contents($url, false, stream_context_create($options));
		$xml = simplexml_load_string($xml_trans);
		$sqlite_description_en = $sqlite_description_en.($xml->elem->data);

	return $sqlite_description_en;
}


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
$result = mysql_query("SELECT * FROM bands");

/*
var_dump($sqlite_bands_array);
exit;
*/

//$sqlite_bands_array=array('10-FEET');
$match_c = 0;
while ($row = mysql_fetch_assoc($result)) {
	//$all_live_array[]= $row;
	$band_name_ja =  $row['band_name_ja'];
	$mysql_band_id =  $row['id'];
	$band_desc =  $row['band_description_ja'];
	$band_desc_flg = 0;
	if(mb_strlen($band_desc) > 10){ $band_desc_flg=1; }

//echo mb_substr(conv($band_name_ja), -3,3);echo'<br />';

//	echo $band_name_ja;echo'<hr />';
//  continue;	
//	$key = array_search($band_name_ja, $sqlite_bands_array);

  	if($band_desc_flg === 0){//descrtiptionが無ければ
		$temp_match_f = 0;
		foreach($sqlite_bands_array as $sqlite_band_id => $sqlite_name_num_array ){		
			$sqlite_name = $sqlite_name_num_array['name'];
			if(strpos($sqlite_name, 'unknow') ){continue;}//とりあえず！！
/*
			if(strpos($sqlite_name, 'the ') ){continue;}//とりあえず！！ the 系は除く
			if(mb_strpos($sqlite_name, 'The ') ){continue;}//とりあえず！！ the 系は除く
			if(mb_strpos($sqlite_name, 'THE ') ){continue;}//とりあえず！！ the 系は除く
*/
//			if (strstr($sqlite_name, mb_substr($band_name_ja, 0,5))) {
			if ( mb_strstr(conv($sqlite_name),mb_substr(conv($band_name_ja), 0,5))) {			
//			if (  urlencode(trim($sqlite_name)) == urlencode(trim($band_name_ja)) ) {
				$sqlite_number = $sqlite_name_num_array['sqlite_number'];

				echo $band_name_ja.'||'. $sqlite_name;
				echo "<form action='./sc_barks_update.php' method='post' target='_blank'>
				<input type='hidden' name='band_id' value='$mysql_band_id' />
				<input type='hidden' name='sqlite_band_id' value='$sqlite_band_id' />
				<input type='hidden' name='sqlite_number' value='$sqlite_number' />
				<input type='submit' />
				</form>";
				echo '<hr />';
			  	$temp_match_f = 1;
			 } else {
			 }
		}	  	
	  	$match_c = $match_c+ $temp_match_f;
	  	
  	}


	
//	echo $key;
	
//	echo '<hr />';
	
//	$all_live_array[$row['id']]= $row;
}
unset($all_live_array[0]);
mysql_close($link);	

echo $match_c;

function conv($str){
	$str =str_replace(' ', '', $str);
	$str =str_replace('　', '', $str);
	$str =str_replace('"', '', $str);
	$str =str_replace("'", '', $str);	
	$str = mb_convert_kana($str,'KHV');
	return $str;
}

exit;