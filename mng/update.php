<?php
$l=array();
$genre_array=array();
$genre_child_array=array();
$house_array=array();
$ticket_type_array=array();

$post_id=1;
if(isset($_GET['id'])){
	$post_id=$_GET['id'];
}

$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

//$result_house = mysql_query('SELECT id, house_name_ja from houses');
$result_genre = mysql_query('SELECT * from live_genres');
while ($row = mysql_fetch_assoc($result_genre)) {
	$array=array();
	$array += array("id"=>$row['id']);
	$array += array("live_genre_name_ja"=>$row['live_genre_name_ja']);
	$genre_array[] =  $array;
}
$result_genre_child = mysql_query('SELECT * from live_genre_childs');
while ($row = mysql_fetch_assoc($result_genre_child)) {
	$array=array();
	$array += array("id"=>$row['id']);
	$array += array("live_genre_child_name_ja"=>$row['live_genre_child_name_ja']);
	$genre_child_array[] =  $array;
}
$result_house = mysql_query('SELECT * from houses order by house_sort_no DESC');
while ($row = mysql_fetch_assoc($result_house)) {
/*print($row['id']);
    print($row['house_name_ja']);*/
	$array=array();
	$array += array("id"=>$row['id']);
	$array += array("house_name_ja"=>$row['house_name_ja']);
	$array += array("house_latitude"=>$row['house_latitude']);
	$array += array("house_longitude"=>$row['house_longitude']);
	$house_array[] =  $array;
}
$result_t = mysql_query('select id, ticket_type_name_ja from ticket_types');
$result_t = mysql_query('select id, ticket_type_name_master from ticket_types');
while ($row = mysql_fetch_assoc($result_t)) {
	$array=array();
	$array += array("id"=>$row['id']);
	$array += array("ticket_type_name_ja"=>$row['ticket_type_name_master']);
	$ticket_type_array[] = $array;
}

//$result = mysql_query('SHOW TABLES');while ($row = mysql_fetch_assoc($result)) {var_dump($row);}exit;
//$result = mysql_query('SELECT * from lives');

$result = mysql_query("SELECT
t.id AS ticket_id
,t.live_id
,t.ticket_type_id
,t.currency_type_id
,t.ticket_seat_type
,t.ticket_description_ja
,t.ticket_description_en
,t.ticket_description_zh
,t.ticket_price
,t.ticket_count_limit
,t.ticket_sort_no
,t.ticket_link_url
,l.id AS live_id
,l.house_id
,l.live_city_id
,l.live_tel
,l.live_title_ja
,l.live_title_en
,l.live_title_zh
,l.live_sub_title_ja
,l.live_sub_title_en
,l.live_sub_title_zh
,l.live_description_ja
,l.live_description_en
,l.live_description_zh
,l.live_start_date
,l.live_end_date
,l.live_fixed_flg
,l.deleted_at_flg
,lms.id AS live_media_id
,lms.live_id
,lms.live_media_type
,lms.live_media_source_material
,lms.live_media_source_seq_no
FROM
lives l INNER JOIN tickets t
ON l.id = t.live_id INNER JOIN live_media_sources lms
ON t.live_id = lms.live_id
WHERE
l.id = $post_id");


$result = mysql_query("SELECT
t.id AS ticket_id
,t.live_id
,t.ticket_type_id
,t.currency_type_id
,t.ticket_seat_type
,t.ticket_description_ja
,t.ticket_description_en
,t.ticket_description_zh
,t.ticket_price
,t.ticket_count_limit
,t.ticket_sort_no
,t.ticket_link_url
,l.id AS live_id
,l.house_id
,l.live_city_id
,l.live_tel
,l.live_title_ja
,l.live_title_en
,l.live_title_zh
,l.live_sub_title_ja
,l.live_sub_title_en
,l.live_sub_title_zh
,l.live_description_ja
,l.live_description_en
,l.live_description_zh
,l.live_start_date
,l.live_end_date
,l.live_fixed_flg
,l.deleted_at_flg
,lms.id AS live_media_id
,lms.live_id
,lms.live_media_type
,lms.live_media_source_material
,lms.live_media_source_seq_no
,lgm.id AS live_genre_management_id
,lgm.live_id
,lgm.live_genre_id
,lgm.live_genre_child_id
,lgm.live_other_txt
,lg.id AS genre_id
,lg.live_genre_name_ja
,lg.live_genre_description_ja
,lgc.id AS genre_child_id
,lgc.live_genre_child_name_ja
,lgc.live_genre_child_description_ja
FROM lives l 
LEFT JOIN tickets t ON l.id = t.live_id 
LEFT JOIN live_media_sources lms ON t.live_id = lms.live_id 
LEFT JOIN live_genre_managements lgm ON l.id = lgm.live_id 
LEFT JOIN live_genres lg ON lg.id = lgm.live_genre_id 
LEFT JOIN live_genre_childs lgc ON lgc.id = lgm.live_genre_child_id 
WHERE
l.id = $post_id");

$media_image_array=array();
$media_youtube_array=array();
$media_soundcloud_array=array();

while ($row = mysql_fetch_assoc($result)) {
	$l = $row;

	if($row['live_media_type']==1){
		$media_image_array[]=array('media_id'=>$row['live_media_id'],'type'=> $row['live_media_type'],'media'=>$row['live_media_source_material'] ,'seq'=>$row['live_media_source_seq_no']);		
	}else if($row['live_media_type']==2){
		$media_youtube_array[]=array('media_id'=>$row['live_media_id'],'type'=> $row['live_media_type'],'media'=>$row['live_media_source_material'] ,'seq'=>$row['live_media_source_seq_no']);		
	}else if($row['live_media_type']==3){
		$media_soundcloud_array[]=array('media_id'=>$row['live_media_id'],'type'=> $row['live_media_type'],'media'=>$row['live_media_source_material'] ,'seq'=>$row['live_media_source_seq_no']);			
	}
}

mysql_close($link);

?>
<?php 
$live_start_date_date =  isset($l["live_start_date"])?substr($l["live_start_date"], 0,10):'';
$live_start_date_hour =  isset($l["live_start_date"])?substr($l["live_start_date"], 11,2):'';
$live_start_date_minute =  isset($l["live_start_date"])?substr($l["live_start_date"], 14,2):'';

$live_end_date_date =  isset($l["live_end_date"])?substr($l["live_end_date"], 0,10):'';
$live_end_date_hour =  isset($l["live_end_date"])?substr($l["live_end_date"], 11,2):'';
$live_end_date_minute =  isset($l["live_end_date"])?substr($l["live_end_date"], 14,2):'';
	
?>
<?php include_once("./common/_header.php")?>
<script src="./assets/js/jquery.xdomainajax.js" type="text/javascript"></script>

<div class="alert alert-block alert-error fade in " id="alert_area">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<div id="errors"></div>
</div>

<script>
$(function(){
  $('#live_genre_id option[value="<?php echo $l["live_genre_id"]?>"]').attr('selected', 'selected');
  $('#live_genre_child_id option[value="<?php echo $l["live_genre_child_id"]?>"]').attr('selected', 'selected');
  $('#live_other_txt').val('<?php echo $l["live_other_txt"]?>');

  $('#house_id option[value="<?php echo $l["house_id"]?>"]').attr('selected', 'selected');

  <?php if($l["deleted_at_flg"] == 0){ ?>
  $("#deleted_at_flg[value=0]").attr("checked", true );  
  <?php }else{ ?>	  
  $("#deleted_at_flg_off[value=1]").attr("checked", true );    
  <?php } ?>

  <?php if($l["live_fixed_flg"] == 1){ ?>
  $("#live_fixed_flg_on[value=1]").attr("checked", true );
  <?php }else{ ?>	  
  $("#live_fixed_flg[value=0]").attr("checked", true );
  <?php } ?>

  

  $('#ticket_type_id option[value="<?php echo $l["ticket_type_id"]?>"]').attr('selected', 'selected');
  $('#ticket_seat_type option[value="<?php echo $l["ticket_seat_type"]?>"]').attr('selected', 'selected');
  $('#currency_type_id option[value="<?php echo $l["currency_type_id"]?>"]').attr('selected', 'selected');
  $('#ticket_sort_no option[value="<?php echo $l["ticket_sort_no"]?>"]').attr('selected', 'selected');

//rested!
  $("#live_id").val('<?php echo $l["live_id"]?>');    
  $("#live_media_id").val('<?php echo $l["live_media_id"]?>');
  $("#live_media_type").val('<?php echo $l["live_media_type"]?>');
  $("#live_media_source_material").val('<?php echo $l["live_media_source_material"]?>');
  $("#live_media_source_seq_no").val('<?php echo $l["live_media_source_seq_no"]?>');

});
</script>

<form action="http://54.238.217.178/crud/live/update/v1/" method="post" name="form" id="form">
			<div class="col_3">
				<h5>Live /ライブ</h5>
				<input type="hidden" name="liveId" value="<?php echo $post_id?>" />
				<input type="text" id="live_title_ja" name="liveTitleJa" placeholder="Live Name/ライブ名" value="<?php echo isset($l["live_title_ja"])?$l["live_title_ja"]:""; ?>" /><!-- live_title_en,live_title_zh -->
				<input type="text" id="live_title_en" name="liveTitleEn" placeholder="英語　Live Name/ライブ名" value="<?php echo isset($l["live_title_en"])?$l["live_title_en"]:""; ?>" />
<script>
$('#live_title_en:text').bind('click blur keydown keyup keypress change',function(){
    var textWrite = $(this).val();
	$('#live_title_zh').val(textWrite);
});	
</script>
				<input type="hidden" id="live_title_zh" name="liveTitleZh" placeholder="英語　Live Name/ライブ名" />				
				
				
				<input type="hidden" name="liveCityId" value="<?php echo isset($l["live_city_id"])?$l["live_city_id"]:1;?>"/>
				
				<input type="text" id="live_tel" name="liveTel" placeholder="Live Tel/ライブ電話番号" value="<?php echo isset($l["live_tel"])?$l["live_tel"]:"";?>"/>
				
				<input type="text" id="live_sub_title_ja" name="liveSubTitleJa" placeholder="Live SubTitle/ライブサブタイトル" value="<?php echo isset($l["live_sub_title_ja"])?$l["live_sub_title_ja"]:"";?>"/><!-- live_sub_title_en, live_sub_title_zh -->
				
				<textarea name="liveDescriptionJa" id="live_description_ja" cols="20" rows="9" ><?php echo isset($l["live_description_ja"])?$l["live_description_ja"]:"";?></textarea><br /><!-- live_description_en, live_description_zh -->
				
				<span>Live Genre/ライブジャンル</span>
				<input type="hidden" id="live_genre_management_id" name="liveGenreManagementId" value="<?php echo isset($l["live_genre_management_id"])?$l["live_genre_management_id"]:"";?>" />
				<select name="liveGenreId" id="live_genre_id">
<?php
foreach($genre_array as $ar){
	$id = $ar['id'];
	$live_genre_name_ja = $ar['live_genre_name_ja'];
 echo "<option value='$id'>$live_genre_name_ja</option>";
}
?>		
<!-- 					<option value="1"> "Music"</option> -->
				</select>

				<select name="liveGenreChildId" id="live_genre_child_id">
					<option value=""> Select Child Genre </option>
<?php
foreach($genre_child_array as $ar){
	$id = $ar['id'];
	$live_genre_child_name_ja = $ar['live_genre_child_name_ja'];
 echo "<option value='$id'>$live_genre_child_name_ja</option>";
}
?>
<!-- 					<option value="1"> "Music"</option> -->
				</select>
				
				<input type="text" name="liveOtherTxt" id="live_other_txt" placeholder="Live Genre Other" />				
				
				<button id="live_start_date_btn" class="btn" type="button">ライブ開始時間</button><br />
				<span id="live_start_date">
					<span class="date"><?php echo $live_start_date_date; ?></span> 
					<span class="hour"><?php echo $live_start_date_hour; ?></span>:
					<span class="minute"><?php echo $live_start_date_minute; ?></span>
				</span>
				<input type="hidden" name="liveStartDate" id="live_start_date_input" />

				<div class="cB"></div>
				<button id="live_end_date_btn" class="btn" type="button">ライブ終了時間</button><br />
				<span id="live_end_date"> 
					<span class="date"><?php echo $live_end_date_date; ?></span> 
					<span class="hour"><?php echo $live_end_date_hour; ?></span>:
					<span class="minute"><?php echo $live_end_date_minute; ?></span>
				</span>			
				<input type="hidden" name="liveEndDate" id="live_end_date_input" />
				
				<label for="">public flag/パブリックフラグ</label>
				<input type="radio" id="deleted_at_flg" name="deletedAtFlg" value="0"/>ON
				<input type="radio" id="deleted_at_flg_off" name="deletedAtFlg" value="1"/>OFF

				<label for="">fixed flg/ライブ固定フラグ</label>
				<input type="radio" id="live_fixed_flg" name="liveFixedFlg" value="0" />OFF
				<input type="radio" id="live_fixed_flg_on" name="liveFixedFlg" value="1"/>1
<!--
				<input type="radio" id="live_fixed_flg" name="live_fixed_flg" value="2"/>2
				<input type="radio" id="live_fixed_flg" name="live_fixed_flg" value="3"/>3		
-->		
				<hr />
				
				<h5>Club /ライブハウス</h5>
<script>
var house_latlng = {};	
</script>				
<!-- 				<button id="live_hosue" placeholder="Live House/ライブハウス名" class="btn" />ライブハウスを選択</button><br /> -->
				<!-- 	プルダウン	houses	house_id	house_name_ja, house_name_en, house_name_zh -->	
	
				<select name="houseId" id="house_id">
					<option value="">Choose One</option>
<?php

foreach($house_array as $ar){
	$id = $ar['id'];	
	$house_name_ja = $ar['house_name_ja'];
	$house_latitude = $ar['house_latitude'];
	$house_longitude = $ar['house_longitude'];	
 echo "<option value='$id'>$house_name_ja</option>";
 echo "<script>house_latlng[$id] = new Array('$house_latitude', '$house_longitude');</script>";
}
?>				
<!--
					<option value="1">sugoi live house shibuya</option>
					<option value="2">normal live house</option>		
-->											
				</select>										



<script>
$(function(){
	var latlng = house_latlng[$("#house_id").val()];
	$("#house_latitude").val(latlng[0]);
	$("#house_longitude").val(latlng[1]);

	$("#house_id").change(function(){
		var latlng = house_latlng[$(this).val()];
		$("#house_latitude").val(latlng[0]);
		$("#house_longitude").val(latlng[1]);
	});
});
</script>				
				
				<hr />
				<button id="submit_create" class="btn " type="submit">登録する</button>

			</div>
			
			<div class="col_4">
	<div id="images">
		<h5>Images</h5>
		<ul>

<script>
$(function(){
	

$('.delete_media_btn').click(function(){
	var delete_id = $(this).attr('delete_id');
	var elem = $(this);
	$.ajax({
	    type: 'POST',
		headers: {
	//	    'API-KEY': '96d5eaa349119d20e7652254b7d47692b75a36ddd4c4167cf1e3db96ae518b4d'
		},
	    url: './_delete_media.php',
	//	dataType: 'jsonp',
	    data: {
			delete_id: delete_id
	    },
	    dataType: 'html',
	    success: function( data, textStatus, jqXHR ) {
	        console.log("ajax Success");
	        alert('削除したよ！');
	        elem.next().next().next().next().remove;
	        elem.next().next().next().val('');
	        elem.next().remove();
	    },
	    error: function( jqXHR, textStatus, errorThrown ) {
	        console.log(jqXHR);
	        console.log(textStatus);
	        console.log(errorThrown);
	        console.log("ajax Error"); 
	        alert('削除失敗!!');
	    },
	    complete: function( jqXHR, textStatus ) {
	        console.log("ajax Complete");        
	    },
	    statusCode: {
	        200: function(data){
				console.log( data );
	        }, 
	        404: function(){
	            console.log("404");
	        }
	    }    
	});		
	
	
	
});

});
</script>

<?php
//images
//liveMediaSourceMaterialImage1
	for($i=1;$i<7;$i++){
		if(count($media_image_array)>=$i){
			$j = $i-1;
			$image = $media_image_array[$j]['media'];
			$media_id = $media_image_array[$j]['media_id'];
			$seq_no = $media_image_array[$j]['seq'];
			echo "<li><div class='thumbnail' >
					<span>$i</span>
					<button type='button' class='delete_media_btn close' delete_id='$media_id'>×</button>
					<div id='image_$i'></div>
					<script type='text/javascript'>
						setTimeout(function(){
								    var striimg = '<img src=\'$image\'/> ';
								    $('#image_$i').append(striimg);
						},$i*1000);
					</script>
					<input type='text' name='liveMediaSourceMaterialImage$i' id='live_media_source_material_image_$i'  placeholder='image_url' live_media_source_seq_no='$i' live_media_type='1' value='$image'/>
					<input type='hidden' name='liveMediaSourceImageId$i' value='$media_id'/>
					<input type='hidden' name='liveMediaTypeImage$i' value='1'/>
					<input type='hidden' name='liveMediaSourceSeqImage$i'  value='$i'/>
				</div></li>";			
		}else{
			echo "<li>
			<div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true' >×</button>
<input type='text' id='live_media_source_material_image_$i' name='liveMediaSourceMaterialImage$i' placeholder='image_url' live_media_source_seq_no='$i' live_media_type='1' />
					<input type='hidden' name='liveMediaTypeImage$i' value='1'/>
					<input type='hidden' name='liveMediaSourceSeqImage$i'  value='$i'/>
				</div>					
			</li>";								
		}
	}

?>
		</ul>
	</div>	
	<div id="youtubes">
		<h5>Youtube</h5>	
		<ul>
<?php
//youtube
	for($i=1;$i<7;$i++){
		if(count($media_youtube_array)>=$i){
			$j = $i-1;
			$youtube = $media_youtube_array[$j]['media'];
			$media_id = $media_youtube_array[$j]['media_id'];
			$seq_no = $media_youtube_array[$j]['seq'];
			echo "<li><div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='delete_media_btn close' delete_id='$media_id'>×</button>
					<div id='youtube_$i'></div>
								<script type='text/javascript'>
								  setTimeout(function(){
								    var striframe = '<iframe webkit-playsinline width=\'310\' height=\'185\' src=\'http://www.youtube.com/embed/$youtube?feature=player_detailpage&playsinline=1\' frameborder=\'0\'></iframe> ';
								    $('#youtube_$i').append(striframe);
								  },$i*1000);
								</script>
					<input type='text' id='live_media_source_material_youtube_$i' name='liveMediaSourceMaterialYoutube$i' placeholder='youtube_ID' live_media_source_seq_no='$i' live_media_type='2' value='$youtube'/>
					<input type='hidden' name='liveMediaSourceYoutubeId$i' value='$media_id'/>
					<input type='hidden' name='liveMediaTypeYoutube$i' value='2'/>
					<input type='hidden' name='liveMediaSourceSeqYoutube$i' value='$i'/>
					
				</div></li>";			
		}else{
			echo "<li>
			<div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true' type='button'>×</button>
<input type='text' id='live_media_source_material_youtube_$i' name='liveMediaSourceMaterialYoutube$i' placeholder='youtube_ID' live_media_source_seq_no='$i' live_media_type='2' />
					<input type='hidden' name='liveMediaTypeYoutube$i' value='2'/>
					<input type='hidden' name='liveMediaSourceSeqYoutube$i' value='$i'/>
				</div>					
			</li>";								
		}
	}

?>
	
		</ul>
		<h5>Soundcloud</h5>	
		<ul>
<?php
//soundcloud
	for($i=1;$i<7;$i++){
		if(count($media_soundcloud_array)>=$i){
			$j = $i-1;
			$soundcloud = $media_soundcloud_array[$j]['media'];
			$media_id = $media_soundcloud_array[$j]['media_id'];
			$seq_no = $media_soundcloud_array[$j]['seq'];	
			echo "<li><div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='delete_media_btn close' delete_id='$media_id'>×</button>
					<div id='soundcloud_$i'></div>
								<script type='text/javascript'>
								  setTimeout(function(){
								    var striframe = '<iframe width=\'310\' height=\'108\' scrolling=\'no\' frameborder=\'no\' src=\'https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/$soundcloud&amp;auto_play=false&amp;hide_related=false&amp;visual=true\'></iframe>';
								    $('#soundcloud_$i').append(striframe);
								  },$i*1000);
								</script>
					<input type='text' id='live_media_source_material_soundcloud_$i' name='liveMediaSourceMaterialSound$i' placeholder='soundcloud_ID' live_media_source_seq_no='$i' live_media_type='2' value='$soundcloud'/>
					<input type='hidden' name='liveMediaSourceSoundId$i' value='$media_id'/>					
					<input type='hidden' name='liveMediaTypeSound$i' value='3'/>
					<input type='hidden' name='liveMediaSourceSeqSound$i' value='$i'/>
				</div></li>";			
		}else{
			echo "<li>
			<div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true' type='button'>×</button>
<input type='text' id='live_media_source_material_soundcloud_$i' name='liveMediaSourceMaterialSound$i' placeholder='soundcloud_ID' live_media_source_seq_no='$i' live_media_type='2' value=''/>
					<input type='hidden' name='liveMediaTypeSound$i' value='3'/>
					<input type='hidden' name='liveMediaSourceSeqSound$i' value='$i'/>
				</div>					
			</li>";								
		}
	}
?>			
		</ul>
	</div>	
	<div class="cB"></div>
				<hr />
			</div>
			
			<div class="col_4">
				<h5>Ticket / チケット</h5>
				<input type="hidden" name="ticketId" value="<?php echo $l["ticket_id"]?>" />
				
				<span>type of ticket/チケットタイプ</span><!--ticket_type_name_ja, ticket_type_name_en, ticket_type_name_zh--->
				<select name="ticketTypeId" id="ticket_type_id">
<?php
foreach($ticket_type_array as $ar){
	$id = $ar['id'];
	$ticket_type_name_ja = $ar['ticket_type_name_ja'];
	echo "<option value='$id'>$ticket_type_name_ja</option>";
}
?>
				</select>				
				<div class="cB"></div>
				
				<span>type of currency/通貨タイプ</span><!--currency_symbol--->
				<select name="currencyTypeId" id="currency_type_id">
					<option value="1">¥</option>
					<option value="2">$</option>
					<option value="3">€</option>					
				</select>					
				<div class="cB"></div>
				
				<span>type of seat/座席タイプ</span><br /><!--currency_symbol--->
				<select name="ticketSeatType" id="ticket_seat_type">
					<option value="1">Normal/通常</option>
					<option value="2">B</option>
					<option value="3">A</option>
					<option value="4">S</option>
				</select>
				<div class="cB"></div>				
				
				<textarea name="ticketDescriptionJa" id="ticket_description_ja" cols="20" rows="5" ><?php echo isset($l["ticket_description_ja"])?$l["ticket_description_ja"]:"";?></textarea><!-- ticket_description_en, ticket_description_zh -->
				<div class="cB"></div>
								
				<span>Price /価格</span><br />
				<input type="text" name="ticketPrice" id="ticket_price" value='<?php echo isset($l["ticket_price"])?$l["ticket_price"]:"";?>'/>
				<div class="cB"></div>
								
				<span>limit of ticket number /チケット購入上限数</span>
				<input type="text" name="ticketCountLimit" id="ticket_count_limit" value='<?php echo  isset($l["ticket_count_limit"])?$l["ticket_count_limit"]:"";?>'/>
				<div class="cB"></div>
								
				<span>sequance of ticket /チケットシーケンス番号</span>
				<select name="ticketSortNo" id="ticket_sort_no">
					<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
					<option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
					<option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
					<option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option>
					<option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
				</select>
				<div class="cB"></div>
				
				<span>external link for ticket/外部リンク</span>
				<input type="text" name="ticketLinkUrl" id="ticket_link_url" value='<?php echo isset($l["ticket_link_url"])?$l["ticket_link_url"]:"";?>'/>
				<div class="cB"></div>
								
				<span>discount rate of ticket /<br />チケットディスカウントレート</span><br />
				<input type="text" name="ticketDiscountRate" id="ticket_discount_rate" />
				<div class="cB"></div>
				
				<span>discount price of ticket /<br />チケットディスカウントプライス</span><br />
				<input type="text" name="ticketDiscountPrice" id="ticket_discount_price" />
				<div class="cB"></div>
								
				<button id="ticket_start_date_btn" class="btn" type='button'>discount start time /割引開始時間</button><br />
				<span id="live_discount_start_date">
					<span class="date"></span> 
					<span class="hour">00</span>:
					<span class="minute">00</span>
				</span> <br />		
				<div class="cB"></div>
					
				<button id="ticket_end_date_btn" class="btn" type='button'>discount end time /割引終了時間</button><br />
				<span id="live_discount_end_date">
					<span class="date"></span> 
					<span class="hour">00</span>:
					<span class="minute">00</span>
				</span>	<br />
				<div class="cB"></div>

<script>
$(function(){
	var start_or_end_date = 0;
	 $('#calendar').fullCalendar({
	 	header:{
		 	left:   'prev',
		    center: 'title',
		    right:  'today next'
		},
	    dayClick: function(date, allDay, jsEvent, view) {
	        $('#calendar td').css('background-color', '#fff');	    
	        if (allDay) {
	            var date_param = toLocaleString(date);
	//	            alert('Clicked on the entire day: ' + date_param);
				if(start_or_end_date == 0 ){
					$("#live_start_date .date").text(date_param);
					if(($("#live_end_date .date").text()).length == 0){
						$("#live_end_date .date").text(date_param);
					}
				}else if(start_or_end_date == 1 ){
					$("#live_end_date .date").text(date_param);					
				}else if(start_or_end_date == 2 ){
					$("#live_discount_start_date .date").text(date_param);					
				}else if(start_or_end_date == 3 ){
					$("#live_discount_end_date .date").text(date_param);					
				}
	        }else{
	//	            alert('Clicked on the slot: ' + date);
	        }
	//	        alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
	//	        alert('Current view: ' + view.name);
	        // change the day's background color just for fun
	        $(this).css('background-color', '#999');
	    },
	    events: [{/*title: '<%= count%>',start: '<%= date%>'*/},    
	    ], 
	 });
	$("#live_start_date_hour").change(function(){
		var hour = $(this).val();
		if( hour < 10){
			hour = '0'+hour;
		}
		if(start_or_end_date == 0 ){
			$("#live_start_date .hour").text(hour);
		}else if(start_or_end_date == 1 ){
			$("#live_end_date .hour").text(hour);
		}else if(start_or_end_date == 2 ){
			$("#live_discount_start_date .hour").text(hour);
		}else if(start_or_end_date == 3 ){
			$("#live_discount_end_date .hour").text(hour);
		}
	});		
	$("#live_start_date_minute").change(function(){
		var minute = $(this).val();
		if( minute < 10){
			minute = '0'+minute;
		}	
		if(start_or_end_date == 0 ){
			$("#live_start_date .minute").text(minute);
		}else if(start_or_end_date == 1 ){
			$("#live_end_date .minute").text(minute);
		}else if(start_or_end_date == 2 ){
			$("#live_discount_start_date .minute").text(minute);
		}else if(start_or_end_date == 3 ){
			$("#live_discount_end_date .minute").text(minute);
		}	
	});	 
		
	//hour
	for(var i = 0;i<=23;i++){
		var option = "<option value='"+i+"'>"+i+"</option>";
		$("#live_start_date_hour").append(option);							
	}
	//minute				
	for(var i = 0;i<=59;i=i+5){
		var option = "<option value='"+i+"'>"+i+"</option>";
		$("#live_start_date_minute").append(option);							
	}
	
	$("#calendarModal").css("display","none");
	
	$("#live_start_date_btn").click(function () {
		start_or_end_date = 0;		
		$('#calendarModal').modal('show');
	});
	$("#live_end_date_btn").click(function(){
		start_or_end_date = 1;
		$('#calendarModal').modal('show');
	});
	$("#ticket_start_date_btn").click(function () {
		start_or_end_date = 2;		
		$('#calendarModal').modal('show');
	});
	$("#ticket_end_date_btn").click(function(){
		start_or_end_date = 3;
		$('#calendarModal').modal('show');
	});
	
	
});
function toLocaleString( date ){
	var y =date.getFullYear()
	var m =date.getMonth() + 1
	var d =date.getDate()
//        ].join( '' ) //+ ' '
//        + date.toLocaleTimeString()
	if (m < 10) {  m = '0' + m;}
	if (d < 10) {  d = '0' + d;}	
	var new_date = String(y)+'-'+String(m)+'-'+String(d);
    return new_date
}
</script>
				
			</div>
</form>

<button id="replica_btn">Replica</button>
<script>
$("#replica_btn").click(function(){
	$("#form").attr("action",'./index.php');
	$("#form").submit();
});
</script>


<!--
				<input type="hidden" value='' name="houseLatitude" id="house_latitude"/>
				<input type="hidden" value='' name="houseLongitude" id="house_longitude"
-->

 <!-- Modal -->
<div id="calendarModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" type='button'>×</button>
    <h3 id="myModalLabel">Select Date and Time!!!</h3>
  </div>
  <div class="modal-body">
		<div id="live_start_date_selecter" class="frame" >
			<div id="calendar"></div>
	<style>
	#calendar h2{
	font-size: 0.8em;
	margin:5px 0 0;
	padding: 0;
	}
	#calendar .fc-text-arrow{
	font-size: 2.0em;
	margin: 3px 5px;
	}
	#calendar .fc-button-today{
	font-size: 0.8em;
	margin:0 5px;
	vertical-align: super;	
	}
	</style>				
		</div><!-- #live_start_date_selecter end-->
  </div>
  <div class="modal-footer">
  	<div class="fL">
		<select name="live_start_date_hour" id="live_start_date_hour" style="width:60px;"></select> : 
		<select name="live_start_date_minute" id="live_start_date_minute" style="width:60px;"></select>
	</div>	
    <button class="btn" data-dismiss="modal" aria-hidden="true" type='button'>Close</button>
<!--     <button class="btn btn-primary">Save changes</button> -->
  </div>
</div>	

<script src="./assets/js/validate.js" type="text/javascript"></script>
<script>
$(function(){
var force = 0;
<?php
if(isset($_GET['force'])  ){
	echo 'force = 1;';
}
?>
	$("#form").submit(function(e) {       
//      e.preventDefault();
    });
	$("#alert_area").hide();
	var validator = new FormValidator('form', [{
	    name: 'liveTitleJa',
	    display: 'Live Name/ライブ名',    
	    rules: 'required'
	}, {
	    name: 'liveMediaSourceMaterialImage1',
	    display: 'Images 1 ',    
	    rules: 'required'
	}, {		
	    name: 'ticketPrice',
	    display: 'Ticket Price ',    
	    rules: 'required'
	}, {			
	    name: 'houseId',
	    display: 'House',    
	    rules: 'required'
	}, {	
	
	    name: 'alphanumeric',
	    rules: 'alpha_numeric'
	}, {
	    name: 'password',
	    rules: 'required'
	}, {
	    name: 'password_confirm',
	    display: 'password confirmation',
	    rules: 'required|matches[password]'
	}, {
	    name: 'email',
	    rules: 'valid_email'
	}, {
	    name: 'minlength',
	    display: 'min length',
	    rules: 'min_length[8]'
	}], function(errors, event) {
		$("#alert_area #errors").empty();
	    if (errors.length > 0) {
	        var errorString = '';	        
	        for (var i = 0, errorLength = errors.length; i < errorLength; i++) {
	            errorString += errors[i].message + '<br />';
	        }
			$("#alert_area").fadeIn();

	        $("#alert_area #errors").append(errorString);

	    }else if(
		    ($("#live_start_date .date").text()+' '+$("#live_start_date .hour").text()+':'+$("#live_start_date .minute").text()).length !=16 ||
			($("#live_end_date .date").text()+' '+$("#live_end_date .hour").text()+':'+$("#live_end_date .minute").text()).length !=16 
	    ){//datetime validate
			$("#alert_area").fadeIn();
	        $("#alert_area #errors").append("Live Start Date or End Date Required!");
			e.preventDefault();
	    }else if(
		    ( $("#house_latitude").val() <=36 || $("#house_latitude").val() >=37 ||
		      $("#house_longitude").val() <=139 || $("#house_longitude").val() >=140 ) 
		    && force == 0 && $('input[name="deletedAtFlg"]:checked').val() == 0
	    ){//datetime validate
			$("#alert_area").fadeIn();
	        $("#alert_area #errors").append("Houseの位置が日本じゃないっぽいです。DBから直接修正して下さい(house house_latitude)。または、URLの末尾に&force=1を入力すれば許してくれるかもしれません。");
			e.preventDefault();			
			
	    }else{//ajax post start
		   $("#live_start_date_input").val($("#live_start_date .date").text()+' '+$("#live_start_date .hour").text()+':'+$("#live_start_date .minute").text()+':00');
		   $("#live_end_date_input").val($("#live_end_date .date").text()+' '+$("#live_end_date .hour").text()+':'+$("#live_end_date .minute").text()+':00');
		   console.log('ok');
			//ajax_post();

	    }//ajax post end
	});	
});
</script>	 

<?php include_once("./common/_footer.php")?>