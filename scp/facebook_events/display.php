<?php
date_default_timezone_set('Asia/Tokyo');
class NewDB extends SQLite3{
    function __construct() {
        $this->open('fb_event.sqlite');
    }
}

$db = new NewDB();

$today = date("Y-m-d");
$tomo = date("Y-m-d",strtotime("+ 1 day"));

$state = "SELECT * FROM events where start_time  >= datetime('$today','localtime') AND start_time  < datetime('$tomo','localtime') order by start_time ASC";

if(isset($_POST['my_date'])){
	$today = $_POST['my_date'];
	$tomo = date('Y-m-d H:i:s', strtotime("$today +1 days"));
	$state = "SELECT * FROM events where start_time  >= datetime('$today','localtime') AND start_time  < datetime('$tomo','localtime') order by start_time ASC";
}
?>
<!doctype html>
<html lang="en">
<head>
	<link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/plugins/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style-metro.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
	<link href="../assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/tools.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/grid.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" href="../assets/plugins/jquery.bxslider/jquery.bxslider.css" type="text/css" />
	<link href="../assets/css/pages/pricing-tables.css" rel="stylesheet" type="text/css"/>
	<link href="../assets/css/ex_style.css" rel="stylesheet" type="text/css"/>	
	<meta charset="UTF-8" />
	<title>disp fb_event</title>
</head>
<body>
<div class="col_12">
	<button class="btn fL"><a href="./test.php" target="_blank">データ更新（要facebookログイン　５分程度）</a></button>
	<form action=""method="post" class="fL">
		<input type="text" placeholder="2014-01-01" name='my_date' value="<?php echo $today;?>" />
		<input type="submit" />
	</form>
</div>
<div class="cB"></div>


<table class="table table-bordered">
	<tbody>
<?php



$result = $db->query($state );
while( $response = $result->fetchArray() ) {

$eid= c($response['event_id']);
$description =c($response['description']);
$is_date_only =c($response['is_date_only']);
$location =c($response['location']);
$name =c($response['name']);
$owner_name =c($response['owner_name']);
$owner_id =c($response['owner_id']);
$privacy =c($response['privacy']);
$start_time =c($response['start_time']);
$end_time =c($response['timezone']);
$update_time =c($response['updated_time']);
$venue_id =c($response['venue_id']);
$venue_city =c($response['venue_city']);
$venue_country =c($response['venue_country']);
$venue_latitude =c($response['venue_latitude']);
$venue_longitude =c($response['venue_longitude']);
$venue_state =c($response['venue_state']);
$pic = $response['pic'];
$pic_big = $response['pic_big'];
$pic_small =$response['pic_small'];
$pic_cover =$response['pic_cover'];
$attending_count =$response['attending_count'];
$all_members_count =$response['all_members_count'];
$ticket_uri =$response['ticket_uri'];

if($venue_state != '' && $venue_state != 'Tokyo' && $venue_state != 'Kanagawa' && $venue_state != 'Saitama'){
	continue;
}


if( strpos($venue_latitude, '35.') !== 0 ){
	continue;	
}

$description_org = $description;
$description = preg_replace('%([^-]\d[\d|,]{3,10}[^年|-])%u', "<span style='color:red;'>$1</span>", $description);
$description = preg_replace('%([０-９|，]{3,10}[^年])%u', "<span style='color:red;'>$1</span>", $description);

$description = str_replace('無料', "<span style='color:red;'>無料</span>", $description);
$description = str_replace('free', "<span style='color:red;'>free</span>", $description);

$form_start_time = date('Y-m-d H:i', strtotime($start_time));
$form_end_time = date('Y-m-d H:i', strtotime($end_time));
if(count($form_end_time) == 0 ){
	$form_end_time = $form_start_time;
}elseif(strpos($form_end_time, '1970')===0){
	$form_end_time = $form_start_time;	
}
//			<input type='hidden' name='house_id' value='$house_id' />	

echo '<tr>';


//echo "<td>$is_date_only</td>";
echo "<td><p>$venue_city $venue_state</p>
<form action='../../mng/index.php' method='post' target='_blank'>
	<input type='hidden' name='prep_data' value='1' />
	<input type='hidden' name='live_start_date' value='$form_start_time' />
	<input type='hidden' name='live_end_date' value='$form_end_time' />
	<input type='hidden' name='live_title_ja' value='$name' />					
	<input type='hidden' name='live_description_ja' value='$description_org' />	
	<input type='submit' value='作成するよ' />	
</form>
</td>";
echo "<td><a href='http://facebook.com/events/$eid'>$name</a> <p><img src='$pic_cover'/></p></td>";
echo "<td>$location </td>";
echo "<td><p style='font-size:0.5em;'>$description</p></td>";
//echo "<td>$owner_name</td>";
/////echo "<td>$owner_id</td>";
//echo "<td>$privacy</td>";
echo "<td>$start_time</td>";
//echo "<td>$timezone</td>";
//echo "<td>$updated_time</td>";
//echo "<td>$venue_id</td>";		
//echo "<td>$venue_country</td>";
//echo "<td>$venue_latitude <br /> $venue_longitude</td>";
//echo "<td>$venue_longitude</td>";
//echo "<td>$ticket_uri</td>";
echo "<td>$attending_count / $all_members_count</td>";
echo '</tr>';		
}

function c($v){
	if(isset($v)){
		return $v;	
	}
	return '';
}

?>

	</tbody>
</table>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42716969-15', 'live3.info');
  ga('send', 'pageview');

</script>
</body>
</html>