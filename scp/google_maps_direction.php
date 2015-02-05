<?php
//header("Access-Control-Allow-Origin:http://live3.info");
header("Access-Control-Allow-Origin:http://live3.info:3000");
header('Content-Type: application/html');

$lat='';
$lng='';
$end_lat='';
$end_lng='';
if(!isset($_GET['lat']) || !isset($_GET['lat']) || !isset($_GET['end_lat']) || !isset($_GET['end_lng']) ){
	exit;
}else{
	$lat=$_GET['lat'];
	$lng=$_GET['lng'];
	$end_lat=$_GET['end_lat'];
	$end_lng=$_GET['end_lng'];
}
$key = 'fanlkh48hifhi38autrihfaihfi8h3ia8h3lifhlaii38yr85yrqpyfeauh';
if($key != $_GET['key'] ){
	exit;
}
?>
<script>
	var start =new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);\
	var end =new google.maps.LatLng(<?php echo $end_lat; ?>,<?php echo $end_lng; ?>);

calcRoute( start, end);
function calcRoute( start, end) {  	
  var directionsService = new google.maps.DirectionsService();
  var request = {
    origin:start,
    destination:end,
    travelMode: google.maps.TravelMode.WALKING
  };
  directionsService.route(request, function(result, status) {
    if (status == google.maps.DirectionsStatus.OK) {
	  var txt = ' 徒歩'+result.routes[0].legs[0].duration.text
	  $('body').append(txt)
    }else{
	   console.log('不正な緯度経度！')    
    }
  });
}
</script>

<body>
	
</body>

