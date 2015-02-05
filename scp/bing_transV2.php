<?php
header("Access-Control-Allow-Origin:http://live3.info");
//header("Access-Control-Allow-Origin:http://live3.info:3000");

$keyword = '明日は晴れだ';
if(isset($_GET['param'])){
	$keyword = $_GET['param'];
	if( $_GET['key'] !='nfjkanjkfnkad7i3riqhf3qffji3aljfj' ){
		//exit;
	}
}else{
	//exit;
}
$result = '';
$result = translateBing($keyword);

$xml = new SimpleXMLElement('<?xml version="1.0" standalone="yes"?><elems><elem></elem></elems>');
$xml->elem->addChild('data',$result);
echo $xml->asXML();

function translateBing($keyword){
	
	$keyword = urlencode($keyword);
	
	$accountKey = 'GB6arGjKiY3N1EP+iBfp/rLAyggWl7LltHv0uoymteg';            
	$cred = sprintf('Authorization: Basic %s', base64_encode($accountKey . ":" . $accountKey) );
	$context = stream_context_create(array(
	    'http' => array(
	        'header' => $cred
	    )
	));
	
	$result = file_get_contents('https://api.datamarket.azure.com/Bing/MicrosoftTranslator/v1/Translate?Text=%27%'.$keyword.'%27&To=%27en%27&From=%27ja%27', 0, $context);
//	var_dump($result);
	//$response = json_decode($response);
	$result = explode('<d:Text m:type="Edm.String">', $result);
	$result = explode('</d:Text>', $result[1]);
	$result = $result[0];
	$result = str_replace('%', '', $result);
//	echo $result;
//	echo '<hr />';
	return $result;
}


function translateBingV2($keyword){
	
	$url = 'http://api.microsofttranslator.com/V2/soap.svc';
	$appid = 'live3_microsoft_api';
	
	$query = array(
		'appId' => $appid,
		'text' => 'てすと',
		'from' => 'ja',
		'to' => 'en'
	);
	
	$client = new SoapClient($url);
	$result = $client->Translate($query); 
	
	echo $result->TranslateResult;	
}

