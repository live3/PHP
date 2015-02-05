<?php
header("Access-Control-Allow-Origin:http://live3.info");
//header("Access-Control-Allow-Origin:http://live3.info:3000");
header('Content-Type: application/json');
set_include_path('/var/www/html/mng/');

if(isset($_POST['keyword'])){
	$keyword=$_POST['keyword'];
}else{
	exit;
}

$ytcls = new YoutubeGetter();
$youtube_contents = $ytcls->getYoutube($keyword);

$cnt = 0;
$json_array=array();

foreach($youtube_contents as $val){

	$id = $val['id'];
	$img_main = $val['thumbs'][0]['url'];	
	
	$json_array[] = array('yt_id'=>$id,'img'=> $img_main);	


	$cnt++;	
	
}
echo json_encode($json_array);

exit;


//$title = '書籍の名前など';
//$ytcls = new YoutubeGetter();
//$youtube_contents = $ytcls->getYoutube($title);
class YoutubeGetter{
    function getYoutube($title){
        require_once'Zend/Gdata/YouTube.php';

        $yt = new Zend_Gdata_YouTube();
        $query = $yt->newVideoQuery();
        $query->videoQuery = $title;
        $query->startIndex = 1;
        $query->maxResults = 20;
        $query->orderBy ='viewCount';//'relevance_lang_ja';// 'viewCount';
        $videoFeed = $yt->getVideoFeed($query);

		$youtube_contents = array();
        if(!empty($videoFeed)){
            foreach ($videoFeed as $videoEntry) {
                $url =  $videoEntry->id;
                $title = $videoEntry->getVideoTitle();
                $descri = $videoEntry->getVideoDescription();
                $thumbs = $videoEntry->getVideoThumbnails();
                preg_match('%^([a-z][a-z0-9+\-.]*:(//[^/?#]+)?)?(/[a-z0-9\-._~\%\!$&\'()*+,;=:@]*)*%i', $url, $res);
                $youtube_id = str_replace('/', '', $res[3]);				
			 	$youtube_contents[] = array('id'=>$youtube_id,'title'=>$title,'description'=>$descri,'thumbs'=>$thumbs );
			}

            return $youtube_contents;
        }else{
            return false;
        }
    }
}
