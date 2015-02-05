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

$fes_id = 4;//define!!
//	$code = 'SST2014';//define!!
$code = 'SSO2014';
	
//$db = new SQLite3('samasoni_tokyo2014.sqlite');
//$db = new SQLite3('ssTokyo.db');//from SSOxx & SSTxx, iOS app
//$db = new SQLite3('ssOsaka.db');	

$db = new SQLite3('ssCrowl.db');	

//$db->exec('CREATE TABLE IF NOT EXISTS bands (id INTEGER PRIMARY KEY ,name TEXT,nameF TEXT, link TEXT,image TEXT,member TEXT, description1 Text, description2 TEXT, rate Text,description3 TEXT )');
//artist_crawler($db);
//exit;

$db_stages_array=array();
$mysql_res = mysql_query("select * from fes_stages where fes_id = $fes_id ");
while ($row = mysql_fetch_assoc($mysql_res)) {
	$stage_id = $row['id'];
	$stage_name = $row['fes_stage_name_ja'];
	$db_stages_array[$stage_name] = $stage_id;
}	

$db_bands_array=array();
$mysql_res = mysql_query("select * from bands");
while ($row = mysql_fetch_assoc($mysql_res)) {
	$band_id = $row['id'];
	$band_name = $row['band_name_ja'];
	$band_description = $row['band_description_ja'];
	
	$mysql_m_res = mysql_query("select count(*) from band_media_sources where band_id = $band_id");
	$media_count_res = mysql_fetch_row($mysql_m_res);
	$mc = intval($media_count_res[0]);
	
	$db_bands_array[$band_id] = array('name'=> $band_name,'desc'=>$band_description,'media'=>$mc);
}	

//	exit;

/*
class NewDB extends SQLite3{
    function __construct() {
        $this->open('sgkc.sqlite');
    }
}
$db = new NewDB();
*/
//seed発行
//


for_seed_date($db,$db_stages_array,$fes_id,$db_bands_array,$code);
exit;

function c($text, $db){
//	$text=sqlite_escape_string($text);
//	$text=mb_convert_encoding($text, "UTF-8",'auto');
//	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
	$text=str_replace("'", '’', $text);
	return $text;
}
function conv($str){
	$str =str_replace(' ', '', $str);
	$str = mb_convert_kana($str,'KHV');
	
$str = mb_convert_kana($str,"k"); //半角カタカナにする 
$str = mb_ereg_replace("ﾞ|ﾟ","",$str); //半角カタカナにすると濁点や半濁点が一時になるので消去 
$str = mb_convert_kana($str,"K"); //全角カタカナにする	
	$str = str_replace(' ', '', $str);
	$str = str_replace('　', '', $str);	
	return $str;
}

function sjis_to_utf8($text){
	$text=mb_convert_encoding($text, "UTF-8",'auto');	
	return $text;
}

function for_seed_date($db,$db_stages_array,$fes_id,$db_bands_array,$code ){
	$count = 0;
	
/* //stages	
//	$fes_id = 3;//define!!
	$stage_count=1;
	$state = "select * from Stages where EventCode ='$code'";
	$result = $db->query($state );
	while( $row = $result->fetchArray() ) {
		$stage_name = $row['StageName'];
		echo "FesStage.create(fes_id: '$fes_id', fes_stage_name_ja: '$stage_name', fes_stage_name_en: '$stage_name', fes_stage_name_zh: '$stage_name', fes_stage_description_ja: '', 
		fes_stage_description_en: '', fes_stage_description_zh: '', fes_stage_main_color: '#009051', fes_stage_sub_color: '#73fcd6', fes_stage_text_color: '#ffffff',
		fes_stage_seq_no: $stage_count, deleted_at_flg: 0 )";	
		echo '<br />';
		$stage_count++;
	}	
	exit;
*/
	
	$state = "select * from bands";
	$result = $db->query($state );
	$cc=0;
	while( $row = $result->fetchArray() ) {
		$current_band_id = 0;
//		$complete_array[] = $row['band_id'];

		$name = sjis_to_utf8($row['name']);
		$image = sjis_to_utf8($row['image']);
		$desc = sjis_to_utf8($row['description1']);

		//dbに既にバンドがあるか
		$is_band_exist_in_db = false;
		foreach($db_bands_array as $b_id => $b_array ){//array('name'=> $band_name,'desc'=>$band_description,'media'=>$mc);
			if($b_array['name']===$name){
				$is_band_exist_in_db = true;
				
				if( strlen($b_array['desc'])<10 ){
					echo $b_id.$name.'<br />';
					$sql = sprintf("UPDATE bands SET band_description_ja = %s WHERE id = %s", quote_smart($desc), quote_smart($b_id));
					$result_flag = mysql_query($sql);
					echo 'result ja: '. $result_flag;
					echo'<br />';					
				} 
				
				if($b_array['media'] === 0){
					echo $b_id.$name.'<br />';				
					$sql = sprintf("INSERT INTO band_media_sources(band_id,band_media_source_material,band_media_type,band_media_source_seq_no,deleted_at_flg) VALUES (%s,%s,%s,%s,%s)", $b_id, quote_smart($image),1,1,0);
					
					$result_flag = mysql_query($sql);
					echo 'result media: '. $result_flag;
					echo'<br />';			
				}
					
			}
		}
		
/*		
		if($is_band_exist_in_db){//既にバンドがあれば
			echo $name.'<hr />';
			continue;
		
			$compare_band_id = $db_bands_array[$name];
			echo "BandGenreManagement.create(band_id: $compare_band_id, genre_id: 2 ,band_genre_management_other_txt: '',deleted_at_flg: 0)";
			echo '<br />';		
			echo "FesStageBand.create(fes_stage_id: $stage_id , band_id: $compare_band_id, 
			fes_stage_band_start_date: DateTime.strptime('$time', '%m/%d/%Y %H:%M'),
			fes_stage_band_end_date: DateTime.strptime('$end_time', '%m/%d/%Y %H:%M'),
			deleted_at_flg: 0)";
			echo '<br />';		
		}else{
			continue;		

			echo "band = Band.create(band_icon_url: '', band_email: '', band_phone_number: '', band_name_ja: '$name', band_name_en: '$name', 
			band_name_zh: '$name', band_description_ja: '', band_description_en: '', band_description_zh: '', 
			band_country_type: 1, deleted_at_flg: 0 )";
			echo '<br />';
	//		echo "BandMediaSource.create(band_id: band.id ,band_media_source_material: '$image',band_media_type: 1, band_media_source_seq_no: 1,deleted_at_flg: 0)";
	//		echo '<br />';
			echo "BandGenreManagement.create(band_id: band.id, genre_id: 2 ,band_genre_management_other_txt: '',deleted_at_flg: 0)";
			echo '<br />';		
			echo "FesStageBand.create(fes_stage_id: $stage_id , band_id: band.id, 
			fes_stage_band_start_date: DateTime.strptime('$time', '%m/%d/%Y %H:%M'),
			fes_stage_band_end_date: DateTime.strptime('$end_time', '%m/%d/%Y %H:%M'),
			deleted_at_flg: 0)";
			echo '<br />';					
		}

*/

		$count++;
	}

/*
	//バンドのみ（時間がないものも念のため）　
	$state = "select * from bands";
	$result = $db->query($state );
	$cc=0;
	while( $row = $result->fetchArray() ) {	
		if( !in_array($row['id'],$complete_array )){
			$name = $row['name'];
			$image = $row['image'];
			$member = $row['member'];
			$desc = mb_substr($row['member'].$row['description1'].$row['description2'].$row['description3'],0,5999);
		
			echo "band = Band.create(band_icon_url: '', band_email: '', band_phone_number: '', band_name_ja: '$name', band_name_en: '$name', 
			band_name_zh: '$name', band_description_ja: '$desc', band_description_en: '$desc', band_description_zh: '$desc', 
			band_country_type: 1, deleted_at_flg: 0 )";
			echo '<br />';
			echo "BandMediaSource.create(band_id: band.id ,band_media_source_material: '$image',band_media_type: 1, band_media_source_seq_no: 1,deleted_at_flg: 0)";
			echo '<br />';
			echo "BandGenreManagement.create(band_id: band.id, genre_id: 1 ,band_genre_management_other_txt: '',deleted_at_flg: 0)";
			echo '<br />';	
		}
	}
*/	

/*	
	array(16) { 
["id"]=> int(75) 
["name"]=> string(14) "the band apart"
["nameF"]=> string(30) "ザ・バンド・アパート"
["image"]=> string(63) "http://image.fujirockfestival.com/a_images/artist/bandapart.jpg"
["member"]=> string(87) "荒井岳史（Vo/Gt）、川崎亘一（Gt）、原昌和（Ba）、木暮栄一（Dr)"
["description1"]=> string(988) "98年結成。2004年にそれまで所属していた大手インディーズメーカーを離れ、...." 
["description2"]=> string(41) "http://www.youtube.com/user/HandLchannel," 
["description3"]=> string(115) "http://www.asiangothic.org,http://xc528.eccart.jp/x859/item_search/?keyword=the+band+apart&submit.x=20&submit.y=16," }
*/	
}

function quote_smart($value)
{
    // 数値以外をクオートする
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}

function artist_crawler($db){

$url_num_array = array(
'001','002','007','009','057','049','058','003','006','016','017','072','029','073','059','102','005','060','018','013','019','031','048','045','061','030','028','039','046','074','047','081','008','082','121','067','078','068','066','069','104','138','084','035','070','085','086','087','088','106','107','108','120','109','110','111','112','071','038','035','137','079','130','080','139','140','131','027','128','032','099','033','014','010','037','025','020','012','021','044','011','052','103','122','004','022','043','015','034','077','026','023','040','051','041','042','053','050','083','055','129','123','056','089','054','063','075','076','105','036','064','090','065','134','135','091','113','114','115','116','117','118','119'
);
foreach($url_num_array as $num ){
	echo $num;
	echo '<hr />';

	$url ='http://www.summersonic.com/2014/lineup/'.$num.'.html';
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1C28 Safari/419.3", 
        'header'=>"Accept-language: ja\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );
    $context = stream_context_create($opts);
    $file = file_get_contents($url, false, $context);
    //$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
    $lines =preg_split('/\r?\n/', $file);

	$name='';
    $start_f=0;    $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $desc_f=0;  $clm_f=0;
    $link="";$title="";$date="";$shop="";$access="";$img="";			
    $detail='';
    
    $artist_name="";$startdate="";
    $href_flg = 0;
    foreach($lines as $line){

		if(strpos($line,'id="artistPhoto"')){
			$start_f=1;
	    	if(preg_match('%src="([^"]*)%',$line, $match)){
	    		$img = "http://www.summersonic.com/2014/lineup/".$match[1];
	    	}			
		}
		if($start_f==0){
			continue;
		}		
		if(strpos($line,'/h3')){
			$title = strip_tags($line);
			$desc_f=1;
		}
		if($desc_f==1 && strpos($line,'p>') && $detail===""){
			$detail =  strip_tags($line);
		}
		
        if($title !== "" && $img != "" && $detail !="" ){
			$title=c($title,$db);
			$detail=c($detail,$db);
			$db->exec("INSERT INTO bands (id,name, image,  description1,link ) VALUES (\"$num\",\"$title\",\"$img\",\"$detail\",\"$url\")");
//			$db->exec("Update bands set name ='$name', nameF = '$nameF', image = '$image',  description1 = '$detail' where id = $i ");			
			break;
        }		
	}
	
	$title=c($title,$db);
	$detail=c($detail,$db);
	$db->exec("INSERT INTO bands (id,name, image,  description1,link ) VALUES (\"$num\",\"$title\",\"$img\",\"$detail\",\"$url\")");
//			$db->exec("Update bands set name ='$name', nameF = '$nameF', image = '$image',  description1 = '$detail' where id = $i ");			
	
	

}    	
	
	
}
?>
