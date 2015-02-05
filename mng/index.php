<?php

$genre_array=array();
$genre_child_array=array();
$house_array=array();
$ticket_type_array=array();

$link = mysql_connect('live3app2.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

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

$result = mysql_query('SELECT * from houses order by house_sort_no DESC');
while ($row = mysql_fetch_assoc($result)) {
/*    print($row['id']);
    print($row['house_name_ja']);*/
	$array=array();
	$array += array("id"=>$row['id']);
	$array += array("house_name_ja"=>$row['house_name_ja']);
	$array += array("house_sort_no"=>$row['house_sort_no']);
	$house_array[] =  $array;
}

//$result_t = mysql_query('select id, ticket_type_name_ja from ticket_types');
$result_t = mysql_query('select id, ticket_type_name_master from ticket_types');
while ($row = mysql_fetch_assoc($result_t)) {
	$array=array();
	$array += array("id"=>$row['id']);
	$array += array("ticket_type_name_ja"=>$row['ticket_type_name_master']);
	$ticket_type_array[] = $array;
}
mysql_close($link);
/*
name:live3appdb
host:live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com
User:live3
pass:TryTryTry
database:live3appdb
port:3306
*/

?>

<?php	

$live_start_date_date =  isset($_GET["live_start_date"])?substr($_GET["live_start_date"], 0,10):'';
$live_start_date_hour =  isset($_GET["live_start_date"])?substr($_GET["live_start_date"], 11,2):'00';
$live_start_date_minute =  isset($_GET["live_start_date"])?substr($_GET["live_start_date"], 14,2):'00';

$live_end_date_date =  isset($_GET["live_end_date"])?substr($_GET["live_end_date"], 0,10):'';
$live_end_date_hour =  isset($_GET["live_end_date"])?substr($_GET["live_end_date"], 11,2):'00';
$live_end_date_minute =  isset($_GET["live_end_date"])?substr($_GET["live_end_date"], 14,2):'00';

//POST ver
$live_start_date_date =  isset($_POST["live_start_date"])?substr($_POST["live_start_date"], 0,10):'';
$live_start_date_hour =  isset($_POST["live_start_date"])?substr($_POST["live_start_date"], 11,2):'00';
$live_start_date_minute =  isset($_POST["live_start_date"])?substr($_POST["live_start_date"], 14,2):'00';

$live_end_date_date =  isset($_POST["live_end_date"])?substr($_POST["live_end_date"], 0,10):'';
$live_end_date_hour =  isset($_POST["live_end_date"])?substr($_POST["live_end_date"], 11,2):'00';
$live_end_date_minute =  isset($_POST["live_end_date"])?substr($_POST["live_end_date"], 14,2):'00';

if($live_end_date_date =='' && $live_end_date_hour == '00' && $live_end_date_minute=='00'){
	$live_end_date_date =  isset($_POST["live_start_date"])?substr($_POST["live_start_date"], 0,10):'';
	$live_end_date_hour =  isset($_POST["live_start_date"])?substr($_POST["live_start_date"], 11,2):'00';
	$live_end_date_minute =  isset($_POST["live_start_date"])?substr($_POST["live_start_date"], 14,2):'00';	
}

?>
<?php include_once('./common/_header.php') ?>
<script src="./assets/js/jquery.xdomainajax.js" type="text/javascript"></script>

<div class="alert alert-block alert-error fade in " id="alert_area">
	<button type="button" class="close" data-dismiss="alert">×</button>
	<div id="errors"></div>
</div>
<!--  http://54.238.217.178/crud/live/create/v1/ -->
<form action="http://54.238.217.178/crud/live/create/v1/" method="post" name="form" id="form">
			<div class="col_3">
				<h5>Live /ライブ</h5>
				<input type="text" id="live_title_ja" name="liveTitleJa" placeholder="Live Name/ライブ名"  /><!-- live_title_en,live_title_zh -->
				<input type="text" id="live_title_en" name="liveTitleEn" placeholder="英語　Live Name/ライブ名" />
<script>
	$('#live_title_en').change(function () {
		$('#live_title_zh').val($(this).val());
	});
</script>
				<input type="hidden" id="live_title_zh" name="liveTitleZh" placeholder="英語　Live Name/ライブ名" />

				<input type="hidden" name="liveCityId" value="1"/>
				
				<input type="text" id="live_tel" name="liveTel" placeholder="Live Tel/ライブ電話番号" />
				
				<input type="text" id="live_sub_title_ja" name="liveSubTitleJa" placeholder="Live SubTitle/ライブサブタイトル" /><!-- live_sub_title_en, live_sub_title_zh -->
				
				<textarea name="liveDescriptionJa" id="live_description_ja" cols="20" rows="5" ></textarea><!-- live_description_en, live_description_zh -->
				
				<span>Live Genre/ライブジャンル</span>
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
				<input type="radio" id="deleted_at_flg" name="deletedAtFlg" value="0" checked="checked" />ON
				<input type="radio" id="deleted_at_flg" name="deletedAtFlg" value="1"/>OFF

				<label for="">fixed flg/ライブ固定フラグ</label>
				<input type="radio" id="live_fixed_flg" name="liveFixedFlg" value="0" checked="checked" />OFF
				<input type="radio" id="live_fixed_flg" name="liveFixedFlg" value="1"/>1
<!--
				<input type="radio" id="live_fixed_flg" name="live_fixed_flg" value="2"/>2
				<input type="radio" id="live_fixed_flg" name="live_fixed_flg" value="3"/>3		
-->		
				<hr />
				
				<h5>Club /ライブハウス</h5>
<!-- 				<button id="live_hosue" placeholder="Live House/ライブハウス名" class="btn" />ライブハウスを選択</button><br /> -->
				<!-- 	プルダウン	houses	house_id	house_name_ja, house_name_en, house_name_zh -->		
				<select name="houseId" id="house_id">
					<option value="">Choose One</option>
<?php
foreach($house_array as $ar){
	$id = $ar['id'];
	$house_name_ja = $ar['house_name_ja'];
 echo "<option value='$id'>$house_name_ja</option>";
}
?>				
<!--
					<option value="1">sugoi live house shibuya</option>
					<option value="2">normal live house</option>		
-->											
				</select>										
				
				<hr />
				<button id="submit_create" class="btn " type="submit">登録する</button>		
			</div>
			
			<div class="col_4">
	<div id="images">
		<h5>Images</h5>
		<ul>

<?php
//images
//liveMediaSourceMaterialImage1
	for($i=1;$i<7;$i++){
		$image="";
		if(strlen($image)>1){
			echo "<li><div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true' type='button'>×</button>
					<div id=image_$i'></div>
								<script type='text/javascript'>
								  setTimeout(function(){
								    var striimg = '<img src='$image' alt='' /> ';
								    $('#image_$i').append(strimg);
								  },$i*1000);
								</script>
					<input type='text' name='liveMediaSourceMaterialImage$i' id='live_media_source_material_image_$i'  placeholder='image_url' live_media_source_seq_no='$i' live_media_type='1'/>
					<input type='hidden' name='liveMediaTypeImage$i' value='1'/>
					<input type='hidden' name='liveMediaSourceSeqImage$i'  value='$i'/>
				</div></li>";			
		}else{
			echo "<li>
			<div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true' type='button'>×</button>
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
		$youtube="";
		if(strlen($youtube)>1){
			echo "<li><div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true' type='button'>×</button>
					<div id=youtube_$i'></div>
								<script type='text/javascript'>
								  setTimeout(function(){
								    var striframe = '<iframe webkit-playsinline width='310' height='185' src='http://www.youtube.com/embed/$youtube?feature=player_detailpage&playsinline=1' frameborder='0'></iframe> ';
								    $('#youtube_$i').append(striframe);
								  },$i*1000);
								</script>
					<input type='text' id='live_media_source_material_youtube_$i' name='liveMediaSourceMaterialYoutube$i' placeholder='youtube_ID' live_media_source_seq_no='$i' live_media_type='2'/>
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
		$soundcloud="";
		if(strlen($soundcloud)>1){
			echo "<li><div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true' type='button'>×</button>
					<div id=soundcloud_$i'></div>
								<script type='text/javascript'>
								  setTimeout(function(){
								    var striframe = '<iframe webkit-playsinline width='310' height='185' src='http://www.soundcloud.com/embed/$soundcloud?feature=player_detailpage&playsinline=1' frameborder='0'></iframe> ';
								    $('#soundcloud_$i').append(striframe);
								  },$i*1000);
								</script>
					<input type='text' id='live_media_source_material_soundcloud_$i' name='liveMediaSourceMaterialSound$i' placeholder='soundcloud_ID' live_media_source_seq_no='$i' live_media_type='2'/>
					<input type='hidden' name='liveMediaTypeSound$i' value='3'/>
					<input type='hidden' name='liveMediaSourceSeqSound$i' value='$i'/>
				</div></li>";			
		}else{
			echo "<li>
			<div class='thumbnail'>
					<span>$i</span>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true' type='button'>×</button>
<input type='text' id='live_media_source_material_soundcloud_$i' name='liveMediaSourceMaterialSound$i' placeholder='soundcloud_ID' live_media_source_seq_no='$i' live_media_type='2' />
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
<!--
				<h5>Media /メディア</h5>
				<select name="live_media_type" id="live_media_type">
					<option value="1">イメージ画像</option>
					<option value="2">Youtube</option>
					<option value="3">Sound Cloud</option>					
				</select>
				
				<input type="text"name="live_media_source_material" id="live_media_source_material" placeholder="image_url, youtube_ID soundcloud_ID"/>
				<div class="cB"></div>
				
				<span>media sequence/ メディアの優先順位</span>
				<select name="live_media_source_seq_no" id="live_media_source_seq_no">
					<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
					<option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
					<option value="9">9</option>
				</select>	
-->			
				<hr />
<!--

				<h5>Band/バンド</h5>
				<span>band name/ ライブ出演バンド</span>
				<select name="band_id" id="band_id">
					<option value="1">Queen</option><option value="2">MEGADEATH</option><option value="3">3</option><option value="4">4</option>
					<option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
					<option value="9">9</option>
				</select>	
				<div class="cB"></div>				

				<span>band start time /出演開始時間</span><br />
				<select name="live_band_start_date_hour" id="live_band_start_date_hour" style="width:60px;"></select>:
				<select name="live_band_start_date_minute" id="live_band_start_date_minute" style="width:60px;"></select>
				<div class="cB"></div>
					
				<span>band end time /出演終了時間</span><br />
				<select name="live_band_end_date_hour" id="live_band_end_date_hour" style="width:60px;"></select>:
				<select name="live_band_end_date_minute" id="live_band_end_date_minute" style="width:60px;"></select>
				<div class="cB"></div>
												
<script>
$(function(){
	var band_start_or_end_date = 0;
	//hour
	for(var i = 0;i<=23;i++){
		var option = "<option value='"+i+"'>"+i+"</option>";
		$("#live_band_start_date_hour").append(option);							
	}
	for(var i = 0;i<=23;i++){
		var option = "<option value='"+i+"'>"+i+"</option>";
		$("#live_band_end_date_hour").append(option);							
	}
	//minute				
	for(var i = 0;i<=59;i=i+5){
		var option = "<option value='"+i+"'>"+i+"</option>";
		$("#live_band_start_date_minute").append(option);							
	}
	for(var i = 0;i<=59;i=i+5){
		var option = "<option value='"+i+"'>"+i+"</option>";
		$("#live_band_end_date_minute").append(option);							
	}
});
</script>
				
				<span>media sequence/ バンドの優先順位</span>
				<select name="live_band_seq_no" id="live_band_seq_no">
					<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option>
					<option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option>
					<option value="9">9</option>
				</select>	
				<div class="cB"></div>
-->
			</div>
			
			<div class="col_4">
				<h5>Ticket / チケット</h5>
				<span>type of ticket/チケットタイプ</span><!--ticket_type_name_ja, ticket_type_name_en, ticket_type_name_zh--->
				<select name="ticketTypeId" id="ticket_type_id">
<?php
foreach($ticket_type_array as $ar){
	$id = $ar['id'];
	$ticket_type_name_ja = $ar['ticket_type_name_ja'];
	if($id == 7 ){
		echo "<option value='$id' selected>$ticket_type_name_ja</option>";		
	}else{		
		echo "<option value='$id'>$ticket_type_name_ja</option>";
	}

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
				
				<textarea name="ticketDescriptionJa" id="ticket_description_ja" cols="20" rows="5" ></textarea><!-- ticket_description_en, ticket_description_zh -->
				<div class="cB"></div>
								
				<span>Price /価格</span><br />
				<input type="text" name="ticketPrice" id="ticket_price" />
				<div class="cB"></div>
								
				<span>limit of ticket number /チケット購入上限数</span>
				<input type="text" name="ticketCountLimit" id="ticket_count_limit" value='999999999'/>
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
				<input type="text" name="ticketLinkUrl" id="ticket_link_url" />
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
	if(($("#live_start_date_input").val()).length != 0){
		var date = ($("#live_start_date_input").val()).slice(0, 10);
		var hour = ($("#live_start_date_input").val()).slice(12, 2);
		var miniute = ($("#live_start_date_input").val()).slice(13, 2);		

		console.log($("#live_start_date_input").val());
		console.log(hour);
		console.log(miniute);

		$("#live_start_date .date").text(date);
		$("#live_start_date .hour").text(hour);
		$("#live_start_date .minute").text(minute);
	}
	if(($("#live_end_date_input").val()).length != 0){
		var date = ($("#live_end_date_input").val()).slice(0, 10);
		var hour = ($("#live_end_date_input").val()).slice(11, 2);
		var miniute = ($("#live_end_date_input").val()).slice(13, 2);
			
		console.log($("#live_end_date_input").val());
		console.log(hour);
		console.log(miniute);

		$("#live_end_date .date").text(date);
		$("#live_end_date .hour").text(hour);
		$("#live_end_date .minute").text(miniute);
	}	

/*
	$("#submit_validate_btn").click(function(){
		$("#live_start_date_input").val($("#live_start_date .date").text()+' '+$("#live_start_date .hour").text()+':'+$("#live_start_date .minute").text()+':00');
		$("#live_end_date_input").val($("#live_end_date .date").text()+' '+$("#live_end_date .hour").text()+':'+$("#live_end_date .minute").text()+':00');	
	
		$("#form").submit();
	}
*/

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
	    name: 'liveStartDate',
	    display: 'StartDate',    
	    rules: 'min_length[16]'
	}, {
	    name: 'liveEndDate',
	    display: 'EndDate',    
	    rules: 'min_length[16]'
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

	    }else{//ajax post start
		   $("#live_start_date_input").val($("#live_start_date .date").text()+' '+$("#live_start_date .hour").text()+':'+$("#live_start_date .minute").text()+':00');
		   $("#live_end_date_input").val($("#live_end_date .date").text()+' '+$("#live_end_date .hour").text()+':'+$("#live_end_date .minute").text()+':00');
//		   console.log($("#live_start_date_input").val());
			//ajax_post();

	    }//ajax post end
	});	
});



</script>						
<?php
///set value! ?prep_data=1
function js_val($val){
	if(isset($_GET[$val])){
		$v = $_GET[$val];
		echo "$('#$val').val('$v');";
	}	
}

function js_val_select($val){
	if(isset($_GET[$val])){
		$v = $_GET[$val];
		echo "$('#$val option[value=\'$v\']').attr('selected', 'selected');";
	}	
}

function js_val_radio($val){
	if(isset($_GET[$val])){
		$v = $_GET[$val];
		echo "$('#$val\[value=$v]').attr('checked', true );";

	}	
}

function js_val_text($val){
	if(isset($_GET[$val])){
		$v = $_GET[$val];
		echo "$('#$val').text('$v');";
	}	
}

if(isset($_GET['prep_data'])){
	echo '<script>';

	js_val('live_title_ja');
	js_val('live_tel');
	js_val('live_sub_title_ja');
	js_val_text('live_description_ja');			
	js_val('live_media_source_material_image_1');
	js_val('live_media_source_material_image_2');
	js_val('live_media_source_material_image_3');
	js_val('live_media_source_material_image_4');
	js_val('live_media_source_material_image_5');
	js_val('live_media_source_material_image_6');
	js_val('live_media_source_material_youtube_1');
	js_val('live_media_source_material_youtube_2');
	js_val('live_media_source_material_youtube_3');
	js_val('live_media_source_material_youtube_4');
	js_val('live_media_source_material_youtube_5');
	js_val('live_media_source_material_youtube_6');		
	js_val('live_media_source_material_soundcloud_1');
	js_val('live_media_source_material_soundcloud_1');
	js_val('live_media_source_material_soundcloud_1');
	js_val('ticket_description_ja');
	js_val('ticket_price');
	js_val('ticket_link_url');		

	js_val_select('live_genre_id');
	js_val_select('house_id');

	js_val_radio('deleted_at_flg');
	js_val_radio('live_fixed_flg');
	echo '</script>';
}	
?>	

<?php
///set value! ?prep_data=1　　POST ver
function js_val_post($val){
	if(isset($_POST[$val])){
		$v = $_POST[$val];
		echo "$('#$val').val('$v');";
	}	
}

function js_val_select_post($val){
	if(isset($_POST[$val])){
		$v = $_POST[$val];
		echo "$('#$val option[value=\'$v\']').attr('selected', 'selected');";
	}	
}

function js_val_radio_post($val){
	if(isset($_POST[$val])){
		$v = $_POST[$val];
		echo "$('#$val\[value=$v]').attr('checked', true );";

	}	
}

function js_val_text_post($val){
	if(isset($_POST[$val])){
		$v = str_replace("'", "\'",str_replace("\n", "\\n",str_replace("\r", "",  $_POST[$val])));
		echo "$('#$val').val('$v');";
	}	
}


if(isset($_POST['prep_data'])){
	echo '<script>';

	js_val_post('live_title_ja');
	js_val_post('live_tel');
	js_val_post('live_sub_title_ja');
	js_val_text_post('live_description_ja');			
	js_val_post('live_media_source_material_image_1');
	js_val_post('live_media_source_material_image_2');
	js_val_post('live_media_source_material_image_3');
	js_val_post('live_media_source_material_image_4');
	js_val_post('live_media_source_material_image_5');
	js_val_post('live_media_source_material_image_6');
	js_val_post('live_media_source_material_youtube_1');
	js_val_post('live_media_source_material_youtube_2');
	js_val_post('live_media_source_material_youtube_3');
	js_val_post('live_media_source_material_youtube_4');
	js_val_post('live_media_source_material_youtube_5');
	js_val_post('live_media_source_material_youtube_6');		
	js_val_post('live_media_source_material_soundcloud_1');
	js_val_post('live_media_source_material_soundcloud_1');
	js_val_post('live_media_source_material_soundcloud_1');
	js_val_post('ticket_description_ja');
	js_val_post('ticket_price');
	js_val_post('ticket_link_url');		

	js_val_select_post('live_genre_id');
	js_val_select_post('live_genre_child_id');
	js_val_select_post('house_id');

	js_val_radio_post('deleted_at_flg');
	js_val_radio_post('live_fixed_flg');
	echo '</script>';	
}	


///snake case//////////////////////////
function snake_case($string){
	$string = preg_replace('/([A-Z])/', '_$1', $string);
	$string = strtolower($string);
	return ltrim($string, '_');
}
function camelize($string){
	$string = pascalize($string);
	$string[0] = strtolower($string[0]);
	return $string;
}
function pascalize($string){	
	$string = strtolower($string);
	$string = str_replace('_', ' ', $string);
	$string = ucwords($string);
	$string = str_replace(' ', '', $string);	
	return $string;
}


function js_val_post_snake($val){
	$val_camel = camelize($val);		
	if(isset($_POST[$val_camel])){
		$v = $_POST[$val_camel];
		echo "$('#$val').val('$v');";
	}	
}

function js_val_select_post_snake($val){
	$val_camel = camelize($val);
	if(isset($_POST[$val_camel])){
		$v = $_POST[$val_camel];
		echo "$('#$val option[value=\'$v\']').attr('selected', 'selected');";
	}	
}

function js_val_radio_post_snake($val){
	$val_camel = camelize($val);
	if(isset($_POST[$val_camel])){
		$v = $_POST[$val_camel];
		echo "$('#$val\[value=$v]').attr('checked', true );";

	}	
}

function js_val_text_post_snake($val){
	$val_camel = camelize($val);
	if(isset($_POST[$val_camel])){
		$v = str_replace("'", "\'",str_replace("\n", "\\n",str_replace("\r", "",  $_POST[$val_camel])));
		echo "$('#$val').text('$v');";
	}	
}

if(isset($_POST['liveId'])){
	echo '<script>';

	js_val_post_snake('live_title_ja');
	js_val_post_snake('live_tel');
	js_val_post_snake('live_sub_title_ja');
	js_val_text_post_snake('live_description_ja');			
	js_val_post_snake('live_media_source_material_image_1');
	js_val_post_snake('live_media_source_material_image_2');
	js_val_post_snake('live_media_source_material_image_3');
	js_val_post_snake('live_media_source_material_image_4');
	js_val_post_snake('live_media_source_material_image_5');
	js_val_post_snake('live_media_source_material_image_6');
	js_val_post_snake('live_media_source_material_youtube_1');
	js_val_post_snake('live_media_source_material_youtube_2');
	js_val_post_snake('live_media_source_material_youtube_3');
	js_val_post_snake('live_media_source_material_youtube_4');
	js_val_post_snake('live_media_source_material_youtube_5');
	js_val_post_snake('live_media_source_material_youtube_6');		
	js_val_post_snake('live_media_source_material_soundcloud_1');
	js_val_post_snake('live_media_source_material_soundcloud_1');
	js_val_post_snake('live_media_source_material_soundcloud_1');
	js_val_post_snake('ticket_description_ja');
	js_val_post_snake('ticket_price');
	js_val_post_snake('ticket_link_url');		

	js_val_select_post_snake('live_genre_id');
	js_val_select_post_snake('house_id');

	js_val_radio_post_snake('deleted_at_flg');
	js_val_radio_post_snake('live_fixed_flg');
	echo '</script>';
}	

?>	


			
<?php include_once('./common/_footer.php') ?>			