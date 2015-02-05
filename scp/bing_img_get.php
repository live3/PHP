<?php
header("Access-Control-Allow-Origin:http://live3.info");
//header("Access-Control-Allow-Origin:http://live3.info:3000");
header('Content-Type: application/json');

if(isset($_POST['keyword'])){
	$keyword=$_POST['keyword'];
}else{
	exit;
	//$keyword='therion';
}
$accountKey = 'GB6arGjKiY3N1EP+iBfp/rLAyggWl7LltHv0uoymteg';            
//$keyword = 'はりねずみ';

$cred = sprintf('Authorization: Basic %s', base64_encode($accountKey . ":" . $accountKey) );

$context = stream_context_create(array(
    'http' => array(
        'header' => $cred
    )
));
//%27Size%3ALarge%27
$response = file_get_contents('https://api.datamarket.azure.com/Bing/Search/v1/Image?$top=50&$format=json&Query='.urlencode( '\'' . $keyword. '\'').'&ImageFilters=%27Aspect%3AWide%2BSize%3ALarge%27', 0, $context);
//https://api.datamarket.azure.com/Bing/Search/v1/Composite?Sources=%27image%27&Query=%27skrillex%27&ImageFilters=  
$response = json_decode($response);
 
 $cnt = 0;
//echo '[';

$json_array=array();
foreach($response->d->results as $val){
	$img_url = $val->MediaUrl;
	$img_width = $val->Width;
	$img_height = $val->Height;	

/*
	echo "{";
	echo "'img':'$img_url'";
	echo "},";
*/
	$json_array[] = array('img'=> $img_url);

	$cnt++;
}
echo json_encode($json_array);
exit;
//echo ']';
/*
object(stdClass)#3 (11) {
  ["__metadata"]=>
  object(stdClass)#4 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=0&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "31777721-ee04-4c26-a408-4b16adb16b27"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(78) "http://www.officiallyjd.com/wp-content/uploads/2012/08/20120808_shifuku_38.jpg"
  ["SourceUrl"]=>
  string(64) "http://www.officiallyjd.com/archives/162145/20120808_shifuku_38/"
  ["DisplayUrl"]=>
  string(56) "www.officiallyjd.com/archives/162145/20120808_shifuku_38"
  ["Width"]=>
  string(3) "240"
  ["Height"]=>
  string(3) "320"
  ["FileSize"]=>
  string(5) "16719"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#5 (6) {
    ["__metadata"]=>
    object(stdClass)#6 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608028551583436723&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "225"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7510"
  }
}
object(stdClass)#7 (11) {
  ["__metadata"]=>
  object(stdClass)#8 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=1&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "299e6eb3-65cb-4865-98d5-36619c5d46a7"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(67) "http://image.eiga.k-img.com/images/person/45456/300x.jpg?1332499302"
  ["SourceUrl"]=>
  string(29) "http://eiga.com/person/45456/"
  ["DisplayUrl"]=>
  string(21) "eiga.com/person/45456"
  ["Width"]=>
  string(3) "300"
  ["Height"]=>
  string(3) "300"
  ["FileSize"]=>
  string(5) "14520"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#9 (6) {
    ["__metadata"]=>
    object(stdClass)#10 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608042716389574311&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(5) "10507"
  }
}
*/