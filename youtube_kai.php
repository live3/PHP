
<?php 
if(isset($_GET['video_id'])){
 $video_id = $_GET['video_id'];
}else{
 $video_id = 'u1zgFlCw8Aw';	
} 
?>
<html>
	<head>
		<meta name='viewport' content='width=device-width'>
		<style>
			body{
				margin:0;
			}
		</style>	
	</head>
	<body>
		<iframe width="320" height="240" src="http://www.youtube.com/embed/<?php echo $video_id ?>?vq=small&playsinline=1&controls=1&theme=light" frameborder="0" allowfullscreen></iframe>
	</body>
</html>
