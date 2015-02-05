<?php
date_default_timezone_set('Asia/Tokyo');
$live_array[] =  array();
$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}
//$result = mysql_query('SHOW TABLES');while ($row = mysql_fetch_assoc($result)) {var_dump($row);}exit;
//$result = mysql_query('SELECT * from lives');

/*
if(isset($_GET["show"])){
	if($_GET["show"] == 0 ){
		$query = $query." where l.deleted_at_flg = '0'";
	}else if($_GET["show"] == 1 ){
		$query = $query." where l.deleted_at_flg = '1'";		
	}
}
*/
$result = mysql_query('select l.id,l.live_title_ja,l.live_start_date,l.deleted_at_flg,count(lms.id), l.house_id from lives l left join live_media_sources lms on l.id = lms.live_id group by l.id order by l.live_start_date');

while ($row = mysql_fetch_assoc($result)) {
	$live_array[]= $row;
/*
	if (array_key_exists($row['live_id'], $live_array)) {
		array_push($live_array[$row['live_id']]["medias"],array('type'=> $row['live_media_type'],'media'=>$row['live_media_source_material'] ,'seq'=>$row['live_media_source_seq_no'] ));
	}else{
		$live_array[$row['live_id']] =  $row;
		$live_array[$row['live_id']]["medias"]=array(array('type'=> $row['live_media_type'],'media'=>$row['live_media_source_material'] ,'seq'=>$row['live_media_source_seq_no'] ));
	}
*/
}

mysql_close($link);
?>
<?php include_once("./common/_header.php")?>

<!--
<div id="control_sort" class="controls col-md-4">
    <h5>Sort</h5>
    <div class="btn-group">
        <button class="sort btn btn-default" id="price_0" data-sort="data-price" data-order="desc" type="button">Price <span class="glyphicon  glyphicon-circle-arrow-down"></span></button>
        <button class="sort btn btn-default " style="display:none;" id="price_1" data-sort="data-price" data-order="asc" type="button" >Price <span class="glyphicon  glyphicon-circle-arrow-up"></span></button>
        <button class="sort btn btn-default" id="quantity_0" data-sort="data-quantity" data-order="desc" type="button" >Quantity <span class="glyphicon  glyphicon-circle-arrow-down"></span></button>
        <button class="sort btn btn-default " style="display:none;" id="quantity_1" data-sort="data-quantity" data-order="asc" type="button" >Quantity <span class="glyphicon  glyphicon-circle-arrow-up"></span></button>
    </div>
</div>
-->

<div >
    <h5>Filter</h5>
    <div class="btn-group">
        <button class="filter btn btn-default" type="button" data-filter="all">Show All</button>
        <button class="filter btn btn-default" type="button" data-filter=".old_data_0_pub_1" id="future_off_btn">future & off</button>
        <button class="filter btn btn-default" type="button" data-filter=".old_data_0" id="future_btn">show future</button>
        <button class="filter btn btn-default" type="button" data-filter=".old_data_1">show old</button>
        <button class="filter btn btn-default" type="button" data-filter=".pub_0">show On</button>
        <button class="filter btn btn-default" type="button" data-filter=".pub_1">show Off</button>
        <button class="filter btn btn-default" type="button" data-filter=".media_c_0">media 0</button>
		<button id="ToggleLayout" class="btn toggle-layout">change</button>
    </div>
</div>

<script src="./assets/js/jquery.mixitup.js" type="text/javascript"></script>
<script>
$(function(){
	$('#Grid').mixItUp();
	$("#ToggleLayout").toggle(function(){
		$('#Grid ul li').css('float','none');		
		$('#Grid ul li.last_li').css('float','none');
		$('#Grid ul li').css('margin-left','0px');
		$('#Grid ul').css('width','12%');
	},function(){
		$('#Grid ul li').css('float','left');
		$('#Grid ul li.last_li').css('float','right');
		$('#Grid ul li').css('margin-left','10px');
		$('#Grid ul').css('width','100%');	
	});
});
</script>

<script>
$(function(){
var list_tools = '';//'<li><a href="./list.php">All</a></li><li><a href="./list.php?show=0">showOn</a></li><li><a href="./ist.php?show=1">showOff</a></li><li><input type="text" placeholder="search" style="width:100px;" /><button id="list_search" class="btn fR mT0 small">検索</button></li>';
$("#header_tools").append(list_tools);
});
</script>
<!--
<div class="pagination">
  <ul>
    <li><a href="#">Prev</a></li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li><a href="#">Next</a></li>
  </ul>
</div>
-->

<!--
<table class="table table-bordered" >
	<thead>
		<tr>
			<th style="width:20%">Title</th>
			<th>StartDateTime</th>
			<th>Media <br />Count</th>
			<th>Pub</th>
			<th>Update</th>									
		</tr>
	</thead>
-->
	<div id="Grid">
<?php
$cnt=1;
foreach ($live_array as $v){
if($cnt==1){
	$cnt++;
	continue;
}
$old_data =0;
$dt = new DateTime();
$today = $dt->format('Y-m-d');			
if( strtotime($v["live_start_date"]) < strtotime($today) ) {//過去判定
	$old_data = 1;
}

//var_dump($v);
?>
		<ul class="mix pub_<?php echo $v["deleted_at_flg"]?> media_c_<?php echo $v["count(lms.id)"]?> old_data_<?php echo $old_data?> old_data_<?php echo $old_data?>_pub_<?php echo $v["deleted_at_flg"]?>">
			<li class="pre"><?php echo $v["live_title_ja"]?></li>
			<li><?php echo $v["live_start_date"]?></li>
			<li>mc : <?php echo $v["count(lms.id)"]?></li>
			<li>pb : <?php echo $v["deleted_at_flg"]?></li>
			<li><a href="http://live3.info/events/<?php echo $v["id"] ?>" target="_blank" >show</a></li>
			<li><a href="http://util.live3.info/scp/bing_translate.php?live_id=<?php echo $v["id"] ?>" target="_blank" >transLive</a></li>
			<li><a href="http://util.live3.info/scp/bing_translate.php?house_id=<?php echo $v["house_id"] ?>" target="_blank" >transHouse</a></li>
			<li class="last_li"><a href="./update.php?id=<?php echo $v["id"]?>"><button class="btn">Update</button></a></li>									
		</ul>
<?php  
	$cnt++;
}//end foreach
?>
	</div>
<!--
</table>
-->
<style>
#Grid .mix{display: none;border:solid 1px #999;}
#Grid ul li{float:left;}
#Grid ul li.last_li{float:right}
#Grid ul li{margin-left:10px}
#Grid ul{width:100%}	

$Grid .mix .pre{
	max-width: 150px;
	white-space: pre;           /* CSS 2.0 */
	white-space: pre-wrap;      /* CSS 2.1 */
	white-space: pre-line;      /* CSS 3.0 */
	white-space: -pre-wrap;     /* Opera 4-6 */
	white-space: -o-pre-wrap;   /* Opera 7 */
	white-space: -moz-pre-wrap; /* Mozilla */
	white-space: -hp-pre-wrap;  /* HP Printers */
	word-wrap: break-word;      /* IE 5+ */
}
</style>

<script>
$(function(){
	$(".btn-group #future_btn").trigger('click');
});	
</script>

<?php include_once("./common/_footer.php")?>