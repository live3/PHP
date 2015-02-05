<?php 
if(isset($_GET['video_id'])){
$video_id = $_GET['video_id'];
}else{
$video_id = 'u1zgFlCw8Aw';	
}
if(isset($_GET['deviceToken'])){
$device_token = $_GET['deviceToken'];
}else{
$device_token = '';	
} 
if(isset($_GET['uuid'])){
$uuid = $_GET['uuid'];
}else{
$uuid = '';
}
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />	
		<meta name='viewport' content='width=device-width'>	
		<script type='text/javascript' src='http://www.youtube.com/iframe_api'></script>
		<style>body{margin:0;background: black;padding-top: 10px;}</style>
	</head>
	<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42716969-14', 'live3.info');
  ga('send', 'pageview');

</script>	
	<div id="player"></div>
<!-- <iframe id="player" width="320" height="240"src="http://www.youtube.com/embed/<?php echo $video_id ?>?playsinline=1" frameborder="0" ></iframe> -->
<script>
var tag = document.createElement('script');
tag.src = "http://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
var player;
function onYouTubeIframeAPIReady() {
player = new YT.Player('player', {
  height: '225',
  width: '320',
  videoId: '<?php echo $video_id ?>',
  vq: 'small',
  playerVars: { 'playsinline': 1, 'controls': 2, 'autohide': 1, 'enablejsapi':1, 'showinfo':0,'rel':0,'controls':2,'origin':'http://util.live3.info' },
  events: {
//    'onReady': onPlayerReady,
    'onStateChange': onPlayerStateChange
  }
});

}
function onPlayerReady(event) {
event.target.cueVideoById("<?php echo $video_id ?>", 0, "medium");
event.target.playVideo();
}
var done = false;
function onPlayerStateChange(event) {
if (event.data == YT.PlayerState.PLAYING && !done) {
  window.location.href = "playing:anything";
  done = true;
  console.log("start");
  ga('send','event', 'Videos', 'playing', '<?php echo $video_id ?>');           
}
if (event.data == YT.PlayerState.BUFFERING ) {
  window.location.href = "buffering:anything";    
  console.log("buf");
  ga('send','event', 'Videos', 'buffering', '<?php echo $video_id ?>');
}
if (event.data == YT.PlayerState.PAUSED) {
  window.location.href = "paused:anything";
  ga('send','event', 'Videos', 'paused', '<?php echo $video_id ?>');
};
if (event.data == YT.PlayerState.CUED) {
  window.location.href = "cued:anything";
  ga('send','event', 'Videos', 'cued', '<?php echo $video_id ?>');  
};
if (event.data == YT.PlayerState.ENDED) {
  window.location.href = "ended:anything";   
  ga('send','event', 'Videos', 'ended', '<?php echo $video_id ?>');         
};
if (event.data == -1 ) {
  window.location.href = "unstarted:anything";        
  ga('send','event', 'Videos', 'unstarted', '<?php echo $video_id ?>');         
};        

}
function stopVideo() {
player.stopVideo();
}

ga('set', 'dimension1', '<?php echo $video_id ?>');
ga('set', 'dimension2', '<?php echo $device_token ?>');
ga('set', 'dimension3', '<?php echo $uuid ?>');
</script>


	</body>
</html>