<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

$gameID = "";
if(isset($_REQUEST['longGameSelect'])){
	$gameID = requestVar("longGameSelect");
}else{
	$ret = mysql_oneline("SELECT gameID FROM long_games ORDER BY creationDate DESC");
	$gameID = $ret['gameID'];
}

if(isset($_REQUEST['ozSelect'])){
	$mysqldate = date( 'Y-m-d H:i:s', $phpdate );
	$deathTime = strtotime( $mysqldate );
	//echo "game = ".$gameID."\n";
	//echo "got time stuff for feeds\n";
	$uid = $_POST['ozUID'];
	updateAchieves($uid, null, "madeOZ");
	//echo "awarded OZ achievement\n";
	//make OZ
	//dummy has same UID, but it starts "OZ" not "US"
	$userData = mysql_query("SELECT * FROM `users` WHERE `UID`='$uid';");
	
	$dummyID = "OZ".substr($uid, 2);
	//echo "created OZ dummy id\n";
	
	//Remove all other dummy OZs before adding this one to avoid duplicates. Maybe not the most efficient but hey it works fite me.
	mysql_query("DELETE FROM `long_players` WHERE `playerID` = '$dummyID';");
	
	/*
	$ret2 = mysql_oneline("SELECT COUNT(*) AS cnt FROM `long_players` WHERE `state`=2 AND `gameID`='$gameID';");
	$filler = "OZ_".$ret2['cnt'];
	$sql = "INSERT INTO  `users` (`UID`, `fname`, `lname`, `uname`, `passwd`, `email`, `publicQR`) "
	."VALUES ('$dummyID',  'Phil',  'the OZ',  '$filler',  '', '$filler', '$filler');";
	//The uname, passwd, email for the dummy zombie do not matter, but need to be values that are not duplicates
	//of anything else in the database.  So I set it to the UID of the other player, with a space added in front because spaces
	//aren't allowed in any of these fields normally. This makes it easier to find the matching real OZ too!
	*/
	
	$sql2 = "INSERT INTO `long_players` (`gameID`, `playerID`, `mainKill`, `feedKill1`, `feedKill2`, `state`) "
			."VALUES ('$gameID', '$dummyID', '','','', -2);";

	mysql_query($sql2);
	//echo "added OZ dummy to player list\n";
	
	//Insert the OZ as dead so we can pull out of here the time the OZ died at for later calculations
	/*mysql_query("INSERT INTO `long_feeds` (`gameID`, `whoDied`, `whoFed`, `wasKilled`, `timeOfKill`, `hoursGiven`) ".
	                              "VALUES ('$gameID', '$dummyID', '', '$deathTime', 0);");*/
	
	mysql_query("UPDATE `long_players` SET `state`=2 WHERE `playerID`='$uid' AND `gameID`='$gameID';");
	
	mysql_query("UPDATE `users` SET `timesAsOZ` = `timesAsOZ` + 1 WHERE `UID`='$uid';");
	//echo "set OZ to OZ\n";

}
?>