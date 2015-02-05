<?php

$latitude = "35.662505";
$longitude = "139.697873";

$url_loc_search = "https://api.instagram.com/v1/locations/search?lat=$latitude&lng=$longitude&access_token=19491131.56504c7.51ebc7fb5865438b80ba5d73ffb3893d";

function get_json($url){
	$options['request_type'] = 0;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, $options['request_type']);
	if( !empty($options['post']) ) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, $options['post']);
	}
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$json = json_decode(curl_exec($ch));
	curl_close($ch);
	return $json;
}

$json = get_json($url_loc_search);

foreach ($json->data as $item) {
	$location_id =  $item->id;
	$url_photo_search ="https://api.instagram.com/v1/locations/".$location_id."/media/recent?access_token=19491131.56504c7.51ebc7fb5865438b80ba5d73ffb3893d";
	$json_photo = get_json($url_photo_search);

	var_dump($json_photo->data["images"]);
	echo "<hr />";	

	
	foreach ($json_photo->data as $item) {
		echo '<img src="'.$item->images->low_resolution->url.'" alt="" />';
		echo "<hr />";	
	
	}


}
