<?php
require_once('includes/pdf.php');
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');

if($_SESSION['uid']){
	$uid = $_SESSION['uid'];
	$longGame = getNextLongGame();
	
	$q = mysql_query("SELECT * FROM long_players JOIN users ON long_players.playerID=users.UID WHERE UID = '$uid' AND gameID='{$longGame['gameID']}';");
	
	if($ret = mysql_fetch_assoc($q)){
		$username = $ret['uname'];
		$name = "{$ret['fname']} {$ret['lname']}";
		$phoneNumber = $ret['phoneNumber'];
		$uid = $ret['UID'];
		$killID = $ret['mainKill'];
		$pdf = openPDF("Benjamin Harris", "HvZ ID Cards");
		page1($pdf, $uid, $username, $name, $phoneNumber, $killID);
		page2($pdf, $uid, $username, $name, $phoneNumber, $killID);
		closePDF($pdf, "ID.pdf");
	}else{
		$q = mysql_query("SELECT * FROM long_preregister NATURAL JOIN users WHERE UID = '$uid' AND gameID='{$longGame['gameID']}';");
		if($ret = mysql_fetch_assoc($q)){
			$username = $ret['uname'];
			$name = "{$ret['fname']} {$ret['lname']}";
			$phoneNumber = $ret['phoneNumber'];
			$uid = $ret['UID'];
			$killID = $ret['mainKill'];
			$pdf = openPDF("Benjamin Harris", "HvZ ID Cards");
			page1($pdf, $uid, $username, $name, $phoneNumber, $killID);
			page2($pdf, $uid, $username, $name, $phoneNumber, $killID);
			closePDF($pdf, "ID.pdf");
		}else{
			echo "WEIRD, GO CALL BEN: 443-599-9236";
		}
	}
}else{
	echo "Hey, you're not logged in any more; go back and log in.";
}
?>