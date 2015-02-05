<?php
/*
        <table class="table"><hr />          <hr />          <tr><hr />            <th>公演名</th><hr />            <td><hr />              <hr />                <a href="/category-32665-tickets/">FC東京対浦和レッズJ1リーグ戦</a><hr />              <hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>公演日</th><hr />            <td><hr />              <hr />              <a href="/category-32665-tickets/event-92345/">14/8/23 (土)</a><hr />              <hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>時間</th><hr />            <td><hr />              18:30<hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>会場</th><hr />            <td><hr />              <hr />                <a href="/venue/1586/">味の素スタジアム (東京)</a><hr />              <hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>購入元</th><hr />            <td>主催者</td><hr />          </tr><hr />          <tr><hr />            <th>チケットの状態</th><hr />            <td>発券・所持済み</td><hr />          </tr><hr />          <hr />          <tr><hr />            <th>名義・塗り潰し</th><hr />            <td><hr />              <span class="tag-ticket tag-ticket-inline"><hr />                <hr />  <hr />    <span class="tag tag-ticket-no-name">名義なし</span><hr />  <hr /><hr /><hr /><hr />                <hr />                <span class="tag tag-ticket-not-filled">塗り潰しなし</span><hr />                <hr />              </span><hr />            </td><hr />          </tr><hr />          <hr />          <tr><hr />            <th>チケット価格</th><hr />            <td><hr />              <span class="ticket-price" data-price="1000">1,000</span> 円/1枚あたり<hr />              <hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>枚数</th><hr />            <td><hr />              <hr />                1〜2枚（バラ売り可）<hr />              <hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>総額</th><hr />            <td><hr />              <hr />                <hr />                  <hr />                  <span class="text-small text-muted">1枚で</span><hr />                  <hr />                  <del class="text-muted"><span class="ticket-regular-price text-large">2,300</span><span class="text-small"> 円</span></del><hr />                  <hr />                  <span class="ticket-price text-x-large">1,000</span> 円<br><hr />                  <hr />                  <span class="text-small text-muted">2枚で</span><hr />                  <hr />                  <del class="text-muted"><span class="ticket-regular-price text-large">4,600</span><span class="text-small"> 円</span></del><hr />                  <hr />                  <span class="ticket-price text-x-large">2,000</span> 円<br><hr />                  <hr />                <hr />              <hr />              <hr />              <span class="tag-ticket tag-ticket-inline tag-ticket-spacing-small"><hr />                <hr />                  <span class="tag tag-ticket-list-price">定価以下</span><hr />                  <hr />                  <span class="tag tag-ticket-discount-rate">56%OFF</span><hr />                  <hr />                <hr />                <hr />              </span><hr />              <hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>発送方法</th><hr />            <td><hr />              <ul class="list-marker"><hr />                <li>電子チケット、QRコード、発券番号の譲渡</li><hr />              </ul><hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>送料</th><hr />            <td>なし(手渡しや電子チケット等のやりとり)</td><hr />          </tr><hr />          <tr><hr />            <th>決済方法</th><hr />            <td><hr />              <hr />              <hr /><p class="general"><img src="//s3-ap-northeast-1.amazonaws.com/ticketcamp-asset/cfd60ec4b4/img/creditcard/payment_logo.png" alt="あんしん決済" width="90" height="19"></p><hr /><hr /><hr /><p class="general"><img src="//s3-ap-northeast-1.amazonaws.com/ticketcamp-asset/cfd60ec4b4/img/creditcard/payment-list-small-full.png" alt="支払い方法一覧" width="260" height="36"></p><hr /><div class="form-caption form-caption-block"><hr />  <ul class="list-marker"><hr />    <li>事務局で入金を一旦お預かりしチケット受渡し完了後に売り手へお支払いする仕組みです。</li><hr />    <li>お預かりした代金は最短即日で売り手様へ振込可能です。</li><hr />  </ul><hr /></div><hr /><hr /><hr />            </td><hr />          </tr><hr />          <hr />          <tr><hr />            <th>チケットID</th><hr />            <td><hr />              3343703<hr />            </td><hr />          </tr><hr />          <tr><hr />            <th>登録日</th><hr />            <td><hr />              14/7/30 (水) 18:30<hr />            </td><hr />          </tr><hr />          <hr />          <hr />          <tr><hr />            <th>出品終了日</th><hr />            <td>14/8/21 (木) 22:00</td><hr />          </tr><hr />          <hr />          <hr />          <hr />
*/

header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

$db = new SQLite3('ticket_camp.sqlite');

$db->exec('CREATE TABLE IF NOT EXISTS tickets (id INTEGER PRIMARY KEY ,link TEXT,title TEXT, title_link TEXT, day TEXT, day_link TEXT, time TEXT, venue TEXT, venue_link TEXT, price TEXT, ticket_count TEXT, total_price TEXT, tag TEXT,created_at TEXT,updated_at TEXT,deleted_at INTEGER, favorite_flg INTEGER )');

$db->exec('CREATE TABLE IF NOT EXISTS manages (id INTEGER PRIMARY KEY ,updated_at TEXT )');
$date = date('c');
$db->exec("insert into manages (updated_at) VALUES (\"$date\")");

$db->exec('update tickets set deleted_at = 1');

$host = 'http://ticketcamp.net';

$start_i = 1;
$end_i = 45;
for($i=$start_i; $i <= $end_i; $i++ ){
	echo $i;
	echo '<hr />';
	$url = 'http://ticketcamp.net/search/regular-price/page-'.$i.'/?sort=event-date';//無料 ライブ

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
    $url_id='';
    
    $artist_name="";$startdate="";
    $href_flg = 0;
    foreach($lines as $line){

    	if(strpos( $line,'id="event-last-minute-')){
    		$start_f=1;
    	}
    	if($start_f == 0){
	    	continue;
    	}
    	
    	if(strpos($line,'href="') ){
	    	if(preg_match('%href="([^"]*)%',$line, $match)){
	    		$link= $host.$match[1];
	    		$url_id= doubleval(str_replace('/', '', $match[1]));
	    	}
    	}
    	//title
    	if(strpos($line,'class="title') ){
    		$title_f =1;
    	}
    	if($title_f == 1){
			$title = $title.strip_tags($line);
	    	if(strpos($line,'</span') ){
	    		$title_f =2;	    		
	    	}
    	}
    	
    	if(strpos($line,'</li') ){
    		$title = conv($title);
    		$link = conv($link);
    		$date = date('c');
    		
			$db->exec("INSERT INTO tickets (id,title, link,created_at,deleted_at,favorite_flg ) VALUES (\"$url_id\",\"$title\",\"$link\",\"$date\",0,0)");
			detailPage($host,$link,$url_id,$db);
    		echo $link.'<br />';
    		echo $title.'<br />';
    		echo $url_id.'<br />';
    		echo '<hr />';
    		
			 $title_f=0;$date_f=0;    $img_f=0;    $shop_f=0;    $access_f=0;  $clm_f=0;
			    $link="";$title="";$date="";$shop="";$access="";$img="";			
			    $detail='';
			    $url_id='';

//    		exit;
//    		break;
    	}
    }
}

function detailPage($host,$link,$url_id,$db){
	$url = $link;
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
    
    $housename='';
    $lineup='';
    $detail='';
    $detail_start_f = 0;
    
	$address_flg=0;
	$lineup_flg=0;
	$detail_flg=0;
	$result_array =array();
	
	$title_link='';$day='';$day_link='';$venue='';$venue_link='';$time='';$price='';$ticket_count='';$total_price='';$tag='';
	$title_f=0;$day_f=0;$venue_f=0;$time_f=0;$price_f=0;$ticket_count_f=0;$total_price_f=0;

    foreach($lines as $line){

		if( $detail_start_f == 0 && strpos( $line,'class="table') ){
			$detail_start_f = 1;		
		}
		if( $detail_start_f == 0){
			continue;
		}
		
		//title
		if(strpos($line, '公演名')){
			$title_f=1;
		}
		if($title_f==1){
	    	if(preg_match('%href="([^"]*)%',$line, $match)){
	    		$title_link= $host.$match[1];
	    	}
	    	if(strpos($line, '</td')){
		    	$title_f=2;
	    	}
		}
		
		//day
		if($day_f==1){
			$day = $day.strip_tags($line); 
	    	if(preg_match('%href="([^"]*)%',$line, $match)){
	    		$day_link= $host.$match[1];
	    	}
	    	if(strpos($line, '</td')){
		    	$day_f=2;
	    	}
		}	
		if(strpos($line, '公演日')){
			$day_f=1;
		}
		
		//venue
		if($venue_f==1){
			$venue = $venue.strip_tags($line); 
	    	if(preg_match('%href="([^"]*)%',$line, $match)){
	    		$venue_link= $host.$match[1];
	    	}
	    	if(strpos($line, '</td')){
		    	$venue_f=2;
	    	}
		}	
		if(strpos($line, '会場')){
			$venue_f=1;
		}
		
		//time
		if($time_f==1){
			$time = $time.strip_tags($line); 
	    	if(strpos($line, '</td')){
		    	$time_f=2;
	    	}
		}	
		if(strpos($line, '時間')){
			$time_f=1;
		}		

		//price
		if($price_f==1){
			$price = $price.strip_tags($line); 
	    	if(strpos($line, '</td')){
		    	$price_f=2;
	    	}
		}	
		if(strpos($line, 'チケット価格')){
			$price_f=1;
		}			

		//ticket count
		if($ticket_count_f ==1){
			$ticket_count = $ticket_count.strip_tags($line); 
	    	if(strpos($line, '</td')){
		    	$ticket_count_f=2;
	    	}
		}	
		if(strpos($line, '枚数')){
			$ticket_count_f=1;
		}				

		//total price
		if($total_price_f ==1){
			if(strpos($line, 'ticket-regular-price')){
				$total_price = $total_price.'#'.strip_tags($line).'&';
			}else{
				$total_price = $total_price.strip_tags($line); 				
			}
	    	if(strpos($line, '</td')){
		    	$total_price_f=2;
	    	}
		}	
		if(strpos($line, '総額')){
			$total_price_f=1;
		}
		
		//tag
		if(strpos($line, 'class="tag')){
			$comm = ',';
			if($tag =='' || strip_tags($line)==''|| strip_tags($line)==' '){$comm='';}
			$tag = $tag.strip_tags($line).$comm;
		}

		if( strpos( $line,'</table') ){
			echo $title_link.'<br />';
			echo $day.'<br />';
			echo $day_link.'<br />';						
			echo $time.'<br />';
			echo $venue.'<br />';
			echo $venue_link.'<br />';	
			echo $price.'<br />';
			echo $ticket_count.'<br />';
			echo $total_price.'<br />';
			echo $tag.'<br />';
			$title_link=conv($title_link);
			$day=conv($day);
			$day_link=conv($day_link);						
			$time=conv($time);
			$venue=conv($venue);
			$venue_link=conv($venue_link);	
			$price=conv($price);
			$ticket_count=conv($ticket_count);
			$total_price=conv($total_price);
			$tag=conv($tag);	
			$date = date('c');
			$db->exec("Update tickets set title_link ='$title_link', day = '$day', day_link = '$day_link',  time = '$time',  venue = '$venue',  venue_link = '$venue_link',  price = '$price',  ticket_count = '$ticket_count',  total_price = '$total_price', tag='$tag', updated_at ='$date', deleted_at = 0 where id = $url_id ");
			break;		
		}
	}
	return $detail;	
}


exit;

$state = "select * from bands";
$result = $db->query($state );
$start_i = 1;
$sqlite_bands_array = array();
//DBと一致確認
while( $row = $result->fetchArray() ) {
	$id = (int)$row['id'];
//	$sqlite_bands_array[] = $row['name'];
	$sqlite_bands_array[$id] = mb_convert_kana($row['name'],'KV');

	//特定のアーティストを出す exit忘れずに
/*
	if(mb_strstr(mb_convert_kana($row['name'], 'KV'), "でんぱ")){
		echo $row['name'];
		echo '<hr />';
		echo $row['description1'];
		echo '<hr />';
		echo tranlate_temp($row['description1']);
		echo '<hr />';
	}
*/
}
//exit;

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
		foreach($sqlite_bands_array as $sqlite_band_id => $sqlite_name ){		
/*
			if(strpos($sqlite_name, 'the ') ){continue;}//とりあえず！！ the 系は除く
			if(mb_strpos($sqlite_name, 'The ') ){continue;}//とりあえず！！ the 系は除く
			if(mb_strpos($sqlite_name, 'THE ') ){continue;}//とりあえず！！ the 系は除く
*/
//			if (strstr($sqlite_name, mb_substr($band_name_ja, 0,5))) {
			if ( mb_strstr(conv($sqlite_name),mb_substr(conv($band_name_ja), 0,4))) {			
//			if (  urlencode(trim($sqlite_name)) == urlencode(trim($band_name_ja)) ) {
				echo $band_name_ja.'||'. $sqlite_name;
				echo "<form action='./__natalie_update.php' method='post' target='_blank'>
				<input type='hidden' name='band_id' value='$mysql_band_id' />
				<input type='hidden' name='sqlite_band_id' value='$sqlite_band_id' />
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

exit;

function conv($str){
	$str =str_replace(' ', '', $str);
	$str =str_replace('　', '', $str);
	$str =str_replace('"', '', $str);
	$str =str_replace("'", '', $str);
//	$str = mb_convert_kana($str,'KHV');
	$str = trim($str);	
	return $str;
}

///scraping
while( $row = $result->fetchArray() ) {
	$id = (int)$row['id'];
	if( $id > $start_i){
		$start_i=$id;	
	}
	echo $row['name'];
	echo '<hr />';
}

echo $start_i;
exit;

$end_i = 11112;//140718
function c($text, $db){
//	$text=sqlite_escape_string($text);
//	$text=mb_convert_encoding($text, "UTF-8",'auto');
//	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
	$text=str_replace("'", '’', $text);
	return $text;
}
?>

<!--



$host = 'http://natalie.mu/';
$url = 'http://natalie.mu/music/search/news/query/free%20live';//無料 ライブ
$url ='http://natalie.mu/music/search/news/query/無料%20ライブ';
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
    
    $artist_name="";$startdate="";
    $href_flg = 0;
    foreach($lines as $line){
    echo $line;
    	if(strpos( $line,'id="search-articles"')){
    		$start_f=1;
    	}
    	if($start_f == 0){
	    	continue;
    	}    	
    	
    	if($href_flg == 0 && strpos($line,'class="news-title"') ){
			$href_flg = 1;
			echo $line.'<hr />';
        }     
        if($href_flg == 1 && preg_match('%href="([^"]*)%',$line, $match)){
			$link = $match[1];
			$href_flg = 0;	
			$title = strip_tags($line);
			
			$detail='';
			$detail =detailPage($host,$link);
			echo $title;
			echo $detail;
			echo '<hr />';			
        }
		

		 		
    }



-->