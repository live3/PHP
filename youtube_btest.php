<?php 
if(isset($_GET['video_id'])){
$video_id = $_GET['video_id'];
}else{
$video_id = 'unXogSLj8oQ';	
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
if(isset($_GET['playlists'])){
$playlists = $_GET['playlists'];
}else{
$playlists = '';
}

$playlists = 'cvj3-MZO9Tw,tjuVxnFVXy4,wZFlfMyy4Tk';
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
<iframe id="player" width="320" height="240"src="http://www.youtube.com/embed/<?php echo $video_id ?>?playsinline=1" frameborder="0" ></iframe>
	</body>
</html>	