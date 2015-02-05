<?php
class NewDB extends SQLite3{
    function __construct() {
        $this->open('tower.db');
    }
}
$db = new NewDB();
$db->exec('CREATE TABLE IF NOT EXISTS lives (id INTEGER PRIMARY KEY , date TEXT, time TEXT, title TEXT, genre TEXT,shop TEXT, detail TEXT, link TEXT,event_id TEXT )');


if(isset($_POST['delete'])){
	$state = "delete FROM lives";
	$result = $db->query($state );
}

$lives_array = array();
if(!isset($_POST['scrape'])){
	$state = "SELECT * FROM lives where event_id";
	$result = $db->query($state );
	if($result){
		while( $row = $result->fetchArray() ) {
			$lives_array += $row;
			array_push($lives_array, $row);
		}				
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Tower</title>
</head>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42716969-15', 'live3.info');
  ga('send', 'pageview');

</script>

<form action=""method="post">
	<input type="hidden" name='scrape' value='1'/>
	<input type="submit" value='Scrpae!!!'/>
</form>
<form action=""method="post">
	<input type="hidden" name='delete' value='1'/>
	<input type="submit" value='Delete!!!'/>
</form>

<hr />
<table>
	
<?php
$cnt=0;
	foreach($lives_array as $l){
		if($cnt<18){$cnt++; continue;}
		$id  = $l['id'];
		$date = $l['date'];
		$time = $l['time'];
		$title = $l['title'];		
		$genre = $l['genre'];
		$shop = str_replace('href="/store/', 'target="_blank" href="http://tower.jp/store/',str_replace('”', '"', $l['shop']));
		$detail = $l['detail'];		
		$link = $l['link'];		
		
		$house_id = 1;
		
		if(strpos($shop,'錦糸町' ) ){
			$house_id = 160;			
		}else if(strpos($shop,'渋谷' ) ){
			$house_id = 161;			
		}else if(strpos($shop,'秋葉原' ) ){
			$house_id = 162;			
		}else if(strpos($shop,'千葉' ) ){
			$house_id = 163;			
		}else if(strpos($shop,'新宿' ) ){
			$house_id = 164;			
		}else if(strpos($shop,'町田' ) ){
			$house_id = 165;			
		}else{
			continue;
		}
		
		
		
		$create_link = '../mng/index.php?prep_data=1&live_start_date='.str_replace('/', '-', $date).' '.$time.'&live_title_ja='.$title.'&house_id='.$house_id.'&live_description_ja='.$detail;
		
		$post_date = str_replace('/', '-', $date).' '.$time;
		$post_title = trim($title);
		$post_detail = strip_tags(trim($detail));
		$post_detail = str_replace(array("\r\n","\r","\n"), '', $post_detail);
		
		echo '<tr>';
		echo "<td><a href='$link' target='_blank'>$id</a><br />";		
		echo "$date $time <hr />";
		echo "$title <hr />";		
		echo "$genre <hr />";		
		echo "$shop <br />";
//		echo "<a href='$create_link' target='_blank'><button>Create</button></a></td>";
		
		echo "<form action='../mng/index.php' method='post' target='_blank'>
			<input type='hidden' name='prep_data' value='1' />
			<input type='hidden' name='live_start_date' value='$post_date' />
			<input type='hidden' name='live_title_ja' value='$post_title' />
			<input type='hidden' name='house_id' value='$house_id' />						
			<input type='hidden' name='live_description_ja' value='$post_detail' />	
			<input type='submit' value='作成するよ' />	
		</form>
		<form action='http://live3.info/events/new' method='post' target='_blank'>
			<input type='hidden' name='prep_data' value='1' />
			<input type='hidden' name='live_start_date' value='$post_date' />
			<input type='hidden' name='live_title_ja' value='$post_title' />
			<input type='hidden' name='house_id' value='$house_id' />						
			<input type='hidden' name='live_description_ja' value='$post_detail' />	
			<input type='submit' value='作成するよ new' />	
		</form>
		</td>";
		
		
		echo "<td style='font-size:10px;'>$detail</td>";
		echo '</tr>';
	}

	
?>	
</table>
<style>
table tr td{
	border: solid 1px #999;
}
</style>	
</body>
</html>

<?php
}else{//if(!isset($_POST['scrape'])){

//http://www.lastfm.jp/events/+place/Japan
$host = 'http://tower.jp';
$path = '/store/event/';
$source_url ="$host$path?page=";

for($i = 1; $i < 20; $i++ ){//
	$url = $source_url.$i;	
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
        'header'=>"Accept-language: ja\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );
    $context = stream_context_create($opts);
    $file = file_get_contents($url, false, $context);
    //$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
    $lines =preg_split('/\r?\n/', $file);
	listPage($lines,$host,$db);
	
}

}//if(!isset($_POST['scrape'])){ end!

function listPage($lines,$host,$db){
    $start_f=0;    $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $access_f=0;  $clm_f=0;
    $link="";$title="";$date="";$time="";$shop="";$access="";$img="";	$genre="";		$housename='';
    $detail='';
    $artist_name="";$startdate="";
    $href_flg = 0;
	foreach($lines as $line){

    	if(strpos( $line,'tableModel-recBase1')){
    		$start_f=1;
    	}
    	if($start_f == 0){
	    	continue;
    	}

    	if(preg_match('%(\d{4}/\d{2}/\d{2})%',$line, $match) && $date ==''){
    		$date = $match[1];
    	}
    	if(preg_match('%(\d{2}:\d{2})%',$line, $match) && $time==''){
    		$time = $match[1];
    	}
   

    	if(strpos($line, '/store/event/')){
	    	$title_f=1;
    	}
    	
    	if($title_f==1){
	    	$title = $title.$line;
    	}   
    	
    	if($title_f == 2){
	    	$genre =  $genre.$line;
    	}

    	if($title_f == 3){
	    	$shop =  $shop.$line;
    	}

    	if( $title_f==3 && strpos($line, '</td>')){
	    	$title_f=4;
    	}

    	if( $title_f==2 && strpos($line, '</td>')){
	    	$title_f=3;
    	}
    	
    	if( $title_f==1 && strpos($line, '</td>')){
	    	$title_f=2;
    	}
    	
    	if($title_f==4){

			if(preg_match('%href="([^"]*?)"%',$title, $match)){
				$url = $match[1];
			}
			$link = $host.$url;
			$detail = detailPage($host,$url);	
			$id = str_replace('/store/event/', '', $url);
			
    		$date = b(strip_tags($date)); 
    		$title=b(strip_tags($title));
    		$detail=b(strip_tags($detail));
    		$genre = b(preg_replace("/<\/?t[^>]*?>/mi","",$genre));
    		$shop = b(preg_replace("/<\/?t[^>]*?>/mi","",$shop));
			
			echo $date;
			echo ' | ';
			echo $time;
			echo ' | ';    	
			echo $title;
			echo ' | ';
			echo $genre;
			echo ' | ';
			echo $shop;
			echo ' | ';			
			echo $detail;
			echo ' | ';		
			echo $id;			
			
			$update_flg=0;
			$state = "SELECT * FROM lives where event_id = $id";
			$result = $db->query($state );
			if($result){
				while( $row = $result->fetchArray() ) {
					$update_flg=1;break;
				}				
			}
			if($update_flg == 1){	
 				$db->exec("Update lives set date ='$date', time ='$time', title ='$title', genre ='$genre',shop ='$shop', detail ='$detail', link ='$link',event_id ='$id' where id = $id");
			}else{
				$db->exec("INSERT INTO lives ( date, time, title, genre, shop,detail, link, event_id ) VALUES (\"$date\",\"$time\",\"$title\",\"$genre\",\"$shop\",\"$detail\",\"$link\",\"$id\")");
			}
			
			$date='';$time='';$title='';$genre='';$shop='';$detail='';$link='';$id='';
			$title_f=0;
			echo '<hr />';			
    	}
    	
	}
}

function detailPage($host,$url){
	$url = $host.$url;
	$opts = array(
      'http'=>array(
        'method'=>"GET",
        'user_agent'  => "Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.2) Gecko/20100301 Ubuntu/9.10 (karmic) Firefox/3.6", 
        'header'=>"Accept-language: ja\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );
    $url=str_replace(" ", "%20", $url);
    $context = stream_context_create($opts);
    $file = file_get_contents($url, false, $context);//URL,include_pathの使用、コンテキスト、読み込み開始のオフセット値、読み込み最大値。
    //$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
    $lines =preg_split('/\r?\n/', $file);
    
    $housename='';
    $lineup='';
    $detail='';
    $detail_start_f = 0;
    
	$address_flg=0;
	$lineup_flg=0;
	$detail_flg=0;
	$result_array =array();;

    foreach($lines as $line){
		if( $detail_start_f == 0 && strpos( $line,'<dt>参加方法</dt>') ){
			$detail_start_f = 1;		
		}
		if( $detail_start_f == 0){
			continue;
		}
		if($detail_start_f == 1 && strpos($line, '<dt>対象店舗</dt>')){
			$detail_start_f = 2;	
			break;		
		}
		
		if( $detail_start_f == 1){
			$detail = $detail.$line;
		}		
	}
	return $detail;	
}

function c($text, $db){
	$text = str_replace(":", "-", $text);
/*
	$text=sqlite_escape_string($text);
	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
*/
	return $text;
}

function b($text){
	$text = str_replace("'", "’", $text);
	$text = str_replace('"', "”", $text);	
	return $text;	
}


?>