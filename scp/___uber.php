<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

/*
$url='https://login.uber.com/oauth/authorize';
$parameters = array(
    'response_type'=> 35.658517,
    'client_id'=>'ima2KswiMv7fJjpQuEaDtCYTdo6r-oSS'
);
$headers = array(
    'header' => "Content-Type: text/xml"
);
$options = array('http' => array(
    'method' => 'GET',
    'content' => http_build_query($parameters),
	'header' => implode("\r\n", $headers)    
));
$contents = file_get_contents($url, false, stream_context_create($options));
var_dump($contents);

exit;
*/


//server_side
$url = 'https://api.uber.com/v1/products';
$parameters = array(
    'server_token'=> 'J9sRKUAnPDkKH8ElaRFDMDZUjtg9VM5C9rNcRp6G',
    'latitude'=> 35.658517,
    'longitude'=>139.701334
);
$headers = array(
    'header' => "Content-Type: text/xml"
);
$options = array('http' => array(
    'method' => 'GET',
    'content' => http_build_query($parameters),
	'header' => implode("\r\n", $headers)    
));
$contents = file_get_contents($url, false, stream_context_create($options));


var_dump($contents);