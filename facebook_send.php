<?php
if(isset($_GET['url'])){
 $url = $_GET['url'];
}else{
 $url = 'http://live3.info';	
} 
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>Facebook Send</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>	
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1&appId=447422225358747";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-send" data-href="<?php echo $url?>" data-colorscheme="dark"></div>

<script>
$(function(){
	$(".fb_iframe_widget iframe").trigger("click");
});

</script>
	
<style>
body{
margin:0;
}
.fb-send{
	width:200px;
}
</style>	
</body>
</html>