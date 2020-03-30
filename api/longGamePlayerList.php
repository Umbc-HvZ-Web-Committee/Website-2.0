<?php
header("Access-Control-Allow-Origin: *");
require_once('../includes/util.php');
load_config('../config.txt');
my_quick_con($config);

$GAME_ID_PARAM = "gameID";

if(array_key_exists($GAME_ID_PARAM, $_GET)){
	$curGame = $_GET[$GAME_ID_PARAM];
}else{
	$curGame = getCurrentLongGame();
	$curGame = $curGame[$GAME_ID_PARAM];
}

if($curGame==null){
	die("{}");
}

//Hey Travis, I think there should be some code here that updates player statuses for $curGame if they've been dead for over an hour? IDK. ~Hiccup

$sql = "SELECT CONCAT(fname,' ',lname) AS name, PlayerState(state) AS state";
$sql .= " FROM users, long_players";
$sql .= " WHERE users.UID=long_players.playerID AND long_players.gameID='$curGame';";

$q = mysql_query($sql);

$playerList = array();
while($ret = mysql_fetch_assoc($q)){
	$playerList[$ret['name']] = $ret['state'];
}

$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE state<0 AND gameID='$curGame'");
$zombies = $ret['cnt'];
$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE state>0 AND gameID='$curGame'");
$humans = $ret['cnt'];
$ret = mysql_oneline("SELECT COUNT(*) AS cnt FROM long_players WHERE playerID LIKE 'OZ%' AND gameID='$curGame'");
$hiddenOZ = $ret['cnt'];

$retval = array(
		'players'=>$playerList,
		'humans'=>$humans,
		'zombies'=>$zombies,
		'ozs'=>$hiddenOZ
);

die(json_encode($retval));
?>