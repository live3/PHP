<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');


$db = new SQLite3('rising2014.sqlite');

$db->exec('CREATE TABLE IF NOT EXISTS bands (id INTEGER PRIMARY KEY ,name TEXT,nameF TEXT, link TEXT,image TEXT,member TEXT, description1 Text, description2 TEXT, rate Text,description3 TEXT,starttime TEXT,endtime TEXT,day TEXT,stage_id INTEGER )');

$db->exec('CREATE TABLE IF NOT EXISTS temp_bands (id INTEGER PRIMARY KEY ,name TEXT,nameF TEXT, link TEXT,image TEXT,member TEXT, description1 Text, description2 TEXT, rate Text,description3 TEXT,starttime TEXT,endtime TEXT,day TEXT,stage_id INTEGER )');


$db->exec('CREATE TABLE IF NOT EXISTS times (id INTEGER PRIMARY KEY ,band_id INTEGER,start TEXT, end TEXT ,day TEXT, stage TEXT)');

$db->exec('CREATE TABLE IF NOT EXISTS stages (id INTEGER PRIMARY KEY ,stage_name TEXT)');


$comp_band_array=array();
$state = "select * from comp_bands";
$result = $db->query($state );
while( $row = $result->fetchArray() ) {
	$comp_band_array[$row['temp_id']]=$row['name'];
}


$state = "select * from bands";//; where id =2";
$result = $db->query($state );
$start_i = 1;
$sqlite_bands_array = array();
while( $row = $result->fetchArray() ) {
	echo $row['name'];
	echo '<hr />';
	echo $row['image'];
	echo '<hr />';
	
}
exit;


$state = "select * from temp_bands";
$result = $db->query($state );
$start_i = 1;
$sqlite_bands_array = array();


/*

while( $row = $result->fetchArray() ) {
	$result_temp = $db->query("select * from bands");
	$comp_flg = 0;
	$band_id = $row['id'];
	$name = str_replace("'", "\'", $row['name']);
	$image = $row['image'];
	$detail = str_replace("'", "\'", $row['description1']);
	$youtube = $row['description2'];

//	if(strlen($image) >3 ){continue;}else{}
	if(array_key_exists(intval($band_id) , $comp_band_array)==true ){continue;}
	while( $row_temp = $result_temp->fetchArray() ) {	
		if($row['name'] == $row_temp['name']){
			$comp_flg=1;
			$temp_id= $row_temp['id'];
//				var_dump($row);				
//				var_dump($row_temp);			
			echo $band_id.'/'.$temp_id.' '.$name.' %%% '.$row_temp['name'];			
			echo "<form action='./rising_gattai.php' method='post' target='_blank'>
				<input type='hidden' name='sqlite_temp_id' value='$band_id' />
				<input type='hidden' name='sqlite_id' value='$temp_id' />
				<input type='submit' />
				</form><hr />";
//			$db->exec("Update temp_bands set name ='$name', image = '$image',  description1 = '$detail',  description2 = '$youtube' where id = $temp_id ");
		}else{
			if( mb_strstr(conv($row['name']), mb_substr(conv($row_temp['name']),0,5) )){ 
				$comp_flg=1;
				$temp_id= $row_temp['id'];
//				var_dump($row);				
//				var_dump($row_temp);
				echo $band_id.'/'.$temp_id.' '.$name.' %%% '.$row_temp['name'];			
				echo "<form action='./rising_gattai.php' method='post' target='_blank'>
				<input type='hidden' name='sqlite_temp_id' value='$band_id' />
				<input type='hidden' name='sqlite_id' value='$temp_id' />
				<input type='submit' />
				</form><hr />";				
//				$db->exec("Update temp_bands set name ='$name', image = '$image',  description1 = '$detail',  description2 = '$youtube' where id = $temp_id ");
			}			
		}

	}
	if($comp_flg==0){
		echo $row['id'];
		echo $row['name'];
		var_dump($row);
		echo '<hr />';		
	}

}

exit;

function conv($str){
	$str =str_replace(' ', '', $str);
	$str =str_replace('　', '', $str);
	$str =str_replace('"', '', $str);
	$str =str_replace("'", '', $str);
	$str = mb_convert_kana($str,'KHV');
	return $str;
}
*/


/*
echo '<table><tr><th></th><th></th><th></th><th></th></tr>';
while( $row = $result->fetchArray() ) {
	echo '<tr>';
	echo '<td >'.$row['name'].'</td>';
//	echo '<td>'.$row['image'].'</td>';
//	echo '<td>'.$row['description2'].'</td>';
	echo '<td  style="border:solid 1px #000;">'.$row['description1'].'</td>';
//	echo '<td>'.$row['link'].'</td>';				
	
	echo '</tr>';
}
echo '</table>';

exit;
*/

//seed発行
/*
scraping($db);
exit;

*/

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
$db_bands_array = array();
$mysql_res = mysql_query("select * from bands");
while ($row = mysql_fetch_assoc($mysql_res)) {
	$band_id = $row['id'];
	$band_name = $row['band_name_ja'];
	$db_bands_array[$band_name] = $band_id;
}	


for_seed_date($db,$db_bands_array);
exit;


//stage
$st_ar = array(
'SUN STAGE','EARTH TENT','RED STAR FIELD','RED STAR CAFE','BOHEMIAN GARDEN','RAINBOW SHANGRI-LA','def garage'
);
foreach($st_ar as $st_name){
	$db->exec("INSERT INTO stages (stage_name) VALUES (\"$st_name\")");	
}
exit;

//$db->exec('delete from temp_bands;');
$band_array =array(	
	array(
//SUN STAGE',
//15',
	'15:00～16:00レキシ',
	'17:00～18:00UNICORN',
	'19:00～20:00ケツメイシ',
	'21:00～22:00電気グルーヴ',
	),
	array(
//16',
	'11:30～12:00爆弾ジョ二ー',
	'12:30～13:30エレファントカシマシ',
	'14:30～15:30氣志團',
	'16:30～17:309mm Parabellum Bullet',
	'18:30～19:30東京スカパラダイスオーケストラ',
	'21:00～22:00ONE OK ROCK',
	'23:00～24:00Dragon Ash',
	'25:20～26:00サカナクション',
	'27:30～28:30フィッシュマンズ',
	),
	array(
//EARTH TENT',
//15',
	'15:30～16:20Crossfaith',
	'17:10～18:00AA=',
	'18:40～19:30木村カエラ',
	'20:10～21:00フラワー力ンパニーズ',
	'21:40～22:30The BONEZ',
	'23:40～24:50FRIDAY NIGHT SESSION ～ ROCK\'N\'ROOL CIRCUS tv（for CAMPERS） NEW OKAMOTO’S are オ力モトショウ（Vox）／オ力モトコウキ（Guitar） ハマ・オ力モト（Bass）／オ力モトレイラ（Drums）＆ 斎藤有太（Key） 吾妻光良／奥田民生／仲井戸”CHABO”麗市 ／中村達也 TAXMAN（THE BAWDIES）／延原達治（THE PRIVATES） 甲本ヒロト（ザ・クロマニヨンズ）／真島昌利（ザ・クロマニヨンズ） チバユウスケ（The Birthday） ／クハラカズユキ（The Birthday） 鮎川誠（シーナ＆ロケッツ）／SHEENA（シーナ＆ロケッツ） （順不同）',
	),
	array(
//16',
	'13:10～14:00ゲスの極み乙女。',
	'14:40～15:30上江洌.清作 ＆ The BK Sounds!!',
	'16:10～17:00GRAPEVINE',
	'17:40～18:30OKAMOTO\'S  NEW',
	'19:10～20:00the pillows',
	'22:00～22:50キュウソネコカミ',
	'23:40～24:30NAMBA69',
	'25:20～26:10ROTTENGRAFFTY',
	'27:00～27:50アルカラ',
	),
	array(
//RED STAR FIELD',
//15',
	'15:00～15:50青葉市子 with 小山田圭吾 ＆ U-zhaan',
	'16:40～17:30カーネーション LOVES 森高千里',
	'18:20～19:10ストレイテナー',
	'20:00～20:50a flood of circle',
	'21:40～22:30The Birthday',
	'23:20～24:10SOIL&”PIMP” SESSIONS',
	),
	array(
//16',
	'12:30～13:50アン・サリー',
	'14:10～15:00SPECIAL OTHERS',
	'15:50～16:40サンフジンズ',
	'17:30～18:20BEGIN',
	'19:10～20:00憂歌団',
	'21:30～22:30U A',
	'23:50～25:00山下達郎',
	'26:10～27:10PSYCHEDELIC FOUNDATION  NEW 演奏:GOMA、SPEEDER-X（中村達也＋KenKen） and more.. 音響:内田直之',
	),
	array(
//RED STAR CAFE',
//15',
	'16:00～16:30鈴木亜紀 with 鈴木裕 ＜スズキ×スズキ＞',
	'17:30～18:00武藤昭平 with ウエノコウジ',
	'19:10～19:40DJみそしるとMCごはん',
	'24:30～25:00TAKUMA（10-FEET）',
	),
	array(
//16',
	'12:00～12:30笹木ヘンドリクス',
	'13:30～14:00TarO&JirO',
	'15:00～15:30藤原ヒロシ × INO hidefumi × 八マ・okamoto・ショウ',
	'16:40～17:10大江健人',
	'18:20～18:50T字路s',
	),
	array(
//BOHEMIAN GARDEN',
//15',
	'15:30～16:10D.W.ニコルズ',
	'16:40～17:20畠山美由紀',
	'17:50～18:30くもゆき（おおはた雄一と福岡晃子 from チャットモンチー）',
	'19:00～19:40片想い',
	'20:20～21:00Caravan',
	'21:40～22:30Leyona',
	'23:10～24:10シアターブルック',
	),
	array(
//16',
	'12:20～12:50エマーソン北村',
	'13:20～14:00ABEDON バンドメンバー：ABEDON、奥田民生、八熊慎一(SPARKS GO GO)、木内健',
	'14:40～15:10吉田省念＋四家卯大＋植田良太',
	'15:50～16:30Gotch',
	'17:00～17:40LIFE IS GROOVE（KenKen×ムッシュかまやつ×山岸竜之介）feat.金子マリ',
	'18:10～18:50うつみようこ ＆ YOKOLOCO BAND',
	'19:20～20:00フラワー力ンパニーズ＜アコースティックセット＞  NEW',
	'21:30～22:50MY LIFE IS MY MESSAGE【田中和将（GRAPEVINE）／Caravan／山口洋（HEATWAVE）／仲井戸”CHABO”麗市 】',
	'23:30～25:50IN THE MIDNIGHT HOURS【阿部芙蓉美／佐藤タイジ（シアターブルック）／曽我部恵一／山内総一郎（フジファブリック）】',
	),
	array(
//RAINBOW SHANGRI-LA',
//15',
	'15:50～16:40Koji Nakamura',
	'17:30～18:20スチャダラパー',
	'19:10～20:00→Pia-no-jaC←×DAISHI DANCE',
	'20:50～21:40salyu × salyu',
	'23:00～24:00BOOM BOOM SATELLITES（for CAMPERS）',
	),
	array(
//16',
	'13:30～14:00電撃ネットワーク',
	'15:00～15:50Predawn',
	'16:40～17:30avengers in sci-ft',
	'18:20～19:10sleepy.ab',
	'21:00～22:00OOIOO',
	'23:00～28:30TONE PARK DJs:TAKKYU ISHINO／YOSHINORI SUNAHARA／SUGIURUMN LIVE:A.Mochi／agraph VJ:DEVICEGIRLS',
	),
	array(
//def garage',
//15',
	'14:00～14:30オトノエ',
	'15:00～15:30SHISHAMO',
	'16:10～16:40パスピエ',
	'17:20～17:50SAKANAMON',
	'18:30～19:00KNOCK OUT MONKEY',
	'19:30～20:10シーナ＆ロケッツ',
	'20:50～21:20THE PRIVATES',
	'22:00～22:30ヒトリエ',
	'23:00～23:30Czecho No Republic',
	),
	array(
//16',
	'11:30～12:00fula',
	'12:30～13:00ザ・チャレンジ',
	'13:40～14:10黒木渚',
	'14:50～15:20ドラマチックアラスカ',
	'16:00～16:30テスラは泣かない。',
	'17:10～17:40空想委員会',
	'18:20～18:50電大',
	'19:20～20:00HUSKING BEE',
	'21:00～21:40eastern youth',
	'22:20～22:50フレデリック',
	'23:30～24:00FOLKS',
	'24:40～25:10The fin.',
	'25:50～25:20OLD',
	'27:00～27:30 溺れたエビの検死報告書',
	)
);
$count= 1;
$band_id = 1;
$day= 1;
$stage = 0;
foreach($band_array as $band_source){
//var_dump($band_source);

	$day = ($count-1)%2+1;
	if(($count-1)%2 == 0){
		$stage++;
	}
	
	foreach($band_source as $band){	
		$start = '';$end = '';$name = '';$image ='';
		$start =  substr($band, 0,5);
		$end = substr($band, 8,5);
		if(strpos($band, '%')){
			$temp = mb_substr($band, 11);
			$temp_arr = explode('%', $temp);
			$name = $temp_arr[0];
			$image = $temp_arr[1];		
		}else{
			$name =  mb_substr($band, 11);
		}
		$name = str_replace('"', '”', $name);
		
		echo $name.'<br />';
		echo $start.'<br />';
		echo $end.'<br />';
		echo 'd '.$day.'<br />';
		echo 'st '.$stage.'<br />';
//		echo $image.'<br />';
		
		$db->exec("INSERT INTO temp_bands (id, name,image,  starttime, endtime, stage_id, day  ) VALUES (\"$band_id\",\"$name\",\"$image\",\"$start\",\"$end\",\"$stage\",\"$day\")");		
		$band_id++;
	}
	$count++;	
}


exit;
//$db->exec('delete from bands');

//$db->exec('delete from times');


function c($text, $db){
//	$text=sqlite_escape_string($text);
//	$text=mb_convert_encoding($text, "UTF-8",'auto');
//	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
	$text=str_replace("'", '’', $text);
	return $text;
}
function sjis_to_utf8($text){
	$text=mb_convert_encoding($text, "UTF-8",'auto');	
	return $text;
}



function for_seed_date($db,$db_bands_array){
	$count = 0;
	
/*
	$fes_id = 6;//define!!
	$stage_count=1;
	$state = "select * from stages";
	$result = $db->query($state );
	while( $row = $result->fetchArray() ) {
		$stage_name = $row['stage_name'];
		echo "FesStage.create(fes_id: '$fes_id', fes_stage_name_ja: '$stage_name', fes_stage_name_en: '$stage_name', fes_stage_name_zh: '$stage_name', fes_stage_description_ja: '', 
		fes_stage_description_en: '', fes_stage_description_zh: '', fes_stage_main_color: '#009051', fes_stage_sub_color: '#73fcd6', fes_stage_text_color: '#ffffff',
		fes_stage_seq_no: $stage_count, deleted_at_flg: 0 )";	
		echo '<br />';
		$stage_count++;
	}	
	exit;

*/
	$state = "select * from comp_bands";// b inner join stages s on b.stage_id = s.id";
	$result = $db->query($state );
	while( $row = $result->fetchArray() ) {

//		$complete_array[] = $row['band_id'];

		$name = str_replace("'", "\'", sjis_to_utf8($row['name']) ) ;
		$image = trim(sjis_to_utf8($row['image']));
//		$desc = mb_substr($row['member'].$row['description1'].$row['description2'].$row['description3'],0,5999);
		$desc = trim($row['description1']);
		$youtube_str =$row['description2'];
		$yt_ar = explode( ',',$youtube_str);

		$stage_id = sjis_to_utf8($row['stage_id'])+35;	
		$day = sjis_to_utf8($row['day']);
		$day_st = "08/15/2014";
		if($day == 2){
			$day_st = "08/16/2014";
		}
		

		$time = $day_st.' '.sjis_to_utf8($row['starttime']);
		if(intval(substr(sjis_to_utf8($row['starttime']),0,2)) >= 24){//24時以降
			$time = date('m/d/Y', strtotime($day_st.'+1 day') ).' 0'.(intval(substr(sjis_to_utf8($row['starttime']),0,2)) - 24).substr(sjis_to_utf8($row['starttime']),2,3);
		}

		$end_time = $day_st.' '.sjis_to_utf8($row['endtime']);
		if(intval(substr(sjis_to_utf8($row['endtime']),0,2)) >= 24){//24時以降
			$end_time = date('m/d/Y', strtotime($day_st.'+1 day') ).' 0'.(intval(substr(sjis_to_utf8($row['endtime']),0,2)) - 24).substr(sjis_to_utf8($row['endtime']),2,3);
		}			
	
		//dbに既にバンドがあるか
		$is_band_exist_in_db = false;
		foreach($db_bands_array as $b_name => $b_id ){
			if($b_name===$name){
				$is_band_exist_in_db = true;	
			}
		}
		if($is_band_exist_in_db){//既にバンドがあれば
//			echo $name.'<hr />';
//			continue;

			$compare_band_id = $db_bands_array[$name];

			//	count media
			$mysql_res = mysql_query("select * from band_media_sources where band_id =$compare_band_id");
			$db_img_count = array();
			$db_yt_count = array();
			while ($row = mysql_fetch_assoc($mysql_res)) {
				if($row['band_media_type'] == 1){
					$db_img_count[] = $row['band_media_source_material'];
				}else if($row['band_media_type'] == 2){
					$db_yt_count[] = $row['band_media_source_material'];
				}
			}

			echo "BandGenreManagement.create(band_id: $compare_band_id, genre_id: 2 ,band_genre_management_other_txt: '',deleted_at_flg: 0)";
			echo '<br />';		
			if( count($db_img_count) <= 5 &&  !in_array($image, $db_img_count) ){
				echo "BandMediaSource.create(band_id: $compare_band_id ,band_media_source_material:'$image',band_media_type: 1, band_media_source_seq_no: 6,deleted_at_flg: 0)";						
				echo '<br />';						
			}
			$yt_count=1;
				
			foreach($yt_ar as $yt){
				if(strlen($yt) > 15 || strlen($yt) < 5){continue;}  
				if(count($db_yt_count) <= 5 &&  !in_array($yt, $db_yt_count) ){
					echo "BandMediaSource.create(band_id: $compare_band_id ,band_media_source_material: '$yt',band_media_type: 2, band_media_source_seq_no: $yt_count,deleted_at_flg: 0)";
					echo '<br />';				
				}
			$yt_count++;
			}			
			echo "FesStageBand.create(fes_stage_id: $stage_id , band_id: $compare_band_id, 
			fes_stage_band_start_date: DateTime.strptime('$time', '%m/%d/%Y %H:%M'),
			fes_stage_band_end_date: DateTime.strptime('$end_time', '%m/%d/%Y %H:%M'),
			deleted_at_flg: 0)";
			echo '<br />';		
		}else{	
			echo "band = Band.create(band_icon_url: '', band_email: '', band_phone_number: '', band_name_ja: '$name', band_name_en: '$name', 
			band_name_zh: '$name', band_description_ja: '$desc', band_description_en: '', band_description_zh: '', 
			band_country_type: 1, deleted_at_flg: 0 )";
			echo '<br />';
			echo "BandMediaSource.create(band_id: band.id ,band_media_source_material: '$image',band_media_type: 1, band_media_source_seq_no: 1,deleted_at_flg: 0)";
			echo '<br />';
			$yt_count=1;
			foreach($yt_ar as $yt){
				if(strlen($yt) > 15 || strlen($yt) < 5){continue;}  
				echo "BandMediaSource.create(band_id: band.id ,band_media_source_material: '$yt',band_media_type: 2, band_media_source_seq_no: $yt_count,deleted_at_flg: 0)";
			echo '<br />';
			$yt_count++;
			}
			echo "BandGenreManagement.create(band_id: band.id, genre_id: 2 ,band_genre_management_other_txt: '',deleted_at_flg: 0)";
			echo '<br />';		
			echo "FesStageBand.create(fes_stage_id: $stage_id , band_id: band.id, 
			fes_stage_band_start_date: DateTime.strptime('$time', '%m/%d/%Y %H:%M'),
			fes_stage_band_end_date: DateTime.strptime('$end_time', '%m/%d/%Y %H:%M'),
			deleted_at_flg: 0)";
			echo '<br />';			
		}


		$count++;
	}

}

function scraping($db){
	$url_array=array("profile/012ki.html","profile/041dg.html","profile/036uc.html","profile/052rs.html","profile/006ek.html","profile/058kd.html","profile/071pb.html","profile/040sa.html","profile/069to.html","profile/062da.html","profile/064bj.html","profile/077fm.html","profile/081or.html","profile/037aa.html","profile/045kk.html","profile/034cf.html","profile/028fc.html","profile/076tb.html","profile/053fn.html","profile/004ak.html","profile/055ut.html","profile/099ot.html","profile/007kn.html","profile/010gv.html","profile/011go.html","profile/063nm.html","profile/038tp.html","profile/035rr.html","profile/001ao.html","profile/002fc.html","profile/070cm.html","profile/018st.html","profile/021sp.html","profile/088tb.html","profile/090ss.html","profile/098tk.html","profile/022dm.html","profile/051mu.html","profile/005as.html","profile/067ua.html","profile/100pf.html","profile/059sz.html","profile/019so.html","profile/032bg.html","profile/082yt.html","profile/030yd.html","profile/101ok.html","profile/094sh.html","profile/095tj.html","profile/043ts.html","profile/091fh.html","profile/044ko.html","profile/046cv.html","profile/009ky.html","profile/073tb.html","profile/049dw.html","profile/050hm.html","profile/078ln.html","profile/054ab.html","profile/083im_01.html","profile/083im_02.html","profile/083im_03.html","profile/083im_04.html","profile/084uy.html","profile/039ek.html","profile/068go.html","profile/028fc_01.html","profile/085mm.html","profile/080ys.html","profile/096km.html","profile/047kn.html","profile/014ss.html","profile/017sp.html","profile/025pd.html","profile/029bs.html","profile/003as.html","profile/056oi.html","profile/020sa.html","profile/024dn.html","profile/079pr.html","profile/031tp.html","profile/031tp_01.html","profile/031tp_02.html","profile/031tp_03.html","profile/031tp_04.html","profile/031tp_05.html","profile/031tp_06.html","profile/013sm.html","profile/015sr.html","profile/016sm.html","profile/048cr.html","profile/089ko.html","profile/074pp.html","profile/075he.html","profile/042pr.html","profile/092on.html","profile/033ey.html","profile/057od.html","profile/086ok.html","profile/008ki.html","profile/072kn.html","profile/060ch.html","profile/023tn.html","profile/061dd.html","profile/087da.html","profile/065hb.html","profile/026tf.html","profile/027fk.html","profile/066hd.html","profile/093fl.html");
	
	$ccc = 1;
	foreach($url_array as $url_part){
		$host = 'http://rsr.wess.co.jp/';
		$url = 'http://rsr.wess.co.jp/2014/artists/lineup/'.$url_part;//無料 ライブ
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
	
	    $start_f=0;    $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $access_f=0;  $clm_f=0;
	    $link="";$title="";$date="";$shop="";$access="";$img="";			
	    $detail='';$detail_f =0;$youtube_f=0;$youtube='';
	    $artist_name="";$startdate="";
	    $href_flg = 0;
	    foreach($lines as $line){
	    	if(strpos($line,"article ")){
		    	$start_f=1;
	    	}
	    	if($start_f==0){continue;}
	    	if(strpos($line,'class="photo"')){
				if(preg_match('%src="([^"]*)%',$line, $match)){	    	
					$img='http://rsr.wess.co.jp/2014/artists/lineup/profile/'.$match[1];
		    	}
				if(preg_match('%alt="([^"]*)%',$line, $match)){	    	
					$title=$match[1];
		    	}
	    	}
	    	
	    	if(strpos($line,'/div>')){	    	
	    		$detail_f=2;
	    	}	    	
	    	if($detail_f==1){
		    	$detail = $detail.strip_tags($line); 
	    	}
	    	if(strpos($line,'<span>PROFILE</span>')){	    	
	    		$detail_f=1;
	    	}

	    	if(strpos($line,'/article>') || strpos($line,'style="text-align:center')){
	    		$youtube_f=2;
	    	}	    	
	    	if($youtube_f==1){
				if(preg_match('%href="([^"]*)%',$line, $match)){	    	
					$com= (strlen($youtube) == 0 )?'':',';
					$youtube=$youtube.$com.str_replace('https://www.youtube.com/watch?v=', '',str_replace('http://youtu.be/', '', $match[1]) );
		    	}
	    	}
	    	if(strpos($line,'<span>YOU TUBE</span>')){	    	
	    		$youtube_f=1;
	    	}
	    	
	    	
	    
	    }
/*
	    echo 'name'.$title.'<br />';
	    echo 'img'.$img.'<br />';
	    echo 'detail'.$detail.'<br />';
	    echo 'yt'.$youtube.'<br />';
*/
	    
	    $url_id = $ccc;
//	    echo intval($url_id);
    
	    $db->exec("INSERT INTO bands (id,name, image,  description1,description2,link ) VALUES (\"$url_id\",\"$title\",\"$img\",\"$detail\",\"$youtube\",\"$url\")");
//	    exit;
		$ccc++;	
	}		
}

?>
