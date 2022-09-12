<?php
require_once('../includes/util.php');
require_once('../includes/update.php');
load_config('../config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('../includes/loginUpdate.php');
$settings = get_settings();

if(isset($_REQUEST['submit'])){ 
	$func = $_REQUEST['submit'];
	//echo($func);
	if($func=="Update Vaccine Status"){
		$playerID = requestVar('playerID');
		$vaccineStatus = requestVar('vaccineStatus');
		$ret = getUID($playerID);
		if($ret) {
			//found user
			$uid = $ret['UID'];
			$name = $ret['fname']." ".$ret['lname'];
			$ret2 = mysql_oneline("SELECT * FROM `users` WHERE `UID` = '$uid';");
			$uname = $ret2['uname'];
			
			mysql_query("UPDATE `users` SET `vaccineStatus`= '$vaccineStatus' WHERE `uname` = '$uname';");
			
			$GLOBALS['meetingMessage']="Updated vaccine status for $name";
		} else {
			$GLOBALS['meetingMessage']="I couldn't find player $playerID. Make sure the username is correct";
		}
	}
	if($func=="Update Waiver Record"){
		$playerID = requestVar('playerID');
		$vaccineStatus = requestVar('waiver');
		$ret = getUID($playerID);
		if($ret) {
			//found user
			$uid = $ret['UID'];
			$name = $ret['fname']." ".$ret['lname'];
			$ret2 = mysql_oneline("SELECT * FROM `users` WHERE `UID` = '$uid';");
			$uname = $ret2['uname'];
			
			mysql_query("UPDATE `users` SET `hasTurnedInWaiver`= '$waiver' WHERE `uname` = '$uname';");
			
			$GLOBALS['meetingMessage']="Updated waiver record for $name";
		} else {
			$GLOBALS['meetingMessage']="I couldn't find player $playerID. Make sure the username is correct";
		}
	}
}
?>