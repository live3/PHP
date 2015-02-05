<?php
header("Content-type:text/html;charset=UTF-8");
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

//songkcik for scraping
$db = new SQLite3('rockin2014.sqlite');
/*
class NewDB extends SQLite3{
    function __construct() {
        $this->open('sgkc.sqlite');
    }
}
$db = new NewDB();
*/
//seed発行

for_seed_date($db);
exit;

//seed発行 end
//scrape!!!!!!!!!!!!!!!!!!!!

$db->exec('CREATE TABLE IF NOT EXISTS bands (id INTEGER PRIMARY KEY ,name TEXT,nameF TEXT, link TEXT,image TEXT,member TEXT, description1 Text, description2 TEXT, rate Text,description3 TEXT,starttime TEXT,endtime TEXT,day TEXT,stage_id INTEGER )');

$db->exec('CREATE TABLE IF NOT EXISTS times (id INTEGER PRIMARY KEY ,band_id INTEGER,start TEXT, end TEXT ,day TEXT, stage TEXT)');

$db->exec('CREATE TABLE IF NOT EXISTS stages (id INTEGER PRIMARY KEY ,stage_name TEXT)');

//$db->exec('delete from bands;');;

//stage
/*
$stage_array = array('GRASS STAGE','LAKE STAGE','SOUND OF FOREST','PARK STAGE','WING TENT','BUZZ STAGE');
$stage_id = 1;
foreach($stage_array as $stage_name){
	$db->exec("INSERT INTO stages (id, stage_name ) VALUES (\"$stage_id\",\"$stage_name\")");
	$stage_id++;	
}
*/

$band_array = array(

array(
"10:30~11:25ゴールデンボンバー%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/goldenbomber.jpg",
"11:55~12:50ACIDMAN%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/acidman.jpg",
"13:20~14:20湘南乃風%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/shonannokaze.jpg",
"14:45~15:40RIP SLYME%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/ripslyme.jpg",
"16:10~17:05Dragon Ash",
"17:35~18:35マキシマム ザ ホルモン%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/maximumthehormone.jpg",
"19:00~20:00KICK THE CAN CREW%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/kickthecancrew.jpg",
),
array(
"10:30~11:25NICO Touches the Walls%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/nicotouchesthewalls.jpg",
"11:55~12:50[Alexandros]%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/alexandros.jpg",
"13:20~14:20ONE OK ROCK%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/oneokrock2.jpg",
"14:45~15:40ケツメイシ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/ketsumeishi.jpg",
"16:10~17:05KREVA",
"17:35~18:30木村カエラ",
"19:00~20:00サカナクション%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/sakanaction.jpg",
),
array(
"10:30~11:25ファンキー加藤%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/funkykato.jpg",
"11:55~12:50Base Ball Bear%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/baseballbear.jpg",
"13:20~14:20くるり",
"14:45~15:40きゃりーぱみゅぱみゅ",
"16:10~17:00チャットモンチー",
"17:35~18:30To Be Announced...",
"19:00~20:00ASIAN KUNG-FU GENERATION%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/asiankungfugeneration.jpg",
),
array(
"10:30~11:25miwa%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/miwa.jpg",
"11:55~12:50エレファントカシマシ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/elephantkashimashi.jpg",
"13:20~14:2010-FEET%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/10feet.jpg",
"14:45~15:509mm Parabellum Bullet%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/9mmparabellumbullet.jpg",
"16:10~17:05the HIATUS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/thehiatus.jpg",
"17:35~18:30ユニコーン%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/unicorn.jpg",
"19:00~20:00SEKAI NO OWARI%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/sekainoowari.jpg",
),
array(
"10:30~11:10ROTTEN GRAFFTY",
"11:40~12:20家入レオ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/ieirileo2.jpg",
"12:50~13:30サンボマスター%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/sambomaster.jpg",
"14:00~14:40HEY-SMITH%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/heysmith.jpg",
"15:10~15:50coldrain%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/coldrain.jpg",
"16:20~17:00KANA-BOON%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/kanaboon.jpg",
"17:30~18:10Every LittleThing",
"18:40~19:40フジファブリック%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/fujifabric.jpg",
),
array(
"10:30~11:10KEMURI%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/kemuri.jpg",
"11:40~12:20高橋優",
"12:50~13:30SPECIAL OTHERS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/specialothers.jpg",
"14:00~14:40秦 基博%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/hatamotohiro.jpg",
"15:10~15:50UNISON SQUARE GARDEN%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/unisonsquaregarden.jpg",
"16:20~17:00Chara × 韻シストBAND%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/charaxinsistband.jpg",
"17:30~18:10THE BACK HORN%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/thebackhorn.jpg",
"18:40~19:40TOTALFAT%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/totalfat2.jpg",
),
array(
"10:30~11:10The Mirraz%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/themirraz.jpg",
"11:40~12:50Nothing s Carved In Stone%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/nothingscarvedinstone.jpg",
"12:50~13:30back number",
"14:00~14:40POLYSICS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/polysics.jpg",
"15:10~15:50グッドモーニング アメリカ",
"16:20~17:00中川翔子",
"17:30~18:10BOOM BOOSATELLITES",
"18:40~19:40androp%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/androp2.jpg",
),
array(
"10:30~11:10パスピエ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/passepied2.jpg",
"11:40~12:20dustbox ",
"12:50~13:30SCANDAL%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/scandal.jpg",
"14:00~14:40スキマスイッチ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/sukimaswitch.jpg",
"15:10~15:50MONGOL800%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/mongol800.jpg",
"16:20~17:00加藤ミリヤ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/katomiliyah.jpg",
"17:30~18:10ORANGE RANGE",
"18:40~19:40BIGMAMA",
),
array(
"11:05~11:40快速東京%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/kaisokutokyo.jpg",
"12:15~12:50PUFFY%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/puffy.jpg",
"13:25~14:00曽我部恵一",
"14:35~15:10moumoon%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/moumoon.jpg",
"15:45~16:20山崎まさよし",
"16:55~17:30ART- SCHOOL%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/artschool.jpg",
"18:05~18:40SOIL&\"PIMP\" SESSIONS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/soilpimpsessions.jpg",
"19:20~20:20Crossfaith%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/crossfaith.jpg",
),
array(
"11:05~11:40tricot%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/tricot.jpg",
"12:15~12:50FLiP",
"13:25~14:00ドレスコーズ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/dresscodes.jpg",
"14:35~15;10坂本真綾",
"15:45~16:20Rihwa",
"16:55~17:30the brilliant green",
"18:05~18:40cinema staff%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/cinemastaff.jpg",
"19:20~20:20Hermann H. &The Pacemakers%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/hermannhandthepacemakers.jpg",
),
array(
"11:05~11:40GOOD ON THE REEL%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/goodonthereel.jpg",
"12:15~12:50プリシラ・ アーン%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/priscillaahn.jpg",
"13:25~14:00でんぱ組.inc",
"14:35~15:10MY FIRST STORY%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/myfirststory.jpg",
"15:45~16:20ZAZEN BOYS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/zazenboys.jpg",
"16:55~17:30スガ シカオ",
"18:05~18:40真心 ブラザーズ",
"19:20~20:20CAPSULE",
),
array(
"11:05~11:40tacica%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/tacica.jpg",
"12:15~12:50前田敦子%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/atsukomaeda.jpg",
"13:25~14:00シアター ブルック",
"14:35~15:10OKAMOTO S",
"15:45~16:20中村一義 (Acoustic set)%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/nakamurakazuyoshi.jpg",
"16:55~17:30きのこ帝国%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/kinokoteikoku.jpg",
"18:05~18:40ASPARAGUS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/asparagus.jpg",
"19:20~20:20在日ファンク%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/zainichifunk.jpg",
),
array(
"10:30~11:05赤い公園%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/akaikoen.jpg",
"11:40~12:15フラワーカンパニーズ",
"12:50~13:25Silent Siren ",
"14:00~14:35group_inou%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/group_inou.jpg",
"15:10~15:45LUNKHEAD%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/lunkhead.jpg",
"16:20~16:55the chef cooks me%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/thechefcooksme.jpg",
"17:30~18:05SAKANAMON%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/sakanamon.jpg",
"18:40~19:40KEYTALK%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/keytalk.jpg",
),
array(
"10:30~11:05東京カランコロン%http://dm0v2r25agjtp.cloudfront.net/2014/img/artist_hga/tokyokarankoron2.jpg",
"11:40~12:15GOOD4 NOTHING%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/good4nothing.jpg",
"12:50~13:25locofrank%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/locofrank.jpg",
"14:00~14:35ねごと%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/negoto.jpg",
"15:10~15:45HUSKING BEE%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/huskingbee.jpg",
"16:20~16:55OVERGROUND ACOUSTIC UNDERGROUND%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/overgroundacousticunderground.jpg",
"17:30~18:05Galileo Galilei%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/galileogalilei.jpg",
"18:40~19:40TRICERATOPS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/triceratops.jpg",
),
array(
"10:30~11:05B-DASH%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/bdash.jpg",
"11:40~12:15キュウソネコカミ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/kyuusonekokami.jpg",
"12:50~13:25The BONEZ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/thebonez.jpg",
"14:00~14:35安藤裕子%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/andoyuko.jpg",
"15:10~15:45DOES%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/does.jpg",
"16:20~16:55ソウル・フラワー・ユニオン%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/soulflowerunion.jpg",
"17:30~18:05ゲスの極み 乙女。",
"18:40~19:40N,夙川BOYS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/nshukugawaboys.jpg",
),
array(
"10:30~11:05Northern19%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/northern19.jpg",
"11:40~12:15中田裕二%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/yujinakada.jpg",
"12:50~13:25THE STARBEMS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/thestarbems.jpg",
"14:00~14:35plenty%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/plenty.jpg",
"15:10~15:45Theピーズ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/thepees.jpg",
"16:20~16:55SCOOBIE DO%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/scoobiedo.jpg",
"17:30~18:05Plastic Tree",
"18:40~19:40アルカラ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/arukara.jpg",
),
array(
"11:10~11:40LAMP IN TERREN",
"12:20~12:50ALL OFF%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/alloff.jpg",
"13:30~14:00さよなら、また今度ね%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/sayonaramatakondone.jpg",
"14:40~15:10FOLKS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/folks.jpg",
"15:50~16:20BUZZ THE BEARS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/buzzthebears.jpg",
"17:00~17:30ボールズ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/balls.jpg",
"18:10~18:40go!go!vanillas",
"19:25~20:25HelloSleepwalkers",
),
array(
"11:10~11:40シャムキャッツ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/siamesecats.jpg",
"12:20~12:50TarO&JirO",
"13:30~14:009nine%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/9nine.jpg",
"14:40~15:10ハルカトミユキ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/harukatomiyuki.jpg",
"15:50~16:20ANGRY FROG REBIRTH%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/angryfrogrebirth.jpg",
"17:00~17:30LEGO BIG MORL",
"18:10~18:40Dr.DOWNER%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/drdowner.jpg",
"19:25~20:25Wienners%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/wienners.jpg",
),
array(
"11:10~11:40テスラは泣かない。",
"12:20~12:50QOOLAND",
"13:30~14:00赤色のグリッター%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/akaironoglitter.jpg",
"14:40~15:10森は生きている%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/moriwaikiteiru.jpg",
"15:50~16:20チームしゃちほこ",
"17:00~17:30KNOCK OUT MONKEY%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/knockoutmonkey2.jpg",
"18:10~18:45Suck a Stew Dry%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/suckastewdry.jpg",
"19:25~20:30The Flickers%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/theflickers.jpg",
),
array(
"11:10~11:40Large Hous Satisfaction%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/largehousesatisfaction.jpg",
"12:20~12:50This is Not a Business%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/thisisnotabusiness.jpg",
"13:30~14:00小林太郎%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/kobayashitaro.jpg",
"14:40~15:10AIR SWELL%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/airswell.jpg",
"15:50~16:20東京女子流%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/tokyogirlsstyle.jpg",
"17:00~17:30THE PRIVATES%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/theprivates.jpg",
"18:10~18:40HAPPY%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/happy.jpg",
"19:25~20:25真空ホロウ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/shinkuhorou.jpg",
),
array(
"9:00~10:35保坂壮彦 (ALL IS LOVE IS ALL)[DJ]",
"10:45~12:25八王子P [DJ] kz(livetune) [DJ]%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_kmk/hachiojip.jpg",
"12:50~13:20Lyu:Lyu%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/lyulyu.jpg",
"13:35~14:20DJ'TEKINA// SOMETHING a.k.aゆよゆっぺ[DJ]%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_kmk/djtekinasomethingakayuyoyuppe2.jpg",
"14:35~15:15DAISHI DANCE%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/daishidance.jpg",
"15:40~16:10Czecho No Republic%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/czechonorepublic2.jpg",
"16:25~18:05DJやつい いちろう(エレキコミック) [DJ]",
"18:30~19:00avengers insci-fi%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/avengersinscifi.jpg",
"19:15~19:45CTS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/cts.jpg",
"20:00~21:00保坂壮彦[DJ]",
),
array(
"9:00~10:35保坂壮彦 ALL IS LOVE IS ALL)[DJ]",
"10:45~12:25BUZZ SPECIAL 山崎あおい 新山詩織 片平里菜 住岡梨奈%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_kmk/katahirarina.jpg",
"12:50~13:20Qaijff%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/qaijff.jpg",
"13:35~14:05HAN-KUN",
"14:20~15:15RAM RIDER × TEMPURA KIDZ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/ramrider.jpg",
"15:40~16:10空想委員会%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/kusoiinkai.jpg",
"16:25~17:20川上洋平 [Alexandros])[DJ]",
"17:35~18:05UL",
"18:30~19:00忘れらんねえよ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/wasureranneyo.jpg",
"19:15~19:45アスタラビスタ",
"20:00~21:00保坂壮彦[DJ]",
),
array(
"9:00~10:35 遠藤孝行 FREAK AFFAIR[DJ]%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_kmk/endotakayuki.jpg",
"10:45~12:30BUZZ SPECIAL たこやきレインボー ベイビーレイズ あゆみくりかまき 寺嶋由芙 PASSPO☆ アップアップ ガールズ(仮)",
"12:50~13:20宇宙まお",
"13:35~15:15ダイノジ [DJ]",
"15:40~16:10FOUR GET ME A NOTS%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/fourgetmeanots.jpg",
"16:25~17:05DJ和 [DJ]",
"17:05~18:00ハヤシ ヒロユキ(POLYSICS) [DJ]%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_kmk/hayashihiroyuki.jpg",
"18:30~19:00ヒトリエ%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/hitorie.jpg",
"19:15~19:45tofubeats%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/tofubeats.jpg",
"20:00~21:00遠藤孝行[DJ]%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_kmk/endotakayuki.jpg",
),
array(
"9:00~10:35 遠藤孝行 (FREAK AFFAIR)[DJ]",
"10:45~11:15SHUN%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/shun.jpg",
"11:30~12:30ピエール中野 (凛として時雨)[DJ]",
"12:50~13:25THE ORAL CIGARETTES%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/theoralcigarettes.jpg",
"13:35~15:15BUZZ SPECIAL 仮面女子 Cheeky Parade lyrical school 武藤彩未 Negicco ひめキュン フルーツ缶",
"15:40~16:10indigo la End%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hga/indigolaend.jpg",
"16:25~17:00大森靖子",
"17:10~18:00TAKUMA (10-FEET)[DJ]",
"18:30~19:00爆弾ジョニー%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/bakudanjohnny.jpg",
"19:15~19:45RHYMESTER%http://d27dxvy21lp7lf.cloudfront.net/2014/img/artist_hkn/rhymester.jpg",
"20:00~21:00遠藤孝行[DJ]"
)
);
$count= 1;
$band_id = 1;
$day= 1;
$stage = 0;
foreach($band_array as $band_source){
//var_dump($band_source);

	$day = ($count-1)%4+1;
	if(($count-1)%4 == 0){
		$stage++;
	}
	
	foreach($band_source as $band){	
		$start = '';$end = '';$name = '';$image ='';
		$start =  substr($band, 0,5);
		$end = substr($band, 6,5);
		if(strpos($band, '%')){
			$temp = mb_substr($band, 11);
			$temp_arr = explode('%', $temp);
			$name = $temp_arr[0];
			$image = $temp_arr[1];		
		}else{
			$name =  mb_substr($band, 11);
		}
		$name = str_replace('"', '”', $name);
		$db->exec("INSERT INTO bands (id, name,image,  starttime, endtime, stage_id, day  ) VALUES (\"$band_id\",\"$name\",\"$image\",\"$start\",\"$end\",\"$stage\",\"$day\")");		
		$band_id++;
	}
	$count++;	
}


exit;
//$db->exec('delete from bands');

//$db->exec('delete from times');


function c($text, $db){
//	$text=sqlite_escape_string($text);
//	$text=mb_convert_encoding($text, "UTF-8",'auto');
//	$text=$db->escapeString($text);
	$text=str_replace('"', '”', $text);
	$text=str_replace("'", '’', $text);
	return $text;
}
function sjis_to_utf8($text){
	$text=mb_convert_encoding($text, "UTF-8",'auto');	
	return $text;
}



function for_seed_date($db){
	$count = 0;
	
	$fes_id = 2;//define!!
	$stage_count=1;
	$state = "select * from stages";
	$result = $db->query($state );
	while( $row = $result->fetchArray() ) {
		$stage_name = $row['stage_name'];
		echo "FesStage.create(fes_id: '$fes_id', fes_stage_name_ja: '$stage_name', fes_stage_name_en: '$stage_name', fes_stage_name_zh: '$stage_name', fes_stage_description_ja: '', 
		fes_stage_description_en: '', fes_stage_description_zh: '', fes_stage_main_color: '#009051', fes_stage_sub_color: '#73fcd6', fes_stage_text_color: '#ffffff',
		fes_stage_seq_no: $stage_count, deleted_at_flg: 0 )";	
		echo '<br />';
		$stage_count++;
	}	
	exit;
	
	$state = "select * from bands b inner join stages s on b.stage_id = s.id";
	$result = $db->query($state );
	while( $row = $result->fetchArray() ) {

//		$complete_array[] = $row['band_id'];

		$name = str_replace("'", "\'", sjis_to_utf8($row['name']) ) ;
		$image = sjis_to_utf8($row['image']);
//		$desc = mb_substr($row['member'].$row['description1'].$row['description2'].$row['description3'],0,5999);


		$stage_id = sjis_to_utf8($row['stage_id'])+14;	
		$day = sjis_to_utf8($row['day']);
		$day_st = "08/02/2014";
		if($day == 2){
			$day_st = "08/03/2014";
		}else if($day == 3){
			$day_st = "08/09/2014";
		}else if($day == 4){
			$day_st = "08/10/2014";			
		}
		
		$time = $day_st.' '.sjis_to_utf8($row['starttime']);
		$end_time = $day_st.' '.sjis_to_utf8($row['endtime']);
	
		echo "band = Band.create(band_icon_url: '', band_email: '', band_phone_number: '', band_name_ja: '$name', band_name_en: '$name', 
		band_name_zh: '$name', band_description_ja: '', band_description_en: '', band_description_zh: '', 
		band_country_type: 1, deleted_at_flg: 0 )";
		echo '<br />';
		echo "BandMediaSource.create(band_id: band.id ,band_media_source_material: '$image',band_media_type: 1, band_media_source_seq_no: 1,deleted_at_flg: 0)";
		echo '<br />';
		echo "BandGenreManagement.create(band_id: band.id, genre_id: 1 ,band_genre_management_other_txt: '',deleted_at_flg: 0)";
		echo '<br />';		
		echo "FesStageBand.create(fes_stage_id: $stage_id , band_id: band.id, 
		fes_stage_band_start_date: DateTime.strptime('$time', '%m/%d/%Y %H:%M'),
		fes_stage_band_end_date: DateTime.strptime('$end_time', '%m/%d/%Y %H:%M'),
		deleted_at_flg: 0)";
		echo '<br />';		

		$count++;
	}

/*
	//バンドのみ（時間がないものも念のため）　
	$state = "select * from bands";
	$result = $db->query($state );
	$cc=0;
	while( $row = $result->fetchArray() ) {	
		if( !in_array($row['id'],$complete_array )){
			$name = $row['name'];
			$image = $row['image'];
			$member = $row['member'];
			$desc = mb_substr($row['member'].$row['description1'].$row['description2'].$row['description3'],0,5999);
		
			echo "band = Band.create(band_icon_url: '', band_email: '', band_phone_number: '', band_name_ja: '$name', band_name_en: '$name', 
			band_name_zh: '$name', band_description_ja: '$desc', band_description_en: '$desc', band_description_zh: '$desc', 
			band_country_type: 1, deleted_at_flg: 0 )";
			echo '<br />';
			echo "BandMediaSource.create(band_id: band.id ,band_media_source_material: '$image',band_media_type: 1, band_media_source_seq_no: 1,deleted_at_flg: 0)";
			echo '<br />';
			echo "BandGenreManagement.create(band_id: band.id, genre_id: 1 ,band_genre_management_other_txt: '',deleted_at_flg: 0)";
			echo '<br />';	
		}
	}
*/	

/*	
	array(16) { 
["id"]=> int(75) 
["name"]=> string(14) "the band apart"
["nameF"]=> string(30) "ザ・バンド・アパート"
["image"]=> string(63) "http://image.fujirockfestival.com/a_images/artist/bandapart.jpg"
["member"]=> string(87) "荒井岳史（Vo/Gt）、川崎亘一（Gt）、原昌和（Ba）、木暮栄一（Dr)"
["description1"]=> string(988) "98年結成。2004年にそれまで所属していた大手インディーズメーカーを離れ、...." 
["description2"]=> string(41) "http://www.youtube.com/user/HandLchannel," 
["description3"]=> string(115) "http://www.asiangothic.org,http://xc528.eccart.jp/x859/item_search/?keyword=the+band+apart&submit.x=20&submit.y=16," }
*/	
}

?>
