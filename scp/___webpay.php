<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');


require "webpay-php-full-2.1.1/autoload.php";
use WebPay\WebPay;
$webpay = new WebPay('test_secret_1lYcPf6hMdOmggX8j3aEH1tF');
/*
$webpay->customer->create(array(
   "card"=>
    array("number"=>"4242-4242-4242-4242",
     "exp_month"=>11,
     "exp_year"=>2014,
     "cvc"=>"123",
     "name"=>"KEI KUBO"),
   "description"=>"Awesome Customer"
));
*/
$webpay->customer->create(array(
   "card"=>"tok_5SI8SafDp1jEgJx",
   "description"=>"2Awesome Customer"
));


var_dump($webpay);


exit;

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
$url = "https://api.webpay.jp/v1/customers?test_secret_1lYcPf6hMdOmggX8j3aEH1tF";
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