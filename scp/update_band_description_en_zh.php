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

$post_band_id = 0;
if(isset($_POST['band_id'])){
	$post_band_id = $_POST['band_id'];
}

if($post_band_id == 0){//post前
	$row_count = 0;
	$mysql_res = mysql_query("select * from bands where band_description_en IS NULL OR band_description_en = '' ");//空のものだけ
//	$mysql_res = mysql_query("select * from bands ");//全部
	while ($row = mysql_fetch_assoc($mysql_res)) {
		echo 'No.'.$row_count.'<br />';
		$band_id = $row['id'];
		$band_name_ja = $row['band_name_ja'];
		$band_description_ja = $row['band_description_ja'];		
		$band_description_en = $row['band_description_en'];	
		echo '<a href="https://www.google.co.jp/search?q='.$band_name_ja.' wiki" target="_blank">'.$band_name_ja.'</a>';echo '<br />';
		echo $band_description_ja;echo '<br />';
		echo '<hr/>';
		echo $band_description_en;echo '<br />';
		echo "<form action='' method='post' target='_blank'>
		<input type='hidden' name='band_id' value='$band_id' />
		<textarea name='band_description_ja' value='' style='width:40%;height:100px;' rows='4' cols='40' /></textarea>
		<input type='submit' />
		</form>";
		echo '<hr />';
		$row_count++;
	}	
	exit;
}



$mysql_res = mysql_query("select * from bands where id = $post_band_id");
$row = mysql_fetch_assoc($mysql_res);

$description_ja = $row['band_description_ja'];
$band_name = $row['band_name_ja'];
if(isset($_POST['band_description_ja'])){
	if( mb_strlen( $_POST['band_description_ja']) < 10 ){
		echo '文字数が少ない';
		exit;
	}
	$description_ja = $_POST['band_description_ja'];
	$sql_ja = sprintf("UPDATE bands SET band_description_ja = %s WHERE id = %s", quote_smart($description_ja), quote_smart($post_band_id));
	$result_flag_ja = mysql_query($sql_ja);
	echo '<hr />result ja: '. $result_flag_ja;	
}

//translate
$sqlite_description_array = mb_str_split($description_ja, 900);

function mb_str_split($str, $split_len = 1) {//マルチバイト分割 str_splitのマルチバイト版が無い
    mb_internal_encoding('UTF-8');
    mb_regex_encoding('UTF-8');
    if ($split_len <= 0) {
        $split_len = 1;
    }
    $strlen = mb_strlen($str, 'UTF-8');
    $ret    = array();
    for ($i = 0; $i < $strlen; $i += $split_len) {
        $ret[ ] = mb_substr($str, $i, $split_len);
    }
    return $ret;
}

$sqlite_description_en='';
foreach($sqlite_description_array as $desc_short ){
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
}

echo $band_name; echo'<br />';
echo $description_ja;
echo '<hr />';
echo $sqlite_description_en;
echo '<hr />';

//update en
$sql_en = sprintf("UPDATE bands SET band_description_en = %s WHERE id = %s", quote_smart($sqlite_description_en), quote_smart($post_band_id));
$result_flag_en = mysql_query($sql_en);
echo 'result en: '. $result_flag_en;
echo'<br />';
$sql_zh = sprintf("UPDATE bands SET band_description_zh = %s WHERE id = %s", quote_smart($sqlite_description_en), quote_smart($post_band_id));
$result_flag_zh = mysql_query($sql_zh);
echo 'result zh: '. $result_flag_zh;

/*
var_dump($sqlite_bands_array);
exit;
*/

function quote_smart($value)
{
    // 数値以外をクオートする
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}
exit;
