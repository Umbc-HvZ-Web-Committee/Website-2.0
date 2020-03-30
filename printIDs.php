<?php
require_once('includes/pdf.php');
require_once('includes/util.php');
load_config('config.txt');
my_quick_con($config);
if(!isset($_SESSION)) session_start();
if(!isset($loginUpdate)) require_once('includes/loginUpdate.php');

$longGame = getNextLongGame();

$q = mysql_query("SELECT * FROM long_preregister NATURAL JOIN users WHERE isPrinted = 0 AND gameID='{$longGame['gameID']}';");
mysql_query("UPDATE long_preregister SET isPrinted = 1;");

$pdf = openPDF("Benjamin Harris", "HvZ ID Cards");

while($ret = mysql_fetch_assoc($q)){
	$username = $ret['uname'];
	$name = "{$ret['fname']} {$ret['lname']}";
	$phoneNumber = $ret['phoneNumber'];
	$uid = $ret['UID'];
	$killID = $ret['mainKill'];
	page1($pdf, $uid, $username, $name, $phoneNumber, $killID);
	page2($pdf, $uid, $username, $name, $phoneNumber, $killID);
}

closePDF($pdf, "ID.pdf");
?>