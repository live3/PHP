<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

//songkcik for scraping
$db = new SQLite3('fr2014.sqlite');


/*
class NewDB extends SQLite3{
    function __construct() {
        $this->open('sgkc.sqlite');
    }
}
$db = new NewDB();
*/

//seed発行

///*
for_seed_date($db);
exit;
//*/

//seed発行 end


//scrape!!!!!!!!!!!!!!!!!!!!

$db->exec('CREATE TABLE IF NOT EXISTS bands (id INTEGER PRIMARY KEY ,name TEXT,nameF TEXT, link TEXT,image TEXT,member TEXT, description1 Text, description2 TEXT, rate Text,description3 TEXT )');

$db->exec('CREATE TABLE IF NOT EXISTS times (id INTEGER PRIMARY KEY ,band_id INTEGER,start TEXT, end TEXT ,day TEXT, stage TEXT)');

//$db->exec('delete from bands');

$db->exec('delete from times');

///*
///////////Time Table Start/////////////////////

$timetable_url_s = 'http://www.fujirockfestival.com/artist/timetable/tt';
for($i = 25; $i<=27;$i++ ){
	$timetable_url = $timetable_url_s.$i.'.asp';
	$url =$timetable_url;
	echo $url;
	get_tt_date($db,$url,$i);
}


function get_tt_date($db,$url,$day_url){

	$cnt=0;
	$pages = array();
	
	if (!fopen($url,'r')) {
		break;		
	}
	$opts = array(
	  'http'=>array(
	    'method'=>"GET",
	    'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
	    'header'=>"Accept-language: ja\r\n" .
	              "Cookie: foo=bar\r\n"
	  )
	);
	$context = stream_context_create($opts);
	$file = file_get_contents($url, false, $context);//URL,include_pathの使用、コンテキスト、読み込み開始のオフセット値、読み込み最大値。
	$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
	$lines =preg_split('/\r?\n/', $file);
	
	$start_f=0;  $cell_f = 0;
	
	//  $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $access_f=0;  $clm_f=0;
	//$link="";$title="";$date="";$shop="";$access="";$img="";
	
	$artist_name="";$startdate="";
	$href_flg = 0;
	
	foreach($lines as $line){
		
		if($cell_f==0){
			$time_span=0;
			$stage='';
			$start_time='';
			$artist_id =0;
		}
	
		if(strpos($line,'id="scheduleArea')){
			$start_f=1;
		}
		if($start_f == 1 ){
	//		echo $line;
	//		echo '<hr class="awesome" />';
			if(preg_match('%<td rowspan="(\d+)" class="([^"]*)"%', $line, $match)){
				$time_span=$match[1]; 
				$stage=$match[2];			
				$cell_f=1;
			}
			if($cell_f == 1){
				if(preg_match('%(\d\d:\d\d)%', $line, $match)){
					$start_time=$match[1];
					if(preg_match("%openArtist\('(\d+)'\)%", $line, $match)){
						$artist_id=$match[1];
						
	//					echo $time_span.' | '.$stage.' | '.$start_time.' | '.$artist_id;
	//					echo '<hr />';
						$st_date = '07/'.$day_url.'/2014 '.$start_time;
						$time_span_min = $time_span*10;
	
						$ed_date = date('m/d/Y H:i', strtotime("$st_date +$time_span_min min"));
	
						$day_insert = 1;
						if($day_url == 26){
							$day_insert = 2;
						}else if($day_url == 27){
							$day_insert = 3;
						}
						echo "INSERT INTO times (band_id, start, end, day, stage ) VALUES (\"$artist_id\",\"$st_date\",\"$ed_date\",\"$day_insert\" ,\"$stage\" )";
						echo '<hr />';
						
						$db->exec("INSERT INTO times (band_id, start, end, day, stage ) VALUES (\"$artist_id\",\"$st_date\",\"$ed_date\",\"$day_insert\" ,\"$stage\" )");					
						
					}
					$cell_f=0;
				}
			}
		}
	
//<td rowspan="6" class="green">
//<hr class="awesome">11:00〜<br><a href="javascript:openArtist('4017')">ROUTE 17 Rock'n'Roll ORCHESTRA</a>
//<hr class="awesome"></td>	
		

	}

}

///////////Time Table End/////////////////////
exit;
//*/

///////////Artist Start/////////////////////
$artist_url = 'http://www.fujirockfestival.com/artist/artistdata.asp?id=';
//$host = 'http://www.fujirockfestival.com';
$artist_all_url = 'http://www.fujirockfestival.com/artist/';

$cnt=0;
$pages = array();

$url =$artist_all_url;
if (!fopen($url,'r')) {
	break;		
}

// ストリーム(オプション)を作成します
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
    'header'=>"Accept-language: ja\r\n" .
              "Cookie: foo=bar\r\n"
  )
);
$context = stream_context_create($opts);
// 上で設定した HTTP ヘッダを使用してファイルをオープンします
$file = file_get_contents($url, false, $context);//URL,include_pathの使用、コンテキスト、読み込み開始のオフセット値、読み込み最大値。
$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
//行毎に分割。配列内の各要素が1行。
$lines =preg_split('/\r?\n/', $file);

$start_f=0;    $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $access_f=0;  $clm_f=0;
$link="";$title="";$date="";$shop="";$access="";$img="";

$artist_name="";$startdate="";
$href_flg = 0;

foreach($lines as $line){

	if(strpos($line,'javascript:openArtist')){    	
	//    	echo $line;
		if(preg_match("%openArtist\('(\d{1,5})%", $line, $match)){
			//var_dump($match);
			$band_id = $match[1];
			$db->exec("INSERT INTO bands (id ) VALUES (\"$band_id\")");	
			
			$result_array =  artistPage($artist_url,$band_id);
//			var_dump($result_array);
			
			$name = c($result_array['name'],$db);
			$nameF = c($result_array['nameF'],$db);
			$image = c($result_array['image'],$db);
			$member = c($result_array['member'],$db);
			$description1 = c($result_array['description1'],$db);
			$description2 = c($result_array['description2'],$db);
			$description3 = c($result_array['description3'],$db);	
						
//			exit;		
			
			$db->exec("Update bands set name ='$name', nameF = '$nameF', image = '$image', member = '$member', description1 = '$description1', description2 = '$description2', description3 = '$description3' where id = $band_id ");			

		}	    	
	}
/*    
    	if(strpos($line,'class="artists summary')){
			$href_flg = 1;
        }     
        if($href_flg == 1 && preg_match('%href="([^"]*)%',$line, $match)){
			$link = $match[1];
			$href_flg = 0;	   
        }
    	if(strpos($line,'strong itemprop="name"')){
			$artist_name = strip_tags($line);

			$result_array =array();
			$result_array =detailPage($host,$link);
			
			$link = $host.$link;
			$startdate = c($result_array['startdate'],$db);
			$image = c($result_array['image'],$db);
			$housename = c($result_array['housename'],$db);
			$address = c($result_array['address'],$db);
			$lineup = c($result_array['lineup'],$db);
			$detail = c($result_array['detail'],$db);
			$songkick_id　=0;
			if(preg_match('%\d{6,9}%', $link, $match)){
				$songkick_id = $match[0];
			}			
			
			$update_flg=0;
			$state = "SELECT * FROM live where id = $songkick_id";
			$result = $db->query($state );
			while( $row = $result->fetchArray() ) {
				$update_flg=1;
				break;
				echo "update!!".'<br />';
			}
			
			if($update_flg == 1){	
			echo "Update live set link ='$link', date = '$startdate', image = '$image', shop = '$housename', address = '$address', artist = '$lineup', description = '$detail' where id = $songkick_id";
				$db->exec("Update live set link ='$link', date = '$startdate', image = '$image', shop = '$housename', address = '$address', artist = '$lineup', description = '$detail',title = '$artist_name' where id = $songkick_id");				
			}else{
				$db->exec("INSERT INTO live (id, link, date, image, shop, address,artist,description,title ) VALUES (\"$songkick_id\",\"$link\",\"$startdate\",\"$image\" ,\"$housename\" ,\"$address\",\"$lineup\" ,\"$detail\" ,\"$artist_name\" )");	
				echo "insert!!".'<br />';											
			}

			$link = '';
			$startdate = '';
			$image = '';
			$housename = '';
			$address = '';
			$lineup = '';
			$detail = '';
echo '</hr>';
*/
}//foreach lines line end
///////////Artist End/////////////////////     	

/*
//list
$title = c($title,$db);
$link = c($link,$db);
$date = c($date,$db);
$img  = c($img ,$db);
$shop  = c($shop ,$db);
$frigna = c($frigna,$db);
$city  = c($city ,$db);
$genre  = c($genre ,$db);
$time  = c($time ,$db);
$price  = c($price ,$db);
$artist  = c($artist ,$db);
$rate = c($rate,$db);
$read = c($read,$db);
			//$db->exec("INSERT INTO live (title,link ,date ) VALUES(\"$title\",\"$link\",\"$date\")");							
			$db->exec("INSERT INTO live (title,link ,date ,image ,shop ,frigana,city,genre,time,price,artist,rate,etc ) VALUES(\"$title\",\"$link\",\"$date\",\"$img\" ,\"$shop\" ,\"$frigna\",\"$city\" ,\"$genre\" ,\"$time\" ,\"$price\" ,\"$artist\" ,\"$rate\",\"$read\"  )");	
			//$db->exec("INSERT INTO live (title ) VALUES(\"$title\")");	
//$db->exec('CREATE TABLE IF NOT EXISTS live (id INTEGER PRIMARY KEY ,title TEXT,firigna TEXT, link TEXT,image TEXT,genre TEXT, price Text, artist TEXT, rate Text,date TEXT,time TEXT, city TEXT,shop TEXT,access TEXT,etc TEXT)');				
*/




//$db->exec('delete from live where city != "        TOKYO, JAPAN"');

function artistPage($artist_url,$band_id){
	$url = $artist_url.$band_id;
	$opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
        'header'=>"Accept-language: ja\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );
    $context = stream_context_create($opts);
    $file = file_get_contents($url, false, $context);//URL,include_pathの使用、コンテキスト、読み込み開始のオフセット値、読み込み最大値。
    $file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
    $lines =preg_split('/\r?\n/', $file);
    
    $name='';
    $img='';
    $name_kana= '';
    $member = '';
    $detail='';    
    $media='';
    $link = '';
    
	$member_flg=0;
    $detail_flg='';   
	$media_flg=0;	
	$links_flg=0;	
	
	$result_array =array();;

    foreach($lines as $line){
    	if(strpos( $line,'</h1>')){
    		$name = strip_tags($line);
    	}
    	if(strpos( $line,'id="artistPhoto"')){
			if(preg_match('%src="([^"]*)%', $line, $match)){
				$img = $match[1];
			}
    	}
    	if(strpos( $line,'<h2>')){
    		$name_kana = strip_tags($line);
    	}

		//member
		if($member_flg==1){
			if(strpos($line,'<h3>')){
				$member_flg=2;
			}else{
				$member=$member.strip_tags($line);
			}
		}
    	if($member_flg==0 && strpos($line,'alt="Member"')){
	    	$member_flg=1;
    	}


		//detail
		if($detail_flg==1){
			if(strpos($line,'<h3>')){
				$detail_flg=2;
			}else{
				$detail=$detail.strip_tags($line);
			}
		}
    	if($detail_flg==0 && strpos($line,'alt="Profile"')){
	    	$detail_flg=1;
    	}

		//media
		if($media_flg==1){
			if(strpos($line,'<h3>')){
				$media_flg=2;
			}else{
				if(preg_match('%href="([^"]*)%', $line, $match)){
					$media = $media.$match[1].',';
				}				
			}
		}
    	if($media_flg==0 && strpos($line,'alt="Audio/Video"')){
	    	$media_flg=1;
    	}

		//links
		if($links_flg==1){
			if(strpos($line,'<h3>')){
				$links_flg=2;
			}else{
				if(preg_match('%href="([^"]*)%', $line, $match)){
					$link = $link.$match[1].',';
				}				
			}
		}
    	if($links_flg==0 && strpos($line,'alt="Links"')){
	    	$links_flg=1;
    	}    	
    	
/*    
		if( strpos( $line,'itemprop="startDate"') ){
			if(preg_match('%datetime="([^"]*)%', $line, $match)){
				$startdate = $match[1];
			}
		}

		if( strpos( $line,'class="profile-picture') ){
			if(preg_match('%src="([^"]*)%', $line, $match)){
				$img = str_replace("/medium_avatar", '/col6', $match[1]); 
			}
		}		

		if( strpos( $line,'href="/venues/') && strlen($housename)==0 ){
			$housename = trim(strip_tags($line));
		}
					
		if( $address_flg==1 && strpos($line,'</p>')){
			$address_flg=2;
		}
		if($address_flg==1){
			$address = $address.$line;
		}
		if( strpos( $line,'class="adr"') ){
			$address_flg=1;
			$address = "";
		}		
		
		if( $lineup_flg==1 && strpos($line,'</ul>')){
			$lineup_flg=2;
		}		
		if( $lineup_flg==0 && strpos( $line,'<h4>Line-up</h4>') ){
			$lineup_flg=1;
			$lineup = '';
		}		
		if($lineup_flg==1 && strpos($line,'<a ')){
			if(strlen($lineup) != 0 ){
				$lineup = $lineup.',';			
			}
			$lineup = $lineup.'['.trim($line).']';
		}

		if( $detail_flg ==1 && strpos($line,'footer') ){ 
			$detail_flg=2;
		}		
		if( $detail_flg ==1){
			$detail = $detail.$line;
		}
		if( $detail_flg==0 && strpos( $line,'<h2>Additional details') ){
			$detail_flg=1;
			$detail = '';
		}		

	}	
	
*/		
	
	}
	$result_array['name'] = trim($name);
	$result_array['nameF'] = trim($name_kana);
	$result_array['image'] = $img;
	$result_array['member'] = trim($member);
	$result_array['description1'] = trim($detail);
	$result_array['description2'] = $media;	
	$result_array['description3'] = $link;	

	return $result_array;		
	
}
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



function for_seed_date($db){
	$count = 0;
	
	$complete_array=array();
	
	$state = "select * from bands b inner join times t on b.id = t.band_id";
	$result = $db->query($state );
	while( $row = $result->fetchArray() ) {

		$complete_array[] = $row['band_id'];

		$name = $row['name'];
		$image = $row['image'];
		$member = $row['member'];
//		$desc = mb_substr($row['member'].'\n'.$row['description1'].'\n'.$row['description2'].'\n'.$row['description3'],0,170)."...";
		$desc = mb_substr($row['member'].$row['description1'].$row['description2'].$row['description3'],0,5999);
		
		$stage_id = 1;
		switch ($row['stage']) {
		    case 'white':$stage_id = 2;break;
		    case 'red':$stage_id = 3;break;
		    case 'heaven':$stage_id = 4;break;
		    case 'orange':$stage_id = 5;break;
		    case 'avalon':$stage_id = 6;break;
		    case 'palace':$stage_id = 7;break;
//		    case 'palace':$stage_id = 8;break;
//		    case 'palace':$stage_id = 9;break;
		    case 'naeba':$stage_id = 10;break;
		    case 'daydream':$stage_id = 11;break;
		    case 'wood':$stage_id = 12;break;		    		    		    		    
		    case 'pyramid':$stage_id = 13;break;
		    case 'paris':$stage_id = 14;break;   		    		    		    
		}
	
		$day = $row['day'];
		
		$time = $row['start'];
		$end_time = $row['end'];
	
		echo "band = Band.create(band_icon_url: '', band_email: '', band_phone_number: '', band_name_ja: '$name', band_name_en: '$name', 
		band_name_zh: '$name', band_description_ja: '$desc', band_description_en: '$desc', band_description_zh: '$desc', 
		band_country_type: 1, deleted_at_flg: 0 )";
		echo '<br />';
		echo "BandMediaSource.create(band_id: band.id ,band_media_source_material: '$image',band_media_type: 1, band_media_source_seq_no: 1,deleted_at_flg: 0)";
		echo '<br />';
		echo "BandGenreManagement.create(band_id: band.id, genre_id: 1 ,band_genre_management_other_txt: '',deleted_at_flg: 0)";
		echo '<br />';		
		echo "FesStageBand.create(fes_stage_id: $stage_id , band_id: band.id, 
		fes_stage_band_start_date: DateTime.strptime('$time', '%m/%d/%Y %H:%M'),
		fes_stage_band_end_date: DateTime.strptime('$end_time', '%m/%d/%Y %H:%M'),
		deleted_at_flg: 0)";
		echo '<br />';		

		$count++;
	}

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

?>
