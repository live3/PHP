<?php

$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

$limited_id_array = array(//type mr eq ls
	"houses" => array('type'=>'mr','ids'=>array('0')),
	"lives" => array('type'=>'mr','ids'=>array('0'))  
);

if(isset($_GET['live_id'])){
	$live_id = $_GET['live_id'];
	$limited_id_array['lives'] = array('type'=>'eq','ids'=>array("$live_id"));
}

if(isset($_GET['house_id'])){
	$hous_id = $_GET['house_id'];
	$limited_id_array['houses'] = array('type'=>'eq','ids'=>array("$hous_id"));

}

$table_array = array('houses','lives');
foreach($table_array as $table){
	$input_array = array();
	if($table == 'houses'){
		$input_array = array('house_name_ja','house_address_ja','house_nearest_station_ja','house_nearest_station_line_ja','house_nearest_station_name_ja','house_nearest_station_detail_ja');
		//'house_nearest_station_line_ja','house_nearest_station_detail_ja'
	}else if($table == 'lives'){
		$input_array = array('live_description_ja');//live_title_ja
	}
	
	foreach($input_array as $input){
		$output = str_replace('_ja','_en', $input);
		execTranslate($table, $input, $output, $limited_id_array);
	
	}
}

//mysql_close($db_selected);	


function execTranslate($table, $input, $output,$limited_id_array){
	$result_genre = mysql_query("SELECT * from $table");
	
	$type = $limited_id_array[$table]['type'];
	$limited_id_first = $limited_id_array[$table]['ids'][0];
	
	if($limited_id_first == 0 ){ return; }
	
	while ($row = mysql_fetch_assoc($result_genre)) {
		$id = $row['id'];
	
		if($type == 'mr' ){
			if($limited_id_first <= $id ){
				continue;
			}
		}else if($type == 'eq' ){
			if( !in_array($id, $limited_id_array[$table]['ids']) ){
				continue;
			}
		}else if($type == 'ls' ){
			if($limited_id_first >= $id ){
				continue;
			}	
		}
	
		$result =translateBing($row[$input]);
		$result = str_replace('Percent', '', $result);
	
		if($input == 'house_address_ja'){
			$result = preg_replace('%\s?Tokyo,?\s?%', '', $result);
			$result = preg_replace('%^,+?%', '', $result);
			$result = preg_replace('%^\s?%', '', $result);	
		}
		echo $id .'-'. $row[$input].' >>><hr /> '.$result . '<hr />';
		
		$result = mysql_real_escape_string($result);
	
		if(strlen($result) <10 ){continue; }
	
		$query = "UPDATE $table SET $output = '$result' WHERE id = $id;";
		$r =mysql_query($query);
		var_dump($r);
		echo '<hr />';		
		//for chinese
		$output =  str_replace('_en','_zh', $output);
		$query = "UPDATE $table SET $output = '$result' WHERE id = $id;";
		$r =mysql_query($query);
		

	}

}



function translateBing($keyword){
	
	$keyword = urlencode($keyword);
	
	$accountKey = 'GB6arGjKiY3N1EP+iBfp/rLAyggWl7LltHv0uoymteg';            
	$cred = sprintf('Authorization: Basic %s', base64_encode($accountKey . ":" . $accountKey) );
	$context = stream_context_create(array(
	    'http' => array(
	        'header' => $cred
	    )
	));
	
	$result = file_get_contents('https://api.datamarket.azure.com/Bing/MicrosoftTranslator/v1/Translate?Text=%27%'.$keyword.'%27&To=%27en%27&From=%27ja%27', 0, $context);
//	var_dump($result);
	//$response = json_decode($response);
	$result = explode('<d:Text m:type="Edm.String">', $result);
	$result = explode('</d:Text>', $result[1]);
	$result = $result[0];
	$result = str_replace('%', '', $result);
//	echo $result;
//	echo '<hr />';
	return $result;
}


function translateBingV2($keyword){
	
	$url = 'http://api.microsofttranslator.com/V2/soap.svc';
	$appid = 'live3_microsoft_api';
	
	$query = array(
		'appId' => $appid,
		'text' => 'てすと',
		'from' => 'ja',
		'to' => 'en'
	);
	
	$client = new SoapClient($url);
	$result = $client->Translate($query); 
	
	echo $result->TranslateResult;	
}

