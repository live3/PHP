<?php
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
        $query->maxResults = 10;
        $query->orderBy ='relevance_lang_ja';// 'viewCount';
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