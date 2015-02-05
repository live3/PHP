<?php

class NewDB extends SQLite3{
    function __construct() {
        $this->open('sgkc.db');
    }
}
$db = new NewDB();
$state = 'SELECT * FROM live';
$result = $db->query($state );
$result_array=array();

$cnt = 0;
$limit = 100;

while( $row = $result->fetchArray() )  {
if($cnt>$limit){break;}
    $id=$row["id"];
    $title=$row["title"];
    $link=$row["link"];
    $image=$row["image"];
    $address=explode("</p>", trim($row["address"]),2);
    $datetime=$row["date"]; 
    $shop=trim($row["shop"]);    
    $artist=trim($row["artist"]);    
    $description=explode("footer",trim(str_replace('”', '"',$row["description"])),2);
	$result_array[] = array(
		'id'=>$id, 
		'title'=>$title,
		'image' => $image,
		'link' => $link,
		'address' => $address[0],
		'shop' => $shop,
		'datetime' => $datetime,
		'artist' => str_replace('href="', 'href="http://www.songkick.com', str_replace('”', '"', $artist)),
		'description' => $description[0] 
//		'public_flg' => $public_flg
    );
$cnt++;           
}

//$json_value	= json_encode( $result_array );
$json_value	= json_xencode( $result_array );

header( 'Content-Type: text/javascript; charset=utf-8' );
echo $json_value;


function json_xencode($value, $options = 0, $unescapee_unicode = true)
{
  $v = json_encode($value, $options);
  if ($unescapee_unicode) {
    $v = unicode_encode($v);
    // スラッシュのエスケープをアンエスケープする
    $v = preg_replace('/\\\\\//', '/', $v);
  }
  return $v;
}
function unicode_encode($str)
{
  return preg_replace_callback("/\\\\u([0-9a-zA-Z]{4})/", "encode_callback", $str);
}

function encode_callback($matches) {
  return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UTF-16");
}