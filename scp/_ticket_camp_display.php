<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

$db = new SQLite3('ticket_camp.sqlite');

$current_url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if(!strpos($current_url, '?') ){$current_url = $current_url.'?';}else{$current_url=$current_url.'&';}

if(isset($_POST['favorite_flg']) && isset($_POST['ticket_id'])){
	$favorite_flg=$_POST['favorite_flg'];
	$ticket_id=$_POST['ticket_id'];
	$db->exec("update tickets set favorite_flg = '$favorite_flg' where id = $ticket_id ");
	echo 'success ticket_id'.$ticket_id.' fv_flg'.$favorite_flg;
	exit;
}

if(isset($_POST['deleted_at']) && isset($_POST['ticket_id'])){
	$deleted_at=$_POST['deleted_at'];
	$ticket_id=$_POST['ticket_id'];
	$db->exec("update tickets set deleted_at = '$deleted_at' where id = $ticket_id ");
	echo 'success ticket_id'.$ticket_id.' deleted_at'.$deleted_at;
	exit;
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>TC</title>
<link href="./assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="./assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
<link href="./assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="./assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
<link href="./assets/css/style.css" rel="stylesheet" type="text/css"/>
<link href="./assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="./assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
<link href="./assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="./assets/css/tools.css" rel="stylesheet" type="text/css"/>
<link href="./assets/css/grid.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="./assets/plugins/jquery.bxslider/jquery.bxslider.css" type="text/css" />
<link href="./assets/css/pages/pricing-tables.css" rel="stylesheet" type="text/css"/>
<link href="./assets/css/pages/timeline.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="./assets/plugins/countdown/countdowncube.min.css"/>
<link href="./assets/css/ex_style.css" rel="stylesheet" type="text/css"/>	
</head>
<body style="padding:20px;background:#fff;">
<style>
table tr th,table tr td{
	word-break:break-all;
}
.datetime{
	min-width:130px;	
}
.control{
	min-width:80px;	
}
#filter_btns ul li{
	float: left;
	margin-right: 4px;
}
</style>
<div class="taC">
	<h1><a href="./_ticket_camp_display.php">Ticket Camp</a></h1>	

</div>
<div class="col_4 fR">
	<div class="fR mR20">
		<a href="./_ticket_camp.php" class="btn red red-stripe" target="_blank">即時更新(1分程度)</a>
	</div>
	<div class="cB"></div>
<?php

$result = $db->query("select * from manages" );
//DBと一致確認
$updated_at='';
while( $row = $result->fetchArray() ) {
	$updated_at = $row['updated_at'];
}
	echo '<p align="right"class="mR20">最終更新日時: '.$updated_at.'</p>';
echo '</div>';
?>

<div class="col_8" id="filter_btns">
<ul class="list">
	<li><a class="btn yellow"  href="<?php echo $current_url; ?>fv=1">お気に入りのみ</a></li>
	<li><a class="btn blue"  href="<?php echo $current_url; ?>date=1">日付順</a></li>
	<li><a class="btn purple"  href="<?php echo $current_url; ?>loc=kanto">南関東</a></li>
	<li><a class="btn purple"  href="<?php echo $current_url; ?>loc=japan">全国</a></li>
</ul>
<div class="cB"></div>

<?php
if(isset($_GET['fv']) && $_GET['fv'] == 1 ){
	$result = $db->query("select * from tickets where deleted_at != 1 AND favorite_flg = 1" );
	echo '<p align="left"class="pL10 pT5 small">お気に入りのみ</p>';	
}else if(isset($_GET['date']) && $_GET['date'] == 1 ){
	$result = $db->query("select * from tickets where deleted_at != 1 Order by date(day) ASC" );
	echo '<p align="left"class="pL10 pT5 small">日付順</p>';	
}else if(isset($_GET['loc']) ){
	if($_GET['loc']==='kanto'){
		$result = $db->query("select * from tickets where deleted_at != 1 AND ( venue LIKE '%埼玉%' OR venue LIKE '%神奈川%'OR venue LIKE '%千葉%' )" );	
		echo '<p align="left"class="pL10 pT5 small">南関東(東京以外)</p>';				
	}else if($_GET['loc']==='japan'){
		$result = $db->query("select * from tickets where deleted_at != 1 " );	
		echo '<p align="left"class="pL10 pT5 small">全国</p>';				
	}	
}else{
	$result = $db->query("select * from tickets where deleted_at != 1 AND venue LIKE '%東京%'" );	
}
echo '</div>';

echo '<div class="cB"></div>';

echo '<table class="table table-bordered table-hover table-striped table-condensed ">';
echo '<tr >

<th class="title">ID / タイトル</th>
<th class="datetime">日時</th>
<th class="venue">場所</th>
<th>価格</th>
<th>枚数</th>
<th>通常価格(#と&で囲まれたものが通常価格)</th>
<th>タグ</th>
<th class="control"></th>
</tr>';


$array=array();
//DBと一致確認
while( $row = $result->fetchArray() ) {

	$id = $row['id'];
	if($id==0){continue;}
	
	$array[]=$row;
}

//日付でならび変え
if(isset($_GET['date']) && $_GET['date'] == 1 ){
	foreach($array as $key=>$row){
		$new_date = '20'.mb_substr($row['day'],0,-3).' '.$row['time'];
	    $date[$key]= date( "Y/m/d H:i", strtotime($new_date));
	}
	//SORT_NATURALを指定
	array_multisort($date,SORT_ASC,$array);
}
$event_cnt=0;
foreach($array as $row){
	$id = $row['id'];
	$link=$row['link'];
	$title=$row['title'];
	$title_link=$row['title_link'];
	$day=$row['day'];
	$day_link=$row['day_link'];						
	$time=$row['time'];
	$venue=$row['venue'];
	$venue_link=$row['venue_link'];	
	$price=$row['price'];
	$ticket_count=$row['ticket_count'];
	$total_price=$row['total_price'];

	$day_time = '20'.mb_substr($day,0,-3).' '.$time;
	$price_int=99999;
	if( preg_match('%.*?([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?).*?%', $price,$match) ){
		$price_int= intval(str_replace(',', '', $match[0]));
	}else{
		$price_int=intval(str_replace(',', '', explode('円', $price)));
	}

	$total_price_int=99999;
	if( preg_match('%.*?#([0-9]{1,3}(,[0-9]{3})*(\.[0-9]+)?).*?%', $total_price,$match) ){
		$total_price_int= intval(str_replace(',', '', $match[1]));		
	}else{
		$total_price_int=intval(str_replace(',', '', explode('円', $total_price)));
	}

	$favorite_flg=$row['favorite_flg'];
	$isnot_favorite=1;
	$btn_warning='';
	if($favorite_flg == 1){
		$isnot_favorite =0;
		$btn_warning='yellow';
	}

	$deleted_at=$row['deleted_at'];
	$isnot_deleted_at=1;
	if($deleted_at == 1){
		$isnot_deleted_at =0;
	}
	
	$tag=$row['tag'];

		echo '<tr>';
		echo '<td><button class="mL5 mB5 favorite_btn btn '.$btn_warning.'" favorite_flg="'.$isnot_favorite.'" ticket_id="'.$id.'">☆</button>
		<a href="'.$link.'">'.$id.'</a><hr class="m0 mB5"/><a href="'.$title_link.'">'.$title.'</a></td>';
		echo '<td><a href="'.$day_link.'">'.$day_time.'</a></td>';
		echo '<td><a href="'.$venue_link.'">'.$venue.'</a><br /><div class="set_address"><button class="get_address btn dark" venue="'.$venue.'" ticket_id="'.$id.'">住所取得</button></div></td>';
		echo '<td>'.$price.'</td>';
		echo '<td>'.$ticket_count.'</td>';
		echo '<td>'.$total_price.'<br /><span class="label-red">'.$total_price_int.'</span></td>';
		echo '<td>'.$tag.'</td>';
		echo '<td><button class="mL5 mB5 deleted_at_btn btn red ffs10 p5" deleted_at="'.$isnot_deleted_at.'" ticket_id="'.$id.'">非表示</button><hr class="mB0"/>';
		echo "<form action='http://live3.info/events/new' method='post' target='_blank'>
			<input type='hidden' name='prep_data' value='1' />
			<input type='hidden' name='live_start_date' value='$day_time' />
			<input type='hidden' name='live_end_date' value='$day_time' />
			<input type='hidden' name='live_title_ja' value='$title' />					
			<input type='hidden' name='ticket_discount_price' value='$price_int' />	
			<input type='hidden' name='ticket_price' value='$total_price_int' />	
			<input type='hidden' name='ticket_count_limit' value='999999' />
			<input type='hidden' name='ticket_type_id' value='5' />
			<input type='submit' value='Live!' class='btn green xsmall'/>
			</form>";
		echo "<form action='http://live3.info/houses/new' method='post' target='_blank' id='house_form_$id'>
			<input type='hidden' name='prep_data' value='1' />
			<input type='hidden' name='house_name_ja' value='$venue' />
			<input type='hidden' name='house_address_ja' value='' />
			<input type='hidden' name='house_latitude' value='' />			
			<input type='hidden' name='house_longitude' value='' />			
			<input type='submit' value='House!' class='btn blue xsmall'/>
			</form>";
		echo '</td>';
		echo '</tr>';
		
		$event_cnt++;	
}

echo '</table>';
echo $event_cnt;
?>
<script src="./assets/plugins/jquery-1.10.1.min.js" type="text/javascript"></script> 
<script src="./assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script> 
<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip --> 
<script src="./assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 

<script>
$('.favorite_btn').click(function(){
	var ticket_id=$(this).attr('ticket_id');
	var favorite_flg=  $(this).attr('favorite_flg')-0;
  $.ajax({
    type: 'post',
    url: './_ticket_camp_display.php',
    data: {
      'favorite_flg': favorite_flg,
      'ticket_id': ticket_id
    },
    success: function(data){
//      alert(data);
    },
  });
  if(favorite_flg == 1){
      $(this).addClass('yellow');
      $(this).attr('favorite_flg','0')
  }else{
      $(this).removeClass('yellow');	      
      $(this).attr('favorite_flg','1')      
  }	
});

$('.deleted_at_btn').click(function(){
	var ticket_id=$(this).attr('ticket_id');
	var deleted_at=  $(this).attr('deleted_at')-0;
  $.ajax({
    type: 'post',
    url: './_ticket_camp_display.php',
    data: {
      'deleted_at': deleted_at,
      'ticket_id': ticket_id
    },
    success: function(data){
      alert(data);
    },
  });
  if(deleted_at == 1){
     $(this).parents('tr').fadeOut();
  }
});

$('.get_address').click(function(){
	var venue=$(this).attr('venue')
	var tg = $(this).parents('.set_address')
	var ticket_id = $(this).attr('ticket_id')
  $.ajax({
    type: 'get',
    url: 'http://maps.googleapis.com/maps/api/geocode/json',
    data: {
      'address': venue
    },
    success: function(data){
      console.log(data['results'][0]['geometry'])
      var ad_obj=data['results'][0]['address_components']
	  var address = ad_obj[5]['long_name']+ad_obj[4]['long_name']+ad_obj[3]['long_name']+ad_obj[2]['long_name']+ad_obj[1]['long_name']
      var geo_obj=data['results'][0]['geometry']['location']
      
      var lat = geo_obj['lat']
      var lng = geo_obj['lng']
	  var latlng = 'lat: '+lat +'<br />lng'+ lng
	  var txt = '<p>'+address+'<br />'+latlng+'</p>'
	  $(tg).children('p').remove()
      $(tg).append(txt)	    
      
      var form_id = '#house_form_'+ticket_id
      $(form_id).children('input[name="house_address_ja"]').val(address)
      $(form_id).children('input[name="house_latitude"]').val(lat)      
      $(form_id).children('input[name="house_longitude"]').val(lng)      
    }
  });
  //$(this).parents('.set_address').append()
});

//
</script>

</body>
</html>