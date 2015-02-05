<?php include_once('./common/_header.php') ?>

<form action="" method="POST" class="col_6">
	<input type="text" name="keyword" style="padding: 6px;margin-top: 10px;" placeholder="keywoody "/>
	<input type="submit"class='btn small' value="検索" />
</form>
<div class="col_6">
<marquee direction="left" behavior="alternate" scrollamount="8">1日に100回程度という制限がありんす</marquee>
<marquee direction="right" scrollamount="8">画像をクリックするとurlがでりんす</marquee>
</div>
<div class="cB"></div>
<?php
if(isset($_POST['keyword'])){
	$keyword=$_POST['keyword'];
}else{
	exit;
}	
?>

<!--youtube-->
<div class="col_6">
<?php
set_include_path('/var/www/html/mng/');
require_once('get_youtube.php');

$ytcls = new YoutubeGetter();
$youtube_contents = $ytcls->getYoutube($keyword);	
//var_dump($youtube_contents);

$cnt = 0;
foreach($youtube_contents as $val){
	
	if($cnt%3 == 0){
		echo '<div class="cB"></div><hr/>';
	}
	$id = $val['id'];
	$img_main = $val['thumbs'][0]['url'];	
	echo '<div class="col_4 ">';
	echo '<iframe src="http://www.youtube.com/embed/'.$id.'" frameborder="0" id='.$id.' allowfullscreen></iframe>';
//	echo '<img src="'.$img_main.'" alt="" />';
	echo '<p class="small">'.$id.'</p>';
	echo '</div>';

	$cnt++;	
	
}

?>
</div>

<!--画像-->
<div class="col_6">
<?php

$accountKey = 'GB6arGjKiY3N1EP+iBfp/rLAyggWl7LltHv0uoymteg';            
//$keyword = 'はりねずみ';

$cred = sprintf('Authorization: Basic %s', base64_encode($accountKey . ":" . $accountKey) );

$context = stream_context_create(array(
    'http' => array(
        'header' => $cred
    )
));
//%27Size%3ALarge%27
$response = file_get_contents('https://api.datamarket.azure.com/Bing/Search/v1/Image?$top=50&$format=json&Query='.urlencode( '\'' . $keyword. '\'').'&ImageFilters=%27Aspect%3AWide%27', 0, $context);
//https://api.datamarket.azure.com/Bing/Search/v1/Composite?Sources=%27image%27&Query=%27skrillex%27&ImageFilters=
$response = json_decode($response);
 
 $cnt = 0;
foreach($response->d->results as $val){
//    var_dump($val);
/*
	if($cnt%6 == 0){
		echo '<div class="row">';
	}
*/

	if($cnt%6 == 0){
		echo '<div class="cB"></div><hr/>';
	}
	$img_url = $val->MediaUrl;
	$img_width = $val->Width;
	$img_height = $val->Height;	
	echo '<div class="col_2 ">';
	echo "<img src='$img_url' alt='' />";
//	echo "<p>$img_url</p>";
	echo "<p>$img_width X $img_height</p>";
	echo '</div>';



	$cnt++;
}
?>
<script>
$("img").click(function(){
	alert($(this).attr('src'));
});
</script>
</div>


<?php include_once('./common/_footer.php') ?>
<?php
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
object(stdClass)#11 (11) {
  ["__metadata"]=>
  object(stdClass)#12 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=2&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "3fff5a06-7f9c-451c-8de2-bd07fb75966b"
  ["Title"]=>
  string(13) "竹野内 豊"
  ["MediaUrl"]=>
  string(65) "http://www.inlifeweb.com/data/restakenouchiyutaka/image/photo.jpg"
  ["SourceUrl"]=>
  string(57) "http://www.inlifeweb.com/restakenouchiyutaka_index_e.html"
  ["DisplayUrl"]=>
  string(50) "www.inlifeweb.com/restakenouchiyutaka_index_e.html"
  ["Width"]=>
  string(3) "220"
  ["Height"]=>
  string(3) "297"
  ["FileSize"]=>
  string(5) "15148"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#13 (6) {
    ["__metadata"]=>
    object(stdClass)#14 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608038949706072139&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "220"
    ["Height"]=>
    string(3) "297"
    ["FileSize"]=>
    string(4) "6233"
  }
}
object(stdClass)#15 (11) {
  ["__metadata"]=>
  object(stdClass)#16 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=3&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "76c86aef-ffb3-440e-8a38-8280817cd23e"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(77) "http://www.officiallyjd.com/wp-content/uploads/2011/11/20111130_ikemen_33.jpg"
  ["SourceUrl"]=>
  string(62) "http://www.officiallyjd.com/archives/79200/20111130_ikemen_33/"
  ["DisplayUrl"]=>
  string(54) "www.officiallyjd.com/archives/79200/20111130_ikemen_33"
  ["Width"]=>
  string(3) "304"
  ["Height"]=>
  string(3) "470"
  ["FileSize"]=>
  string(5) "49846"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#17 (6) {
    ["__metadata"]=>
    object(stdClass)#18 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.607998117445438931&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "194"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "8169"
  }
}
object(stdClass)#19 (11) {
  ["__metadata"]=>
  object(stdClass)#20 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=4&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "5e87e22e-cabd-4588-9f49-215b5c729f08"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(77) "http://www.officiallyjd.com/wp-content/uploads/2011/11/20111130_ikemen_35.jpg"
  ["SourceUrl"]=>
  string(62) "http://www.officiallyjd.com/archives/79200/20111130_ikemen_35/"
  ["DisplayUrl"]=>
  string(54) "www.officiallyjd.com/archives/79200/20111130_ikemen_35"
  ["Width"]=>
  string(3) "320"
  ["Height"]=>
  string(3) "458"
  ["FileSize"]=>
  string(5) "19289"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#21 (6) {
    ["__metadata"]=>
    object(stdClass)#22 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608019527854785559&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "209"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "6614"
  }
}
object(stdClass)#23 (11) {
  ["__metadata"]=>
  object(stdClass)#24 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=5&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "1dda7dd4-f811-45d8-a8da-94111b87c4f2"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(80) "http://www.officiallyjd.com/wp-content/uploads/2013/05/20130508_nishijima_20.jpg"
  ["SourceUrl"]=>
  string(66) "http://www.officiallyjd.com/archives/247372/20130508_nishijima_20/"
  ["DisplayUrl"]=>
  string(58) "www.officiallyjd.com/archives/247372/20130508_nishijima_20"
  ["Width"]=>
  string(3) "400"
  ["Height"]=>
  string(3) "268"
  ["FileSize"]=>
  string(5) "35169"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#25 (6) {
    ["__metadata"]=>
    object(stdClass)#26 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.607996751648460565&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "201"
    ["FileSize"]=>
    string(4) "6180"
  }
}
object(stdClass)#27 (11) {
  ["__metadata"]=>
  object(stdClass)#28 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=6&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "76b91c1e-3c8e-4c3f-ad12-534d6b8e0ca6"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(60) "http://blog-imgs-35-origin.fc2.com/i/d/o/idolqvga1/y523h.jpg"
  ["SourceUrl"]=>
  string(50) "http://idolqvga1.blog136.fc2.com/category76-1.html"
  ["DisplayUrl"]=>
  string(43) "idolqvga1.blog136.fc2.com/category76-1.html"
  ["Width"]=>
  string(3) "240"
  ["Height"]=>
  string(3) "320"
  ["FileSize"]=>
  string(5) "19337"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#29 (6) {
    ["__metadata"]=>
    object(stdClass)#30 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608040216716316489&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "225"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7444"
  }
}
object(stdClass)#31 (11) {
  ["__metadata"]=>
  object(stdClass)#32 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=7&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "4f78bab1-27d8-4b75-ad24-c99539a0033c"
  ["Title"]=>
  string(34) "竹野内豊の画像 プリ画像"
  ["MediaUrl"]=>
  string(53) "http://pic.prepics-cdn.com/pib1305988377/9498462.jpeg"
  ["SourceUrl"]=>
  string(46) "http://prcm.jp/album/pib1305988377/pic/9498462"
  ["DisplayUrl"]=>
  string(39) "prcm.jp/album/pib1305988377/pic/9498462"
  ["Width"]=>
  string(3) "480"
  ["Height"]=>
  string(3) "344"
  ["FileSize"]=>
  string(5) "15708"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#33 (6) {
    ["__metadata"]=>
    object(stdClass)#34 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts3.mm.bing.net/th?id=HN.607997026524726490&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "215"
    ["FileSize"]=>
    string(4) "5036"
  }
}
object(stdClass)#35 (11) {
  ["__metadata"]=>
  object(stdClass)#36 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=8&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "ac9a4fa0-3711-4ae2-a6e4-1398eae01c37"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(61) "http://www.tvnaviweb.jp/TVnavi_contents/201012/takenouchi.jpg"
  ["SourceUrl"]=>
  string(62) "http://www.tvnaviweb.jp/TVnavi_contents/201012/takanouchi.html"
  ["DisplayUrl"]=>
  string(55) "www.tvnaviweb.jp/TVnavi_contents/201012/takanouchi.html"
  ["Width"]=>
  string(3) "260"
  ["Height"]=>
  string(3) "372"
  ["FileSize"]=>
  string(5) "34170"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#37 (6) {
    ["__metadata"]=>
    object(stdClass)#38 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.607999470359415389&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "209"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7174"
  }
}
object(stdClass)#39 (11) {
  ["__metadata"]=>
  object(stdClass)#40 (2) {
    ["uri"]=>
    string(126) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=9&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "de70ae5f-27a3-4f5c-82cf-6fc45bae240e"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(81) "http://www.officiallyjd.com/wp-content/uploads/2013/05/20130514_kaneshiro_291.jpg"
  ["SourceUrl"]=>
  string(68) "http://www.officiallyjd.com/archives/249451/20130514_kaneshiro_29-2/"
  ["DisplayUrl"]=>
  string(60) "www.officiallyjd.com/archives/249451/20130514_kaneshiro_29-2"
  ["Width"]=>
  string(3) "286"
  ["Height"]=>
  string(3) "188"
  ["FileSize"]=>
  string(5) "12822"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#41 (6) {
    ["__metadata"]=>
    object(stdClass)#42 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608014068953124805&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "286"
    ["Height"]=>
    string(3) "188"
    ["FileSize"]=>
    string(4) "4252"
  }
}
object(stdClass)#43 (11) {
  ["__metadata"]=>
  object(stdClass)#44 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=10&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "98a5d7da-2cc2-4789-8f1b-10d86c10cab1"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(54) "http://www.tvnaviweb.jp/nihoneiga/vol27/takenouchi.jpg"
  ["SourceUrl"]=>
  string(55) "http://www.tvnaviweb.jp/nihoneiga/vol27/takenouchi.html"
  ["DisplayUrl"]=>
  string(48) "www.tvnaviweb.jp/nihoneiga/vol27/takenouchi.html"
  ["Width"]=>
  string(3) "227"
  ["Height"]=>
  string(3) "340"
  ["FileSize"]=>
  string(5) "53252"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#45 (6) {
    ["__metadata"]=>
    object(stdClass)#46 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.607988780190403444&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "200"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(5) "15736"
  }
}
object(stdClass)#47 (11) {
  ["__metadata"]=>
  object(stdClass)#48 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=11&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "2260e16d-bb99-4355-ab62-530be1b9644d"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(59) "http://blog-imgs-24-origin.fc2.com/i/d/o/idolqvga1/yz8w.jpg"
  ["SourceUrl"]=>
  string(54) "http://idolqvga1.blog136.fc2.com/blog-category-76.html"
  ["DisplayUrl"]=>
  string(47) "idolqvga1.blog136.fc2.com/blog-category-76.html"
  ["Width"]=>
  string(3) "240"
  ["Height"]=>
  string(3) "320"
  ["FileSize"]=>
  string(5) "19431"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#49 (6) {
    ["__metadata"]=>
    object(stdClass)#50 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.607994385116105069&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "225"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "4510"
  }
}
object(stdClass)#51 (11) {
  ["__metadata"]=>
  object(stdClass)#52 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=12&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "5a73e14a-a727-4992-be90-12ae88eb1352"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(60) "http://blog-imgs-55-origin.fc2.com/i/d/o/idolqvga1/z817f.jpg"
  ["SourceUrl"]=>
  string(58) "http://ga-o.net/kw/%E7%AB%B9%E9%87%8E%E5%86%85%E8%B1%8A/3/"
  ["DisplayUrl"]=>
  string(26) "ga-o.net/kw/竹野内豊/3"
  ["Width"]=>
  string(3) "240"
  ["Height"]=>
  string(3) "320"
  ["FileSize"]=>
  string(5) "18417"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#53 (6) {
    ["__metadata"]=>
    object(stdClass)#54 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts3.mm.bing.net/th?id=HN.608049055758680802&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "225"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "4094"
  }
}
object(stdClass)#55 (11) {
  ["__metadata"]=>
  object(stdClass)#56 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=13&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "b963a605-75e1-4bea-b27a-f20a49658fa6"
  ["Title"]=>
  string(34) "想像を絶していました ..."
  ["MediaUrl"]=>
  string(47) "http://cinema.pia.co.jp/img/upload/450/5956.jpg"
  ["SourceUrl"]=>
  string(49) "http://cinema.pia.co.jp/news/154461/41290/?page=3"
  ["DisplayUrl"]=>
  string(42) "cinema.pia.co.jp/news/154461/41290/?page=3"
  ["Width"]=>
  string(3) "450"
  ["Height"]=>
  string(3) "300"
  ["FileSize"]=>
  string(5) "28799"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#57 (6) {
    ["__metadata"]=>
    object(stdClass)#58 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.607993985682506439&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "200"
    ["FileSize"]=>
    string(4) "7303"
  }
}
object(stdClass)#59 (11) {
  ["__metadata"]=>
  object(stdClass)#60 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=14&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "f3e6c1f6-c347-428d-ab72-7269548a93d3"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(74) "http://www.officiallyjd.com/wp-content/uploads/2012/01/20120130_gay_09.jpg"
  ["SourceUrl"]=>
  string(60) "http://www.officiallyjd.com/archives/103651/20120130_gay_09/"
  ["DisplayUrl"]=>
  string(52) "www.officiallyjd.com/archives/103651/20120130_gay_09"
  ["Width"]=>
  string(3) "323"
  ["Height"]=>
  string(3) "450"
  ["FileSize"]=>
  string(5) "15372"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#61 (6) {
    ["__metadata"]=>
    object(stdClass)#62 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608042415736228319&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "215"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "4745"
  }
}
object(stdClass)#63 (11) {
  ["__metadata"]=>
  object(stdClass)#64 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=15&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "0b679eca-6556-4eec-bbf5-570d915af9ce"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(77) "http://www.officiallyjd.com/wp-content/uploads/2013/06/20130634_haiyuu_02.jpg"
  ["SourceUrl"]=>
  string(63) "http://www.officiallyjd.com/archives/261217/20130634_haiyuu_02/"
  ["DisplayUrl"]=>
  string(55) "www.officiallyjd.com/archives/261217/20130634_haiyuu_02"
  ["Width"]=>
  string(3) "316"
  ["Height"]=>
  string(3) "314"
  ["FileSize"]=>
  string(5) "13651"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#65 (6) {
    ["__metadata"]=>
    object(stdClass)#66 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts3.mm.bing.net/th?id=HN.608032588849154994&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "298"
    ["FileSize"]=>
    string(4) "7435"
  }
}
object(stdClass)#67 (11) {
  ["__metadata"]=>
  object(stdClass)#68 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=16&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "7916b432-d25e-43b4-8d74-9a1353859990"
  ["Title"]=>
  string(34) "竹野内豊の画像 プリ画像"
  ["MediaUrl"]=>
  string(51) "http://pic.prepics-cdn.com/pib66924844/4276602.jpeg"
  ["SourceUrl"]=>
  string(44) "http://prcm.jp/album/pib66924844/pic/4276602"
  ["DisplayUrl"]=>
  string(37) "prcm.jp/album/pib66924844/pic/4276602"
  ["Width"]=>
  string(3) "240"
  ["Height"]=>
  string(3) "320"
  ["FileSize"]=>
  string(5) "13824"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#69 (6) {
    ["__metadata"]=>
    object(stdClass)#70 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.607994458128518667&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "225"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7738"
  }
}
object(stdClass)#71 (11) {
  ["__metadata"]=>
  object(stdClass)#72 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=17&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "272ac3cc-904f-4f27-a6df-c776b665dd84"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(77) "http://www.officiallyjd.com/wp-content/uploads/2012/03/20120308_ikemen_41.jpg"
  ["SourceUrl"]=>
  string(63) "http://www.officiallyjd.com/archives/115460/20120308_ikemen_41/"
  ["DisplayUrl"]=>
  string(55) "www.officiallyjd.com/archives/115460/20120308_ikemen_41"
  ["Width"]=>
  string(3) "294"
  ["Height"]=>
  string(3) "450"
  ["FileSize"]=>
  string(6) "116775"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#73 (6) {
    ["__metadata"]=>
    object(stdClass)#74 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts3.mm.bing.net/th?id=HN.608038962589664970&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "196"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7475"
  }
}
object(stdClass)#75 (11) {
  ["__metadata"]=>
  object(stdClass)#76 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=18&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "d04f4800-5d98-4a0b-9b40-458d9145b1b7"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(87) "http://contents.oricon.co.jp/upimg/news/20080208/51850_200802080578817001202469434c.jpg"
  ["SourceUrl"]=>
  string(43) "http://www.oricon.co.jp/news/photo/51850/1/"
  ["DisplayUrl"]=>
  string(35) "www.oricon.co.jp/news/photo/51850/1"
  ["Width"]=>
  string(3) "294"
  ["Height"]=>
  string(3) "470"
  ["FileSize"]=>
  string(5) "53207"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#77 (6) {
    ["__metadata"]=>
    object(stdClass)#78 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.608017900061328740&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "187"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(5) "13605"
  }
}
object(stdClass)#79 (11) {
  ["__metadata"]=>
  object(stdClass)#80 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=19&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "5df586d1-e278-4176-820d-97c2bb4fc5bc"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(75) "http://www.officiallyjd.com/wp-content/uploads/2011/06/20110607_furu_25.jpg"
  ["SourceUrl"]=>
  string(60) "http://www.officiallyjd.com/archives/22664/20110607_furu_25/"
  ["DisplayUrl"]=>
  string(52) "www.officiallyjd.com/archives/22664/20110607_furu_25"
  ["Width"]=>
  string(3) "176"
  ["Height"]=>
  string(3) "226"
  ["FileSize"]=>
  string(5) "11964"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#81 (6) {
    ["__metadata"]=>
    object(stdClass)#82 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.608018578668521332&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "176"
    ["Height"]=>
    string(3) "226"
    ["FileSize"]=>
    string(4) "3390"
  }
}
object(stdClass)#83 (11) {
  ["__metadata"]=>
  object(stdClass)#84 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=20&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "7e344a41-ead3-4ad5-abc2-bd2b02065a10"
  ["Title"]=>
  string(34) "竹野内豊の画像 プリ画像"
  ["MediaUrl"]=>
  string(47) "http://pic.prepics-cdn.com/oddeye/13867928.jpeg"
  ["SourceUrl"]=>
  string(40) "http://prcm.jp/album/oddeye/pic/13867928"
  ["DisplayUrl"]=>
  string(33) "prcm.jp/album/oddeye/pic/13867928"
  ["Width"]=>
  string(3) "240"
  ["Height"]=>
  string(3) "320"
  ["FileSize"]=>
  string(5) "11472"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#85 (6) {
    ["__metadata"]=>
    object(stdClass)#86 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts3.mm.bing.net/th?id=HN.608001458930451722&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "225"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "5735"
  }
}
object(stdClass)#87 (11) {
  ["__metadata"]=>
  object(stdClass)#88 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=21&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "72a8f9a5-d195-45bc-8fc5-f19f2bbce572"
  ["Title"]=>
  string(32) "竹野内豊の画像-p1_6-S : :"
  ["MediaUrl"]=>
  string(64) "http://image.news.livedoor.com/newsimage/4/2/42900_298_okike.jpg"
  ["SourceUrl"]=>
  string(27) "http://gensun.org/wid/69947"
  ["DisplayUrl"]=>
  string(20) "gensun.org/wid/69947"
  ["Width"]=>
  string(3) "300"
  ["Height"]=>
  string(3) "300"
  ["FileSize"]=>
  string(5) "22809"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#89 (6) {
    ["__metadata"]=>
    object(stdClass)#90 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608001617846535033&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7564"
  }
}
object(stdClass)#91 (11) {
  ["__metadata"]=>
  object(stdClass)#92 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=22&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "909a0cd5-3efe-4fc7-a401-dd3efd04dbdb"
  ["Title"]=>
  string(37) "竹野内豊の画像：竹野内 ..."
  ["MediaUrl"]=>
  string(55) "http://images.star-pedia.com/big/2012426/3D5OCTP2I4.jpg"
  ["SourceUrl"]=>
  string(77) "http://jp.star-pedia.com/%E7%AB%B9%E9%87%8E%E5%86%85%E8%B1%8A/showphoto/78650"
  ["DisplayUrl"]=>
  string(46) "jp.star-pedia.com/竹野内豊/showphoto/78650"
  ["Width"]=>
  string(3) "498"
  ["Height"]=>
  string(3) "391"
  ["FileSize"]=>
  string(5) "19732"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#93 (6) {
    ["__metadata"]=>
    object(stdClass)#94 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.608047243285496224&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "235"
    ["FileSize"]=>
    string(4) "6225"
  }
}
object(stdClass)#95 (11) {
  ["__metadata"]=>
  object(stdClass)#96 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=23&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "8bf98c31-19f8-4b52-b4f0-25ac51ae0702"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(80) "http://www.officiallyjd.com/wp-content/uploads/2011/10/20111001_kaneshiro_40.jpg"
  ["SourceUrl"]=>
  string(65) "http://www.officiallyjd.com/archives/59646/20111001_kaneshiro_40/"
  ["DisplayUrl"]=>
  string(57) "www.officiallyjd.com/archives/59646/20111001_kaneshiro_40"
  ["Width"]=>
  string(3) "300"
  ["Height"]=>
  string(3) "450"
  ["FileSize"]=>
  string(5) "55415"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#97 (6) {
    ["__metadata"]=>
    object(stdClass)#98 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.607986907581385752&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "200"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "9024"
  }
}
object(stdClass)#99 (11) {
  ["__metadata"]=>
  object(stdClass)#100 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=24&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "ff0b501e-29a5-4244-9cc9-4623ebd67043"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(60) "http://blog-imgs-45-origin.fc2.com/i/d/o/idolqvga1/xz21l.jpg"
  ["SourceUrl"]=>
  string(52) "http://idolqvga1.blog136.fc2.com/blog-entry-103.html"
  ["DisplayUrl"]=>
  string(45) "idolqvga1.blog136.fc2.com/blog-entry-103.html"
  ["Width"]=>
  string(3) "240"
  ["Height"]=>
  string(3) "320"
  ["FileSize"]=>
  string(5) "19877"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#101 (6) {
    ["__metadata"]=>
    object(stdClass)#102 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608054321390684527&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "225"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "8386"
  }
}
object(stdClass)#103 (11) {
  ["__metadata"]=>
  object(stdClass)#104 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=25&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "c62233b7-30e0-485a-b694-e42d01d939b8"
  ["Title"]=>
  string(34) "竹野内豊の画像 プリ画像"
  ["MediaUrl"]=>
  string(53) "http://pic.prepics-cdn.com/pib1301756493/5291487.jpeg"
  ["SourceUrl"]=>
  string(46) "http://prcm.jp/album/pib1301756493/pic/5291487"
  ["DisplayUrl"]=>
  string(39) "prcm.jp/album/pib1301756493/pic/5291487"
  ["Width"]=>
  string(3) "240"
  ["Height"]=>
  string(3) "320"
  ["FileSize"]=>
  string(5) "10088"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#105 (6) {
    ["__metadata"]=>
    object(stdClass)#106 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608036033414303211&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "225"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "5987"
  }
}
object(stdClass)#107 (11) {
  ["__metadata"]=>
  object(stdClass)#108 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=26&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "6fefc9b9-8599-429a-8526-61af3fc6371d"
  ["Title"]=>
  string(11) "LOVE LETTER"
  ["MediaUrl"]=>
  string(97) "http://stat.ameba.jp/user_images/20100731/23/realstones/0c/e0/j/t02200278_0226028610668704419.jpg"
  ["SourceUrl"]=>
  string(50) "http://ameblo.jp/realstones/entry-10606566969.html"
  ["DisplayUrl"]=>
  string(43) "ameblo.jp/realstones/entry-10606566969.html"
  ["Width"]=>
  string(3) "220"
  ["Height"]=>
  string(3) "278"
  ["FileSize"]=>
  string(4) "7129"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#109 (6) {
    ["__metadata"]=>
    object(stdClass)#110 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608043588263085941&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "220"
    ["Height"]=>
    string(3) "278"
    ["FileSize"]=>
    string(4) "4078"
  }
}
object(stdClass)#111 (11) {
  ["__metadata"]=>
  object(stdClass)#112 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=27&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "26081547-ef8f-4ca9-802a-eee56c935cf1"
  ["Title"]=>
  string(26) "INTERVIEW564 竹野内 豊"
  ["MediaUrl"]=>
  string(38) "http://dogatch.jp/interview/564/04.jpg"
  ["SourceUrl"]=>
  string(39) "http://dogatch.jp/interview/564/04.html"
  ["DisplayUrl"]=>
  string(32) "dogatch.jp/interview/564/04.html"
  ["Width"]=>
  string(3) "317"
  ["Height"]=>
  string(3) "215"
  ["FileSize"]=>
  string(5) "15419"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#113 (6) {
    ["__metadata"]=>
    object(stdClass)#114 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts3.mm.bing.net/th?id=HN.608001845482818098&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "203"
    ["FileSize"]=>
    string(4) "4721"
  }
}
object(stdClass)#115 (11) {
  ["__metadata"]=>
  object(stdClass)#116 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=28&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "70c45ae7-7a42-4e26-8ca8-340d5d7d8c4d"
  ["Title"]=>
  string(34) "竹野内豊の画像 プリ画像"
  ["MediaUrl"]=>
  string(44) "http://pic.prepics-cdn.com/8880/7295425.jpeg"
  ["SourceUrl"]=>
  string(37) "http://prcm.jp/album/8880/pic/7295425"
  ["DisplayUrl"]=>
  string(30) "prcm.jp/album/8880/pic/7295425"
  ["Width"]=>
  string(3) "480"
  ["Height"]=>
  string(3) "400"
  ["FileSize"]=>
  string(5) "13857"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#117 (6) {
    ["__metadata"]=>
    object(stdClass)#118 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608004929265863547&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "250"
    ["FileSize"]=>
    string(4) "3882"
  }
}
object(stdClass)#119 (11) {
  ["__metadata"]=>
  object(stdClass)#120 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=29&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "358a0678-e353-45c4-9747-9cff663752bb"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(64) "http://boukenka.info/wp-content/uploads/2014/01/竹野内豊.jpg"
  ["SourceUrl"]=>
  string(30) "http://boukenka.info/post-778/"
  ["DisplayUrl"]=>
  string(22) "boukenka.info/post-778"
  ["Width"]=>
  string(3) "292"
  ["Height"]=>
  string(3) "285"
  ["FileSize"]=>
  string(5) "22330"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#121 (6) {
    ["__metadata"]=>
    object(stdClass)#122 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608031051252042421&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "292"
    ["Height"]=>
    string(3) "285"
    ["FileSize"]=>
    string(4) "6604"
  }
}
object(stdClass)#123 (11) {
  ["__metadata"]=>
  object(stdClass)#124 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=30&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "5110b4b1-ef70-4a21-801a-de1c7f4c0fe1"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(82) "http://mitu11.info/wp-content/uploads/2014/01/946a2b1318714b42c60aa22ab68aa6a3.jpg"
  ["SourceUrl"]=>
  string(218) "http://mitu11.info/%e7%ab%b9%e9%87%8e%e5%86%85%e8%b1%8a%e3%81%ae%e5%bd%bc%e5%a5%b3%e3%81%af%e3%83%96%e3%83%ac%e3%83%b3%e3%83%80%e3%80%90%e7%94%bb%e5%83%8f%e3%80%91%ef%bc%9f%e5%92%8c%e4%b9%85%e4%ba%95%e6%98%a0%e8%a6%8b/"
  ["DisplayUrl"]=>
  string(60) "mitu11.info/竹野内豊の彼女はブレンダ【画像..."
  ["Width"]=>
  string(3) "222"
  ["Height"]=>
  string(3) "287"
  ["FileSize"]=>
  string(5) "13611"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#125 (6) {
    ["__metadata"]=>
    object(stdClass)#126 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608005814024078007&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "222"
    ["Height"]=>
    string(3) "287"
    ["FileSize"]=>
    string(4) "5419"
  }
}
object(stdClass)#127 (11) {
  ["__metadata"]=>
  object(stdClass)#128 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=31&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "151a2bfd-6c9c-400d-bf57-b1374a60b423"
  ["Title"]=>
  string(7) "画像1"
  ["MediaUrl"]=>
  string(61) "http://livedoor.blogimg.jp/misutiru7878/imgs/c/6/c64e4a53.jpg"
  ["SourceUrl"]=>
  string(59) "http://blog.livedoor.jp/misutiru7878/archives/25444503.html"
  ["DisplayUrl"]=>
  string(52) "blog.livedoor.jp/misutiru7878/archives/25444503.html"
  ["Width"]=>
  string(3) "219"
  ["Height"]=>
  string(3) "300"
  ["FileSize"]=>
  string(5) "12765"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#129 (6) {
    ["__metadata"]=>
    object(stdClass)#130 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608029805718209189&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "219"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7595"
  }
}
object(stdClass)#131 (11) {
  ["__metadata"]=>
  object(stdClass)#132 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=32&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "473e56a0-e2fc-479b-8f4b-fa23c41edb84"
  ["Title"]=>
  string(37) "竹野内豊の画像：竹野内 ..."
  ["MediaUrl"]=>
  string(55) "http://images.star-pedia.com/big/2012831/SLMC373T2W.jpg"
  ["SourceUrl"]=>
  string(54) "http://jp.star-pedia.com/竹野内豊/showphoto/237691"
  ["DisplayUrl"]=>
  string(47) "jp.star-pedia.com/竹野内豊/showphoto/237691"
  ["Width"]=>
  string(3) "361"
  ["Height"]=>
  string(3) "495"
  ["FileSize"]=>
  string(6) "125733"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#133 (6) {
    ["__metadata"]=>
    object(stdClass)#134 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.608013536377701656&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "218"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "8682"
  }
}
object(stdClass)#135 (11) {
  ["__metadata"]=>
  object(stdClass)#136 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=33&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "cf2de132-c043-4bcb-86d1-bd43ce3f688c"
  ["Title"]=>
  string(36) "竹野内豊の画像：竹野内丰"
  ["MediaUrl"]=>
  string(54) "http://images.star-pedia.com/big/201251/7AAK6HHJSK.jpg"
  ["SourceUrl"]=>
  string(77) "http://jp.star-pedia.com/%E7%AB%B9%E9%87%8E%E5%86%85%E8%B1%8A/showphoto/87823"
  ["DisplayUrl"]=>
  string(46) "jp.star-pedia.com/竹野内豊/showphoto/87823"
  ["Width"]=>
  string(3) "464"
  ["Height"]=>
  string(3) "344"
  ["FileSize"]=>
  string(5) "22980"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#137 (6) {
    ["__metadata"]=>
    object(stdClass)#138 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608041410713552205&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "222"
    ["FileSize"]=>
    string(4) "5412"
  }
}
object(stdClass)#139 (11) {
  ["__metadata"]=>
  object(stdClass)#140 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=34&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "9076d7b2-1e23-43c2-8460-4332902ea288"
  ["Title"]=>
  string(13) "前へ 次へ"
  ["MediaUrl"]=>
  string(48) "http://bbs3.aimix-z.com/gbbsimg/yukiyott/217.jpg"
  ["SourceUrl"]=>
  string(67) "http://matome.naver.jp/odai/2133398154539170801/2133398225539282903"
  ["DisplayUrl"]=>
  string(60) "matome.naver.jp/odai/2133398154539170801/2133398225539282903"
  ["Width"]=>
  string(3) "300"
  ["Height"]=>
  string(3) "414"
  ["FileSize"]=>
  string(5) "27239"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#141 (6) {
    ["__metadata"]=>
    object(stdClass)#142 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.607993384388658855&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "217"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7390"
  }
}
object(stdClass)#143 (11) {
  ["__metadata"]=>
  object(stdClass)#144 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=35&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "ec022142-ced4-4f3e-befa-624998ad2bc3"
  ["Title"]=>
  string(30) "画像 サイズ 調整 回転"
  ["MediaUrl"]=>
  string(59) "http://pic.prepics-cdn.com/pib66924844/4276601_218x291.jpeg"
  ["SourceUrl"]=>
  string(44) "http://prcm.jp/album/pib66924844/pic/4276601"
  ["DisplayUrl"]=>
  string(37) "prcm.jp/album/pib66924844/pic/4276601"
  ["Width"]=>
  string(3) "218"
  ["Height"]=>
  string(3) "291"
  ["FileSize"]=>
  string(5) "12572"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#145 (6) {
    ["__metadata"]=>
    object(stdClass)#146 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608051422284746313&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "218"
    ["Height"]=>
    string(3) "291"
    ["FileSize"]=>
    string(4) "8420"
  }
}
object(stdClass)#147 (11) {
  ["__metadata"]=>
  object(stdClass)#148 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=36&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "87a070a3-24d5-4829-9415-06bbf77e8c63"
  ["Title"]=>
  string(35) "もう10年以上前に25歳の ..."
  ["MediaUrl"]=>
  string(91) "http://stat.ameba.jp/user_images/20120303/17/skylove244866/74/85/j/o0465046511829473325.jpg"
  ["SourceUrl"]=>
  string(53) "http://ameblo.jp/skylove244866/entry-11181834558.html"
  ["DisplayUrl"]=>
  string(46) "ameblo.jp/skylove244866/entry-11181834558.html"
  ["Width"]=>
  string(3) "465"
  ["Height"]=>
  string(3) "465"
  ["FileSize"]=>
  string(6) "151098"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#149 (6) {
    ["__metadata"]=>
    object(stdClass)#150 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608053582652310425&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(5) "10445"
  }
}
object(stdClass)#151 (11) {
  ["__metadata"]=>
  object(stdClass)#152 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=37&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "f2b8d496-f3cf-46dd-806c-c4eea9bdb9f6"
  ["Title"]=>
  string(11) "LOVE LETTER"
  ["MediaUrl"]=>
  string(97) "http://stat.ameba.jp/user_images/20100731/23/realstones/97/ac/j/t02200316_0276039710668704409.jpg"
  ["SourceUrl"]=>
  string(50) "http://ameblo.jp/realstones/entry-10606566969.html"
  ["DisplayUrl"]=>
  string(43) "ameblo.jp/realstones/entry-10606566969.html"
  ["Width"]=>
  string(3) "220"
  ["Height"]=>
  string(3) "316"
  ["FileSize"]=>
  string(5) "13226"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#153 (6) {
    ["__metadata"]=>
    object(stdClass)#154 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608050181040243153&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "208"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "4769"
  }
}
object(stdClass)#155 (11) {
  ["__metadata"]=>
  object(stdClass)#156 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=38&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "d4646622-b4aa-437a-8c7e-8f2ff436acf8"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(50) "http://cinema-magazine.com/image/blog/taiheiyo.jpg"
  ["SourceUrl"]=>
  string(36) "http://cinema-magazine.com/news/2242"
  ["DisplayUrl"]=>
  string(29) "cinema-magazine.com/news/2242"
  ["Width"]=>
  string(3) "300"
  ["Height"]=>
  string(3) "329"
  ["FileSize"]=>
  string(5) "39546"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#157 (6) {
    ["__metadata"]=>
    object(stdClass)#158 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608035513722933119&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "273"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(5) "11495"
  }
}
object(stdClass)#159 (11) {
  ["__metadata"]=>
  object(stdClass)#160 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=39&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "07ae6376-8640-451b-b547-d23f399f46f8"
  ["Title"]=>
  string(27) "俳優： 竹野内 豊 氏"
  ["MediaUrl"]=>
  string(78) "http://blogimg.goo.ne.jp/user_image/7f/ed/f24d46012efdf075353c9544e7a45e9d.jpg"
  ["SourceUrl"]=>
  string(67) "http://blog.goo.ne.jp/akirariran/e/4d557fe05bb9be68466512204ecea730"
  ["DisplayUrl"]=>
  string(60) "blog.goo.ne.jp/akirariran/e/4d557fe05bb9be68466512204ecea730"
  ["Width"]=>
  string(3) "176"
  ["Height"]=>
  string(3) "226"
  ["FileSize"]=>
  string(5) "35446"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#161 (6) {
    ["__metadata"]=>
    object(stdClass)#162 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.607988393636203956&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "176"
    ["Height"]=>
    string(3) "226"
    ["FileSize"]=>
    string(4) "3877"
  }
}
object(stdClass)#163 (11) {
  ["__metadata"]=>
  object(stdClass)#164 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=40&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "486f421d-4d38-4e1e-8bc4-2603a8ea1642"
  ["Title"]=>
  string(26) "INTERVIEW564 竹野内 豊"
  ["MediaUrl"]=>
  string(38) "http://dogatch.jp/interview/564/03.jpg"
  ["SourceUrl"]=>
  string(39) "http://dogatch.jp/interview/564/03.html"
  ["DisplayUrl"]=>
  string(32) "dogatch.jp/interview/564/03.html"
  ["Width"]=>
  string(3) "317"
  ["Height"]=>
  string(3) "215"
  ["FileSize"]=>
  string(5) "16989"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#165 (6) {
    ["__metadata"]=>
    object(stdClass)#166 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608002524084438183&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "203"
    ["FileSize"]=>
    string(4) "4889"
  }
}
object(stdClass)#167 (11) {
  ["__metadata"]=>
  object(stdClass)#168 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=41&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "df63df3a-0bb4-4e52-9816-50cef066c6e4"
  ["Title"]=>
  string(34) "竹野内豊の画像 プリ画像"
  ["MediaUrl"]=>
  string(44) "http://pic.prepics-cdn.com/8880/7258733.jpeg"
  ["SourceUrl"]=>
  string(37) "http://prcm.jp/album/8880/pic/7258733"
  ["DisplayUrl"]=>
  string(30) "prcm.jp/album/8880/pic/7258733"
  ["Width"]=>
  string(3) "230"
  ["Height"]=>
  string(3) "244"
  ["FileSize"]=>
  string(5) "10785"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#169 (6) {
    ["__metadata"]=>
    object(stdClass)#170 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608024518610652989&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "230"
    ["Height"]=>
    string(3) "244"
    ["FileSize"]=>
    string(4) "6736"
  }
}
object(stdClass)#171 (11) {
  ["__metadata"]=>
  object(stdClass)#172 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=42&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "9002ef69-d056-4c2f-9da6-bb350bafd783"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(44) "http://pic.prepics-cdn.com/8880/9080464.jpeg"
  ["SourceUrl"]=>
  string(37) "http://prcm.jp/album/8880/pic/9080464"
  ["DisplayUrl"]=>
  string(30) "prcm.jp/album/8880/pic/9080464"
  ["Width"]=>
  string(3) "190"
  ["Height"]=>
  string(3) "317"
  ["FileSize"]=>
  string(5) "12819"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#173 (6) {
    ["__metadata"]=>
    object(stdClass)#174 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts3.mm.bing.net/th?id=HN.608001351556334634&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "179"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "7809"
  }
}
object(stdClass)#175 (11) {
  ["__metadata"]=>
  object(stdClass)#176 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=43&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "caeff45b-0f47-4dd5-b655-c6c11dadde27"
  ["Title"]=>
  string(36) "竹野内豊の画像：竹野内丰"
  ["MediaUrl"]=>
  string(54) "http://images.star-pedia.com/big/201283/KIHOFN8Z1T.jpg"
  ["SourceUrl"]=>
  string(78) "http://jp.star-pedia.com/%E7%AB%B9%E9%87%8E%E5%86%85%E8%B1%8A/showphoto/190782"
  ["DisplayUrl"]=>
  string(47) "jp.star-pedia.com/竹野内豊/showphoto/190782"
  ["Width"]=>
  string(3) "464"
  ["Height"]=>
  string(3) "344"
  ["FileSize"]=>
  string(5) "37573"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#177 (6) {
    ["__metadata"]=>
    object(stdClass)#178 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.608016207847033072&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "222"
    ["FileSize"]=>
    string(4) "6583"
  }
}
object(stdClass)#179 (11) {
  ["__metadata"]=>
  object(stdClass)#180 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=44&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "af459e27-ce22-4031-9aec-587d57a19e52"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(79) "http://www.officiallyjd.com/wp-content/uploads/2013/08/20130809_kiritani_22.jpg"
  ["SourceUrl"]=>
  string(65) "http://www.officiallyjd.com/archives/275410/20130809_kiritani_22/"
  ["DisplayUrl"]=>
  string(57) "www.officiallyjd.com/archives/275410/20130809_kiritani_22"
  ["Width"]=>
  string(3) "194"
  ["Height"]=>
  string(3) "253"
  ["FileSize"]=>
  string(5) "63334"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#181 (6) {
    ["__metadata"]=>
    object(stdClass)#182 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.608003584937037140&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "194"
    ["Height"]=>
    string(3) "253"
    ["FileSize"]=>
    string(4) "6793"
  }
}
object(stdClass)#183 (11) {
  ["__metadata"]=>
  object(stdClass)#184 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=45&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "d3efd3ce-c42d-4b44-882a-9b930fa153c9"
  ["Title"]=>
  string(36) "竹野内豊の画像：竹野内丰"
  ["MediaUrl"]=>
  string(55) "http://images.star-pedia.com/big/2012821/V7010JAQCU.jpg"
  ["SourceUrl"]=>
  string(54) "http://jp.star-pedia.com/竹野内豊/showphoto/222175"
  ["DisplayUrl"]=>
  string(47) "jp.star-pedia.com/竹野内豊/showphoto/222175"
  ["Width"]=>
  string(3) "370"
  ["Height"]=>
  string(3) "467"
  ["FileSize"]=>
  string(5) "50678"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#185 (6) {
    ["__metadata"]=>
    object(stdClass)#186 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts2.mm.bing.net/th?id=HN.608029749880360601&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "237"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "8158"
  }
}
object(stdClass)#187 (11) {
  ["__metadata"]=>
  object(stdClass)#188 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=46&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "254e2be5-1537-4b33-9b05-73466dc6048d"
  ["Title"]=>
  string(19) "研音 竹野内豊"
  ["MediaUrl"]=>
  string(84) "http://a1.smlycdn.com/data/product2/1/9fd0bf0eebc53490995fe1bdb06a6a6521ad2d79_m.jpg"
  ["SourceUrl"]=>
  string(27) "http://sumally.com/p/524343"
  ["DisplayUrl"]=>
  string(20) "sumally.com/p/524343"
  ["Width"]=>
  string(3) "450"
  ["Height"]=>
  string(3) "303"
  ["FileSize"]=>
  string(5) "25272"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#189 (6) {
    ["__metadata"]=>
    object(stdClass)#190 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts1.mm.bing.net/th?id=HN.607992826044155176&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "202"
    ["FileSize"]=>
    string(4) "5337"
  }
}
object(stdClass)#191 (11) {
  ["__metadata"]=>
  object(stdClass)#192 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=47&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "ee85e611-9546-43b7-a593-5fe138627e00"
  ["Title"]=>
  string(34) "竹野内豊の画像 プリ画像"
  ["MediaUrl"]=>
  string(44) "http://pic.prepics-cdn.com/8880/7258796.jpeg"
  ["SourceUrl"]=>
  string(37) "http://prcm.jp/album/8880/pic/7258796"
  ["DisplayUrl"]=>
  string(30) "prcm.jp/album/8880/pic/7258796"
  ["Width"]=>
  string(3) "230"
  ["Height"]=>
  string(3) "316"
  ["FileSize"]=>
  string(5) "12282"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#193 (6) {
    ["__metadata"]=>
    object(stdClass)#194 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608031510818916755&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "218"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "6602"
  }
}
object(stdClass)#195 (11) {
  ["__metadata"]=>
  object(stdClass)#196 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=48&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "40b30776-ab6d-4261-88c6-2c458610921b"
  ["Title"]=>
  string(34) "竹野内豊の画像 プリ画像"
  ["MediaUrl"]=>
  string(44) "http://pic.prepics-cdn.com/8880/7250662.jpeg"
  ["SourceUrl"]=>
  string(37) "http://prcm.jp/album/8880/pic/7250662"
  ["DisplayUrl"]=>
  string(30) "prcm.jp/album/8880/pic/7250662"
  ["Width"]=>
  string(3) "230"
  ["Height"]=>
  string(3) "330"
  ["FileSize"]=>
  string(4) "9876"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#197 (6) {
    ["__metadata"]=>
    object(stdClass)#198 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608013613684754055&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "209"
    ["Height"]=>
    string(3) "300"
    ["FileSize"]=>
    string(4) "5738"
  }
}
object(stdClass)#199 (11) {
  ["__metadata"]=>
  object(stdClass)#200 (2) {
    ["uri"]=>
    string(127) "https://api.datamarket.azure.com/Data.ashx/Bing/Search/v1/Image?Query='竹ノ内豊'&ImageFilters='Size:Medium'&$skip=49&$top=1"
    ["type"]=>
    string(11) "ImageResult"
  }
  ["ID"]=>
  string(36) "0bd31a18-cba6-4569-8cc5-fb67840f959f"
  ["Title"]=>
  string(12) "竹野内豊"
  ["MediaUrl"]=>
  string(92) "http://shino1977.com/wp-content/uploads/2014/02/946a2b1318714b42c60aa22ab68aa6a3-300x231.jpg"
  ["SourceUrl"]=>
  string(28) "http://shino1977.com/?p=2300"
  ["DisplayUrl"]=>
  string(21) "shino1977.com/?p=2300"
  ["Width"]=>
  string(3) "300"
  ["Height"]=>
  string(3) "231"
  ["FileSize"]=>
  string(5) "13609"
  ["ContentType"]=>
  string(10) "image/jpeg"
  ["Thumbnail"]=>
  object(stdClass)#201 (6) {
    ["__metadata"]=>
    object(stdClass)#202 (1) {
      ["type"]=>
      string(14) "Bing.Thumbnail"
    }
    ["MediaUrl"]=>
    string(59) "http://ts4.mm.bing.net/th?id=HN.608004886311406247&pid=15.1"
    ["ContentType"]=>
    string(9) "image/jpg"
    ["Width"]=>
    string(3) "300"
    ["Height"]=>
    string(3) "231"
    ["FileSize"]=>
    string(4) "4772"
  }
}
*/