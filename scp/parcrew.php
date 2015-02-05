<?php
class NewDB extends SQLite3{
    function __construct() {
        $this->open('parcrew.db');
    }
}
$db = new NewDB();
$db->exec('CREATE TABLE IF NOT EXISTS lives (id INTEGER PRIMARY KEY , date TEXT, img TEXT, title TEXT, genre TEXT,shop TEXT, detail TEXT, link TEXT,event_id TEXT )');

date_default_timezone_set('UTC');

//?y=2014&m=03&d=15
if(isset($_GET['y'])){
	$y = $_GET['y'];
	$m = $_GET['m'];
	$d = $_GET['d'];
}else{
	$y = date('Y');
	$m = date('m');
	$d = date('D');
}

$date = $y.'/'.$m.'/'.$d;

if(isset($_POST['delete'])){
	$state = "delete FROM lives";
	$result = $db->query($state );
}

$lives_array = array();
$all_count = 0;
if(!isset($_POST['scrape'])){

	if(isset($_GET['start_id'])){
		$start = $_GET['start_id'];
		$end = $start +100;
		$state = "SELECT * FROM lives where id >= $start AND id < $end";
	}else{
		$state = "SELECT * FROM lives where id >= 0 and id < 100";
	}
	$result = $db->query($state );
	if($result){
		while( $row = $result->fetchArray() ) {
			$lives_array += $row;
			array_push($lives_array, $row);
		}				
	}
	
	$state = "SELECT count(*) FROM `lives`";
	$result = $db->query($state );
	if($result){
		while( $row = $result->fetchArray() ) {
			$all_count= $row[0];
		}
	}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Parcrew</title>
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
<style>
#paging li{
	list-style: none;
	float:left;
	border:solid 1px #999;
	padding:5px;
}
#danger_btns{
	float:right;
}
div.cB{
	clear: both;
}
</style>
<ul id="paging">
<?php
$pages = ($all_count/100);
for($i=0;$i<=$pages;$i++){
	$start_id = $i*100;
	echo "<li><a href='./parcrew.php?start_id=$start_id'>$i</a></li>";
}
?>
</ul>
<div class="cB"></div>

<div id="danger_btns">
<form action=""method="post">
	<input type="hidden" name='scrape' value='1'/>
	<input type="submit" value='Scrpae!!!'/>
</form>
<form action=""method="post">
	<input type="hidden" name='delete' value='1'/>
	<input type="submit" value='Delete!!!'/>
</form>
</div>
<div class="cB"></div>
<hr />
<table>
	
<?php
$cnt=0;
	foreach($lives_array as $l){
		if($cnt<18){$cnt++; continue;}
		
		$id  = $l['id'];
		$date = $l['date'];
		$title = $l['title'];
		$img = $l['img'];
		$detail = $l['detail'];
		$link = $l['link'];
		
		$create_link = '../mng/index.php?prep_data=1&live_start_date='.str_replace('/', '-', $date).'&live_title_ja='.$title.'&live_description_ja='.$detail;
		
		$post_date = str_replace('/', '-', $date);
		$post_title = trim($title);
		$post_detail = strip_tags(trim($detail));
		$post_detail = str_replace(array("\r\n","\r","\n"), '', $post_detail);		
		
		echo '<tr>';
		echo "<td><a href='$link' target='_blank'>$id</a><br />";		
		echo "$date <hr />";
		echo "$title <hr />";		
		echo "<img src='$img' alt='' style='width:200px;'/> <br />";
//		echo "<a href='$create_link' target='_blank'><button>Create</button></a></td>";
		echo "<form action='../mng/index.php' method='post' target='_blank'>
			<input type='hidden' name='prep_data' value='1' />
			<input type='hidden' name='live_start_date' value='$post_date' />
			<input type='hidden' name='live_title_ja' value='$post_title' />
			<input type='hidden' name='live_description_ja' value='$post_detail' />	
			<input type='submit' value='作成するよ' />	
		</form></td>";		
		
		
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
$host = 'http://parcrew.com';
$type_path = array('guest','event');
	for($i=0;$i<31;$i++){
		$y = date('Y',strtotime("+$i day"));
		$m = date('m',strtotime("+$i day"));
		$d = date('j',strtotime("+$i day"));
	
		foreach($type_path as $t){
			$path = "/contents/$t/1/$y/$m/$d.html";
			$url = $host.$path;	
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
			
			echo $t;
			$date = $y.'/'.$m.'/'.$d;
			listPage($lines,$host,$db,$date);
			
		}
	}

}//if(!isset($_POST['scrape'])){ end!

function listPage($lines,$host,$db,$date){
    $start_f=0;    $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $access_f=0;  $clm_f=0;
    $link="";$title="";
    //$date="";
    $time="";$shop="";$access="";$img="";	$genre="";		$housename='';
    $detail='';
    $artist_name="";$startdate="";
    $href_flg = 0;
	foreach($lines as $line){

    	if(strpos( $line,'id="main"')){
    		$start_f=1;
    	}
    	if($start_f == 0){
	    	continue;
    	}
		
    	if(strpos( $line,'>詳細<')){
	    	if(preg_match('%href="([^"]*?)"%',$line, $match) && $link ==''){
	    		$link = $match[1];
	    		$ar = array();
				$ar = detailPage($host,$link);

				$title = $ar[0];
				$img = $ar[1];
				$detail = strip_tags($ar[2]);

				if(preg_match('%(\d{4}\d*?)\.%',$link, $match) ){
					$id=$match[1];
				}

				if(preg_match('%src="([^"]*?)"%',$img, $match) ){
					$img=$match[1];
				}

				echo $date;
				echo ' | ';
				echo $link;
				echo ' | ';    	
				echo $title;
				echo ' | ';
				echo $img;
				echo ' | ';			
				echo $detail;
				echo ' | ';		
				echo $id;					
				echo '<hr />';
					
	    		$title=b(strip_tags($title));
	    		$detail=b(strip_tags($detail));
/*	    		$genre = b(preg_replace("/<\/?t[^>]*?>/mi","",$genre));
	    		$shop = b(preg_replace("/<\/?t[^>]*?>/mi","",$shop));
*/				
				$update_flg=0;
				$state = "SELECT * FROM lives where event_id = $id";
				$result = $db->query($state );
				if($result){
					while( $row = $result->fetchArray() ) {
						$update_flg=1;break;
					}				
				}
				if($update_flg == 1){	
	 				$db->exec("Update lives set date ='$date', img ='$img', title ='$title', detail ='$detail', link ='$link',event_id ='$id' where id = $id");
				}else{
					$db->exec("INSERT INTO lives ( date, img, title,  detail, link, event_id ) VALUES (\"$date\",\"$img\",\"$title\",\"$detail\",\"$link\",\"$id\")");
				}
				
				$title='';$genre='';$shop='';$detail='';$link='';$id='';				
					
				
				$link ='';
				continue;				
				
	    	}
    	}		
	}
}

function detailPage($host,$url){
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
    //$file = sjis_to_utf8($file);//amazonはSHIFT-JISなので変換。
    $lines =preg_split('/\r?\n/', $file);
    
    $title ='';
    $housename='';
    $lineup='';
    $detail='';
    $detail_start_f = 0;
    $img = '';
    
	$address_flg=0;
	$lineup_flg=0;
	$detail_flg=0;
	$result_array =array();;

    foreach($lines as $line){
		if( $detail_start_f == 0 && strpos( $line,'id="detail_box_title"') ){
			$detail_start_f = 1;
			$title=strip_tags($line);
		}
		if( $detail_start_f == 0){
			continue;
		}
		
		if($detail_start_f == 1 && strpos($line, 'detail_box_main_img')){
			$img=preg_replace('%</?div[^>]*?>%', "", $line);
			$detail_start_f = 2;
		}


		if($detail_start_f == 3 && strpos($line,'<!--/#detail_box_inner-->')){
			return array($title,$img,$detail);	
		}
		
		if( $detail_start_f == 3){
			$detail = $detail.$line;
		}	
		
		if($detail_start_f >= 1 && strpos($line,'detail_box_body')){
			$detail_start_f = 3;
		}
		
	}
	return array($title,$img,$detail);	
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