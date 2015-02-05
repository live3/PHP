<?php
$l=array();
$house_array=array();
$ticket_type_array=array();

$post_id=1;
if(isset($_GET['id'])){
	$post_id=$_GET['id'];
}

$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}


$delete_id = 99999999999999;
if(isset($_POST['delete_id'])){
	$delete_id = $_POST['delete_id'];
}

//$result_house = mysql_query('SELECT id, house_name_ja from houses');
$result_house = mysql_query("delete from live_media_sources where id = $delete_id");

exit;
