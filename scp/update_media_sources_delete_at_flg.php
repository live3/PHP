<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

//LIVE3 DB
$all_live_array[] = array();
$link = mysql_connect('live3app.c4jv7rwndgep.ap-northeast-1.rds.amazonaws.com', 'live3', 'TryTryTry');
if (!$link) {
    print(mysql_error());
}
mysql_query("SET NAMES utf8",$link); 
$db_selected = mysql_select_db('live3appdb', $link);
if (!$db_selected){
    die('データベース選択失敗です。'.mysql_error());
}

$post_id = 0;
if($post_id == 0){//post前
	$row_count = 0;
	//live
	$mysql_res = mysql_query("select * from live_media_sources where live_media_type = 1 AND deleted_at_flg = 0");
	while ($row = mysql_fetch_assoc($mysql_res)) {
		echo 'No.'.$row_count.'<br />';
		$id = $row['id'];
		$img = $row['live_media_source_material'];		
		$res =  get_http_header( $img);
		if($res['Status-Code'] == "404"){
			$sql = sprintf("UPDATE live_media_sources SET deleted_at_flg = 1 WHERE id = %s", quote_smart($id));
			mysql_query($sql);
			echo $id;
			echo '<hr />';
		}
		$row_count++;
	}	

	//band
	$mysql_res = mysql_query("select * from band_media_sources where band_media_type = 1 AND deleted_at_flg = 0");
	while ($row = mysql_fetch_assoc($mysql_res)) {
		echo 'No.'.$row_count.'<br />';
		$id = $row['id'];
		$img = $row['band_media_source_material'];		
		$res =  get_http_header( $img);
		if($res['Status-Code'] == "404"){
			$sql = sprintf("UPDATE band_media_sources SET deleted_at_flg = 1 WHERE id = %s", quote_smart($id));
			mysql_query($sql);
			echo $id;
			echo '<hr />';
		}
		$row_count++;
	}	
	exit;
}


//-------------------------------------------------------------------------
// array get_http_header( string URI )
// URIがHTTPプロトコルだった場合、そのURIにHEADリクエストを行います。
// 返り値にはHTTP-Version、Status-Code、Reason-Phraseが必ず含まれ、それ以外
// にサーバが返した情報（index: value）が含まれます。
// Status-Codeが9xxの場合、それはホストが存在しない場合などHTTPリクエストが
// 正常に行われなかったことを意味します。
//-------------------------------------------------------------------------
function get_http_header( $target ) {

    // URIから各情報を取得
    $info = parse_url( $target );

    $scheme = $info['scheme'];
    $host = $info['host'];
//    $port = $info['port'];
    $path = $info['path'];
    // ポートが空の時はデフォルトの80にします。
	$port = 80;    
    if( isset($info['port']) ) {
        $port = $info['port'];
    }

    // リクエストフィールドを制作。
    $msg_req = "HEAD " . $path . " HTTP/1.0\r\n";
    $msg_req .= "Host: $host\r\n";
    $msg_req .=
        "User-Agent: H2C/1.0\r\n";
    $msg_req .= "\r\n";

    // スキームがHTTPの時のみ実行
    if ( $scheme == 'http' ) {

        $status = array();

        // 指定ホストに接続。
        if ( $handle = @fsockopen( $host, $port, $errno, $errstr, 1 ) ) {

            fputs ( $handle, $msg_req );

            if ( socket_set_timeout( $handle, 3 ) ) { 

                $line = 0;
                while( ! feof( $handle) ) {

                    // 1行めはステータスライン
                    if( $line == 0 ) {
                        $temp_stat =
                            explode( ' ', fgets( $handle, 4096 ) );
                        $status['HTTP-Version'] =
                            array_shift( $temp_stat );
                        $status['Status-Code'] = array_shift( $temp_stat );
                        $status['Reason-Phrase'] =
                            implode( ' ', $temp_stat );

                    // 2行目以降はコロンで分割してそれぞれ代入
                    } else {
                        $temp_stat =
                            explode( ':', fgets( $handle, 4096 ) );
                        $name = array_shift( $temp_stat );
                        // 通常:の後に1文字半角スペースがあるので除去
                        $status[ $name ] =
                            substr( implode( ':', $temp_stat ), 1);
                    }
                    $line++;
                }

            } else {
                    $status['HTTP-Version'] = '---';
                    $status['Status-Code'] = '902';
                    $status['Reason-Phrase'] = "No Response";
            }

            fclose ( $handle );

        } else {
            $status['HTTP-Version'] = '---';
            $status['Status-Code'] = '901';
            $status['Reason-Phrase'] = "Unable To Connect";
        }


    } else {
        $status['HTTP-Version'] = '---';
        $status['Status-Code'] = '903';
        $status['Reason-Phrase'] = "Not HTTP Request";
    }

    return $status;

}


function quote_smart($value)
{
    // 数値以外をクオートする
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}
