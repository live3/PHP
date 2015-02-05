<?php
ini_set("max_execution_time",0);
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');


//1つずつ！
$needle=1;
if(isset($_GET['needle'])){
	$needle=$_GET['needle'];
}

$db = new SQLite3('barks_artist'.$needle.'.sqlite');

$db->exec('CREATE TABLE IF NOT EXISTS bands (id INTEGER PRIMARY KEY ,name TEXT,nameF TEXT, link TEXT,image TEXT,member TEXT, description1 Text, description2 TEXT, rate Text,description3 TEXT )');

$state = "select * from bands";
$result = $db->query($state );
$start_i = 1;
$id_array = array();
$ccc = 0;
while( $row = $result->fetchArray() ) {
	$id = (int)$row['id'];
	$id_array[] = doubleval($id);
	$ccc++;
	
//	echo $row['name'];
//	echo '<hr />';
}
echo $ccc;
exit;//5293前回

$host = 'http://www.barks.jp/';

for($num=1;$num<=6;$num++){//6まで140718
	if($num !=  $needle ){continue;}
	$xmlfile = "./barks_sitemaps/sitemap".$num.".xml";
	$xml = simplexml_load_file($xmlfile);

	foreach($xml->url as $url_ar ){		
		$url = $url_ar->loc;
		
		//済ならスキップ
		$url_ar = explode('=', $url);
		$url_id = doubleval( $url_ar[1]);
		if( in_array($url_id, $id_array)){echo '<hr />';continue;}
		echo $num; echo '||'; echo $url_id; 
		echo 'go';echo '<hr />';
		
//		$url = 'http://www.barks.jp/artist/?id=1000000035';//zazen

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
	    $detail='';
	    $detail_f = 0;
	    
	    $artist_name="";$startdate="";
	    $href_flg = 0;
	    foreach($lines as $line){

	    	if(strpos($line,'name="keywords"') && $title == ''){
		    	$title= str_replace('：プロフィール・バイオグラフィ・リンク', '', strip_tags($line));
		    	if(preg_match('%content="([^"]*)%',$line, $match)){
		    		$keywords = explode(',', $match[1]);
		    		$title = $keywords[0];
		    		$title_f = $keywords[1];
		    	}
	        }   	    
	    
	    	if(strpos( $line,'id="artist_biography"')){
	    		$start_f=1;
	    	}
	    	if($start_f == 0){
		    	continue;
	    	}    	
	    	
	    	if(strpos($line,'id="artist_biography"') && $detail_f ==0 ){
	    		$detail_f = 1;
	    	}
	    	
	    	if(strpos($line,'/div>') && $detail == "" && $detail_f == 1 ){
			    	$detail_f = 2;	
	    	}
	    	
	    	if($detail_f == 2){
		    	if(strpos($line,'</div') || strpos($line,'artist_link_title') ||  strpos($line,'</section')||  strpos($line,'ul>')){
			    	$detail_f=3;	    	
		    	}
	    	}	 
	    	   	
	    	if($detail_f == 2){
		    	$detail = $detail.$line;
	    	}
	         
	        if(($title !== "" && $detail !="" && $detail_f == 3 ) || strpos( $line,'id="artist_cd"')){
	        	$img = "http://img.barks.jp/image/photo/artist/".$url_id."/IMAGE_FILE_URL.jpg";
	        
				$title=c($title,$db);
				$title_f=c($title_f,$db);
				$detail= strip_tags(str_replace('<br>', '\n', c($detail,$db)));
				
				$db->exec("INSERT INTO bands (id,name, nameF, image,  description1,link ) VALUES (\"$url_id\",\"$title\",\"$title_f\",\"$img\",\"$detail\",\"$url\")");
	//			$db->exec("Update bands set name ='$name', nameF = '$nameF', image = '$image',  description1 = '$detail' where id = $i ");			
				break;
	        }
	    }
		//test 
	        	$img = "http://img.barks.jp/image/photo/artist/".$url_id."/IMAGE_FILE_URL.jpg";
	        
				$title=c($title,$db);
				$title_f=c($title_f,$db);
				$detail= strip_tags(str_replace('<br>', '\n', c($detail,$db)));
				
				$db->exec("INSERT INTO bands (id,name, nameF, image,  description1,link ) VALUES (\"$url_id\",\"$title\",\"$title_f\",\"$img\",\"$detail\",\"$url\")");
	//			$db->exec("Update bands set name ='$name', nameF = '$nameF', image = '$image',  description1 = '$detail' where id = $i ");			


	}	

}

function c($text, $db){
//	$text=sqlite_escape_string($text);
//	$text=mb_convert_encoding($text, "UTF-8",'auto');
//	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
	$text=str_replace("'", '’', $text);
	return $text;
}
